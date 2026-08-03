<?php

namespace Modules\Mpesa\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mpesa\Entities\MpesaSetting;
use Modules\Mpesa\Entities\MpesaTransaction;
use Modules\Mpesa\Services\DarajaService;

class MpesaController extends Controller
{
    /**
     * AJAX endpoint called from the POS "Pay with M-Pesa" modal.
     */
    public function stkPush(Request $request)
    {
        try {
            if (!auth()->user()->can('mpesa.collect_payment')) {
                abort(403, 'Unauthorized action.');
            }

            $request->validate([
                'phone' => 'required|string',
                'amount' => 'required|numeric|min:1',
            ]);

            $business_id = $request->session()->get('user.business_id');

            $service = DarajaService::forBusiness($business_id);

            $phone = $this->normalizePhone($request->input('phone'));

            $callback_url = action('\Modules\Mpesa\Http\Controllers\CallbackController@stkCallback', ['business_id' => $business_id]);

            $account_reference = $request->input('account_reference') ?: ('POS-' . ($request->input('transaction_id') ?: time()));

            $response = $service->stkPush(
                $phone,
                (float) $request->input('amount'),
                $account_reference,
                $callback_url
            );

            if (($response['ResponseCode'] ?? null) !== '0') {
                throw new \Exception($response['ResponseDescription'] ?? 'Failed to initiate M-Pesa payment.');
            }

            $mpesa_transaction = MpesaTransaction::create([
                'business_id'          => $business_id,
                'location_id'          => $request->input('location_id'),
                'transaction_id'       => $request->input('transaction_id'),
                'type'                 => 'stk',
                'status'               => 'pending',
                'phone_number'         => $phone,
                'amount'               => $request->input('amount'),
                'merchant_request_id'  => $response['MerchantRequestID'] ?? null,
                'checkout_request_id'  => $response['CheckoutRequestID'] ?? null,
                'account_reference'    => $account_reference,
            ]);

            $output = [
                'success'              => true,
                'msg'                  => __('mpesa::lang.stk_push_sent'),
                'checkout_request_id'  => $mpesa_transaction->checkout_request_id,
                'mpesa_transaction_id' => $mpesa_transaction->id,
            ];
        } catch (\Exception $e) {
            $output = [
                'success' => false,
                'msg'     => $e->getMessage(),
            ];
        }

        return response()->json($output);
    }

    /**
     * AJAX: poll payment status.
     */
    public function checkStatus(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');
        $checkout_request_id = $request->input('checkout_request_id');

        $mpesa_transaction = MpesaTransaction::forBusiness($business_id)
            ->where('checkout_request_id', $checkout_request_id)
            ->first();

        if (!$mpesa_transaction) {
            return response()->json([
                'success' => false,
                'status'  => 'not_found',
                'msg'     => __('mpesa::lang.transaction_not_found'),
            ]);
        }

        return response()->json([
            'success'              => true,
            'status'               => $mpesa_transaction->status,
            'mpesa_receipt_number' => $mpesa_transaction->mpesa_receipt_number,
            'result_desc'          => $mpesa_transaction->result_desc,
        ]);
    }

