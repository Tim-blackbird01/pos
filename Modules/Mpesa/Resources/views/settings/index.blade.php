@extends('layouts.app')

@section('title', __('mpesa::lang.mpesa_settings'))

@section('content')
<section class="content-header">
    <h1>@lang('mpesa::lang.mpesa_settings')</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('mpesa::lang.mpesa_settings')</h3>
                </div>

                {!! Form::open(['url' => action('\Modules\Mpesa\Http\Controllers\SettingController@store'), 'method' => 'post', 'id' => 'mpesa_settings_form']) !!}
                <div class="box-body">

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_enabled" value="1"
                                    {{ !empty($settings->is_enabled) ? 'checked' : '' }}>
                                @lang('mpesa::lang.enable_mpesa')
                            </label>
                        </div>
                        <p class="help-block">
                            <i class="fa fa-info-circle"></i>
                            Enabling this adds an <strong>M-Pesa</strong> express-checkout button to the
                            POS (sell) screen, right next to Cash and Card. It works the exact same way:
                            one click, and once the customer completes payment on their phone, the sale
                            is finalized automatically. Behind the scenes it uses your business's
                            <strong>"Custom Payment 1"</strong> slot (Business Settings &rarr; Payment
                            Methods) to record M-Pesa payments &mdash; this module automatically relabels
                            that slot to "M-Pesa" for you when you save these settings, unless you've
                            already customized it to something else.
                        </p>
                    </div>

                    <div class="form-group">
                        {!! Form::label('environment', __('mpesa::lang.environment') . ':*') !!}
                        {!! Form::select('environment', [
                                'sandbox' => __('mpesa::lang.sandbox'),
                                'production' => __('mpesa::lang.production'),
                            ], old('environment', $settings->environment ?? 'sandbox'), ['class' => 'form-control select2', 'required']) !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('shortcode', __('mpesa::lang.shortcode') . ':*') !!}
                                {!! Form::text('shortcode', old('shortcode', $settings->shortcode ?? ''), ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('shortcode_type', __('mpesa::lang.shortcode_type') . ':*') !!}
                                {!! Form::select('shortcode_type', [
                                        'paybill' => __('mpesa::lang.paybill'),
                                        'till' => __('mpesa::lang.till'),
                                    ], old('shortcode_type', $settings->shortcode_type ?? 'paybill'), ['class' => 'form-control select2', 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('consumer_key', __('mpesa::lang.consumer_key') . ':*') !!}
                        {!! Form::text('consumer_key', old('consumer_key', $settings->consumer_key ?? ''), ['class' => 'form-control', 'required', 'autocomplete' => 'off']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('consumer_secret', __('mpesa::lang.consumer_secret') . ':*') !!}
                        {!! Form::password('consumer_secret', ['class' => 'form-control', 'required', 'autocomplete' => 'off']) !!}
                        @if(!empty($settings->consumer_secret))
                            <small class="help-block text-muted">Leave as-is to keep the existing value, or type a new secret to replace it.</small>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label('passkey', __('mpesa::lang.passkey') . ':*') !!}
                        {!! Form::password('passkey', ['class' => 'form-control', 'required', 'autocomplete' => 'off']) !!}
                        @if(!empty($settings->passkey))
                            <small class="help-block text-muted">Leave as-is to keep the existing value, or type a new passkey to replace it.</small>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('account_reference', __('mpesa::lang.account_reference') . ':') !!}
                                {!! Form::text('account_reference', old('account_reference', $settings->account_reference ?? ''), ['class' => 'form-control', 'placeholder' => 'e.g. ' . config('app.name')]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('transaction_desc', __('mpesa::lang.transaction_desc') . ':') !!}
                                {!! Form::text('transaction_desc', old('transaction_desc', $settings->transaction_desc ?? 'Payment'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">@lang('mpesa::lang.save_settings')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('mpesa::lang.callback_urls')</h3>
                </div>
                <div class="box-body">
                    <p class="text-muted">@lang('mpesa::lang.callback_urls_help')</p>

                    <div class="form-group">
                        <label>@lang('mpesa::lang.stk_callback_url')</label>
                        <input type="text" class="form-control" readonly value="{{ $stk_callback_url }}" onclick="this.select();">
                    </div>

                    <div class="form-group">
                        <label>@lang('mpesa::lang.c2b_confirmation_url')</label>
                        <input type="text" class="form-control" readonly value="{{ $c2b_confirmation_url }}" onclick="this.select();">
                    </div>

                    <div class="form-group">
                        <label>@lang('mpesa::lang.c2b_validation_url')</label>
                        <input type="text" class="form-control" readonly value="{{ $c2b_validation_url }}" onclick="this.select();">
                    </div>

                    <hr>

                    <p class="text-muted small">@lang('mpesa::lang.c2b_only_production')</p>

                    <button type="button" class="btn btn-default btn-block" id="register_c2b_urls_btn">
                        @lang('mpesa::lang.register_c2b_urls')
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        $('#register_c2b_urls_btn').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                method: 'POST',
                url: '{{ action("\Modules\Mpesa\Http\Controllers\SettingController@registerC2BUrls") }}',
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong. Please try again.');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection
