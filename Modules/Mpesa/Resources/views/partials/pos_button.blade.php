{{--
    M-Pesa POS integration — STK Push modal + Recent Payments panel.
    ALL JavaScript is inline here. pos_express_script.blade.php is NOT used.
--}}

@php
    $__mpesa_business_id = session()->get('user.business_id');
    $__mpesa_settings = \Modules\Mpesa\Entities\MpesaSetting::getForBusiness($__mpesa_business_id);
    $__mpesa_active = $__mpesa_settings && $__mpesa_settings->is_enabled;
@endphp

@if ($__mpesa_active)

{{-- ══════════════════════════ STK PUSH MODAL ══════════════════════════ --}}
<div class="modal fade" tabindex="-1" role="dialog" id="mpesa_pos_modal"
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#00A859;">
                <button type="button" class="close" data-dismiss="modal"
                        id="mpesa_pos_close_x" aria-label="Close">
                    <span aria-hidden="true" style="color:#fff;">&times;</span>
                </button>
                <h4 class="modal-title" style="color:#fff;">
                    <i class="fas fa-mobile-alt"></i> Pay with M-Pesa
                </h4>
            </div>
            <div class="modal-body">
                {{-- Step 1: enter phone + amount --}}
                <div id="mpesa_pos_form_section">
                    <div class="form-group">
                        <label>Customer Phone Number</label>
                        <input type="text" id="mpesa_pos_phone" class="form-control"
                               placeholder="07XXXXXXXX or 2547XXXXXXXX" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Amount (KES)</label>
                        <input type="number" id="mpesa_pos_amount" class="form-control"
                               step="0.01" min="1" readonly>
                        <p class="help-block">Total payable amount for this sale.</p>
                    </div>
                    <div id="mpesa_pos_error" class="alert alert-danger" style="display:none;"></div>
                </div>
                {{-- Step 2: waiting for PIN --}}
                <div id="mpesa_pos_waiting_section" style="display:none;text-align:center;">
                    <i class="fa fa-spinner fa-spin fa-3x" style="color:#00A859;"></i>
                    <p style="margin-top:15px;">
                        Payment request sent. Ask the customer to check their phone and enter their M-Pesa PIN…
                    </p>
                    <p id="mpesa_pos_waiting_note" style="color:#999;font-size:12px;"></p>
                </div>
                {{-- Step 3a: success --}}
                <div id="mpesa_pos_success_section" style="display:none;text-align:center;">
                    <i class="fa fa-check-circle fa-3x text-green"></i>
                    <p style="margin-top:15px;">Payment received! Completing sale…</p>
                    <p><strong id="mpesa_pos_receipt_display"></strong></p>
                </div>
                {{-- Step 3b: failed --}}
                <div id="mpesa_pos_failed_section" style="display:none;text-align:center;">
                    <i class="fa fa-times-circle fa-3x text-red"></i>
                    <p style="margin-top:15px;" id="mpesa_pos_failed_text">Payment failed or was cancelled.</p>
                    <p style="font-size:13px;color:#555;margin-top:8px;">
                        If the customer already paid, close this and tap
                        <strong style="color:#00A859;">Recent M-Pesa</strong> on the sell screen.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" id="mpesa_pos_cancel_btn">Cancel</button>
                <button type="button" id="mpesa_pos_send_btn"
                        style="background:#00A859;color:#fff;border:none;padding:8px 20px;
                               border-radius:5px;font-weight:bold;cursor:pointer;">
                    Send Payment Request
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════ RECENT M-PESA PANEL ══════════════════════ --}}
<div id="mpesa_recent_panel" style="
    display:none;
    position:fixed;
    bottom:70px;
    right:16px;
    width:340px;
    max-height:78vh;
    background:#fff;
    border:2px solid #00A859;
    border-radius:10px;
    box-shadow:0 6px 24px rgba(0,0,0,0.2);
    z-index:99999;
    font-size:13px;
    overflow:hidden;
    flex-direction:column;
