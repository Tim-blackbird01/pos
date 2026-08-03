{{-- 
    M-Pesa "Pay with M-Pesa" modal.

    Include this partial in the sell screen (resources/views/sell/pos.blade.php or
    the payment modal partial), then add a button that opens it, e.g.:

        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#mpesa_modal">
            <i class="fa fa-mobile"></i> @lang('mpesa::lang.pay_with_mpesa')
        </button>

    @include('mpesa::partials.stk_modal')
--}}

<div class="modal fade" id="mpesa_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('mpesa::lang.pay_with_mpesa')</h4>
            </div>
            <div class="modal-body">

                <div id="mpesa_form_section">
                    <div class="form-group">
                        <label>@lang('mpesa::lang.phone_number')</label>
                        <input type="text" id="mpesa_phone" class="form-control" placeholder="07XXXXXXXX">
                    </div>
                    <div class="form-group">
                        <label>@lang('mpesa::lang.amount')</label>
                        <input type="number" id="mpesa_amount" class="form-control" step="0.01" min="1">
                    </div>
                    <div id="mpesa_error" class="alert alert-danger" style="display:none;"></div>
                </div>

                <div id="mpesa_waiting_section" style="display:none; text-align:center;">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p class="mpesa-waiting-text" style="margin-top:15px;">@lang('mpesa::lang.waiting_for_payment')</p>
                </div>

                <div id="mpesa_success_section" style="display:none; text-align:center;">
                    <i class="fa fa-check-circle fa-3x text-green"></i>
                    <p style="margin-top:15px;">@lang('mpesa::lang.payment_successful')</p>
                    <p><strong id="mpesa_receipt_display"></strong></p>
                </div>

                <div id="mpesa_failed_section" style="display:none; text-align:center;">
                    <i class="fa fa-times-circle fa-3x text-red"></i>
                    <p style="margin-top:15px;" id="mpesa_failed_text">@lang('mpesa::lang.payment_failed')</p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('mpesa::lang.close')</button>
                <button type="button" class="btn btn-primary" id="mpesa_send_btn">@lang('mpesa::lang.send_stk_push')</button>
            </div>
        </div>
    </div>
</div>
