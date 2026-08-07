@extends ('layouts.auth2')
@section ('title', __('lang_v1.login'))
@inject ('request', 'Illuminate\Http\Request')
@section ('standalone_auth', 'true')
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

    <div class="cs-auth-container">
        <a
            class="cs-brand-logo"
            href="{{ url('/') }}"
            aria-label="{{ config('app.name', 'ultimatePOS') }} home"
        >
            @if (file_exists(public_path('uploads/logo.svg')))
                <img
                    src="{{ asset('uploads/logo.svg') }}"
                    alt="{{ config('app.name', 'ultimatePOS') }}"
                />
            @else
                <img
                    src="{{ asset('img/logo-small.png') }}"
                    alt="{{ config('app.name', 'ultimatePOS') }}"
                />
            @endif
        </a>
        <header class="cs-top-nav">
            @if (config('constants.allow_registration') && !($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                <a
                    href="{{ route('business.getRegister') }}@if(!empty(request()->lang)){{'?lang='.request()->lang}}@endif"
                    class="cs-btn-pill"
                    >{{ __('business.register') }}</a
                >
            @endif
            @if (Route::has('pricing') && config('app.env') != 'demo')
                <a
                    href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}"
                    class="cs-nav-link"
                >
                    @lang ('superadmin::lang.pricing')
                </a>
            @endif
            <details class="cs-language-menu">
                <summary class="cs-nav-link">
                    {{ isset($_GET['lang']) ? config('constants.langs')[$_GET['lang']]['full_name'] : config('constants.langs')[config('app.locale')]['full_name'] }}
                </summary>
                <div class="cs-language-list">
                    @foreach (config('constants.langs') as $key => $val)
                        <a
                            href="#"
                            value="{{ $key }}"
                            class="change_lang"
                            >{{ $val['full_name'] }}</a
                        >
                    @endforeach
                </div>
            </details>
        </header>

        <div class="cs-split-layout">
            <div class="cs-left-panel">
                <div class="lp-brand">
                    <span class="lp-brand-mark" aria-hidden="true"
                        ><i class="fas fa-cash-register"></i
                    ></span>
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

                <div class="cs-bg-squares" aria-hidden="true">
                    <div class="cs-sq cs-sq-1"></div>
                    <div class="cs-sq cs-sq-2"></div>
                    <div class="cs-sq cs-sq-3"></div>
                    <div class="cs-sq cs-sq-4"></div>
                    <div class="cs-sq cs-sq-5"></div>
                    <div class="cs-sq cs-sq-6"></div>
                    <div class="cs-sq cs-sq-7"></div>
                    <div class="cs-sq cs-sq-8"></div>
                    <div class="cs-sq cs-sq-9"></div>
                    <div class="cs-sq cs-sq-10"></div>
                    <div class="cs-sq cs-sq-11"></div>
                    <div class="cs-sq cs-sq-12"></div>
                </div>

                <div class="cs-dot-grid" aria-hidden="true"></div>

                <div class="cs-wave-bottom" aria-hidden="true">
                    <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                        <path fill="#6bbd88" fill-opacity="0.85" d="M0,192C280,290 560,110 840,210C1120,310 1300,220 1440,180L1440,320L0,320Z"></path>
                        <path fill="#124a2f" fill-opacity="1" d="M0,230C320,310 640,180 960,250C1200,290 1360,240 1440,220L1440,320L0,320Z"></path>
                    </svg>
                </div>
            </div>

            <div class="cs-right-panel">
                <main class="lp-login-card">
                    <span class="lp-form-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                            <circle cx="10" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </span>

                    <div class="lp-form-heading">
                        <h2>Welcome Back</h2>
                        <p>Sign in to continue to {{ config('app.name', 'ultimatePOS') }}</p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        id="login-form"
                        class="lp-form"
                    >
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
                                <a
                                    href="{{ route('password.request') }}"
                                    class="lp-link"
                                    tabindex="-1"
                                >
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
                    @if (config('constants.allow_registration'))
                        <p class="lp-register-prompt">{{ __('business.not_yet_registered') }} <a href="{{ route('business.getRegister') }}@if(!empty(request()->lang)){{ '?lang='.request()->lang }}@endif">{{ __('business.register') }}</a></p>
                    @endif
                </main>
                <p class="cs-copyright">&copy; {{ date('Y') }} {{ config('app.name', 'ultimatePOS') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
@stop

@section ('css')
    <style>
        :root {
            --cs-green-dark: #124a2f;
            --cs-green-accent: #48a96b;
            --cs-bg-light: #f3f5f3;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html,
        body {
            height: 100%;
            font-family:
                'Inter',
                ui-sans-serif,
                system-ui,
                -apple-system,
                sans-serif;
            background-color: var(--cs-green-dark);
            overflow-x: hidden;
        }

        .cs-top-nav {
            position: absolute;
            top: 24px;
            right: 48px;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .cs-btn-pill {
            background: #ffffff;
            color: var(--cs-green-dark);
            padding: 8px 22px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }
        .cs-nav-link {
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            opacity: 0.9;
        }
        .cs-brand-logo { position: absolute; top: 25px; left: 48px; z-index: 20; display: block; width: 210px; height: 62px; filter: drop-shadow(0 2px 8px rgba(255, 255, 255, .55)) drop-shadow(0 2px 5px rgba(5, 45, 27, .22)); }
        .cs-brand-logo img { display: block; width: 100%; height: 100%; object-fit: contain; object-position: left center; }
        .cs-language-menu { position: relative; }
        .cs-language-menu summary { list-style: none; cursor: pointer; }
        .cs-language-menu summary::-webkit-details-marker { display: none; }
        .cs-language-menu summary::before { content: '▸'; margin-right: 7px; font-size: 11px; }
        .cs-language-menu[open] summary::before { content: '▾'; }
        .cs-language-list { position: absolute; right: 0; top: 28px; min-width: 150px; padding: 8px; border-radius: 12px; background: #fff; box-shadow: 0 16px 35px rgba(0, 0, 0, .18); }
        .cs-language-list a { display: block; padding: 8px 10px; border-radius: 8px; color: var(--cs-green-dark); font-size: 13px; text-decoration: none; }
        .cs-language-list a:hover { background: #edf8ef; }

        .cs-auth-container {
            position: relative;
            min-height: 100vh;
            width: 100%;
            background-color: var(--cs-green-dark);
        }
        .cs-split-layout {
            display: grid;
            grid-template-columns: 44% 56%;
            min-height: 100vh;
        }

        .cs-left-panel {
            background-color: var(--cs-bg-light);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px 64px;
            overflow: hidden;
            border-top-right-radius: 60px;
        }
        .lp-brand { position: relative; z-index: 5; max-width: 455px; color: var(--cs-green-dark); }
        .lp-brand-mark { display: inline-flex; align-items: center; justify-content: center; width: 92px; height: 92px; margin-bottom: 26px; border-radius: 50%; background: linear-gradient(145deg, #5fc46c, #258347); color: #fff; box-shadow: 0 12px 24px rgba(17, 89, 47, .22); font-size: 36px; }
        .lp-brand h1 { margin: 0 0 12px; color: #104a2e; font-size: clamp(38px, 4vw, 58px); font-weight: 800; letter-spacing: -2px; line-height: 1; }
        .lp-brand-copy { margin: 0; color: #283a30; font-size: 17px; line-height: 1.8; }
        .cs-bg-squares {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }
        .cs-sq {
            position: absolute;
            background: #ffffff;
            border-radius: 16px;
        }
        .cs-sq-1 {
            width: 580px;
            height: 400px;
            top: -140px;
            left: -120px;
            transform: rotate(-16deg);
            opacity: 0.65;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.02);
        }
        .cs-sq-2 {
            width: 460px;
            height: 320px;
            top: 40px;
            left: -30px;
            transform: rotate(-10deg);
            opacity: 0.45;
        }
        .cs-sq-3 {
            width: 260px;
            height: 260px;
            top: -30px;
            left: 260px;
            transform: rotate(25deg);
            opacity: 0.3;
        }
        .cs-sq-4 {
            width: 230px;
            height: 230px;
            top: 200px;
            left: 220px;
            transform: rotate(18deg);
            opacity: 0.35;
            background: #f8fafc;
            border-radius: 20px;
        }
        .cs-sq-5 {
            width: 170px;
            height: 170px;
            top: 150px;
            left: 310px;
            transform: rotate(32deg);
            opacity: 0.2;
            background: #e2e8f0;
        }
        .cs-sq-6 {
            width: 340px;
            height: 240px;
            top: 360px;
            left: 20px;
            transform: rotate(-14deg);
            opacity: 0.35;
        }
        .cs-sq-7 {
            width: 210px;
            height: 210px;
            top: 380px;
            left: 280px;
            transform: rotate(12deg);
            opacity: 0.25;
            background: #cbd5e1;
        }
        .cs-sq-8 {
            width: 300px;
            height: 300px;
            top: -100px;
            left: 380px;
            transform: rotate(-8deg);
            opacity: 0.15;
            background: #e2e8f0;
        }
        .cs-sq-9 {
            width: 480px;
            height: 300px;
            bottom: 40px;
            left: -100px;
            transform: rotate(-18deg);
            opacity: 0.4;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.015);
        }
        .cs-sq-10 {
            width: 280px;
            height: 280px;
            bottom: 80px;
            left: 150px;
            transform: rotate(22deg);
            opacity: 0.28;
            background: #f1f5f9;
        }
        .cs-sq-11 {
            width: 220px;
            height: 220px;
            bottom: -50px;
            left: -40px;
            transform: rotate(10deg);
            opacity: 0.3;
            background: #e2e8f0;
        }
        .cs-sq-12 {
            width: 260px;
            height: 260px;
            bottom: 20px;
            left: 320px;
            transform: rotate(-12deg);
            opacity: 0.18;
            background: #cbd5e1;
        }

        .cs-dot-grid {
            position: absolute;
            right: 48px;
            bottom: 110px;
            width: 110px;
            height: 90px;
            background-image: radial-gradient(#2d8350 2.2px, transparent 2.2px);
            background-size: 14px 14px;
            opacity: 0.55;
            z-index: 4;
        }

        .cs-wave-bottom {
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            line-height: 0;
            z-index: 3;
        }
        .cs-wave-bottom svg {
            width: 100%;
            height: 150px;
        }

        .cs-right-panel {
            position: relative;
            background: radial-gradient(circle at 45% 38%, #1d7c43 0, #104b2d 43%, #07331f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 32px 72px;
        }

        .lp-login-card { width: min(100%, 548px); padding: 54px 58px 50px; border: 1px solid rgba(184, 239, 194, .2); border-radius: 26px; background: linear-gradient(145deg, rgba(23, 112, 62, .76), rgba(7, 71, 39, .78)); box-shadow: 0 26px 70px rgba(0, 0, 0, .18); text-align: center; backdrop-filter: blur(10px); }
        .lp-form-icon { display: inline-flex; align-items: center; justify-content: center; width: 78px; height: 78px; margin-bottom: 17px; border-radius: 50%; background: #f7fbf7; color: #267442; box-shadow: 0 8px 24px rgba(0, 0, 0, .12); }
        .lp-form-icon svg { width: 48px; height: 48px; }
        .lp-form-heading { margin-bottom: 28px; color: #fff; }
        .lp-form-heading h2 { margin: 0 0 7px; color: #fff; font-size: 28px; font-weight: 800; }
        .lp-form-heading p { margin: 0; color: rgba(255,255,255,.8); font-size: 15px; }

        /* keep existing form styles but adapt colors for right panel */
        .lp-form {
            width: 100%;
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
            background: #fff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #536159;
            text-align: left;
        }
        .lp-field input {
            flex: 1;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: #1a2b20;
        }
        .lp-field input::placeholder { color: #7b857e; }
        .lp-eye-btn { display: inline-flex; width: 23px; height: 23px; padding: 0; border: 0; background: transparent; color: #5d6861; cursor: pointer; }
        .lp-eye-btn svg { width: 100%; height: 100%; }
        .lp-form-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; color: #fff; font-size: 13px; text-align: left; }
        .lp-checkbox { display: inline-flex; align-items: center; gap: 7px; margin: 0; cursor: pointer; }
        .lp-checkbox input { position: absolute; opacity: 0; }
        .lp-checkbox-box { display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; border: 1px solid rgba(255,255,255,.8); border-radius: 4px; font-size: 9px; }
        .lp-checkbox input:not(:checked) + .lp-checkbox-box i { display: none; }
        .lp-link, .lp-register-prompt a { color: #86dc84; font-weight: 700; text-decoration: none; }
        .lp-link:hover, .lp-register-prompt a:hover { color: #c4f6b9; }
        .lp-error { color: #ffd5d5; font-size: 12px; text-align: left; }
        .lp-submit {
            height: 46px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(90deg, #57b95d, #8bd977);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 8px 18px rgba(65, 170, 74, .24);
            cursor: pointer;
        }
        .lp-register-prompt { margin: 25px 0 0; color: rgba(255,255,255,.86); font-size: 14px; }
        .cs-copyright { position: absolute; bottom: 28px; left: 0; right: 0; margin: 0; color: rgba(255,255,255,.62); font-size: 12px; text-align: center; }

        @media (max-width: 900px) {
            .cs-split-layout {
                grid-template-columns: 1fr;
            }
            .cs-left-panel {
                display: none;
                border-top-right-radius: 0;
            }
            .cs-right-panel { min-height: 100vh; padding: 92px 20px 68px; }
            .lp-login-card { padding: 38px 28px; }
            .cs-top-nav { top: 20px; right: 20px; gap: 14px; }
            .cs-brand-logo { top: 14px; left: 16px; width: 62px; height: 62px; padding: 6px; isolation: isolate; border-radius: 0; background: transparent; box-shadow: none; backdrop-filter: none; }
            .cs-brand-logo::before { content: ''; position: absolute; z-index: -1; inset: -13px; background: radial-gradient(circle, rgba(255, 255, 255, .92) 0, rgba(236, 255, 239, .52) 36%, transparent 72%); filter: blur(11px); }
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
