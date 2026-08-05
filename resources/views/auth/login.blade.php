@extends ('layouts.auth2')
@section ('title', __('lang_v1.login'))
@inject ('request', 'Illuminate\Http\Request')
@section ('content')
    @php
        $username = old('username');
        $password = null;
        if (config('app.env') == 'demo') {
            $username = 'admin';
            $password = '123456';

            $demo_types = [
                'all_in_one' => 'admin',
                'super_market' => 'admin',
                'pharmacy' => 'admin-pharmacy',
                'electronics' => 'admin-electronics',
                'services' => 'admin-services',
                'restaurant' => 'admin-restaurant',
                'superadmin' => 'superadmin',
                'woocommerce' => 'woocommerce_user',
                'essentials' => 'admin-essentials',
                'manufacturing' => 'manufacturer-demo',
            ];

            if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                $username = $demo_types[$_GET['demo_type']];
            }
        }

        $demo_buttons = [
            ['type' => 'all_in_one', 'label' => 'All In One', 'icon' => 'fa-star',
             'title' => 'Showcases all feature available in the application.'],
            ['type' => 'pharmacy', 'label' => 'Pharmacy', 'icon' => 'fa-medkit',
             'title' => 'Shops with products having expiry dates.'],
            ['type' => 'services', 'label' => 'Multi-Service Center', 'icon' => 'fa-wrench',
             'title' => 'For all service providers like Web Development, Restaurants, Repairing, Plumber, Salons, Beauty Parlors etc.'],
            ['type' => 'electronics', 'label' => 'Electronics & Mobile Shop', 'icon' => 'fa-laptop',
             'title' => 'Products having IMEI or Serial number code.'],
            ['type' => 'super_market', 'label' => 'Super Market', 'icon' => 'fa-shopping-cart',
             'title' => 'Super market & Similar kind of shops.'],
            ['type' => 'restaurant', 'label' => 'Restaurant', 'icon' => 'fa-utensils',
             'title' => 'Restaurants, Salons and other similar kind of shops.'],
        ];

        $premium_buttons = [
            ['type' => 'superadmin', 'label' => 'SaaS / Superadmin', 'icon' => 'fa-university',
             'title' => 'SaaS & Superadmin extension Demo'],
            ['type' => 'woocommerce', 'label' => 'WooCommerce', 'icon' => 'fab fa-wordpress',
             'title' => 'WooCommerce demo user - Open web shop in minutes!!'],
            ['type' => 'essentials', 'label' => 'Essentials & HRM', 'icon' => 'fa-check-circle',
             'title' => 'Essentials & HRM (human resource management) Module Demo'],
            ['type' => 'manufacturing', 'label' => 'Manufacturing Module', 'icon' => 'fa-industry',
             'title' => 'Manufacturing module demo'],
            ['type' => 'superadmin', 'label' => 'Project Module', 'icon' => 'fa-project-diagram',
             'title' => 'Project module demo'],
            ['type' => 'services', 'label' => 'Advance Repair Module', 'icon' => 'fa-wrench',
             'title' => 'Advance repair module demo'],
        ];
    @endphp

    <div class="lp-wrap">
        <div class="lp-stage">
            <div class="lp-left">
                <div class="lp-brand">
                    <span class="lp-brand-mark" aria-hidden="true">
                        <i class="fas fa-cash-register"></i>
                    </span>
                    <h1>{{ config('app.name', 'ultimatePOS') }}</h1>
                    <p class="lp-brand-copy">Point of sale, inventory and business management built for every kind of shop, from a single counter to multi-location retail.</p>
                </div>

                @if (config('app.env') == 'demo')
                    <div class="lp-demo-block">
                        <span class="lp-demo-label">Try a demo shop</span>
                        <div class="lp-demo-list">
                            @foreach ($demo_buttons as $btn)
                                <a
                                    href="?demo_type={{ $btn['type'] }}"
                                    class="lp-demo-link demo-login"
                                    data-toggle="tooltip"
                                    title="{{ $btn['title'] }}"
                                    data-admin="{{ $demo_types[$btn['type']] }}"
                                >
                                    <i class="fas {{ $btn['icon'] }}"></i>
                                    <span>{{ $btn['label'] }}</span>
                                </a>
                            @endforeach
                        </div>

                        <span class="lp-demo-label lp-demo-label--sub"
                            >Premium optional modules</span
                        >
                        <div class="lp-demo-list lp-demo-list--sub">
                            @foreach ($premium_buttons as $btn)
                                <a
                                    href="?demo_type={{ $btn['type'] }}"
                                    class="lp-demo-link demo-login"
                                    data-toggle="tooltip"
                                    title="{{ $btn['title'] }}"
                                    data-admin="{{ $demo_types[$btn['type']] }}"
                                >
                                    <i
                                        class="{{ str_contains($btn['icon'], 'fab') ? $btn['icon'] : 'fas ' . $btn['icon'] }}"
                                    ></i>
                                    <span>{{ $btn['label'] }}</span>
                                </a>
                            @endforeach
                            <a
                                href="{{ url('docs') }}"
                                target="_blank"
                                class="lp-demo-link"
                                data-toggle="tooltip"
                                title="Connector Module / API Documentation"
                            >
                                <i class="fas fa-network-wired"></i>
                                <span>Connector Module / API Docs</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="lp-divider" aria-hidden="true"></div>

            <div class="lp-right">
                <span class="lp-form-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                        <circle cx="10" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </span>

                <form method="POST" action="{{ route('login') }}" id="login-form" class="lp-form">
                    {{ csrf_field() }}

                    <div class="lp-field {{ $errors->has('username') ? 'has-error' : '' }}">
                        <i class="fas fa-user"></i>
                        <input
                            name="username"
                            required
                            autofocus
                            placeholder="@lang('lang_v1.username')"
                            data-last-active-input=""
                            id="username"
                            type="text"
                            value="{{ $username }}"
                        />
                    </div>
                    @if ($errors->has('username'))
                        <span class="lp-error">{{ $errors->first('username') }}</span>
                    @endif

                    <div
                        class="lp-field lp-field-password {{ $errors->has('password') ? 'has-error' : '' }}"
                    >
                        <i class="fas fa-lock"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            value="{{ $password }}"
                            required
                            placeholder="@lang('lang_v1.password')"
                        />
                        <button type="button" id="show_hide_icon" class="lp-eye-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <span class="lp-error">{{ $errors->first('password') }}</span>
                    @endif

                    <div class="lp-form-row">
                        <label class="lp-checkbox">
                            <input
                                type="checkbox"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            />
                            <span class="lp-checkbox-box"><i class="fas fa-check"></i></span>
                            <span class="lp-checkbox-label">@lang ('lang_v1.remember_me')</span>
                        </label>

                        @if (config('app.env') != 'demo')
                            <a href="{{ route('password.request') }}" class="lp-link" tabindex="-1">
                                <i class="far fa-envelope"></i>
                                @lang ('lang_v1.forgot_your_password')
                            </a>
                        @endif
                    </div>

                    @if (config('constants.enable_recaptcha'))
                        <div class="lp-recaptcha">
                            <div
                                class="g-recaptcha"
                                data-sitekey="{{ config('constants.google_recaptcha_key') }}"
                            ></div>
                            @if ($errors->has('g-recaptcha-response'))
                                <span
                                    class="lp-error"
                                    >{{ $errors->first('g-recaptcha-response') }}</span
                                >
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="lp-submit">@lang ('lang_v1.login')</button>
                </form>

                @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                    @if (config('constants.allow_registration'))
                        <div class="lp-register">
                            <a
                                href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif"
                            >
                                {{ __('business.not_yet_registered') }}
                                <span
                                    class="lp-register-accent"
                                    >{{ __('business.register_now') }}</span
                                >
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@stop

