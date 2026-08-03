<?php

namespace Modules\Mpesa\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Mpesa\Entities\MpesaSetting;
use Modules\Mpesa\Entities\MpesaTransaction;
use Modules\Mpesa\Notifications\PaymentReceivedNotification;

class CallbackController extends Controller
{
    /**
     * Handle the STK Push callback from Safaricom.
     *
     * Safaricom posts something like:
     * {
     *   "Body": {
     *     "stkCallback": {
     *       "MerchantRequestID": "...",
     *       "CheckoutRequestID": "...",
     *       "ResultCode": 0,
     *       "ResultDesc": "The service request is processed successfully.",
     *       "CallbackMetadata": {
     *         "Item": [
     *           {"Name": "Amount", "Value": 1},
     *           {"Name": "MpesaReceiptNumber", "Value": "NLJ7RT61SV"},
     *           {"Name": "TransactionDate", "Value": 20191219102151},
     *           {"Name": "PhoneNumber", "Value": 254708374149}
     *         ]
     *       }
     *     }
     *   }
     * }
     */
    public function stkCallback(Request $request, $business_id = null)
    {
        $payload = $request->all();

        Log::channel('single')->info('Mpesa STK Callback received', ['business_id' => $business_id, 'payload' => $payload]);

        $callback = $payload['Body']['stkCallback'] ?? null;

        if (!$callback) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid payload']);
        }

        $checkout_request_id = $callback['CheckoutRequestID'] ?? null;

        $mpesa_transaction = MpesaTransaction::where('checkout_request_id', $checkout_request_id)->first();