">
    <div style="background:#00A859;color:#fff;padding:11px 14px;
                display:flex;justify-content:space-between;align-items:center;">
        <strong><i class="fas fa-mobile-alt"></i> Recent M-Pesa Payments</strong>
        <span id="mpesa_recent_close" style="cursor:pointer;font-size:22px;line-height:1;">&times;</span>
    </div>
    <div style="padding:8px 14px 4px;border-bottom:1px solid #eee;">
        <span style="font-size:12px;color:#555;">
            Cart Total: <strong id="mpesa_recent_cart_total" style="color:#00A859;"></strong>
        </span>
    </div>
    <div style="padding:8px 14px;border-bottom:1px solid #eee;">
        <input type="text" id="mpesa_recent_search" class="form-control" style="font-size:12px;height:32px;"
            placeholder="Search by phone or M-Pesa code…" autocomplete="off">
    </div>
    <div id="mpesa_recent_list" style="overflow-y:auto;max-height:55vh;padding:10px 14px 8px;"></div>
    <div id="mpesa_recent_footer"
         style="display:none;padding:7px 14px 10px;border-top:1px solid #eee;
                font-size:11px;color:#999;">
        Tap a payment to link it to this sale.
    </div>
</div>

{{-- ══════════════════════ HIDDEN CONFIG INPUTS ══════════════════════ --}}
<input type="hidden" id="mpesa_stk_push_url"
    value="{{ action(\Modules\Mpesa\Http\Controllers\MpesaController::class . '@stkPush') }}">
<input type="hidden" id="mpesa_check_status_url"
    value="{{ action(\Modules\Mpesa\Http\Controllers\MpesaController::class . '@checkStatus') }}">
<input type="hidden" id="mpesa_recent_payments_url"
    value="{{ action(\Modules\Mpesa\Http\Controllers\MpesaController::class . '@recentPayments') }}">
<input type="hidden" id="mpesa_link_payment_url"
    value="{{ action(\Modules\Mpesa\Http\Controllers\MpesaController::class . '@linkPayment') }}">
<input type="hidden" id="mpesa_payment_method_key" value="custom_pay_1">

