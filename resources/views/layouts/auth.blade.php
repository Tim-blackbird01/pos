<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <html>
        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <!-- Tell the browser to be responsive to screen width -->
            <meta
                content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
                name="viewport"
            />

            <!-- CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}" />

            <title>
                @yield ('title')
                - {{ config('app.name', 'POS') }}
            </title>

            @include ('layouts.partials.css')

            @include ('layouts.partials.extracss_auth')
        </head>

        <body class="hold-transition">
            @if (session('status'))
                <input
                    type="hidden"
                    id="status_span"
                    data-status="{{ session('status.success') }}"
                    data-msg="{{ session('status.msg') }}"
                />
            @endif

            <div class="auth-page">
                <div class="auth-page-bg" aria-hidden="true">
                    <span class="poly poly-1"></span>
                    <span class="poly poly-2"></span>
                    <span class="poly poly-3"></span>
                    <span class="poly poly-4"></span>
                    <span class="poly poly-5"></span>
                    <span class="poly poly-6"></span>
                    <span class="poly poly-7"></span>
                    <span class="poly poly-8"></span>
                    <span class="poly poly-9"></span>
                    <span class="poly poly-10"></span>
                    <span class="poly poly-11"></span>
                    <span class="poly poly-12"></span>
                </div>
                <div class="auth-page-body">
                    @if (!isset($no_header))
                        @include ('layouts.partials.header-auth')
                    @endif

                    @yield ('content')
                </div>
            </div>

            @include ('layouts.partials.javascripts')
            <!-- Scripts -->
            <script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>
            @yield ('javascript')

            <script type="text/javascript">
                $(document).ready(function () {
                    $('.select2_register').select2();

                    // $('input').iCheck({
                    //     checkboxClass: 'icheckbox_square-blue',
                    //     radioClass: 'iradio_square-blue',
                    //     increaseArea: '20%' // optional
                    // });
                });
            </script>
        </body>
    </html>
