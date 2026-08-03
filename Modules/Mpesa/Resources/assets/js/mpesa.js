/**
 * M-Pesa STK Push - sell screen integration
 *
 * Expects on the page:
 *  - URLs (set these as global JS vars before including this file, see README):
 *      window.mpesa_stk_push_url
 *      window.mpesa_check_status_url
 *  - Optional global vars set by the sell screen before opening the modal:
 *      window.mpesa_transaction_id   (the sale id, if available - used to link payment to sale)
 *      window.mpesa_location_id
 *
 * On success, triggers a custom event "mpesa:payment_success" on $(document) with
 * the mpesa transaction details, so the sell screen can finalize the sale.
 */

(function ($) {
    'use strict';

    var pollInterval = null;
    var pollTimeoutAt = null;
    var POLL_EVERY_MS = 4000;
    var GIVE_UP_AFTER_MS = 120000; // 2 minutes

    function resetModal() {
        $('#mpesa_form_section').show();
        $('#mpesa_waiting_section').hide();
        $('#mpesa_success_section').hide();
        $('#mpesa_failed_section').hide();
        $('#mpesa_error').hide().text('');
        $('#mpesa_send_btn').show().prop('disabled', false);
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    function showError(msg) {
        $('#mpesa_error').text(msg).show();
        $('#mpesa_send_btn').prop('disabled', false);
    }

    function startPolling(checkoutRequestId) {
        pollTimeoutAt = Date.now() + GIVE_UP_AFTER_MS;

        pollInterval = setInterval(function () {
            if (Date.now() > pollTimeoutAt) {
                clearInterval(pollInterval);
                pollInterval = null;
                showFailed('Payment timed out. Ask the customer to confirm whether they received the prompt and try again.');
                return;
            }

            $.ajax({
                method: 'POST',
                url: window.mpesa_check_status_url,
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
                        clearInterval(pollInterval);
                        pollInterval = null;
                        showSuccess(result.mpesa_receipt_number);

                        $(document).trigger('mpesa:payment_success', {
                            checkout_request_id: checkoutRequestId,
                            mpesa_receipt_number: result.mpesa_receipt_number
                        });
                    } else if (result.status === 'failed' || result.status === 'cancelled') {
                        clearInterval(pollInterval);
                        pollInterval = null;
                        showFailed(result.result_desc || 'Payment was not completed.');
                    }
                    // 'pending' -> keep polling
                }
            });
        }, POLL_EVERY_MS);
    }

    function showWaiting() {
        $('#mpesa_form_section').hide();
        $('#mpesa_waiting_section').show();
        $('#mpesa_send_btn').hide();
    }

    function showSuccess(receipt) {
        $('#mpesa_waiting_section').hide();
        $('#mpesa_success_section').show();
        $('#mpesa_receipt_display').text(receipt ? ('Receipt: ' + receipt) : '');
    }

    function showFailed(msg) {
        $('#mpesa_waiting_section').hide();
        $('#mpesa_failed_section').show();
        $('#mpesa_failed_text').text(msg);
        $('#mpesa_send_btn').show().prop('disabled', false).text('Retry');
    }

    $(document).ready(function () {

        $('#mpesa_modal').on('show.bs.modal', function () {
            resetModal();

            // Pre-fill amount from the sell screen total if available
            if (typeof window.mpesa_default_amount !== 'undefined') {
                $('#mpesa_amount').val(window.mpesa_default_amount);
            }
        });

        $('#mpesa_send_btn').on('click', function () {
            var phone = $('#mpesa_phone').val().trim();
            var amount = $('#mpesa_amount').val();

            if (!phone) {
                showError('Please enter the customer\'s phone number.');
                return;
            }
            if (!amount || parseFloat(amount) <= 0) {
                showError('Please enter a valid amount.');
                return;
            }

            $('#mpesa_error').hide();
            $(this).prop('disabled', true);

            $.ajax({
                method: 'POST',
                url: window.mpesa_stk_push_url,
                data: {
                    phone: phone,
                    amount: amount,
                    transaction_id: window.mpesa_transaction_id || null,
                    location_id: window.mpesa_location_id || null,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        showWaiting();
                        startPolling(result.checkout_request_id);
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

        $('#mpesa_modal').on('hidden.bs.modal', function () {
            resetModal();
        });
    });

})(jQuery);
