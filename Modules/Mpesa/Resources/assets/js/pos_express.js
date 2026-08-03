/**
 * M-Pesa express-checkout integration for the UltimatePOS sell (POS) screen.
 *
 * Behaves exactly like the built-in Cash / Card express-checkout buttons
 * (see public/js/pos.js, "button.pos-express-finalize" handler):
 *   1. Validates that products have been added.
 *   2. Tops up the first payment row to cover the full balance due.
 *   3. Sets that row's payment method.
 *   4. Submits the POS sale form.
 *
 * The only difference is that BEFORE step 4 we have to actually collect the
 * money: we open a modal, send an STK push to the customer's phone, and poll
 * until Safaricom confirms payment (or it fails/times out). Only then do we
 * fill in the row and submit - so a sale is never recorded as paid until the
 * customer has actually paid.
 */
(function ($) {
    'use strict';

    var pollInterval = null;
    var pollDeadline = null;
    var POLL_EVERY_MS = 4000;
    var GIVE_UP_AFTER_MS = 120000; // 2 minutes - matches Safaricom's own STK push expiry window
    var currentCheckoutRequestId = null;
    var mpesaReceiptNumber = null;

    function paymentMethodKey() {
        return $('#mpesa_payment_method_key').val() || 'custom_pay_1';
    }

    function resetModal() {
        $('#mpesa_pos_form_section').show();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_success_section').hide();
        $('#mpesa_pos_failed_section').hide();
        $('#mpesa_pos_error').hide().text('');
        $('#mpesa_pos_send_btn').show().prop('disabled', false).text('Send Payment Request');
        $('#mpesa_pos_cancel_btn').show();
        $('#mpesa_pos_close_x').show();
        mpesaReceiptNumber = null;
        currentCheckoutRequestId = null;
        stopPolling();
    }

    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    function showError(msg) {
        $('#mpesa_pos_error').text(msg).show();
        $('#mpesa_pos_send_btn').prop('disabled', false).text('Send Payment Request');
    }

    function showWaiting() {
        $('#mpesa_pos_form_section').hide();
        $('#mpesa_pos_send_btn').hide();
        $('#mpesa_pos_cancel_btn').hide();
        $('#mpesa_pos_waiting_section').show();
    }

    function showSuccess(receipt) {
        stopPolling();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_success_section').show();
        $('#mpesa_pos_receipt_display').text(receipt ? ('Receipt: ' + receipt) : '');
    }

    function showFailed(msg) {
        stopPolling();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_failed_section').show();
        $('#mpesa_pos_failed_text').text(msg);
        $('#mpesa_pos_send_btn').show().prop('disabled', false).text('Retry');
        $('#mpesa_pos_cancel_btn').show();
        $('#mpesa_pos_close_x').show();
    }

    function startPolling(checkoutRequestId) {
        pollDeadline = Date.now() + GIVE_UP_AFTER_MS;

        pollInterval = setInterval(function () {
            if (Date.now() > pollDeadline) {
                showFailed('Payment timed out. Ask the customer to confirm whether they received the prompt, then try again.');
                return;
            }

            $.ajax({
                method: 'POST',
                url: $('#mpesa_check_status_url').val(),
                data: {
                    checkout_request_id: checkoutRequestId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    if (!result.success) {
                        return;
                    }

                    if (result.status === 'success') {
                        mpesaReceiptNumber = result.mpesa_receipt_number;
                        showSuccess(mpesaReceiptNumber);
                        // Give the cashier/customer a beat to see the confirmation,
                        // then finalize the sale automatically - same instant feel
                        // as clicking Cash/Card.
                        setTimeout(finalizeSaleAsMpesa, 900);
                    } else if (result.status === 'failed' || result.status === 'cancelled') {
                        showFailed(result.result_desc || 'Payment was not completed.');
                    }
                    // status === 'pending' -> keep polling, do nothing
                },
                error: function () {
                    // Network hiccup - don't kill the poll, just try again next tick
                }
            });
        }, POLL_EVERY_MS);
    }

    /**
     * Fill the first payment row with the M-Pesa method + receipt number and
     * submit the form - identical mechanics to the core
     * "button.pos-express-finalize" handler for cash/card.
     */
    function finalizeSaleAsMpesa() {
        var methodKey = paymentMethodKey();

        var total_payable = __read_number($('input#final_total_input'));

        var first_row = $('#payment_rows_div').find('.payment-amount').first();
        __write_number(first_row, total_payable);
        first_row.trigger('change');

        var payment_method_dropdown = $('#payment_rows_div').find('.payment_types_dropdown').first();
        payment_method_dropdown.val(methodKey);
        payment_method_dropdown.change();

        // Stamp the M-Pesa receipt number into that row's "transaction no" field
        // (the same field used for custom_pay_X reference numbers), so it's
        // visible later on the sale, receipt, and in reports. This targets the
        // first payment row (row index 0), which is the row the express
        // checkout flow always uses.
        if (mpesaReceiptNumber) {
            $('input[name="payment[0][transaction_no_1]"]').val(mpesaReceiptNumber);
        }

        $('#mpesa_pos_modal').modal('hide');

        if (typeof pos_form_obj !== 'undefined' && pos_form_obj) {
            pos_form_obj.submit();
        } else {
            $('#add_pos_sell_form').submit();
        }
    }

    $(document).ready(function () {

        $('#mpesa-express-checkout').click(function () {
            // Same pre-flight checks core Cash/Card buttons run
            if ($('table#pos_table tbody').find('.product_row').length <= 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning(typeof LANG !== 'undefined' ? LANG.no_products_added : 'Please add at least one product.');
                }
                return false;
            }

            if ($('#reward_point_enabled').length && typeof isValidatRewardPoint === 'function') {
                var validate_rp = isValidatRewardPoint();
                if (!validate_rp['is_valid']) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(validate_rp['msg']);
                    }
                    return false;
                }
            }

            var total_payable = __read_number($('input#final_total_input'));
            if (!total_payable || total_payable <= 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('Total payable amount must be greater than zero.');
                }
                return false;
            }

            resetModal();
            $('#mpesa_pos_amount').val(total_payable.toFixed(2));

            // Pre-fill phone from the selected customer's mobile number, if present
            var customer_mobile = $('#contact_id').closest('.form-group').find('.mobile_number').val()
                || $('input[name="mobile_number"]').val();
            if (customer_mobile) {
                $('#mpesa_pos_phone').val(customer_mobile);
            }

            $('#mpesa_pos_modal').modal('show');
        });

        $('#mpesa_pos_send_btn').click(function () {
            var phone = $('#mpesa_pos_phone').val().trim();
            var amount = $('#mpesa_pos_amount').val();

            if (!phone) {
                showError('Please enter the customer\'s phone number.');
                return;
            }
            if (!amount || parseFloat(amount) <= 0) {
                showError('Invalid amount.');
                return;
            }

            $('#mpesa_pos_error').hide();
            $(this).prop('disabled', true).text('Sending...');

            $.ajax({
                method: 'POST',
                url: $('#mpesa_stk_push_url').val(),
                data: {
                    phone: phone,
                    amount: amount,
                    // Intentionally NOT sending transaction_id here: in the express
                    // checkout flow the sale doesn't exist in Ultimate POS yet (it's
                    // only created when we submit the form in finalizeSaleAsMpesa()
                    // below). Because transaction_id is omitted, CallbackController's
                    // markSalePaid() correctly skips this transaction - core's own
                    // SellPosController::store() is what creates the TransactionPayment
                    // row (with method=custom_pay_1 and the receipt number), avoiding
                    // any risk of double-paying the sale.
                    location_id: $('#location_id').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        currentCheckoutRequestId = result.checkout_request_id;
                        showWaiting();
                        startPolling(currentCheckoutRequestId);
                    } else {
                        showError(result.msg);
                    }
                },
                error: function (xhr) {
                    var msg = 'Something went wrong while sending the payment request.';
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        msg = xhr.responseJSON.msg;
                    }
                    showError(msg);
                }
            });
        });

        $('#mpesa_pos_modal').on('hidden.bs.modal', function () {
            // If the cashier dismisses mid-wait, stop polling - don't auto-finalize
            // a sale they backed out of.
            resetModal();
        });
    });

})(jQuery);