@section ('css')
    <style>
        :root {
            --lp-ink: #0e2242;
            --lp-line: rgba(255, 255, 255, 0.28);
            --lp-danger: #ff8080;
        }

        * {
            box-sizing: border-box;
        }
        html,
        body {
            height: 100%;
        }

        /* ---------- full-page wrap ---------- */
        .lp-wrap {
            position: relative;
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ---------- content stage: text left / divider / borderless form right ---------- */
        .lp-stage {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1040px;
            display: grid;
            grid-template-columns: 1fr 1px 1fr;
            align-items: center;
            gap: 56px;
            padding: 64px 40px;
            color: #fff;
        }

        /* ---------- left column: brand text + demo links ---------- */
        .lp-left {
            display: flex;
            flex-direction: column;
            gap: 34px;
        }

        .lp-brand-mark {
            display: inline-grid;
            place-items: center;
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 22px;
            margin-bottom: 18px;
        }
        .lp-brand h1 {
            margin: 0 0 12px;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 0.01em;
        }
        .lp-brand p {
            margin: 0;
            max-width: 360px;
            font-size: 14.5px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.78);
        }

        .lp-demo-block {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .lp-demo-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
        }
        .lp-demo-label--sub {
            margin-top: 6px;
        }
        .lp-demo-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .lp-demo-list--sub .lp-demo-link {
            opacity: 0.85;
        }

        .lp-demo-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition:
                background 0.15s ease,
                transform 0.15s ease;
        }
        .lp-demo-link i {
            font-size: 11px;
        }
        .lp-demo-link:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-1px);
            color: #fff;
            text-decoration: none;
        }

        /* ---------- vertical divider ---------- */
        .lp-divider {
            align-self: stretch;
            width: 1px;
            background: linear-gradient(
                to bottom,
                transparent,
                var(--lp-line) 15%,
                var(--lp-line) 85%,
                transparent
            );
        }

        /* ---------- right column: borderless / background-less form ---------- */
        .lp-right {
            display: flex;
            flex-direction: column;
            gap: 18px;
            max-width: 340px;
        }

        .lp-form-icon {
            align-self: flex-start;
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            margin-bottom: 6px;
            color: #fff;
        }
        .lp-form-icon svg {
            width: 44px;
            height: 44px;
        }

        .lp-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .lp-field {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 46px;
            padding: 0 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition:
                background 0.15s ease,
                border-color 0.15s ease;
        }
        .lp-field:focus-within {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.55);
        }
        .lp-field.has-error {
            border-color: var(--lp-danger);
        }
        .lp-field i {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            width: 14px;
            text-align: center;
        }

        .lp-field input {
            flex: 1;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: #fff;
            font-size: 13.5px;
            font-weight: 500;
        }
        .lp-field input::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .lp-field-password {
            position: relative;
            padding-right: 6px;
        }
        .lp-eye-btn {
            flex: 0 0 auto;
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            cursor: pointer;
        }
        .lp-eye-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .lp-eye-btn svg {
            width: 17px;
            height: 17px;
        }

        .lp-error {
            display: block;
            margin-top: -6px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--lp-danger);
            padding-left: 16px;
        }

        .lp-form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 2px;
        }

        .lp-checkbox {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            user-select: none;
        }
        .lp-checkbox input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .lp-checkbox-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid rgba(255, 255, 255, 0.55);
            display: grid;
            place-items: center;
            color: transparent;
            transition:
                background 0.15s ease,
                border-color 0.15s ease,
                color 0.15s ease;
        }
        .lp-checkbox-box i {
            font-size: 9px;
        }
        .lp-checkbox input:checked + .lp-checkbox-box {
            background: #fff;
            border-color: #fff;
            color: #1a4fa0;
        }
        .lp-checkbox-label {
            font-size: 12.5px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
        }

        .lp-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
        }
        .lp-link i {
            font-size: 11px;
        }
        .lp-link:hover {
            color: #fff;
            text-decoration: underline;
        }

        .lp-recaptcha {
            margin-top: 2px;
        }

        .lp-submit {
            height: 46px;
            margin-top: 6px;
            border: none;
            border-radius: 999px;
            background: #fff;
            color: #1a4fa0;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            cursor: pointer;
            transition:
                transform 0.12s ease,
                box-shadow 0.12s ease,
                filter 0.12s ease;
            box-shadow: 0 14px 28px -12px rgba(0, 0, 0, 0.4);
        }
        .lp-submit:hover {
            filter: brightness(0.96);
            transform: translateY(-1px);
        }
        .lp-submit:active {
            transform: translateY(0);
        }

        .lp-register {
            margin-top: 6px;
            font-size: 12.5px;
        }
        .lp-register a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
        }
        .lp-register-accent {
            font-weight: 700;
            color: #fff;
        }
        .lp-register a:hover .lp-register-accent {
            text-decoration: underline;
        }

        /* ---------- responsive ---------- */
        @media (max-width: 860px) {
            .lp-stage {
                grid-template-columns: 1fr;
                gap: 36px;
                padding: 56px 24px;
                max-width: 460px;
            }
            .lp-divider {
                width: 100%;
                height: 1px;
                background: linear-gradient(
                    to right,
                    transparent,
                    var(--lp-line) 15%,
                    var(--lp-line) 85%,
                    transparent
                );
            }
            .lp-right {
                max-width: none;
            }
            .lp-brand p {
                max-width: none;
            }
        }
    </style>
@endsection

@section ('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#show_hide_icon').off('click');
            $('.change_lang').click(function () {
                window.location = '{{ route('login') }}?lang=' + $(this).attr('value');
            });
            $('a.demo-login').click(function (e) {
                e.preventDefault();
                $('#username').val($(this).data('admin'));
                $('#password').val('{{ $password }}');
                $('form#login-form').submit();
            });

            $('#show_hide_icon').on('click', function (e) {
                e.preventDefault();
                const passwordInput = $('#password');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    $('#show_hide_icon').html(
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/></svg>'
                    );
                } else if (passwordInput.attr('type') === 'text') {
                    passwordInput.attr('type', 'password');
                    $('#show_hide_icon').html(
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>'
                    );
                }
            });
        });
    </script>
@endsection
