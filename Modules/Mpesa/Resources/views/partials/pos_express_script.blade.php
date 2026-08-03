<script>
/**
 * M-Pesa express-checkout + Recent Payments integration for the POS sell screen.
 */
(function ($) {
    'use strict';

    var pollInterval  = null;
    var pollDeadline  = null;
    var POLL_EVERY_MS = 4000;
    var GIVE_UP_AFTER_MS = 180000; // 3 minutes
    var currentCheckoutRequestId = null;
    var mpesaReceiptNumber = null;

    function paymentMethodKey() {
        return $('#mpesa_payment_method_key').val() || 'custom_pay_1';
    }

    // ─── Modal helpers ────────────────────────────────────────────────────────

    function resetModal() {
        $('#mpesa_pos_form_section').show();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_success_section').hide();
        $('#mpesa_pos_failed_section').hide();
        $('#mpesa_pos_error').hide().text('');
        $('#mpesa_pos_waiting_note').text('');
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
        // Refresh the recent panel so the used transaction disappears
        loadRecentPayments();
    }

    function showFailed(msg) {
        stopPolling();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_failed_section').show();
        $('#mpesa_pos_failed_text').text(msg);
        $('#mpesa_pos_send_btn').show().prop('disabled', false).text('Retry');
        $('#mpesa_pos_cancel_btn').show();
        $('#mpesa_pos_close_x').show();
        // Auto-load recent payments panel so cashier can pick one immediately
        loadRecentPayments();
        showRecentPanel();
    }

    // ─── Polling ──────────────────────────────────────────────────────────────

    function startPolling(checkoutRequestId) {
        pollDeadline = Date.now() + GIVE_UP_AFTER_MS;

        pollInterval = setInterval(function () {
            if (Date.now() > pollDeadline) {
                showFailed('Payment timed out. If the customer already paid, use the Recent M-Pesa Payments panel.');
                return;
            }

            // Show elapsed time hint
            var elapsed = Math.round((GIVE_UP_AFTER_MS - (pollDeadline - Date.now())) / 1000);
            $('#mpesa_pos_waiting_note').text('Waiting... ' + elapsed + 's');

            $.ajax({
                method: 'POST',
                url: $('#mpesa_check_status_url').val(),
                data: {
                    checkout_request_id: checkoutRequestId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    if (!result.success) { return; }

                    if (result.status === 'success') {
                        mpesaReceiptNumber = result.mpesa_receipt_number;
                        showSuccess(mpesaReceiptNumber);
                        setTimeout(finalizeSaleAsMpesa, 900);
                    } else if (result.status === 'failed' || result.status === 'cancelled') {
                        showFailed(result.result_desc || 'Payment was not completed.');
                    }
                    // pending → keep polling
                },
                error: function () {
                    // network hiccup — try again next tick
                }
            });
        }, POLL_EVERY_MS);
    }

    // ─── Finalize sale ────────────────────────────────────────────────────────

    function finalizeSaleAsMpesa(receiptOverride) {
        var methodKey    = paymentMethodKey();
        var receipt      = receiptOverride || mpesaReceiptNumber;
        var total_payable = __read_number($('input#final_total_input'));

        var first_row = $('#payment_rows_div').find('.payment-amount').first();
        __write_number(first_row, total_payable);
        first_row.trigger('change');

        var payment_method_dropdown = $('#payment_rows_div').find('.payment_types_dropdown').first();
        payment_method_dropdown.val(methodKey);
        payment_method_dropdown.change();

        if (receipt) {
            $('input[name="payment[0][transaction_no_1]"]').val(receipt);
        }

        $('#mpesa_pos_modal').modal('hide');
        hideRecentPanel();

        if (typeof pos_form_obj !== 'undefined' && pos_form_obj) {
            pos_form_obj.submit();
        } else {
            $('#add_pos_sell_form').submit();
        }
    }

    // ─── Recent Payments Panel ────────────────────────────────────────────────

    function showRecentPanel() {
        $('#mpesa_recent_panel').show();
    }

    function hideRecentPanel() {
        $('#mpesa_recent_panel').hide();
    }

    function formatPhone(phone) {
        // Show as 07XXXXXXXX for readability
        if (phone && phone.toString().startsWith('254')) {
            return '0' + phone.toString().substring(3);
        }
        return phone || '-';
    }

    function formatAmount(amount) {
        return 'Ksh ' + parseFloat(amount).toLocaleString('en-KE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function loadRecentPayments() {
        var $list = $('#mpesa_recent_list');
        $list.html('<div id="mpesa_recent_loading" style="text-align:center;padding:16px;color:#888;"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
        $('#mpesa_recent_footer').hide();

        // Show cart total
        var total_payable = __read_number($('input#final_total_input'));
        $('#mpesa_recent_cart_total').text(formatAmount(total_payable));

        $.ajax({
            method: 'GET',
            url: $('#mpesa_recent_payments_url').val(),
            dataType: 'json',
            success: function (result) {
                $list.empty();

                if (!result.success || !result.transactions || result.transactions.length === 0) {
                    $list.html('<div style="text-align:center;padding:16px;color:#888;">No recent unlinked M-Pesa payments found.</div>');
                    return;
                }

                $.each(result.transactions, function (i, tx) {
                    var amountMatch = Math.abs(parseFloat(tx.amount) - total_payable) < 1;
                    var remaining   = total_payable - parseFloat(tx.amount);
                    var remainingTxt = remaining > 0
                        ? '<span style="color:#c0392b;font-size:11px;">Cash remaining: ' + formatAmount(remaining) + '</span>'
                        : (remaining < 0
                            ? '<span style="color:#e67e22;font-size:11px;">Overpaid by: ' + formatAmount(Math.abs(remaining)) + '</span>'
                            : '<span style="color:#27ae60;font-size:11px;">Exact amount ✓</span>');

                    var item = $(
                        '<div class="mpesa-recent-item" data-id="' + tx.id + '" data-receipt="' + tx.mpesa_receipt_number + '" data-amount="' + tx.amount + '" style="'
                        + 'border:1px solid ' + (amountMatch ? '#00A859' : '#ddd') + ';'
                        + 'border-radius:7px;padding:10px 12px;margin-bottom:8px;cursor:pointer;'
                        + 'background:' + (amountMatch ? '#f0fbf5' : '#fafafa') + ';'
                        + 'transition:background 0.15s;">'
                        + '<div style="display:flex;justify-content:space-between;align-items:center;">'
                        +   '<strong style="color:#00A859;font-size:13px;">' + tx.mpesa_receipt_number + '</strong>'
                        +   '<span style="font-size:13px;font-weight:bold;">' + formatAmount(tx.amount) + '</span>'
                        + '</div>'
                        + '<div style="color:#666;font-size:11px;margin-top:3px;">'
                        +   formatPhone(tx.phone_number) + ' &bull; ' + tx.created_at
                        + '</div>'
                        + '<div style="margin-top:4px;">' + remainingTxt + '</div>'
                        + '</div>'
                    );

                    item.on('mouseenter', function () { $(this).css('background', '#e8f8f0'); });
                    item.on('mouseleave', function () { $(this).css('background', amountMatch ? '#f0fbf5' : '#fafafa'); });

                    item.on('click', function () {
                        linkAndFinalize($(this).data('id'), $(this).data('receipt'), $(this).data('amount'));
                    });

                    $list.append(item);
                });

                $('#mpesa_recent_footer').show();
                $('#mpesa_recent_hint').text('Select a payment to complete the sale.');
            },
            error: function () {
                $list.html('<div style="text-align:center;padding:16px;color:#c0392b;">Failed to load recent payments.</div>');
            }
        });
    }

    function linkAndFinalize(mpesaTransactionId, receipt, amount) {
        // Confirm with cashier if amounts don't match
        var total_payable = __read_number($('input#final_total_input'));
        var remaining = total_payable - parseFloat(amount);

        if (remaining > 0.5) {
            if (!confirm('This payment is Ksh ' + parseFloat(amount).toFixed(2) + ' but the cart total is Ksh ' + total_payable.toFixed(2) + '.\nCash remaining: Ksh ' + remaining.toFixed(2) + '.\n\nLink this M-Pesa payment and complete the sale?')) {
                return;
            }
        }

        // Mark as linked in the backend
        $.ajax({
            method: 'POST',
            url: $('#mpesa_link_payment_url').val(),
            data: {
                mpesa_transaction_id: mpesaTransactionId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    mpesaReceiptNumber = result.mpesa_receipt_number;
                    finalizeSaleAsMpesa(mpesaReceiptNumber);
                } else {
                    alert('Could not link payment: ' + (result.msg || 'Unknown error'));
                }
            },
            error: function () {
                alert('Network error while linking payment. Please try again.');
            }
        });
    }

    // ─── Init ─────────────────────────────────────────────────────────────────

    $(document).ready(function () {

        // STK Push button click
        $('#mpesa-express-checkout').click(function () {
            if ($('table#pos_table tbody').find('.product_row').length <= 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning(typeof LANG !== 'undefined' ? LANG.no_products_added : 'Please add at least one product.');
                }
                return false;
            }

            if ($('#reward_point_enabled').length && typeof isValidatRewardPoint === 'function') {
                var validate_rp = isValidatRewardPoint();
                if (!validate_rp['is_valid']) {
                    if (typeof toastr !== 'undefined') { toastr.error(validate_rp['msg']); }
                    return false;
                }
            }

            var total_payable = __read_number($('input#final_total_input'));
            if (!total_payable || total_payable <= 0) {
                if (typeof toastr !== 'undefined') { toastr.warning('Total payable amount must be greater than zero.'); }
                return false;
            }

            resetModal();
            $('#mpesa_pos_amount').val(total_payable.toFixed(2));

            var customer_mobile = $('#contact_id').closest('.form-group').find('.mobile_number').val()
                || $('input[name="mobile_number"]').val();
            if (customer_mobile) { $('#mpesa_pos_phone').val(customer_mobile); }

            $('#mpesa_pos_modal').modal('show');
        });

        // Send STK push
        $('#mpesa_pos_send_btn').click(function () {
            var phone  = $('#mpesa_pos_phone').val().trim();
            var amount = $('#mpesa_pos_amount').val();

            if (!phone)                        { showError('Please enter the customer\'s phone number.'); return; }
            if (!amount || parseFloat(amount) <= 0) { showError('Invalid amount.'); return; }

            $('#mpesa_pos_error').hide();
            $(this).prop('disabled', true).text('Sending...');

            $.ajax({
                method: 'POST',
                url: $('#mpesa_stk_push_url').val(),
                data: {
                    phone: phone,
                    amount: amount,
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
                    if (xhr.responseJSON && xhr.responseJSON.msg) { msg = xhr.responseJSON.msg; }
                    showError(msg);
                }
            });
        });

        // Recent M-Pesa button toggle
        $(document).on('click', '#mpesa_recent_toggle_btn', function () {
            if ($('#mpesa_recent_panel').is(':visible')) {
                hideRecentPanel();
            } else {
                loadRecentPayments();
                showRecentPanel();
            }
        });

        // Close panel X
        $(document).on('click', '#mpesa_recent_close', function () {
            hideRecentPanel();
        });

        // Reset modal on close
        $('#mpesa_pos_modal').on('hidden.bs.modal', function () {
            resetModal();
        });
    });

})(jQuery);
</script>
