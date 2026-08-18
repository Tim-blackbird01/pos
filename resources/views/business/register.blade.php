@extends('layouts.auth2')
@section('title', __('lang_v1.register'))
@section('standalone_auth', 'true')

@section('content')
    <div class="register-page">
        <div class="bg-wrapper" aria-hidden="true"><div class="left-canvas"><i class="light-sq light-sq-1"></i><i class="light-sq light-sq-2"></i><i class="light-sq light-sq-3"></i></div><div class="green-section"><i class="border-glow-main"></i><i class="border-glow-accent"></i><i class="dark-sq dark-sq-1"></i><i class="dark-sq dark-sq-2"></i><i class="dark-sq dark-sq-3"></i><i class="dark-sq dark-sq-4"></i><i class="dark-sq dark-sq-5"></i><i class="dot-grid"></i></div></div>
        <header class="register-nav">
            <a href="{{ url('/') }}" class="register-logo">
                @if (file_exists(public_path('uploads/logo.svg')))<img src="{{ asset('uploads/logo.svg') }}" alt="{{ config('app.name', 'ultimatePOS') }}">@else<img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name', 'ultimatePOS') }}">@endif
            </a>
            <nav class="register-nav-links">
                @if (Route::has('marketing.plans') && config('app.env') != 'demo')
                    <a href="{{ route('marketing.plans') }}">@lang('superadmin::lang.pricing')</a>
                @endif
                <a href="{{ route('login') }}@if(!empty(request()->lang)){{ '?lang='.request()->lang }}@endif" class="register-login">{{ __('business.sign_in') }}</a>
                <details class="register-language">
                    <summary>{{ isset($_GET['lang']) ? config('constants.langs')[$_GET['lang']]['full_name'] : config('constants.langs')[config('app.locale')]['full_name'] }}</summary>
                    <div>
                        @foreach (config('constants.langs') as $key => $val)
                            <a href="#" value="{{ $key }}" class="change_lang">{{ $val['full_name'] }}</a>
                        @endforeach
                    </div>
                </details>
            </nav>
        </header>
        <main class="register-panel">
            <div class="register-card">
            {!! Form::open([
                'url' => route('business.postRegister'),
                'method' => 'post',
                'id' => 'business_register_form',
                'files' => true,
            ]) !!}
            @include('business.partials.register_form', ['is_register' => true])
            {!! Form::hidden('package_id', $package_id) !!}
            {!! Form::hidden('billing_cycle', request('billing') === 'annual' ? 'annual' : 'monthly') !!}
            {!! Form::close() !!}
            </div>
        </main>
    </div>
@stop