    /**
     * AJAX: return recent successful M-Pesa transactions not yet linked to a sale.
     */
    public function recentPayments(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');

            // Cart total, sent by the POS screen, used to surface the most
            // useful match first - an unlinked M-Pesa payment for exactly
            // (or closest to) what's currently in the cart.
            $cart_total = $request->input('cart_total');
            $cart_total = is_numeric($cart_total) ? (float) $cart_total : null;

            $query = MpesaTransaction::forBusiness($business_id)
                ->where('status', 'success')
                // Show both unused (transaction_id IS NULL) and used (transaction_id IS NOT NULL)
                // so cashiers can see the full recent history. Used ones are flagged below.
                ->whereNotNull('mpesa_receipt_number'); // must have a receipt

            // Cashier can search by phone number (07.., 01.., 2547.., or just
            // the last several digits) or by the M-Pesa receipt/transaction
            // code (e.g. "UFJPL7ZFNF" or a fragment of it like "FNF").
            $search = trim((string) $request->input('search'));
            if ($search !== '') {
                $digits = preg_replace('/[^0-9]/', '', $search);

                // Normalize a locally-formatted phone number (07.., 01..) into
                // the 2547../2541.. format actually stored in phone_number.
                // Without this, searching "0712345678" never matched anything,
                // since the stored value is "254712345678" - "07" never
                // appears as a literal substring inside that.
                $normalized_phone = null;
                if ($digits !== '') {
                    if (str_starts_with($digits, '0') && strlen($digits) >= 2) {
                        $normalized_phone = '254' . substr($digits, 1);
                    } elseif (str_starts_with($digits, '254')) {
                        $normalized_phone = $digits;
                    } elseif (str_starts_with($digits, '7') || str_starts_with($digits, '1')) {
                        $normalized_phone = '254' . $digits;
                    }
                }

                // Only treat this as "search by digits" when the search term
                // looks like a phone number on its own (i.e. it's ALL digits -
                // optionally with spaces/dashes/+ that got stripped above).
                // Without this guard, a receipt code that merely *contains* a
                // digit (e.g. "UFJPL7ZFNF" contains "7") would incidentally
                // match phone_number LIKE '%7%' against almost every record,
                // since "7" appears in nearly every 2547XXXXXXXX number -
                // which is exactly why a full receipt code search was
                // wrongly returning everything instead of nothing/one match.
                $looks_like_phone_only = $digits !== '' && preg_match('/^[0-9+\-\s]+$/', $search);

                $query->where(function ($q) use ($search, $digits, $normalized_phone, $looks_like_phone_only) {
                    $q->where('mpesa_receipt_number', 'like', "%{$search}%");

                    if ($looks_like_phone_only) {
                        if ($normalized_phone) {
                            $q->orWhere('phone_number', 'like', "%{$normalized_phone}%");
                        } else {
                            $q->orWhere('phone_number', 'like', "%{$digits}%");
                        }
                    }
                });
            }

            $transactions = $query
                ->orderBy('created_at', 'desc')
                ->limit(25) // pull a bit more than we show, so an exact match
                            // further back in time still surfaces after pinning
                ->get(['id', 'mpesa_receipt_number', 'phone_number', 'amount', 'created_at', 'transaction_id', 'invoice_no']);

            if ($cart_total !== null && $search === '') {
                // Keep everything latest-first (that's the natural, expected
                // order), but pin any exact, still-unused match(es) to the
                // very top so the cashier doesn't have to scroll/hunt for it.
                // This does NOT resort the whole list by amount - only an
                // actual exact match jumps the queue.
                //
                // Skipped entirely while actively searching: if the cashier
                // typed a phone number or receipt code, they're hunting for a
                // SPECIFIC transaction, not asking "what matches my cart" -
                // pinning an unrelated exact-match transaction above their
                // actual search result would be confusing, not helpful.
                $transactions = $transactions->sortByDesc(function ($tx) use ($cart_total) {
                    $is_unused = is_null($tx->transaction_id) || $tx->transaction_id == 0;
                    $is_exact = $is_unused && abs((float) $tx->amount - $cart_total) < 0.01;

                    // Sort key: [is_exact_match_flag, original recency rank]
                    // PHP's stable-ish sort + this composite key keeps
                    // everything else in its original (latest-first) order.
                    return $is_exact ? 1 : 0;
                })->values();
            }

            $transactions = $transactions->take(10)->values();

            // Flag exact matches, used status, and a human-readable local-time
            // string for the client. created_at serializes to JSON as raw UTC
            // ISO-8601 by default (e.g. "2026-06-18T13:14:21.000000Z") - that's
            // what was showing up unformatted in the panel. The app's
            // Timezone middleware already calls date_default_timezone_set()
            // with the business's configured timezone for every authenticated
            // request, so setTimezone(date_default_timezone_get()) re-renders
            // the same instant using that already-correct local timezone.
            $transactions = $transactions->map(function ($tx) use ($cart_total) {
                // A transaction is "used" if transaction_id is set and non-zero
                $tx->is_used = !is_null($tx->transaction_id) && $tx->transaction_id != 0;
                $tx->is_exact_match = !$tx->is_used && ($cart_total !== null) && abs((float) $tx->amount - $cart_total) < 0.01;
                $tx->display_time = $tx->created_at
                    ->copy()
                    ->setTimezone(date_default_timezone_get())
                    ->format('M j, g:i A');
                return $tx;
            });

            return response()->json([
                'success'      => true,
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: link an existing M-Pesa transaction to the current sale and finalize.
     * Called when cashier picks a recent payment from the panel.
     */
    public function linkPayment(Request $request)
    {
        try {
            $request->validate([
                'mpesa_transaction_id' => 'required|integer',
                'cart_total' => 'required|numeric|min:0.01',
            ]);

            $business_id = $request->session()->get('user.business_id');
            $mpesa_transaction_id = $request->input('mpesa_transaction_id');

            $mpesa_transaction = MpesaTransaction::forBusiness($business_id)
                ->where('id', $mpesa_transaction_id)
                ->first();

            if (!$mpesa_transaction) {
                return response()->json([
                    'success' => false,
                    'msg' => 'This M-Pesa transaction no longer exists.',
                ]);
            }

            if ($mpesa_transaction->status !== 'success') {
                return response()->json([
                    'success' => false,
                    'msg' => 'This M-Pesa payment is not confirmed yet, so it cannot be used.',
                ]);
            }

            // Authoritative amount check - this is what actually enforces the
            // rule; the client-side check in pos_button.blade.php is just so
            // the cashier gets instant feedback without a round trip. A
            // payment can only be used if it matches the cart total exactly
            // (to the cent), no split payments / over- or under-payments.
            $cart_total = (float) $request->input('cart_total');
            if (abs((float) $mpesa_transaction->amount - $cart_total) >= 0.01) {
                return response()->json([
                    'success' => false,
                    'msg' => 'This M-Pesa payment (' . number_format($mpesa_transaction->amount, 2) .
                        ') does not match the cart total (' . number_format($cart_total, 2) .
                        '). Pick a payment with the exact amount.',
                ]);
            }

            // Race-condition guard: atomically claim the row only if it's
            // still unclaimed (transaction_id IS NULL or 0), so two cashiers
            // double-clicking the same card - or one cashier double-clicking
            // fast - can never both succeed. The update() call's WHERE clause
            // re-checks "still unclaimed" at the database level, not just in
            // the PHP we read a moment ago.
            $claimed = MpesaTransaction::where('id', $mpesa_transaction->id)
                ->where(function ($q) {
                    $q->whereNull('transaction_id')->orWhere('transaction_id', 0);
                })
                ->update(['transaction_id' => $request->input('transaction_id') ?? -1]);

            if ($claimed === 0) {
                // Someone else (or a duplicate click) claimed it a moment ago.
                return response()->json([
                    'success' => false,
                    'msg' => 'This M-Pesa payment was just used for another sale. Please refresh and pick a different one.',
                ]);
            }

            return response()->json([
                'success'              => true,
                'mpesa_receipt_number' => $mpesa_transaction->mpesa_receipt_number,
                'amount'               => $mpesa_transaction->amount,
                'phone_number'         => $mpesa_transaction->phone_number,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * List M-Pesa transactions for the business.
     */
    public function transactions(Request $request)
    {
        if (!auth()->user()->can('mpesa.view_transactions')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $query = MpesaTransaction::forBusiness($business_id);

        // Simple search box on the Transactions page: matches invoice number,
        // M-Pesa receipt number, or phone number - covers the common ways an
        // admin would want to "look up" a specific M-Pesa payment.
        $search = trim((string) $request->input('search'));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('mpesa_receipt_number', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('id', 'desc')
            ->paginate(25)
            ->appends($request->only('search'));

        return view('mpesa::transactions.index', compact('transactions', 'search'));
    }

    /**
     * Normalize phone to 2547XXXXXXXX format.
     */
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        } elseif (str_starts_with($phone, '7') || str_starts_with($phone, '1')) {
            $phone = '254' . $phone;
        }

        return $phone;
    }
}