{{-- ══════════════════════ ALL JAVASCRIPT ══════════════════════ --}}
<script>
(function ($) {
    'use strict';

    /* ── config ─────────────────────────────────────────────────── */
    var POLL_MS      = 4000;
    var TIMEOUT_MS   = 180000; // 3 min
    var pollTimer    = null;
    var pollDeadline = null;
    var currentCRID  = null;   // CheckoutRequestID being polled
    var mpesaReceipt = null;

    /* ── helpers ────────────────────────────────────────────────── */
    function cartTotal()  { return __read_number($('input#final_total_input')); }
    function pmKey()      { return $('#mpesa_payment_method_key').val() || 'custom_pay_1'; }
    function csrfToken()  { return $('meta[name="csrf-token"]').attr('content'); }

    function fmtKes(n) {
        return 'Ksh\u00a0' + parseFloat(n).toLocaleString('en-KE', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
        });
    }
    function fmtPhone(p) {
        p = String(p || '');
        return p.startsWith('254') ? '0' + p.slice(3) : (p || '-');
    }

    /* ── modal helpers ──────────────────────────────────────────── */
    function modalReset() {
        $('#mpesa_pos_form_section').show();
        $('#mpesa_pos_waiting_section, #mpesa_pos_success_section, #mpesa_pos_failed_section').hide();
        $('#mpesa_pos_error').hide().text('');
        $('#mpesa_pos_waiting_note').text('');
        $('#mpesa_pos_send_btn').show().prop('disabled', false).text('Send Payment Request');
        $('#mpesa_pos_cancel_btn, #mpesa_pos_close_x').show();
        currentCRID = null; mpesaReceipt = null;
        stopPoll();
    }
    function modalError(msg) {
        $('#mpesa_pos_error').text(msg).show();
        $('#mpesa_pos_send_btn').prop('disabled', false).text('Send Payment Request');
    }
    function modalWaiting() {
        $('#mpesa_pos_form_section').hide();
        $('#mpesa_pos_send_btn, #mpesa_pos_cancel_btn').hide();
        $('#mpesa_pos_waiting_section').show();
    }
    function modalSuccess(receipt) {
        stopPoll();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_success_section').show();
        $('#mpesa_pos_receipt_display').text(receipt ? 'Receipt: ' + receipt : '');
        loadRecent(); // refresh panel in background
    }
    function modalFailed(msg) {
        stopPoll();
        $('#mpesa_pos_waiting_section').hide();
        $('#mpesa_pos_failed_section').show();
        $('#mpesa_pos_failed_text').text(msg || 'Payment failed or was cancelled.');
        $('#mpesa_pos_send_btn').show().prop('disabled', false).text('Retry');
        $('#mpesa_pos_cancel_btn, #mpesa_pos_close_x').show();
        // open recent panel automatically
        loadRecent();
        panelShow();
    }

    /* ── polling ────────────────────────────────────────────────── */
    function stopPoll() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }
    function startPoll(crid) {
        pollDeadline = Date.now() + TIMEOUT_MS;
        pollTimer = setInterval(function () {
            if (Date.now() > pollDeadline) {
                modalFailed('Timed out. If the customer already paid, use Recent M-Pesa panel.');
                return;
            }
            var secs = Math.round((Date.now() - (pollDeadline - TIMEOUT_MS)) / 1000);
            $('#mpesa_pos_waiting_note').text('Waiting… ' + secs + 's');

            $.ajax({
                method: 'POST',
                url: $('#mpesa_check_status_url').val(),
                data: { checkout_request_id: crid, _token: csrfToken() },
                dataType: 'json',
                success: function (r) {
                    if (!r.success) return;
                    if (r.status === 'success') {
                        mpesaReceipt = r.mpesa_receipt_number;
                        modalSuccess(mpesaReceipt);
                        setTimeout(function () { finalizeSale(mpesaReceipt, null); }, 900);
                    } else if (r.status === 'failed' || r.status === 'cancelled') {
                        modalFailed(r.result_desc || 'Payment was not completed.');
                    }
                    // pending → keep polling
                }
            });
        }, POLL_MS);
    }

    /* ── finalize sale ──────────────────────────────────────────── */
    // mpesaAmount: actual M-Pesa amount (may differ from cart total).
    // Pass null for full-cart STK push flow.
    function finalizeSale(receipt, mpesaAmount) {
        var total     = cartTotal();
        var mpesaPaid = (mpesaAmount !== null && mpesaAmount !== undefined)
                        ? parseFloat(mpesaAmount) : total;
        var cashLeft  = parseFloat((total - mpesaPaid).toFixed(2));

        // Row 0 → M-Pesa
        var $rows = $('#payment_rows_div');
        var $amt0 = $rows.find('.payment-amount').first();
        var $mth0 = $rows.find('.payment_types_dropdown').first();
        __write_number($amt0, mpesaPaid);
        $amt0.trigger('change');
        $mth0.val(pmKey()).trigger('change');
        if (receipt) { $('input[name="payment[0][transaction_no_1]"]').val(receipt); }

        if (cashLeft > 0.009) {
            // Need a second row for cash remainder
            // Try several selectors UltimatePOS uses for "Add Payment" button
            var $addBtn = $(
                '#add_payment_row, ' +
                '.add_payment_row, ' +
                'button.add_payment, ' +
                'a.add_payment, ' +
                '[data-action="add-payment"]'
            ).first();

            if ($addBtn.length) {
                $addBtn.trigger('click');
                setTimeout(function () {
                    var $amt1 = $rows.find('.payment-amount').eq(1);
                    var $mth1 = $rows.find('.payment_types_dropdown').eq(1);
                    if ($amt1.length) { __write_number($amt1, cashLeft); $amt1.trigger('change'); }
                    if ($mth1.length) { $mth1.val('cash').trigger('change'); }
                    doSubmit();
                }, 200);
                return;
            }
        }
        doSubmit();
    }

    function doSubmit() {
        $('#mpesa_pos_modal').modal('hide');
        panelHide();
        if (typeof pos_form_obj !== 'undefined' && pos_form_obj) {
            pos_form_obj.submit();
        } else {
            $('#add_pos_sell_form').submit();
        }
    }

    /* ── recent payments panel ──────────────────────────────────── */
    var mpesaCartWatchInterval = null;
    var mpesaLastWatchedTotal = null;

    function panelShow() {
        $('#mpesa_recent_panel').show();

        // While the panel is open, watch the cart total and auto-refresh the
        // list if it changes (price edits, discounts, qty changes, voided
        // items, etc. don't fire a 'change' event on #final_total_input, so
        // polling is the simplest reliable way to catch them without editing
        // core pos.js). This keeps the EXACT MATCH badge/pin honest - it
        // should always reflect what's actually in the cart right now.
        mpesaLastWatchedTotal = cartTotal();
        clearInterval(mpesaCartWatchInterval);
        mpesaCartWatchInterval = setInterval(function () {
            var liveTotal = cartTotal();
            if (Math.abs(liveTotal - mpesaLastWatchedTotal) >= 0.01) {
                mpesaLastWatchedTotal = liveTotal;
                loadRecent();
            }
        }, 1200);
    }

    function panelHide() {
        $('#mpesa_recent_panel').hide();
        clearInterval(mpesaCartWatchInterval);
        mpesaCartWatchInterval = null;
    }

    function loadRecent() {
        var total = cartTotal();
        var search = $('#mpesa_recent_search').val() || '';
        $('#mpesa_recent_cart_total').text(fmtKes(total));
        $('#mpesa_recent_list').html(
            '<div style="text-align:center;padding:20px;color:#888;">' +
            '<i class="fa fa-spinner fa-spin"></i>&nbsp;Loading…</div>'
        );
        $('#mpesa_recent_footer').hide();

        $.ajax({
            method: 'GET',
            url: $('#mpesa_recent_payments_url').val(),
            data: { cart_total: total, search: search },
            dataType: 'json',
            success: function (r) {
                var $list = $('#mpesa_recent_list').empty();

                if (!r.success || !r.transactions || r.transactions.length === 0) {
                    var emptyMsg = search
                        ? 'No M-Pesa payments match "' + search + '".'
                        : 'No recent M-Pesa payments found.';
                    $list.html(
                        '<div style="text-align:center;padding:20px;color:#888;">' + emptyMsg + '</div>'
                    );
                    return;
                }

                var hasExactMatch = r.transactions.some(function (tx) {
                    return tx.is_exact_match === true;
                });

                if (hasExactMatch) {
                    $list.append(
                        '<div style="background:#f0fbf5;border:1px solid #00A859;border-radius:6px;' +
                        '     padding:8px 10px;margin-bottom:10px;font-size:12px;color:#1a7a45;">' +
                        '<i class="fa fa-check-circle"></i> An exact match for this cart total is listed first below.' +
                        '</div>'
                    );
                }

                $.each(r.transactions, function (i, tx) {
                    var paid      = parseFloat(tx.amount);
                    var remaining = parseFloat((total - paid).toFixed(2));
                    var isUsed    = tx.is_used === true;
                    // Use server flag as source of truth; also catch floating-point ties
                    var isExact   = !isUsed && (tx.is_exact_match === true || Math.abs(remaining) < 0.01);
                    var isOver    = !isUsed && remaining < -0.009;

                    /* status / remaining label */
                    var remHtml;
                    if (isUsed) {
                        remHtml = '<span style="color:#888;"><i class="fa fa-check"></i> Already used' +
                            (tx.invoice_no ? ' — Invoice ' + tx.invoice_no : '') + '</span>';
                    } else if (isExact) {
                        remHtml = '<span style="color:#27ae60;font-weight:bold;">✓ Exact amount</span>';
                    } else if (isOver) {
                        remHtml = '<span style="color:#c0392b;">Does not match — ' +
                            fmtKes(Math.abs(remaining)) + ' more than cart total</span>';
                    } else {
                        remHtml = '<span style="color:#c0392b;">Does not match — ' +
                            fmtKes(remaining) + ' less than cart total</span>';
                    }

                    var border = isUsed ? '#ccc' : (isExact ? '#00A859' : '#ddd');
                    var bg     = isUsed ? '#f5f5f5' : (isExact ? '#f0fbf5' : '#fafafa');
                    var cardCursor = 'default'; // only the button is clickable now

                    var badgeHtml = isUsed
                        ? '  <div style="display:inline-block;background:#888;color:#fff;' +
                          '       font-size:10px;font-weight:bold;padding:2px 7px;border-radius:10px;' +
                          '       margin-bottom:6px;letter-spacing:.3px;">USED</div>'
                        : (isExact
                            ? '  <div style="display:inline-block;background:#00A859;color:#fff;' +
                              '       font-size:10px;font-weight:bold;padding:2px 7px;border-radius:10px;' +
                              '       margin-bottom:6px;letter-spacing:.3px;">EXACT MATCH</div>'
                            : '');

                    // Used: no button. Exact: green button. Non-exact: red warning button (click shows toast).
                    var btnHtml;
                    if (isUsed) {
                        btnHtml = '';  // no action button for used transactions
                    } else if (isExact) {
                        btnHtml = '<button class="mpesa-use-btn" style="margin-top:9px;width:100%;' +
                            'background:#00A859;color:#fff;border:none;cursor:pointer;border-radius:5px;' +
                            'padding:6px 0;font-weight:bold;font-size:13px;">Use this payment ✓</button>';
                    } else {
                        btnHtml = '<button class="mpesa-use-btn" style="margin-top:9px;width:100%;' +
                            'background:#c0392b;color:#fff;border:none;cursor:pointer;border-radius:5px;' +
                            'padding:6px 0;font-weight:bold;font-size:13px;">⚠ Amount does not match</button>';
                    }

                    var $card = $(
                        '<div style="border:2px solid ' + border + ';border-radius:8px;' +
                        '     padding:10px 12px;margin-bottom:10px;cursor:' + cardCursor + ';background:' + bg + ';' +
                        '     ' + (isUsed ? 'opacity:0.7;' : '') + '">' +
                        badgeHtml +
                        '  <div style="display:flex;justify-content:space-between;align-items:center;">' +
                        '    <strong style="color:' + (isUsed ? '#888' : '#00A859') + ';">' + tx.mpesa_receipt_number + '</strong>' +
                        '    <strong>' + fmtKes(paid) + '</strong>' +
                        '  </div>' +
                        '  <div style="color:#666;font-size:11px;margin-top:3px;">' +
                        '    ' + fmtPhone(tx.phone_number) + ' &bull; ' + tx.display_time +
                        '  </div>' +
                        '  <div style="margin-top:5px;font-size:12px;">' + remHtml + '</div>' +
                        btnHtml +
                        '</div>'
                    );

                    (function (t, exact, used) {
                        if (!used) {
                            // Linking only ever happens when the cashier
                            // explicitly presses the button - not from
                            // clicking the receipt code, phone number, or
                            // anywhere else on the card. This avoids
                            // accidental linking from a stray tap/click.
                            $card.find('.mpesa-use-btn').on('click', function (e) {
                                e.stopPropagation();
                                doLink(t.id, t.mpesa_receipt_number, t.amount, $card, exact, total);
                            });
                        }
                    })(tx, isExact, isUsed);

                    $list.append($card);
                });

                $('#mpesa_recent_footer').show();
            },
            error: function () {
                $('#mpesa_recent_list').html(
                    '<div style="text-align:center;padding:20px;color:#c0392b;">' +
                    'Failed to load. Please try again.</div>'
                );
            }
        });
    }

    function doLink(txId, receipt, amount, $card, isExact, cartTotal) {
        // Hard rule: a recent M-Pesa payment can only be used if it matches
        // the cart total exactly. Refuse immediately and tell the cashier
        // why - don't even hit the server for this case.
        if (!isExact) {
            if (typeof toastr !== 'undefined') {
                toastr.error(
                    'This M-Pesa payment (' + fmtKes(amount) + ') does not match the cart total (' +
                    fmtKes(cartTotal) + '). Only payments with the exact amount can be used.',
                    'Amount Does Not Match — Cannot Use This Transaction'
                );
            } else {
                alert('This M-Pesa payment does not match the cart total. Only exact-amount payments can be used.');
            }
            return;
        }

        // Only touch the button on the card that was actually clicked - not
        // every card in the list. The other cards get disabled (not
        // relabeled) so the cashier can't accidentally trigger two links at
        // once while this request is in flight, but their text stays as-is.
        var $allButtons  = $('#mpesa_recent_list .mpesa-use-btn');
        var $clickedBtn  = $card.find('.mpesa-use-btn');

        $allButtons.prop('disabled', true);
        $clickedBtn.text('Linking…');
        $card.css('opacity', '1');
        $card.siblings('div').not($card).css('opacity', '0.5');

        $.ajax({
            method: 'POST',
            url: $('#mpesa_link_payment_url').val(),
            data: { mpesa_transaction_id: txId, cart_total: cartTotal, _token: csrfToken() },
            dataType: 'json',
            success: function (r) {
                if (r.success) {
                    mpesaReceipt = r.mpesa_receipt_number;
                    finalizeSale(mpesaReceipt, r.amount);
                } else {
                    $allButtons.prop('disabled', false);
                    $clickedBtn.text(isExact ? 'Use this payment ✓' : 'Amount does not match');
                    $card.siblings().css('opacity', '1');
                    if (typeof toastr !== 'undefined') {
                        toastr.error(r.msg || 'Unknown error', 'Could not link payment');
                    } else {
                        alert('Could not link payment: ' + (r.msg || 'Unknown error'));
                    }
                }
            },
            error: function () {
                $allButtons.prop('disabled', false);
                $clickedBtn.text(isExact ? 'Use this payment ✓' : 'Amount does not match');
                $card.siblings().css('opacity', '1');
                if (typeof toastr !== 'undefined') {
                    toastr.error('Network error. Please try again.');
                } else {
                    alert('Network error. Please try again.');
                }
            }
        });
    }

    /* ── boot — runs AFTER DOM is fully ready ───────────────────── */
    $(document).ready(function () {

        /* inject buttons next to every Card express-checkout button */
        var $cardBtns = $('button[data-pay_method="card"]');

        var stkHtml =
            '<button type="button" class="mpesa-stk-btn"' +
            ' style="font-weight:bold;color:#fff;cursor:pointer;background:#00A859;' +
            '        padding:8px 10px;border-radius:6px;border:none;margin-left:4px;' +
            '        display:inline-flex;align-items:center;gap:5px;">' +
            '<i class="fas fa-mobile-alt"></i> M-Pesa</button>';

        var recentHtml =
            '<button type="button" class="mpesa-recent-btn"' +
            ' style="font-weight:bold;color:#00A859;cursor:pointer;background:#e8f8f0;' +
            '        padding:8px 10px;border-radius:6px;border:2px solid #00A859;' +
            '        margin-left:4px;display:inline-flex;align-items:center;gap:5px;">' +
            '<i class="fas fa-history"></i> Recent M-Pesa</button>';

        if ($cardBtns.length) {
            $cardBtns.each(function () {
                $(this).after(recentHtml).after(stkHtml);
            });
        } else {
            /* fallback: append to the express-checkout button area */
            var $area = $('.pos-express-finalize').parent();
            if ($area.length) {
                $area.append(stkHtml).append(recentHtml);
            }
        }

        /* ── click: STK push button (delegated — works even if injected late) */
        $(document).on('click', '.mpesa-stk-btn', function () {
            if ($('table#pos_table tbody .product_row').length < 1) {
                typeof toastr !== 'undefined' && toastr.warning('Please add at least one product.');
                return;
            }
            var total = cartTotal();
            if (!total || total <= 0) {
                typeof toastr !== 'undefined' && toastr.warning('Total must be greater than zero.');
                return;
            }
            modalReset();
            $('#mpesa_pos_amount').val(total.toFixed(2));
            /* pre-fill phone from customer record if available */
            var mob = $('#contact_id').closest('.form-group').find('.mobile_number').val()
                   || $('input[name="mobile_number"]').val() || '';
            if (mob) $('#mpesa_pos_phone').val(mob);
            $('#mpesa_pos_modal').modal('show');
        });

        /* ── click: send STK push */
        $(document).on('click', '#mpesa_pos_send_btn', function () {
            var phone  = $('#mpesa_pos_phone').val().trim();
            var amount = $('#mpesa_pos_amount').val();
            if (!phone)  { modalError('Please enter the customer\'s phone number.'); return; }
            if (!amount || parseFloat(amount) <= 0) { modalError('Invalid amount.'); return; }

            $('#mpesa_pos_error').hide();
            $(this).prop('disabled', true).text('Sending…');

            $.ajax({
                method: 'POST',
                url: $('#mpesa_stk_push_url').val(),
                data: {
                    phone: phone, amount: amount,
                    location_id: $('#location_id').val(),
                    _token: csrfToken()
                },
                dataType: 'json',
                success: function (r) {
                    if (r.success) {
                        currentCRID = r.checkout_request_id;
                        modalWaiting();
                        startPoll(currentCRID);
                    } else {
                        modalError(r.msg);
                    }
                },
                error: function (xhr) {
                    modalError((xhr.responseJSON && xhr.responseJSON.msg)
                        ? xhr.responseJSON.msg
                        : 'Something went wrong. Please try again.');
                }
            });
        });

        /* ── click: Recent M-Pesa button */
        $(document).on('click', '.mpesa-recent-btn', function () {
            if ($('#mpesa_recent_panel').is(':visible')) {
                panelHide();
            } else {
                loadRecent();
                panelShow();
            }
        });

        /* ── click: close panel × */
        $(document).on('click', '#mpesa_recent_close', function () { panelHide(); });

        /* ── search box: phone number or M-Pesa code, debounced ─────── */
        var searchDebounce = null;
        $(document).on('keyup', '#mpesa_recent_search', function () {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(function () {
                loadRecent();
            }, 350);
        });

        /* ── modal dismissed: stop polling */
        $('#mpesa_pos_modal').on('hidden.bs.modal', function () { modalReset(); });
    });

})(jQuery);
</script>

@endif