@section('css')
<style>
html, body { min-height: 100%; overflow: auto !important; }
.register-page { --bg-white:#f4f7f5; --green-dark:#0f3d26; --green-glow:#82e28a; --green-lime:#52c26d; position:relative; min-height:100vh; overflow:hidden; font-family:Inter,ui-sans-serif,system-ui,sans-serif; background:var(--green-dark); }
.bg-wrapper,.left-canvas,.green-section { position:absolute; inset:0; overflow:hidden; }
.left-canvas { display:block; width:1000px; height:690px; inset:auto; top:-310px; left:-175px; z-index:3; overflow:visible; background:linear-gradient(135deg,#fff 0%,#f8fbf8 62%,#e1f1e5 100%); border-radius:52px; box-shadow:0 14px 36px rgba(0,0,0,.08); transform:rotate(-39deg); }.left-canvas::after{content:'';position:absolute;right:-5px;bottom:-5px;width:102%;height:100%;border-right:3px solid var(--green-lime);border-bottom:3px solid var(--green-lime);border-radius:0 0 52px 0;filter:drop-shadow(0 0 5px var(--green-glow))}.left-canvas .light-sq{opacity:.72}.light-sq-1{width:620px;height:310px;top:205px;left:95px}.light-sq-2{width:470px;height:240px;top:330px;left:205px;background:rgba(255,255,255,.48)}.light-sq-3{width:335px;height:190px;top:410px;left:305px;background:rgba(219,241,225,.6)}
.light-sq,.dark-sq { position:absolute; display:block; border-radius:28px; transform:rotate(-38deg); }
.light-sq { background:rgba(255,255,255,.65); box-shadow:0 10px 30px rgba(0,0,0,.03); }.light-sq-1{width:480px;height:280px;top:-100px;left:-80px}.light-sq-2{width:360px;height:200px;top:80px;left:40px;background:rgba(255,255,255,.45)}.light-sq-3{width:280px;height:160px;top:180px;left:120px;background:rgba(230,242,235,.7)}
.green-section { top:0; bottom:auto; left:0; right:auto; width:100%; height:100%; background:radial-gradient(circle at 80% 20%,#175a38 0%,#0c331f 70%,#072214 100%); transform:none; z-index:2; }.border-glow-main,.border-glow-accent{display:none}
.dark-sq{border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04)}.dark-sq-1{width:420px;height:220px;bottom:8%;left:-5%}.dark-sq-2{width:320px;height:180px;bottom:22%;left:15%}.dark-sq-3{width:380px;height:200px;bottom:12%;right:18%;border-color:rgba(130,226,138,.25)}.dark-sq-4{width:260px;height:140px;bottom:5%;right:8%}.dark-sq-5{width:200px;height:90px;top:15%;left:20%}.dot-grid{position:absolute;top:18%;right:12%;width:320px;height:320px;background-image:radial-gradient(rgba(82,194,109,.4) 2px,transparent 2px);background-size:16px 16px;opacity:.7}
.register-nav{position:relative;z-index:5;display:flex;align-items:center;justify-content:space-between;padding:16px 5.5vw}.register-logo{width:185px;height:46px;filter:drop-shadow(0 2px 8px rgba(255,255,255,.5)) drop-shadow(0 2px 5px rgba(0,0,0,.3))}.register-logo img{width:100%;height:100%;object-fit:contain;object-position:left}.register-nav-links{display:flex;align-items:center;gap:24px}.register-nav-links>a,.register-language summary{color:#fff;text-decoration:none;font-weight:700;font-size:14px;cursor:pointer;list-style:none}.register-language summary::-webkit-details-marker{display:none}.register-language summary::before{content:'▸';margin-right:7px;font-size:11px}.register-language[open] summary::before{content:'▾'}.register-language{position:relative}.register-language>div{position:absolute;right:0;top:28px;min-width:150px;padding:8px;border-radius:12px;background:#fff;box-shadow:0 16px 35px rgba(0,0,0,.2)}.register-language>div a{display:block;padding:8px 10px;border-radius:8px;color:#164b2f;text-decoration:none;font-size:13px}.register-language>div a:hover{background:#edf8ef}.register-login{padding:8px 19px;border:1px solid rgba(255,255,255,.75);border-radius:999px}.register-panel{position:relative;z-index:4;width:100%;padding:4px 6vw 28px}.register-card{max-width:1080px;margin:auto;padding:18px 26px;background:rgba(255,255,255,.97);border-radius:18px;box-shadow:0 24px 65px rgba(0,0,0,.22)}.register-card fieldset{margin:0}.register-card legend{padding:0 0 5px;border:0;color:#164b2f;font-size:16px;font-weight:800}.register-card .form-group{margin-bottom:7px}.register-card .form-group label{margin-bottom:3px;color:#34463a;font-size:12px;font-weight:700}.register-card .form-control{height:34px;border-color:#d7e3da;box-shadow:none}.register-card .input-group-addon{min-width:34px;padding:6px 9px;border-color:#d7e3da;background:#f4f8f5;color:#278647}.register-card .select2-container .select2-selection--single{height:34px;border-color:#d7e3da}.register-card .select2-container .select2-selection__rendered{line-height:32px}.register-card .select2-container .select2-selection__arrow{height:32px}
@media(max-width:900px){.register-page{overflow:visible;background:#0b3e25}.left-canvas{display:none}.green-section{background:radial-gradient(circle at 14% -8%,#25754a 0,transparent 34%),radial-gradient(circle at 100% 4%,#1c633d 0,transparent 30%),linear-gradient(145deg,#0c4c2d,#062d1c 72%)}.green-section .dark-sq{opacity:.5}.green-section .dot-grid{top:5%;right:-18%;opacity:.4}.register-panel{width:100%;padding:10px 18px 35px}.register-nav{min-height:82px;padding:12px 16px;background:rgba(4,42,25,.38);border-bottom:1px solid rgba(164,239,177,.17);backdrop-filter:blur(12px)}.register-logo{position:relative;isolation:isolate;flex:0 0 58px;width:58px;height:58px;padding:6px;border-radius:0;background:transparent;box-shadow:none;backdrop-filter:none}.register-logo::before{content:'';position:absolute;z-index:-1;inset:-13px;background:radial-gradient(circle,rgba(255,255,255,.92) 0,rgba(236,255,239,.52) 36%,transparent 72%);filter:blur(11px)}.register-nav-links{margin-left:auto;gap:10px;white-space:nowrap}.register-nav-links>a,.register-language summary{font-size:11px}.register-login{padding:7px 10px;white-space:nowrap}.register-card{padding:18px}}
/* Only the wizard controls use the page's green palette; form fields retain their native styling. */
#business_register_form .wizard > .steps > ul > li a { border-radius:999px; color:#427354; background:#edf6ef; font-weight:700; }
#business_register_form .wizard > .steps > ul > li.current a { color:#fff; background:linear-gradient(90deg,#176b3b,#3da957); }
#business_register_form .wizard > .steps > ul > li.done a { color:#165d34; background:#bdecc6; }
#business_register_form .wizard > .actions a, #business_register_form .actions a, #business_register_form .actions button { border:0; border-radius:999px; color:#fff; background:linear-gradient(90deg,#176b3b,#58bc65); box-shadow:0 8px 18px rgba(22,107,59,.22); font-weight:800; }
#business_register_form .wizard > .actions li:first-child a { color:#176b3b; background:#e7f4e9; box-shadow:none; }
/* jQuery Steps applies its colours after the page stylesheet, so retain these
   final, component-only overrides for the generated wizard controls. */
.wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active { background:#176b3b !important; color:#fff !important; }
.wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active { background:#bdecc6 !important; color:#165d34 !important; }
.wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active { border-radius:6px !important; background:#21804a !important; color:#fff !important; }
.wizard > .actions > ul > li:first-child a, .wizard > .actions > ul > li:first-child a:hover, .wizard > .actions > ul > li:first-child a:active { background:#e5e7eb !important; color:#4b5563 !important; box-shadow:none !important; }
</style>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.change_lang').click(function() {
                window.location = "{{ route('business.getRegister') }}?lang=" + $(this).attr('value');
            });
        })
    </script>
@endsection
