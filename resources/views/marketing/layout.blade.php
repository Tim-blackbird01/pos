<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        @yield ('title')
        | {{ config('app.name', 'CraftSalesPOS') }}
    </title>
    <style>
        :root {
            --ink: #14203a;
            --muted: #66738a;
            --green: #1d5f3d;
            --mint: #aef0d1;
            --paper: #f7f8fc;
            --line: #e0e5ef;
            --card: #fff;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            color: var(--ink);
            background: var(--paper);
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                'Segoe UI',
                sans-serif;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        .shell {
            width: min(1160px, calc(100% - 40px));
            margin: auto;
        }
        .top {
            background: #ffffffef;
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 5;
        }
        .nav {
            min-height: 90px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 19px;
            font-weight: 850;
            letter-spacing: -0.04em;
        }
        .top .brand > img { height: 54px !important; width: auto; object-fit: contain; }
        .mark {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: #fff;
            background: linear-gradient(135deg, #1d5f3d, #7fd8b5);
            box-shadow: 0 9px 20px #1d5f3d33;
        }
        .mark svg {
            width: 20px;
        }
        .navlinks {
            display: flex;
            gap: 27px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
        }
        .navlinks a:hover,
        .signin:hover {
            color: var(--green);
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 750;
        }
        .signin {
            color: var(--muted);
            padding: 10px 6px;
        }
        .active {
            color: var(--green) !important;
        }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 17px;
            border: 1px solid transparent;
            border-radius: 11px;
            font: inherit;
            font-weight: 800;
            color: var(--ink);
        }
        .primary {
            color: #fff;
            background: var(--ink);
            box-shadow: 0 10px 22px #14203a24;
        }
        .primary:hover {
            background: #1d5f3d;
        }
        .language {
            position: relative;
        }
        .language summary {
            cursor: pointer;
            list-style: none;
            padding: 9px 7px;
            color: var(--muted);
        }
        .language summary::-webkit-details-marker {
            display: none;
        }
        .language summary:after {
            content: 'v';
            margin-left: 4px;
            font-size: 10px;
        }
        .language-menu {
            position: absolute;
            right: 0;
            top: 38px;
            width: 178px;
            max-height: 270px;
            overflow: auto;
            padding: 7px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 16px 35px #1b294520;
        }
        .language-menu a {
            display: block;
            padding: 8px 9px;
            border-radius: 8px;
            color: var(--ink);
            font-size: 13px;
            font-weight: 650;
        }
        .language-menu a:hover {
            color: var(--green);
            background: #e4f6ea;
        }
        .page-hero {
            padding: 82px 0 55px;
            text-align: center;
            background: radial-gradient(circle at 50% 0, #d7f1e0 0, transparent 36%), var(--paper);
        }
        .eyebrow {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 999px;
            color: #1e6b3c;
            background: #e3f9eb;
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .page-hero h1 {
            max-width: 760px;
            margin: 17px auto 13px;
            font-size: clamp(39px, 5vw, 62px);
            line-height: 1;
            letter-spacing: -0.065em;
        }
        .page-hero p {
            max-width: 640px;
            margin: auto;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.65;
        }
        .section {
            padding: 82px 0;
        }
        .heading {
            text-align: center;
            margin: 0 auto 34px;
        }
        .heading h2 {
            margin: 0 0 10px;
            font-size: clamp(28px, 3.6vw, 43px);
            letter-spacing: -0.055em;
        }
        .heading p {
            max-width: 590px;
            margin: auto;
            color: var(--muted);
            line-height: 1.6;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 17px;
        }
        .card {
            padding: 27px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: var(--card);
            box-shadow: 0 10px 25px #1b2a4710;
        }
        .card h3 {
            margin: 16px 0 8px;
            font-size: 21px;
            letter-spacing: -0.045em;
        }
        .card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.62;
        }
        .icon {
            display: grid;
            place-items: center;
            width: 45px;
            height: 45px;
            border-radius: 13px;
            color: #1f6a47;
            background: #daf5e7;
            font-size: 20px;
            font-weight: 900;
        }
        .icon.mint {
            color: #197251;
            background: #daf5e7;
        }
        .icon.gold {
            color: #966511;
            background: #fff0c2;
        }
        .dark {
            background: #207152;
            color: #fff;
        }
        .dark p {
            color: #d9f1df;
        }
        .dark .icon {
            color: #1e634d;
            background: #daf5e7;
        }
        .band {
            padding: 56px 0;
            background: linear-gradient(135deg, var(--primary) 0%, #62bd96 100%);
            color: #fff;
        }
        .band-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }
        .band h2 {
            max-width: 650px;
            margin: 0;
            font-size: clamp(27px, 3.6vw, 42px);
            line-height: 1.08;
            letter-spacing: -0.05em;
        }
        .band p {
            max-width: 570px;
            color: #f2f8f2;
            line-height: 1.6;
        }
        .band .button {
            flex: 0 0 auto;
            color: var(--ink);
            background: #fff;
            border-color: transparent;
            box-shadow: 0 14px 30px rgba(29, 95, 61, 0.16);
        }
        .band .button:hover {
            background: #e3f5e8;
            color: var(--ink);
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(29, 95, 61, 0.18);
        }
        .site-footer {
            padding: 64px 0 23px;
            color: #71809a;
            background: linear-gradient(112deg, #f5f6fe, #f7fbfa);
            border-top: 1px solid var(--line);
        }
        .site-footer .brand {
            color: var(--ink);
        }
        .site-footer .accent {
            color: var(--primary);
        }
        .footer-columns {
            display: grid;
            grid-template-columns: 1.65fr repeat(3, 1fr);
            gap: 34px;
        }
        .footer-intro p {
            max-width: 290px;
            margin: 17px 0 20px;
            font-size: 14px;
            line-height: 1.65;
        }
        .footer-columns h4 {
            margin: 5px 0 15px;
            color: var(--ink);
            font-size: 14px;
            font-weight: 700;
        }
        .footer-columns a {
            display: block;
            width: max-content;
            max-width: 100%;
            margin: 0 0 14px;
            color: #71809a;
            font-size: 14px;
        }
        .footer-columns a:hover {
            color: var(--green);
        }
        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid #dfe5ed;
            font-size: 12px;
        }
        .plans {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 17px;
            align-items: stretch;
        }
        .plan {
            display: flex;
            flex-direction: column;
            padding: 29px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: #fff;
        }
        .plan.highlight {
            color: #fff;
            border-color: var(--ink);
            background: var(--ink);
            box-shadow: 0 22px 44px #17213b25;
        }
        .plan h3 {
            margin: 0 0 8px;
            font-size: 23px;
        }
        .plan p {
            min-height: 66px;
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }
        .highlight p {
            color: #c6d2e8;
        }
        .price {
            margin: 24px 0;
            font-size: 34px;
            font-weight: 850;
            letter-spacing: -0.06em;
        }
        .price small {
            font-size: 13px;
            letter-spacing: 0;
            color: var(--muted);
        }
        .highlight .price small {
            color: #b6c4df;
        }
        .plan ul {
            display: grid;
            gap: 10px;
            margin: 0 0 25px;
            padding: 18px 0 0;
            border-top: 1px solid var(--line);
            list-style: none;
            color: var(--muted);
            font-size: 13px;
        }
        .highlight ul {
            border-color: #ffffff22;
            color: #d7e0f2;
        }
        .plan li:before {
            content: '+';
            display: inline-block;
            width: 19px;
            color: #198464;
            font-weight: 900;
        }
        .highlight li:before {
            color: var(--mint);
        }
        .plan .button {
            margin-top: auto;
        }
        .highlight .button {
            color: var(--ink);
            background: var(--mint);
        }
        .split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .form {
            display: grid;
            gap: 15px;
            padding: 29px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: #fff;
        }
        .form label {
            display: grid;
            gap: 7px;
            font-size: 13px;
            font-weight: 750;
        }
        .form input,
        .form select,
        .form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd4e3;
            border-radius: 10px;
            color: var(--ink);
            background: #fff;
            font: inherit;
        }
        .form textarea {
            min-height: 130px;
            resize: vertical;
        }
        .form-note {
            padding: 31px;
            border-radius: 20px;
            background: #e8eeff;
        }
        .form-note h2 {
            margin: 0 0 12px;
            font-size: 29px;
            letter-spacing: -0.05em;
        }
        .form-note p {
            color: var(--muted);
            line-height: 1.65;
        }
        .form-note .item {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #cad4f2;
            font-size: 14px;
        }
        .form-note b {
            display: block;
            margin-bottom: 4px;
        }
        .post-grid {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 18px;
        }
        .post {
            padding: 30px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: #fff;
        }
        .post.large {
            min-height: 310px;
            color: #fff;
            border: 0;
            background: linear-gradient(135deg, #162448, #2f57d2);
        }
        .tag {
            display: inline-block;
            margin-bottom: 14px;
            color: #3a56a9;
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .large .tag {
            color: var(--mint);
        }
        .post h2,
        .post h3 {
            margin: 0 0 10px;
            letter-spacing: -0.05em;
        }
        .post h2 {
            max-width: 500px;
            font-size: clamp(27px, 3.5vw, 40px);
        }
        .post h3 {
            font-size: 21px;
        }
        .post p {
            margin: 0;
            color: var(--muted);
            line-height: 1.62;
            font-size: 14px;
        }
        .large p {
            max-width: 530px;
            color: #d7e0f3;
        }
        .post a {
            display: inline-block;
            margin-top: 22px;
            color: #1d5f3d;
            font-size: 13px;
            font-weight: 800;
        }
        .large a {
            color: #1d5f3d;
        }
        .story {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 18px;
            align-items: stretch;
        }
        .visual {
            min-height: 300px;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(145deg, #daf3e5, #eff8f1);
        }
        .visual:before {
            content: '';
            position: absolute;
            width: 245px;
            height: 245px;
            right: -48px;
            bottom: -55px;
            border: 35px solid #fff;
            border-radius: 50%;
        }
        .visual:after {
            content: '';
            position: absolute;
            left: 50px;
            top: 49px;
            width: 180px;
            height: 113px;
            border-radius: 17px;
            background: #1d5f3d;
            box-shadow:
                0 20px 0 #ffffff9c,
                0 40px 0 #ffffff73;
        }
        .copy {
            padding: 38px;
            border-radius: 20px;
            background: #fff;
            border: 1px solid var(--line);
        }
        .copy h2 {
            margin: 0 0 13px;
            font-size: clamp(28px, 3.5vw, 42px);
            letter-spacing: -0.055em;
        }
        .copy p {
            color: var(--muted);
            line-height: 1.68;
        }
        .mission {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 17px;
            margin-top: 18px;
        }
        .mission .card {
            min-height: 190px;
        }
        @media (max-width: 820px) {
            .links {
                display: none;
            }
            .grid-3,
            .plans {
                grid-template-columns: 1fr;
            }
            .split,
            .post-grid,
            .story {
                grid-template-columns: 1fr;
            }
            .footer-grid {
                grid-template-columns: 1.4fr 1fr;
            }
            .footer-grid > :first-child {
                grid-column: span 2;
            }
            .band-inner {
                align-items: start;
                flex-direction: column;
            }
            .plan p {
                min-height: 0;
            }
            .visual {
                min-height: 235px;
            }
        }
        @media (max-width: 540px) {
            .shell {
                width: min(100% - 28px, 1160px);
            }
            .nav {
                min-height: 64px;
            }
            .lang {
                display: none;
            }
            .actions > a:first-of-type {
                display: none;
            }
            .page-hero {
                padding: 58px 0 43px;
            }
            .section {
                padding: 62px 0;
            }
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
            .footer-grid > :first-child {
                grid-column: span 2;
            }
            .mission {
                grid-template-columns: 1fr;
            }
            .site-footer {
                padding-top: 46px;
            }
            .footer-columns {
                grid-template-columns: 1fr 1fr;
                gap: 28px 22px;
            }
            .footer-intro {
                grid-column: span 2;
                grid-row: auto;
            }
            .footer-columns > div:last-child {
                grid-column: auto;
            }
            .footer-bottom {
                align-items: flex-start;
                flex-direction: column;
                margin-top: 34px;
            }
        }
    </style>
    @stack ('styles')
</head>
<body>
    <header class="top">
        <nav class="shell nav">
            <a class="brand" href="{{ url('/') }}">
                @if (file_exists(public_path('uploads/logo.svg')))
                    <img
                        src="/uploads/logo.svg"
                        alt="{{ config('app.name', 'CraftSalesPOS') }}"
                        style="height: 38px; width: auto; object-fit: contain"
                    />
                @else
                    <span class="mark"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 7h16M7 3v8m10-8v8M6 21h12a2 2 0 0 0 2-2V7H4v12a2 2 0 0 0 2 2Z" />
                            <path d="M8 15h3m2 0h3" />
                        </svg
                    ></span>
                @endif
                {{ config('app.name', 'CraftSalesPOS') }}
            </a>
            <div class="navlinks">
                <a
                    href="{{ route('marketing.industries') }}"
                    class="{{ request()->routeIs('marketing.industries') ? 'active' : '' }}"
                    >Industries</a
                ><a
                    class="{{ request()->routeIs('marketing.features') ? 'active' : '' }}"
                    href="{{ route('marketing.features') }}"
                    >Features</a
                ><a
                    class="{{ request()->routeIs('marketing.pricing') ? 'active' : '' }}"
                    href="{{ route('marketing.pricing') }}"
                    >Pricing</a
                ><a
                    class="{{ request()->routeIs('marketing.about') ? 'active' : '' }}"
                    href="{{ route('marketing.about') }}"
                    >About</a
                ><a
                    class="{{ request()->routeIs('marketing.updates') ? 'active' : '' }}"
                    href="{{ route('marketing.updates') }}"
                    >Updates</a
                ><a
                    class="{{ request()->routeIs('marketing.contact') ? 'active' : '' }}"
                    href="{{ route('marketing.contact') }}"
                    >Contact</a
                >
            </div>
            <div class="nav-actions">
                <details class="language">
                    <summary>
                        {{ isset($_GET['lang']) && isset(config('constants.langs')[$_GET['lang']]) ? config('constants.langs')[$_GET['lang']]['full_name'] : config('constants.langs')[config('app.locale')]['full_name'] }}
                    </summary>
                    <div class="language-menu">
                        @foreach (config('constants.langs') as $key => $language)
                            <a
                                href="{{ url()->current() }}?lang={{ $key }}"
                                >{{ $language['full_name'] }}</a
                            >
                        @endforeach
                    </div>
                </details>
                <a class="signin" href="{{ route('login') }}">Sign in</a
                ><a class="button primary" href="{{ route('business.getRegister') }}"
                    >Get started</a
                >
            </div>
        </nav>
    </header>
    <main>@yield ('content')</main>
    @include('layouts.partials.footer')
    @stack ('scripts')
</body>
</html>
