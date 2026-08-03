<?php

namespace Modules\Mpesa\Listeners;

use App\Events\TransactionPaymentAdded;
use Illuminate\Support\Facades\Log;
use Modules\Mpesa\Entities\MpesaTransaction;

/**
 * Listens for core Ultimate POS's TransactionPaymentAdded event and, when the
 * payment being saved is an M-Pesa payment (method = "custom_pay_1", the slot
 * this module relabels to "M-Pesa"), writes the resulting sale's id and
 * invoice number back onto the matching mpesa_transactions row.
 *
 * WHY THIS IS NEEDED:
 * The POS express-checkout button (pos_button.blade.php / pos_express.js)
 * sends the STK push BEFORE the sale exists in Ultimate POS - the cashier
 * hasn't clicked anything that creates a `transactions` row yet, so at that
 * point there is no invoice number to store. The sale (and its invoice_no)
 * only gets created afterwards, when the POS form is submitted with the
 * M-Pesa receipt number already filled into payment[0][transaction_no_1].
 *
 * Core's own TransactionUtil::createOrUpdatePaymentLines() copies that value
 * into transaction_payments.transaction_no and fires TransactionPaymentAdded
 * - which is the first moment both the M-Pesa receipt number AND the sale's
 * invoice_no exist at the same time. This listener is what bridges them.
 *
 * Registered in MpesaServiceProvider::boot() via Event::listen() - no core
 * EventServiceProvider.php edit required.
 */
class LinkMpesaTransactionToInvoice
{
    public function handle(TransactionPaymentAdded $event)
    {
        try {
            $payment = $event->transactionPayment;

            // Only act on the payment method this module uses for M-Pesa.
            // (Must match SettingController::ensureCustomPaymentLabel()'s slot.)
            if ($payment->method !== 'custom_pay_1') {
                return;
            }

            // The M-Pesa receipt number was stamped into this field by
            // pos_express.js (payment[0][transaction_no_1]) - see
            // TransactionUtil::createOrUpdatePaymentLines()'s custom_pay_X loop.
            $receipt_number = $payment->transaction_no;

            if (empty($receipt_number)) {
                return;
            }

            $mpesa_transaction = MpesaTransaction::where('business_id', $payment->business_id)
                ->where('mpesa_receipt_number', $receipt_number)
                ->where('status', 'success')
                ->first();

            if (!$mpesa_transaction) {
                // Could be a manually-entered "M-Pesa" payment with no matching
                // STK push row (e.g. cashier typed the receipt number in
                // manually for a paybill payment). Nothing to link - that's fine.
                return;
            }

            $transaction = $payment->transaction; // App\Transaction, via relation on TransactionPayment

            if (!$transaction) {
                return;
            }

            $mpesa_transaction->transaction_id = $transaction->id;
            $mpesa_transaction->invoice_no = $transaction->invoice_no;
            $mpesa_transaction->save();
        } catch (\Exception $e) {
            // Never let a notification/linking failure break the sale itself.
            Log::error('Mpesa LinkMpesaTransactionToInvoice error: ' . $e->getMessage());
        }
    }
}