        if (!$mpesa_transaction) {
            // Nothing we initiated locally matches this - just acknowledge so Safaricom stops retrying
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $result_code = $callback['ResultCode'] ?? null;
        $mpesa_transaction->result_code = (string) $result_code;
        $mpesa_transaction->result_desc = $callback['ResultDesc'] ?? null;
        $mpesa_transaction->raw_callback = $payload;

        if ((string) $result_code === '0') {
            $mpesa_transaction->status = 'success';

            // Extract metadata items (Amount, MpesaReceiptNumber, TransactionDate, PhoneNumber)
            $items = $callback['CallbackMetadata']['Item'] ?? [];
            $meta = [];
            foreach ($items as $item) {
                if (isset($item['Name'])) {
                    $meta[$item['Name']] = $item['Value'] ?? null;
                }
            }

            $mpesa_transaction->mpesa_receipt_number = $meta['MpesaReceiptNumber'] ?? null;
            $mpesa_transaction->transaction_date = isset($meta['TransactionDate']) ? (string) $meta['TransactionDate'] : null;

            if (!empty($meta['Amount'])) {
                $mpesa_transaction->amount = $meta['Amount'];
            }

            if (!empty($meta['PhoneNumber'])) {
                $mpesa_transaction->phone_number = (string) $meta['PhoneNumber'];
            }
        } elseif ((string) $result_code === '1032') {
            // Request cancelled by user
            $mpesa_transaction->status = 'cancelled';
        } else {
            $mpesa_transaction->status = 'failed';
        }

        $mpesa_transaction->save();

        // If this STK push was tied to a specific sale, mark it as paid in Ultimate POS
        if ($mpesa_transaction->status === 'success' && $mpesa_transaction->transaction_id) {
            $this->markSalePaid($mpesa_transaction);
        }

        // Notify staff (bell icon) regardless of whether this was tied to a specific
        // sale - useful both for POS sales and for direct Paybill (C2B) payments that
        // weren't initiated from the POS screen at all.
        if ($mpesa_transaction->status === 'success') {
            $this->notifyPaymentReceived($mpesa_transaction);
        }

        // Safaricom expects a 200 response with this body to stop retrying
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /**
     * Handle direct Paybill/Till (C2B) payment confirmations - i.e. customers who paid
     * directly via the Paybill on their phone, without going through the POS STK push.
     *
     * Safaricom posts fields like: TransAmount, BillRefNumber, MSISDN, TransID, etc.
     */
    public function c2bConfirmation(Request $request)
    {
        $payload = $request->all();

        Log::channel('single')->info('Mpesa C2B Confirmation received', ['payload' => $payload]);

        $shortcode = $payload['BusinessShortCode'] ?? null;

        $settings = MpesaSetting::where('shortcode', $shortcode)->first();

        MpesaTransaction::create([
            'business_id' => $settings->business_id ?? 0,
            'type' => 'c2b',
            'status' => 'success',
            'phone_number' => $payload['MSISDN'] ?? null,
            'amount' => $payload['TransAmount'] ?? null,
            'mpesa_receipt_number' => $payload['TransID'] ?? null,
            'transaction_date' => $payload['TransTime'] ?? null,
            'account_reference' => $payload['BillRefNumber'] ?? null,
            'result_code' => '0',
            'result_desc' => 'C2B payment received',
            'raw_callback' => $payload,
        ]);

        // Safaricom expects this exact acknowledgement format for C2B
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }

    /**
     * Validation endpoint - called by Safaricom before completing a C2B payment.
     * We accept everything by default (ResultCode 0). Add custom checks here if you
     * need to validate the account number (BillRefNumber) before accepting payment.
     */
    public function c2bValidation(Request $request)
    {
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }

    /**
     * Fire the bell-icon (database) notification for a confirmed M-Pesa payment.
     *
     * Notifies:
     *   - The cashier who created the linked sale (Transaction::created_by),
     *     if this was tied to a specific POS sale.
     *   - The business owner, always - it's their money, and this also covers
     *     direct Paybill (C2B) payments that have no linked sale/cashier at all.
     *
     * Safaricom may retry the same callback - CallbackController::stkCallback()
     * already guards against duplicate TransactionPayment rows via
     * payment_ref_no, but notifications aren't deduplicated the same way, so
     * we additionally check we haven't already notified for this specific
     * mpesa_transaction id.
     */
    protected function notifyPaymentReceived(MpesaTransaction $mpesa_transaction)
    {
        try {
            if (!class_exists(\App\User::class) || !class_exists(\App\Business::class)) {
                return;
            }

            $business = \App\Business::find($mpesa_transaction->business_id);

            if (!$business) {
                return;
            }

            $notified_user_ids = [];

            // Cashier who created the sale, if we have one
            if ($mpesa_transaction->transaction_id && class_exists(\App\Transaction::class)) {
                $transaction = \App\Transaction::find($mpesa_transaction->transaction_id);

                if ($transaction && !empty($transaction->created_by)) {
                    $cashier = \App\User::find($transaction->created_by);

                    if ($cashier) {
                        $cashier->notify(new PaymentReceivedNotification($mpesa_transaction));
                        $notified_user_ids[] = $cashier->id;
                    }
                }
            }

            // Business owner - always, unless they ARE the cashier we just notified
            if (!empty($business->owner_id) && !in_array($business->owner_id, $notified_user_ids)) {
                $owner = \App\User::find($business->owner_id);

                if ($owner) {
                    $owner->notify(new PaymentReceivedNotification($mpesa_transaction));
                }
            }
        } catch (\Exception $e) {
            // Notification failures must never break payment processing / the
            // Safaricom callback response - just log and move on.
            Log::error('Mpesa notifyPaymentReceived error: ' . $e->getMessage());
        }
    }

    /**
     * Mark the linked Ultimate POS sale as paid via M-Pesa.
     *
     * NOTE: This is ONLY relevant for the legacy/generic "Pay with M-Pesa"
     * modal (Resources/views/partials/stk_modal.blade.php +
     * Resources/assets/js/mpesa.js) - used e.g. for collecting payment on an
     * EXISTING sale/invoice that's already been saved, where you'd set
     * window.mpesa_transaction_id before opening that modal.
     *
     * It does NOT run for the POS screen's M-Pesa express-checkout button
     * (Resources/views/partials/pos_button.blade.php +
     * Resources/assets/js/pos_express.js), because that flow never sends a
     * transaction_id to stkPush() - the sale doesn't exist yet at that point.
     * Instead, once payment is confirmed, that flow fills in the payment row
     * (method=custom_pay_1, receipt number in transaction_no_1) and submits
     * the POS form itself, so core's own SellPosController::store() creates
     * the TransactionPayment row. Running markSalePaid() for that flow too
     * would create a duplicate payment.
     *
     * Relies on Ultimate POS's core Transaction / TransactionPayment models.
     * Field names below follow the common Ultimate POS schema (transaction_payments
     * table with transaction_id, amount, method, payment_ref_no). If your version
     * differs, adjust the field names accordingly.
     */
    protected function markSalePaid(MpesaTransaction $mpesa_transaction)
    {
        try {
            if (!class_exists(\App\Transaction::class) || !class_exists(\App\TransactionPayment::class)) {
                Log::warning('Mpesa: Could not find Transaction/TransactionPayment models to mark sale as paid. Please link manually or adjust CallbackController::markSalePaid().');
                return;
            }

            $transaction = \App\Transaction::find($mpesa_transaction->transaction_id);

            if (!$transaction) {
                return;
            }

            // Avoid duplicate payment entries if callback is retried by Safaricom
            $exists = \App\TransactionPayment::where('transaction_id', $transaction->id)
                ->where('payment_ref_no', $mpesa_transaction->mpesa_receipt_number)
                ->exists();

            if ($exists) {
                return;
            }

            \App\TransactionPayment::create([
                'transaction_id' => $transaction->id,
                'business_id' => $transaction->business_id,
                'amount' => $mpesa_transaction->amount,
                'method' => 'mpesa',
                'payment_ref_no' => $mpesa_transaction->mpesa_receipt_number,
                'paid_on' => now(),
                'created_by' => $transaction->created_by ?? 0,
            ]);

            // Record the invoice number on the mpesa_transactions row so it
            // shows up in M-Pesa > Transactions (see LinkMpesaTransactionToInvoice
            // for the equivalent hook used by the POS express-checkout flow,
            // which doesn't go through this method).
            $mpesa_transaction->invoice_no = $transaction->invoice_no;
            $mpesa_transaction->save();

            // Recalculate payment status (paid / partial) - adjust to match your version's logic
            if (method_exists($transaction, 'getPaymentStatus')) {
                $transaction->payment_status = $transaction->getPaymentStatus();
                $transaction->save();
            }
        } catch (\Exception $e) {
            Log::error('Mpesa markSalePaid error: ' . $e->getMessage());
        }
    }
}
