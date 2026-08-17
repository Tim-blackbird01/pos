<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'CraftSalesPOS') }} | Run the day beautifully</title>
    <style>
        :root { --navy:#111c35; --ink:#1d2942; --muted:#667189; --paper:#f7f8fc; --line:#e1e5ee; --green:#1d5f3d; --mint:#baf0d9; --coral:#ffb9a7; --yellow:#ffe189; }
        * { box-sizing:border-box; } html { scroll-behavior:smooth; scroll-padding-top:96px; } body { margin:0; color:var(--ink); background:var(--paper); font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; } a { color:inherit; text-decoration:none; }
        .shell { width:min(1160px,calc(100% - 40px)); margin:auto; } .top { position:fixed; top:0; left:0; right:0; z-index:50; background:#fff; border-bottom:1px solid #edf0f5; } .nav { position:relative; min-height:64px; display:flex; align-items:center; justify-content:space-between; gap:22px; }
        .brand { display:inline-flex; gap:10px; align-items:center; font-size:19px; font-weight:850; letter-spacing:-.04em; }
        .brand-logo { height:54px; display:block; width:auto; object-fit:contain; }
        .mark { width:35px; height:35px; display:grid; place-items:center; color:#fff; border-radius:11px; background:linear-gradient(135deg,#1d5f3d,var(--mint)); box-shadow:0 10px 23px #1d5f3d33; } .mark svg { width:20px; }
        .navlinks { display:flex; gap:27px; color:var(--muted); font-size:14px; font-weight:700; } .navlinks a:hover,.signin:hover { color:#1d5f3d; } .nav-actions { display:flex; align-items:center; gap:12px; font-size:14px; font-weight:750; } .signin { color:var(--muted); padding:10px 6px; } .language { position:relative; } .language summary { cursor:pointer; list-style:none; padding:9px 7px; color:var(--muted); } .language summary::-webkit-details-marker { display:none; } .language summary:after { content:"v"; margin-left:5px; font-size:10px; } .language-menu { position:absolute; z-index:5; top:38px; right:0; width:178px; max-height:270px; overflow:auto; padding:7px; border:1px solid var(--line); border-radius:12px; background:#fff; box-shadow:0 16px 35px #1b294520; } .language-menu a { display:block; padding:8px 9px; border-radius:8px; color:var(--ink); font-size:13px; font-weight:650; } .language-menu a:hover { color:#1d5f3d; background:#e4f6ea; }
        .nav-toggle { display:none; appearance:none; border:1px solid var(--line); background:#fff; width:44px; height:44px; border-radius:14px; place-items:center; cursor:pointer; } .nav-toggle span, .nav-toggle span::before, .nav-toggle span::after { content:""; display:block; width:20px; height:2px; border-radius:999px; background:var(--ink); position:relative; } .nav-toggle span::before { top:-6px; position:absolute; left:0; } .nav-toggle span::after { top:6px; position:absolute; left:0; }
        .mobile-menu { display:none; position:absolute; inset:100% 0 auto; margin-top:12px; width:100%; max-width:100%; border:1px solid var(--line); border-radius:18px; background:#fff; box-shadow:0 20px 60px rgba(17,28,53,.13); z-index:10; overflow:hidden; }
        .mobile-menu.open { display:block; }
        .mobile-menu .navlinks { display:grid; gap:12px; padding:18px 20px; border-bottom:1px solid var(--line); background:#fff; }
        .mobile-menu .navlinks a { padding:13px 0; font-size:15px; }
        .mobile-menu .nav-actions { flex-direction:column; align-items:stretch; padding:18px 20px; gap:12px; background:#fff; }
        .mobile-menu .signin { padding:14px 0; }
        .mobile-menu .button { width:100%; justify-content:center; }
        .button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 17px; border:1px solid transparent; border-radius:11px; font:inherit; font-weight:800; transition:transform .18s,box-shadow .18s,background .18s; } .button:hover { transform:translateY(-2px); } .primary { color:#fff; background:var(--navy); box-shadow:0 10px 22px #15213b29; } .primary:hover { background:#1d5f3d; box-shadow:0 14px 30px #1d5f3d33; } .secondary { background:#fff; color:var(--ink); border-color:var(--line); }
        .hero { position:relative; overflow:hidden; padding:90px 0 74px; background:radial-gradient(circle at 82% 20%,#d8f5e4 0,transparent 25%),radial-gradient(circle at 10% 0,#e3f8ed 0,transparent 25%),var(--paper); } .hero:before { content:""; position:absolute; inset:0; opacity:.45; background-image:radial-gradient(#b9d8be 1px,transparent 1px); background-size:25px 25px; mask-image:linear-gradient(black,transparent 75%); }
        .hero-grid { position:relative; display:grid; grid-template-columns:.98fr 1.02fr; gap:56px; align-items:center; } .eyebrow { display:inline-flex; gap:8px; align-items:center; padding:7px 11px; border-radius:999px; color:#1e6b3c; background:#e3f9eb; font-size:11px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; } .eyebrow i { width:7px; height:7px; border-radius:50%; background:#50c88c; box-shadow:0 0 0 4px #50c88c2b; }
        h1 { max-width:600px; margin:19px 0; font-size:clamp(44px,5.4vw,69px); line-height:.99; letter-spacing:-.07em; } .accent { color:#1d5f3d; } .lede { max-width:540px; margin:0 0 28px; color:var(--muted); font-size:18px; line-height:1.65; } .actions { display:flex; flex-wrap:wrap; gap:12px; }.note { display:flex; align-items:center; gap:10px; margin-top:29px; color:var(--muted); font-size:13px; }.tick { display:grid; place-items:center; width:21px; height:21px; flex:0 0 auto; border-radius:50%; color:#126b4b; background:var(--mint); font-size:12px; font-weight:900; }
        .hero-visual { position:relative; min-height:397px; } .report { position:absolute; inset:8px 0 auto auto; width:min(100%,530px); padding:18px; border:1px solid #ffffffcc; border-radius:23px; background:#fffffff0; box-shadow:0 31px 65px #30406225; transform:rotate(2.2deg); backdrop-filter:blur(12px); } .window { display:flex; gap:6px; align-items:center; padding-bottom:14px; border-bottom:1px solid var(--line); } .window i { width:8px; height:8px; border-radius:50%; background:#ff9986; }.window i:nth-child(2){background:#ffdd78}.window i:nth-child(3){background:#6bd4a1}.window b { margin-left:7px; font-size:12px; }.window span { margin-left:auto; color:var(--muted); font-size:11px; }
        .panel { display:grid; grid-template-columns:105px 1fr; gap:15px; padding-top:15px; } .side { display:grid; align-content:start; gap:10px; padding:7px 6px; }.side span { height:10px; border-radius:8px; background:#e9f0e4; }.side span:nth-child(2){width:75%;background:#d8eed8}.side span:nth-child(5){width:65%}.main-panel { display:grid; gap:12px; }.headline { display:flex; align-items:baseline; justify-content:space-between; }.headline b { font-size:17px; }.headline small { color:var(--muted); }.chart { height:137px; position:relative; overflow:hidden; border-radius:15px; background:linear-gradient(#eaf6ed,#fff); }.chart:before { content:""; position:absolute; inset:13px; background:repeating-linear-gradient(to bottom,transparent 0 27px,#dfeae4 28px 29px); }.chart svg { position:absolute; inset:12px 5px 0; width:calc(100% - 10px); height:calc(100% - 12px); }.numbers { display:grid; grid-template-columns:1fr 1fr; gap:10px; }.number { padding:11px; border:1px solid var(--line); border-radius:12px; background:#fff; }.number small { display:block; margin-bottom:4px; color:var(--muted); }.number b { font-size:15px; }.number b:before { content:""; display:inline-block; width:7px; height:7px; margin-right:6px; border-radius:50%; background:#5ebf89; }
        .receipt { position:absolute; left:-13px; bottom:4px; display:flex; align-items:center; gap:11px; padding:13px 15px; color:#fff; border-radius:15px; background:var(--navy); box-shadow:0 18px 34px #12392438; transform:rotate(-3deg); }.receipt strong { display:block; font-size:13px; }.receipt small { display:block; margin-bottom:3px; color:#c1cbe1; font-size:10px; }.receipt-icon { width:30px; height:30px; display:grid; place-items:center; border-radius:9px; color:#17654c; background:var(--mint); font-weight:900; }
        .assurance { padding:24px 0; background:#fff; border-top:1px solid var(--line); border-bottom:1px solid var(--line); }.assurance div { display:flex; justify-content:space-between; gap:18px; color:#7c8598; font-size:12px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; }.assurance span { display:inline-flex; align-items:center; gap:8px; }.assurance i { width:19px; height:1px; background:#c7cedc; }
        .solutions { padding:102px 0 86px; }.heading { display:flex; align-items:end; justify-content:space-between; gap:26px; margin-bottom:31px; }.heading h2 { max-width:600px; margin:0; font-size:clamp(31px,4vw,47px); line-height:1.05; letter-spacing:-.055em; }.heading p { max-width:360px; margin:0; color:var(--muted); line-height:1.65; }
        .bento { display:grid; grid-template-columns:1.18fr .82fr .82fr; gap:17px; }.card { min-height:294px; position:relative; overflow:hidden; padding:25px; border:1px solid var(--line); border-radius:21px; background:#fff; transition:transform .2s,box-shadow .2s; }.card:hover { transform:translateY(-5px); box-shadow:0 22px 45px #293b5c16; }.card h3 { position:relative; z-index:1; max-width:285px; margin:16px 0 7px; font-size:21px; letter-spacing:-.045em; }.card p { position:relative; z-index:1; max-width:285px; margin:0; color:var(--muted); font-size:14px; line-height:1.62; }.icon { display:grid; place-items:center; width:45px; height:45px; border-radius:13px; color:#3d57db; background:#e8edff; }.icon svg { width:23px; height:23px; }.restaurant .icon { color:#9f6810; background:#fff0c6; }.service .icon { color:#117858; background:#d9f5e7; }
        .retail-art { position:absolute; right:-22px; bottom:-23px; width:205px; height:170px; border-radius:26px 0 0; background:linear-gradient(145deg,#e5f2e7,#d3ebd8); transform:rotate(-10deg); }.retail-art:before,.retail-art:after { content:""; position:absolute; border-radius:12px; background:#fff; box-shadow:0 7px 14px #3f7f5d2a; }.retail-art:before { width:104px; height:78px; left:27px; top:30px; }.retail-art:after { width:75px; height:11px; left:43px; top:47px; box-shadow:0 20px 0 #c7e4d7,0 40px 0 #c7e4d7; }.kitchen-art { position:absolute; right:-19px; bottom:-28px; width:164px; height:180px; border:15px solid #fff0ba; border-radius:28px; transform:rotate(26deg); }.kitchen-art:after { content:""; position:absolute; inset:25px; border:9px solid #fff8df; border-radius:15px; }.service-art { position:absolute; right:-36px; bottom:-50px; width:183px; height:183px; border:20px solid #d9f4e7; border-radius:50%; }.service-art:before { content:""; position:absolute; inset:28px; border-radius:50%; background:#eefbf5; }
        .enterprise { grid-column:span 3; min-height:261px; padding:38px; color:#fff; border:0; background:var(--navy); }.enterprise:after { content:""; position:absolute; width:410px; height:410px; right:-90px; top:-210px; border:54px solid #5ebf89; border-radius:50%; opacity:.72; }.enterprise h3 { max-width:525px; margin:0 0 11px; font-size:clamp(27px,3.2vw,39px); line-height:1.05; }.enterprise p { max-width:540px; color:#c4cee4; }.enterprise .text-link { position:relative; z-index:1; display:inline-flex; gap:9px; align-items:center; margin-top:23px; color:var(--mint); font-size:14px; font-weight:800; }.location-stats { position:absolute; z-index:1; right:46px; bottom:36px; display:grid; grid-template-columns:repeat(3,112px); gap:11px; }.location-stats span { padding:13px; border:1px solid #ffffff1a; border-radius:13px; background:#ffffff0b; }.location-stats small { display:block; color:#aebbd7; font-size:10px; }.location-stats b { display:block; margin-top:5px; font-size:16px; }
        .quote { padding:0 0 85px; }.quote-box { display:grid; grid-template-columns:72px 1fr; gap:22px; align-items:start; padding:37px; border-radius:22px; background:#e8f5eb; }.avatar { display:grid; place-items:center; width:60px; height:60px; border-radius:20px; color:#2d7c50; background:linear-gradient(135deg,#cdf0d7,#fff); font-size:21px; font-weight:900; }.quote blockquote { max-width:760px; margin:0; font-size:clamp(20px,2.6vw,29px); line-height:1.35; letter-spacing:-.035em; }.quote cite { display:block; margin-top:15px; color:var(--muted); font-size:13px; font-style:normal; }
        .how { padding:0 0 98px; }.how-grid { display:grid; grid-template-columns:1fr .95fr; gap:18px; }.process { padding:34px; border-radius:22px; color:#f4f7ff; background:#1f4f36; }.process .eyebrow { color:#cfe7d7; background:#ebf8ef; }.process h2 { max-width:470px; margin:18px 0 29px; font-size:clamp(29px,3.7vw,43px); line-height:1.06; letter-spacing:-.05em; }.steps { display:grid; gap:17px; }.step { display:grid; grid-template-columns:34px 1fr; gap:13px; }.step-number { display:grid; place-items:center; width:30px; height:30px; border:1px solid #ffffff2e; border-radius:9px; color:var(--mint); background:#ffffff10; font-size:12px; font-weight:850; }.step b { display:block; margin:3px 0 4px; }.step p { margin:0; color:#c7d3eb; font-size:13px; line-height:1.55; }.outcomes { display:grid; grid-template-rows:1fr 1fr; gap:18px; }.outcome { position:relative; overflow:hidden; min-height:190px; padding:30px; border:1px solid var(--line); border-radius:22px; background:#fff; }.outcome h3 { position:relative; z-index:1; max-width:270px; margin:0 0 8px; font-size:23px; letter-spacing:-.045em; }.outcome p { position:relative; z-index:1; max-width:300px; margin:0; color:var(--muted); line-height:1.6; font-size:14px; }.outcome:first-child:after { content:""; position:absolute; right:-16px; bottom:-36px; width:170px; height:170px; border:21px solid #ffe39b; border-radius:50%; }.outcome:last-child { background:#ddf6eb; }.outcome:last-child:after { content:""; position:absolute; right:30px; bottom:26px; width:71px; height:47px; border:10px solid #55bd8b; border-top:0; border-radius:0 0 14px 14px; transform:rotate(-18deg); }
        .numbers-strip { padding:32px 0 97px; }.numbers-strip .inner { display:grid; grid-template-columns:repeat(4,1fr); border:1px solid var(--line); border-radius:20px; overflow:hidden; background:#fff; }.fact { padding:27px 25px; border-right:1px solid var(--line); }.fact:last-child { border:0; }.fact b { display:block; margin-bottom:5px; font-size:25px; letter-spacing:-.05em; }.fact span { color:var(--muted); font-size:13px; }
        .trusted { padding:0 0 96px; text-align:center; }.trusted > p { margin:0 0 24px; color:#8993a7; font-size:11px; font-weight:850; letter-spacing:.14em; text-transform:uppercase; }.brand-row { display:grid; grid-template-columns:repeat(5,1fr); gap:12px; }.brand-row span { display:grid; place-items:center; min-height:63px; border:1px solid var(--line); border-radius:14px; color:#7c879b; background:#fff; font-size:15px; font-weight:850; letter-spacing:-.04em; }

        .footer-cta { padding:61px 0 30px; color:#e7ecf8; background:#0f1930; }.footer-grid { display:grid; grid-template-columns:1.45fr 1fr 1fr; gap:30px; }.footer-grid .brand { color:#fff; }.footer-grid p { max-width:315px; color:#aeb8ce; font-size:14px; line-height:1.6; }.footer-grid h4 { margin:5px 0 13px; color:#fff; font-size:13px; }.footer-grid a { display:block; margin:9px 0; color:#aeb8ce; font-size:13px; }.footer-grid a:hover { color:var(--mint); }.footer-bottom { display:flex; align-items:center; justify-content:space-between; gap:18px; margin-top:48px; padding-top:24px; color:#8895b2; border-top:1px solid #ffffff17; font-size:12px; } .footer-bottom-links { display:flex; gap:26px; margin-right:22px; } .footer-bottom-links a { color:#aeb8ce; text-decoration:none; } .footer-bottom-links a:hover { color:var(--mint); } .legal { display:flex; justify-content:space-between; gap:15px; margin-top:44px; padding-top:18px; color:#8895b2; border-top:1px solid #ffffff17; font-size:12px; }
        @media(max-width:850px) { .navlinks { display:none; }.nav-actions { display:none; }.nav-toggle { display:grid; }.hero { padding-top:52px; }.hero-grid,.bento,.how-grid { grid-template-columns:1fr; }.hero-visual { min-height:365px; }.report { left:0; transform:none; }.receipt { left:9px; }.heading { align-items:start; flex-direction:column; }.enterprise { grid-column:auto; }.location-stats { right:25px; bottom:23px; }.footer-grid { grid-template-columns:1.3fr 1fr; }.footer-grid > :first-child { grid-column:span 2; } .numbers-strip .inner { grid-template-columns:1fr 1fr; }.fact:nth-child(2) { border-right:0; }.fact:nth-child(-n+2) { border-bottom:1px solid var(--line); } .brand-row { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:540px) { .shell { width:min(100% - 28px,1160px); }.nav { min-height:65px; }.signin,.language { display:none; }.brand { font-size:17px; }.button { padding:11px 14px; }.hero { padding:90px 0 55px; }.lede { font-size:16px; }.hero-visual { min-height:326px; }.report { padding:13px; }.panel { grid-template-columns:72px 1fr; gap:10px; }.side { gap:8px; }.assurance div { flex-wrap:wrap; }.assurance span { width:45%; }.solutions { padding:69px 0 62px; }.enterprise { padding:28px 24px 145px; }.location-stats { left:24px; right:auto; grid-template-columns:repeat(3,1fr); }.location-stats span { padding:10px; }.quote { padding-bottom:61px; }.quote-box { grid-template-columns:1fr; gap:13px; padding:28px; }.how { padding-bottom:62px; }.process,.outcome { padding:26px; }.footer-grid { grid-template-columns:1fr 1fr; }.footer-grid > :first-child { grid-column:span 2; }.legal { flex-direction:column; }.numbers-strip { padding-bottom:65px; }.numbers-strip .inner { grid-template-columns:1fr; }.fact,.fact:nth-child(2) { border-right:0; border-bottom:1px solid var(--line); }.fact:last-child { border-bottom:0; }.trusted { padding-bottom:66px; }.brand-row { grid-template-columns:1fr 1fr; } }
        /* Keep account actions available inside the opened mobile navigation. */
        @media(max-width:850px) {
            .mobile-menu { max-height:calc(100vh - 78px); overflow-y:auto; }
            .mobile-menu .nav-actions { display:grid; grid-template-columns:1fr 1fr; align-items:stretch; padding:16px 20px 20px; gap:12px; }
            .mobile-menu .signin { display:inline-flex; align-items:center; justify-content:center; min-height:46px; padding:11px 14px; border:1px solid var(--line); border-radius:11px; color:var(--ink); background:#fff; }
            .mobile-menu .signin:hover { color:#1d5f3d; border-color:#a8d9bb; background:#f3fbf6; }
            .mobile-menu .button { min-height:46px; }
        }
        @media(max-width:540px) {
            .mobile-menu .nav-actions { grid-template-columns:1fr; }
            .mobile-menu .signin { display:inline-flex; }
        }
        .card-photo{position:absolute;inset:0;z-index:0}.card-photo img{width:100%;height:100%;object-fit:cover;transform:scale(1.03);transition:transform .55s ease}.card-photo .wash{position:absolute;inset:0}.retail .wash{background:linear-gradient(195deg,#0c3121f2 8%,#1d5c3cc5 48%,#2f7f5d66)}.restaurant .wash{background:linear-gradient(195deg,#221607f2 8%,#5a3a10c7 48%,#8a5a1858)}.service .wash{background:linear-gradient(195deg,#04241af2 8%,#0e4a34c7 48%,#1a6f4f58)}.photo-card{color:#fff}.photo-card p{color:#d7e7d4}.photo-card .icon{position:relative;z-index:1;color:#fff;background:#ffffff27;backdrop-filter:blur(5px)}.card:hover .card-photo img{transform:scale(1.1)}.workflow { padding:0 0 96px; }.workflow-head { max-width:650px; margin:0 auto 32px; text-align:center; }.workflow-head h2 { margin:0 0 12px; font-size:clamp(31px,4vw,47px); line-height:1.05; letter-spacing:-.055em; }.workflow-head p { margin:0; color:var(--muted); line-height:1.65; }.workflow-grid { display:grid; grid-template-columns:1.15fr .85fr .85fr; gap:17px; }.workflow-card { min-height:345px; position:relative; overflow:hidden; padding:25px; border:1px solid var(--line); border-radius:21px; background:#fff; }.workflow-card h3 { position:relative; z-index:1; margin:15px 0 7px; font-size:21px; letter-spacing:-.045em; }.workflow-card p { position:relative; z-index:1; max-width:285px; margin:0; color:var(--muted); font-size:14px; line-height:1.6; }.workflow-card svg { position:absolute; width:100%; max-width:230px; height:118px; right:12px; bottom:14px; }.workflow-card.focus { color:#fff; border:0; background:linear-gradient(145deg,#1d5f3d,#5ebf89); }.workflow-card.focus p { color:#e6f4e9; }.workflow-card.focus svg { max-width:290px; height:130px; right:-6px; bottom:12px; }.workflow-badge { position:relative; z-index:1; display:inline-flex; align-items:center; gap:7px; padding:6px 9px; border-radius:999px; color:#1f6a47; background:#e3f9eb; font-size:11px; font-weight:850; }.focus .workflow-badge {  color:#dce5ff; background:#ffffff19; }.workflow-badge i { width:6px; height:6px; border-radius:50%; background:#4eca8b; }.workflow-card:hover svg { transform:translateY(-4px); transition:transform .25s ease; }.gallery{padding:0 0 92px}.gallery-head{max-width:650px;margin:0 auto 31px;text-align:center}.gallery-head h2{margin:0 0 11px;font-size:clamp(31px,4vw,47px);letter-spacing:-.055em}.gallery-head p{margin:0;color:var(--muted);line-height:1.65}.gallery-grid{display:grid;grid-template-columns:1.25fr 1fr 1fr;gap:17px}.gallery-item{position:relative;min-height:250px;overflow:hidden;border-radius:21px;background:#dbe3f5}.gallery-item.tall{grid-row:span 2;min-height:517px}.gallery-item img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:scale(1.03);transition:transform .55s ease}.gallery-item:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#0b1225a8 0%,transparent 47%)}.gallery-item:hover img{transform:scale(1.1)}.gallery-tag{position:absolute;z-index:1;left:16px;bottom:16px;display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border-radius:999px;color:var(--ink);background:#ffffffec;font-size:12px;font-weight:850}.gallery-tag i{width:7px;height:7px;border-radius:50%;background:#4eca8b}.top{position:fixed;top:0;left:0;right:0;z-index:50;transition:box-shadow .25s ease,border-color .25s ease}.top.scrolled{box-shadow:0 8px 24px #17213d14;border-color:transparent}[data-reveal]{opacity:0;transform:translateY(20px);transition:opacity .65s cubic-bezier(.2,.7,.2,1),transform .65s cubic-bezier(.2,.7,.2,1)}[data-reveal].in{opacity:1;transform:none}@media(max-width:850px){.workflow-grid{grid-template-columns:1fr 1fr}.workflow-card.focus{grid-column:span 2}.gallery-grid{grid-template-columns:1fr 1fr}.gallery-item.tall{grid-row:span 1;min-height:250px}}@media(max-width:540px){.workflow{padding-bottom:64px}.workflow-grid,.gallery-grid{grid-template-columns:1fr}.workflow-card.focus{grid-column:auto}.workflow-card{min-height:325px}.workflow-card.focus svg{max-width:270px}.gallery{padding-bottom:64px}.gallery-item,.gallery-item.tall{min-height:240px}}@media(prefers-reduced-motion:reduce){*,*:before,*:after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}}
        .hero{position:relative;overflow:hidden;padding:110px 0 100px;background:#fafbfc;}
        .hero-grid{position:relative;z-index:2;display:grid;grid-template-columns:0.75fr 1.25fr;gap:20px;align-items:center;}
        .hero-content{padding-right:10px;}
        h1.hero-title{max-width:500px;margin:0 0 20px;font-size:clamp(36px,3.8vw,58px);line-height:1.08;font-weight:850;letter-spacing:-.04em;color:var(--navy);}
        h1.hero-title .accent{color:#137847;}
        .hero-lede{max-width:440px;margin:0 0 34px;color:#55637d;font-size:16px;line-height:1.6;font-weight:450;}
        .hero-actions{display:flex;flex-wrap:wrap;gap:14px;align-items:center;}
        .btn-green{display:inline-flex;align-items:center;gap:8px;padding:14px 26px;border-radius:12px;background:linear-gradient(180deg,#187747 0%,#115e37 100%);color:#fff;font-weight:700;font-size:15px;box-shadow:0 10px 24px rgba(23,114,68,0.25);transition:all .25s ease;}
        .btn-green:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(23,114,68,0.35);}
        .btn-outline{display:inline-flex;align-items:center;gap:8px;padding:14px 26px;border-radius:12px;background:#fff;border:1px solid #dbe2ea;color:#187747;font-weight:700;font-size:15px;transition:all .25s ease;}
        .btn-outline:hover{background:#f4f9f6;border-color:#b5d8c5;transform:translateY(-2px);}
        .hero-visual-wrapper{position:relative;display:flex;align-items:center;justify-content:flex-start;min-height:clamp(440px,42vw,560px);}
        .hero-bg-arc{position:absolute;left:2%;top:50%;transform:translateY(-50%);width:clamp(400px,45vw,560px);height:clamp(400px,45vw,560px);border-radius:50%;background:radial-gradient(circle,rgba(162,222,187,0.45) 0%,rgba(205,240,220,0.2) 60%,rgba(255,255,255,0) 75%);border:75px solid rgba(132,210,163,0.22);pointer-events:none;z-index:1;animation:pulseGlow 6s ease-in-out infinite alternate;}
        .laptop-container{position:relative;z-index:2;width:100%;max-width:660px;animation:floatLaptop 6s ease-in-out infinite alternate;}
        .laptop-img{width:100%;height:auto;display:block;filter:drop-shadow(0 28px 40px rgba(20,32,55,0.2));}
        .badges-container{position:relative;z-index:3;display:flex;flex-direction:column;justify-content:space-between;height:clamp(380px,44vw,540px);margin-left:clamp(-140px,-10vw,-100px);min-width:clamp(260px,28vw,360px);}
        .badges-arc-svg{position:absolute;left:-10px;top:0;width:260px;height:100%;pointer-events:none;z-index:1;overflow:visible;}
        .animated-vibrant-ring{stroke-linecap:round;filter:drop-shadow(0px 0px 12px rgba(0,223,162,0.7));animation:colorCycle 6s linear infinite alternate;}
        .feature-badge{position:relative;z-index:2;display:flex;align-items:center;gap:14px;padding:10px 18px 10px 10px;background:rgba(255,255,255,0.94);border:1px solid rgba(255,255,255,0.95);border-radius:50px;box-shadow:0 10px 28px rgba(17,28,53,0.08);backdrop-filter:blur(10px);transition:all .3s cubic-bezier(0.34,1.56,0.64,1);max-width:280px;opacity:0;animation:slideInBadge 0.8s cubic-bezier(0.16,1,0.3,1) forwards,floatBadge 5s ease-in-out infinite alternate;}
        .feature-badge:hover{transform:translateX(6px) scale(1.02) !important;box-shadow:0 16px 36px rgba(19,120,71,0.16);background:#ffffff;border-color:#bce5cd;}
        .badge-1{margin-left:20px;animation-delay:0.1s,0.0s;}
        .badge-2{margin-left:105px;animation-delay:0.2s,0.4s;}
        .badge-3{margin-left:145px;animation-delay:0.3s,0.8s;}
        .badge-4{margin-left:105px;animation-delay:0.4s,1.2s;}
        .badge-5{margin-left:20px;animation-delay:0.5s,1.6s;}
        .badge-icon{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#a4e6bb 0%,#58c084 100%);display:flex;align-items:center;justify-content:center;color:#0e472a;flex-shrink:0;box-shadow:0 4px 12px rgba(40,140,80,0.2);transition:transform 0.3s ease;}
        .feature-badge:hover .badge-icon{transform:scale(1.08) rotate(4deg);}
        .badge-icon svg{width:20px;height:20px;stroke-width:2.2;}
        .badge-text h4{margin:0 0 2px;font-size:12.5px;font-weight:800;color:#166a3e;}
        .badge-text p{margin:0;font-size:10.5px;color:#647188;line-height:1.3;font-weight:500;}
        @keyframes floatLaptop{0%{transform:translateY(0px);}100%{transform:translateY(-10px);}}
        @keyframes pulseGlow{0%{transform:translateY(-50%) scale(0.96);opacity:0.8;}100%{transform:translateY(-50%) scale(1.04);opacity:1;}}
        @keyframes slideInBadge{0%{opacity:0;transform:translateX(30px);}100%{opacity:1;transform:translateX(0);}}
        @keyframes floatBadge{0%{transform:translateY(0px);}100%{transform:translateY(-5px);}}
        @keyframes colorCycle{0%{filter:hue-rotate(0deg) drop-shadow(0px 0px 10px rgba(0,255,135,0.7));}50%{filter:hue-rotate(180deg) drop-shadow(0px 0px 14px rgba(255,0,127,0.7));}100%{filter:hue-rotate(360deg) drop-shadow(0px 0px 10px rgba(0,223,162,0.7));}}
        @media (max-width:1080px){.hero-grid{grid-template-columns:1fr;gap:40px;text-align:center;}.hero-content{padding-right:0;display:flex;flex-direction:column;align-items:center;}.hero-actions{justify-content:center;}.hero-visual-wrapper{justify-content:center;}.laptop-container{margin-left:0;}}
        @media (max-width:900px){.badges-container,.hero-bg-arc{display:none !important;}.hero-visual-wrapper{min-height:auto;justify-content:center;}.laptop-container{max-width:100%;animation:none;}}
        @media (max-width:540px){.hero{padding:90px 0 60px;}.hero-title{font-size:34px;}.hero-lede{font-size:15px;}.hero-actions{width:100%;flex-direction:column;}.btn-green,.btn-outline{width:100%;justify-content:center;}}
        @media (prefers-reduced-motion:reduce){.hero-bg-arc,.laptop-container,.feature-badge,.animated-vibrant-ring{animation:none !important;opacity:1 !important;}}
        .industry-marquee-section{padding:70px 0 48px;background:#f8fafc;}
        .industry-marquee-section .section-head{max-width:720px;margin:0 auto 32px;text-align:center;}
        .industry-marquee-section .section-head h2{margin:0;font-size:clamp(32px,3.8vw,44px);line-height:1.08;letter-spacing:-.04em;color:var(--ink);}
        .industry-marquee-section .section-head p{margin:18px auto 0;max-width:620px;color:#5a677f;font-size:16px;line-height:1.75;}
        .industry-marquee{position:relative;overflow:hidden;left:50%;right:50%;width:100vw;margin-left:-50vw;margin-right:-50vw;}
        .industry-track{display:flex;gap:18px;width:max-content;animation:industryScroll 32s linear infinite;padding:0;}
        .industry-marquee:hover .industry-track,.industry-marquee.is-paused .industry-track{animation-play-state:paused;}
        @keyframes industryScroll{0%{transform:translateX(0);}100%{transform:translateX(-50%);}}
        .industry-card{position:relative;flex-shrink:0;width:320px;min-height:220px;background:#fff;border-radius:22px;border:1px solid #e2e8f0;box-shadow:0 18px 35px -18px rgba(0,0,0,.16);overflow:hidden;transition:transform .25s ease,border-color .25s ease,box-shadow .25s ease;}
        .industry-card:hover{transform:translateY(-4px);border-color:rgba(29,95,61,.18);box-shadow:0 24px 42px -20px rgba(0,0,0,.14);}
        .industry-card img{width:100%;height:100%;object-fit:cover;display:block;}
        .industry-badge{position:absolute;left:24px;right:16px;bottom:16px;display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:9999px;background:rgba(255,255,255,.92);backdrop-filter:blur(10px);color:var(--ink);font-weight:700;font-size:14px;border:1px solid rgba(255,255,255,.7);}
        .industry-badge i{width:8px;height:8px;border-radius:50%;background:var(--badge-color,#2563eb);box-shadow:0 0 0 2px var(--badge-glow,rgba(37,99,235,.12));flex-shrink:0;animation:badgeGlow 2.6s ease-in-out infinite;}
        .industry-badge.badge-blue{--badge-color:#2563eb;--badge-glow:rgba(37,99,235,.16);}
        .industry-badge.badge-green{--badge-color:#1d5f61;--badge-glow:rgba(29,95,97,.16);}
        .industry-badge.badge-gold{--badge-color:#d97706;--badge-glow:rgba(217,119,6,.16);}
        .industry-badge.badge-purple{--badge-color:#8b5cf6;--badge-glow:rgba(139,92,246,.16);}
        .industry-badge.badge-coral{--badge-color:#ef4444;--badge-glow:rgba(239,68,68,.16);}
        .industry-badge.badge-teal{--badge-color:#0ea5e9;--badge-glow:rgba(14,165,233,.16);}
        @keyframes badgeGlow{0%,100%{transform:scale(1);box-shadow:0 0 0 2px var(--badge-glow);}50%{transform:scale(1.1);box-shadow:0 0 0 6px var(--badge-glow);}}
        @media(max-width:900px){.industry-track{gap:14px;}.industry-card{width:280px;min-height:190px;}}
        @media(max-width:640px){.industry-marquee-section{padding:42px 0 32px;}.industry-track{gap:12px;}.industry-card{width:220px;min-height:170px;}.industry-badge{font-size:13px;padding:11px 13px;}}
        html,body{max-width:100%;overflow-x:hidden}.gallery{padding-bottom:96px}.site-footer{padding:64px 0 23px;color:#71809a;background:linear-gradient(112deg,#f5f6fe,#f7fbfa);border-top:1px solid var(--line)}.footer-columns{display:grid;grid-template-columns:1.65fr repeat(3,1fr);gap:34px}.footer-intro .brand{color:var(--ink)}.footer-intro p{max-width:290px;margin:17px 0 20px;font-size:14px;line-height:1.65}.footer-pills{display:flex;gap:9px}.footer-pills span{display:grid;place-items:center;width:37px;height:37px;border:1px solid var(--line);border-radius:50%;color:#536280;background:#fff;font-size:13px;font-weight:850}.footer-columns h4{margin:5px 0 15px;color:var(--ink);font-size:14px}.footer-columns a{display:block;width:max-content;max-width:100%;margin:0 0 14px;color:#71809a;font-size:14px}.footer-columns a:hover{color:var(--green)}.footer-bottom{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-top:48px;padding-top:24px;border-top:1px solid #dfe5ed;font-size:12px}.footer-status{display:inline-flex;align-items:center;gap:7px;padding:6px 10px;border-radius:999px;color:#27845f;background:#e0f7eb}.footer-status i{width:7px;height:7px;border-radius:50%;background:#48c98d}@media(max-width:850px){.footer-columns{grid-template-columns:1.5fr 1fr 1fr}.footer-columns>div:last-child{grid-column:2}.footer-intro{grid-row:span 2}}@media(max-width:540px){.site-footer{padding-top:46px}.footer-columns{grid-template-columns:1fr 1fr;gap:28px 22px}.footer-intro{grid-column:span 2;grid-row:auto}.footer-columns>div:last-child{grid-column:auto}.footer-bottom{align-items:flex-start;flex-direction:column;margin-top:34px}}
        .mobile-app{padding:0 0 100px}.mobile-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:56px;align-items:center}.mobile-copy h2{max-width:440px;margin:16px 0 14px;font-size:clamp(29px,3.6vw,43px);line-height:1.08;letter-spacing:-.05em}.mobile-copy p{max-width:440px;margin:0 0 24px;color:var(--muted);line-height:1.65}.mobile-points{display:grid;gap:13px;margin:0 0 28px;padding:0;list-style:none;color:var(--ink);font-size:14px;font-weight:650}.mobile-points li{display:flex;align-items:center;gap:11px}.mobile-points li:before{content:"";flex:0 0 auto;width:8px;height:8px;border-radius:50%;background:#5ebf89;box-shadow:0 0 0 4px #5ebf892b}.mobile-visual{position:relative;display:flex;justify-content:center;align-items:flex-end;height:360px}.mobile-shot{position:relative;z-index:1;width:auto;max-width:190px;max-height:340px;filter:drop-shadow(0 22px 30px #24365a30);animation:mobile-float 5.5s ease-in-out infinite}.mobile-orbit{position:absolute;inset:0;z-index:0;pointer-events:none;animation:mobile-orbit-spin 11s linear infinite}.mobile-orbit i{position:absolute;top:50%;left:50%;width:92px;height:92px;margin:-46px 0 0 -46px;border-radius:50%;filter:blur(13px);opacity:.6;animation:mobile-orbit-pulse 3.6s ease-in-out infinite}.mobile-orbit i:nth-child(1){background:#5ebf89;transform:translate(128px,0)}.mobile-orbit i:nth-child(2){background:#8d9fff;transform:translate(-64px,111px);animation-delay:-1.2s}.mobile-orbit i:nth-child(3){background:#ffb976;transform:translate(-64px,-111px);animation-delay:-2.4s}@keyframes mobile-orbit-spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}@keyframes mobile-orbit-pulse{0%,100%{opacity:.42}50%{opacity:.72}}.mobile-shadow{position:absolute;z-index:0;bottom:6px;left:50%;width:130px;height:22px;border-radius:50%;background:#1d2942;filter:blur(9px);opacity:.4;transform:translateX(-50%) scale(1);animation:mobile-shadow-pulse 5.5s ease-in-out infinite}.mobile-ping{position:absolute;z-index:2;left:50%;top:14px;display:flex;align-items:center;gap:8px;padding:8px 11px;border:1px solid #ffffffc7;border-radius:12px;color:#fff;background:#17284ee8;box-shadow:0 14px 26px #17213e38;backdrop-filter:blur(8px);font-size:11px;white-space:nowrap;animation:mobile-ping-float 5.5s ease-in-out -1.8s infinite}.mobile-ping i{width:8px;height:8px;border-radius:50%;background:#aef0d9;box-shadow:0 0 0 4px #aef0d930;flex:0 0 auto}.mobile-ping b{font-weight:800}@keyframes mobile-float{0%,100%{transform:translateY(0)}50%{transform:translateY(-11px)}}@keyframes mobile-shadow-pulse{0%,100%{transform:translateX(-50%) scale(1);opacity:.4}50%{transform:translateX(-50%) scale(.7);opacity:.2}}@keyframes mobile-ping-float{0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(-11px)}}@media(max-width:850px){.mobile-app{padding-bottom:70px}.mobile-grid{grid-template-columns:1fr;text-align:center;gap:36px}.mobile-copy h2,.mobile-copy p{margin-left:auto;margin-right:auto}.mobile-points{justify-items:center}.mobile-points li{justify-content:center}.mobile-visual{order:-1;height:300px}}@media(prefers-reduced-motion:reduce){.mobile-shot,.mobile-ping,.mobile-shadow,.mobile-orbit,.mobile-orbit i{animation:none!important}}
    </style>
    <link rel="stylesheet" href="{{ asset('css/marketing-pricing.css') }}" />
</head>
<body>
    <header class="top">
        <nav class="shell nav" aria-label="Primary navigation">
            <a
                class="brand"
                href="{{ url('/') }}"
                aria-label="{{ config('app.name', 'CraftSalesPOS') }} home"
            >
                @if (file_exists(public_path('uploads/logo.svg')))
                    <img
                        src="/uploads/logo.svg"
                        alt="{{ config('app.name', 'CraftSalesPOS') }}"
                        class="brand-logo"
                    />
                @else
                    <span class="mark"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 7h16M7 3v8m10-8v8M6 21h12a2 2 0 0 0 2-2V7H4v12a2 2 0 0 0 2 2Z" />
                            <path d="M8 15h3m2 0h3" />
                        </svg
                    ></span>
                @endif
                {{ config('app.name', 'CraftSalesPOS') }}</a
            >
            <div class="navlinks">
                <a href="{{ route('marketing.industries') }}">Industries</a
                ><a href="{{ route('marketing.features') }}">Features</a
                ><a href="{{ route('marketing.pricing') }}">Pricing</a
                ><a href="{{ route('marketing.about') }}">About</a
                ><a href="{{ route('marketing.updates') }}">Updates</a
                ><a href="{{ route('marketing.contact') }}">Contact</a>
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
            <button class="nav-toggle" aria-expanded="false" aria-label="Open navigation">
                <span></span>
            </button>
            <div class="mobile-menu" id="mobile-nav" aria-hidden="true">
                <div class="navlinks">
                    <a href="{{ route('marketing.industries') }}">Industries</a
                    ><a href="{{ route('marketing.features') }}">Features</a
                    ><a href="{{ route('marketing.pricing') }}">Pricing</a
                    ><a href="{{ route('marketing.about') }}">About</a
                    ><a href="{{ route('marketing.updates') }}">Updates</a
                    ><a href="{{ route('marketing.contact') }}">Contact</a>
                </div>
                <div class="nav-actions">
                    <a class="signin" href="{{ route('login') }}">Sign in</a
                    ><a class="button primary" href="{{ route('business.getRegister') }}"
                        >Get started</a
                    >
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="hero">
            <div class="shell hero-grid">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Sell more and worry less <span class="accent">with our POS.</span>
                    </h1>
                    <p class="hero-lede">A calm, capable workspace for the real work of running a business, selling, stocking, serving and seeing what comes next.</p>
                    <div class="hero-actions">
                        <a class="btn-green" href="{{ route('business.getRegister') }}"
                            >Build your workspace &rarr;</a
                        >
                        <a class="btn-outline" href="{{ route('login') }}">Open your POS</a>
                    </div>
                </div>
                <div class="hero-visual-wrapper" aria-label="CraftSalesPOS on a laptop">
                    <div class="hero-bg-arc" aria-hidden="true"></div>
                    <div class="laptop-container">
                        <img
                            class="laptop-img"
                            src="{{ asset('images/landing/laptop.webp') }}"
                            alt="CraftSalesPOS running on a laptop"
                            fetchpriority="high"
                            decoding="async"
                        />
                    </div>
                    <div class="badges-container">
                        <svg class="badges-arc-svg" viewBox="0 0 240 460" fill="none" aria-hidden="true">
                            <defs>
                                <linearGradient id="brightRingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#00ff87" />
                                    <stop offset="25%" stop-color="#00dfa2" />
                                    <stop offset="50%" stop-color="#ff007f" />
                                    <stop offset="75%" stop-color="#ffbd59" />
                                    <stop offset="100%" stop-color="#7f00ff" />
                                    <animate attributeName="x1" values="0%;100%;0%" dur="8s" repeatCount="indefinite" />
                                    <animate attributeName="y1" values="0%;100%;0%" dur="8s" repeatCount="indefinite" />
                                    <animate attributeName="x2" values="100%;0%;100%" dur="8s" repeatCount="indefinite" />
                                    <animate attributeName="y2" values="100%;0%;100%" dur="8s" repeatCount="indefinite" />
                                </linearGradient>
                            </defs>
                            <path class="animated-vibrant-ring" d="M 10 10 C 230 110, 230 350, 10 450" stroke="url(#brightRingGrad)" stroke-width="7" fill="none" />
                        </svg>
                        <div class="feature-badge badge-1">
                            <div class="badge-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" /></svg>
                            </div>
                            <div class="badge-text">
                                <h4>Smart Sales</h4>
                                <p>Fast billing, discounts and receipts.</p>
                            </div>
                        </div>
                        <div class="feature-badge badge-2">
                            <div class="badge-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                            <div class="badge-text">
                                <h4>Inventory Control</h4>
                                <p>Track stock in real-time and avoid stockouts.</p>
                            </div>
                        </div>
                        <div class="feature-badge badge-3">
                            <div class="badge-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <div class="badge-text">
                                <h4>Customer Management</h4>
                                <p>Build stronger relationships and grow loyalty.</p>
                            </div>
                        </div>
                        <div class="feature-badge badge-4">
                            <div class="badge-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18" />
                                    <path d="m19 9-5 5-4-4-3 3" />
                                </svg>
                            </div>
                            <div class="badge-text">
                                <h4>Powerful Reports</h4>
                                <p>See insights that help you make better decisions.</p>
                            </div>
                        </div>
                        <div class="feature-badge badge-5">
                            <div class="badge-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div class="badge-text">
                                <h4>Secure &amp; Compliant</h4>
                                <p>Role-based access, audit logs &amp; data backup.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="industry-marquee-section shell" data-reveal>
            <div class="section-head">
                <h2>Businesses across industries rely on CraftSalesPOS</h2>
                <p>From busy pharmacies to fashion boutiques, this section shows the everyday operations that our platform helps keep in flow.</p>
            </div>
            <div
                class="industry-marquee"
                role="region"
                aria-label="Industries served by CraftSalesPOS"
            >
                <div class="industry-track">
                    <article class="industry-card">
                        <img
                            src="{{ asset('images/landing/retail-store-pos-embedded.webp') }}"
                            alt="Supermarkets using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="industry-badge badge-blue"><i></i> Supermarkets</span>
                    </article>
                    <article class="industry-card">
                        <img
                            src="{{ asset('images/landing/african-pharmacies.webp') }}"
                            alt="Pharmacies using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="industry-badge badge-green"><i></i> Pharmacies</span>
                    </article>
                    <article class="industry-card">
                        <img
                            src="{{ asset('images/landing/barbershop-interior-embedded.webp') }}"
                            alt="Salons and barbershops using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="industry-badge badge-gold"
                            ><i></i> Salons &amp; Barbershops</span
                        >
                    </article>
                    <article class="industry-card">
                        <img
                            src="{{ asset('images/landing/african-laptop.webp') }}"
                            alt="Agrovet stores using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="industry-badge badge-purple"><i></i> Agrovet Stores</span>
                    </article>
                    <article class="industry-card">
                        <img
                            src="{{ asset('images/landing/african-retail.webp') }}"
                            alt="Hardware and building supplies stores using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="industry-badge badge-coral"
                            ><i></i> Hardware &amp; Building Supplies</span
                        >
                    </article>
                    <article class="industry-card">
                        <img
                            src="{{ asset('images/landing/clothing-store-display-embedded.webp') }}"
                            alt="Fashion boutiques using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="industry-badge badge-teal"><i></i> Fashion Boutiques</span>
                    </article>
                    <article class="industry-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/retail-store-pos-embedded.webp') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="industry-badge badge-blue"><i></i> Supermarkets</span>
                    </article>
                    <article class="industry-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-pharmacies.webp') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="industry-badge badge-green"><i></i> Pharmacies</span>
                    </article>
                    <article class="industry-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/barbershop-interior-embedded.webp') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="industry-badge badge-gold"
                            ><i></i> Salons &amp; Barbershops</span
                        >
                    </article>
                    <article class="industry-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-laptop.webp') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="industry-badge badge-purple"><i></i> Agrovet Stores</span>
                    </article>
                    <article class="industry-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-retail.webp') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="industry-badge badge-coral"
                            ><i></i> Hardware &amp; Building Supplies</span
                        >
                    </article>
                    <article class="industry-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/clothing-store-display-embedded.webp') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="industry-badge badge-teal"><i></i> Fashion Boutiques</span>
                    </article>
                </div>
            </div>
        </section>
        <section class="assurance">
            <div class="shell">
                <span><i></i> Fast checkout</span><span><i></i> Stock confidence</span
                ><span><i></i> Clear reporting</span><span><i></i> Ready to grow</span>
            </div>
        </section>
        <section class="shell solutions" id="solutions">
            <div class="heading">
                <h2>Tailored to how your business actually runs.</h2>
                <p>Pick the capabilities you need now, then let CraftSalesPOS scale with you as you grow.</p>
            </div>
            <div class="bento">
                <article class="card retail photo-card" data-reveal>
                    <span class="card-photo"
                        ><img
                            src="{{ asset('images/landing/retail.webp') }}"
                            alt="Retail workspace"
                            loading="lazy" /><span class="wash"></span></span
                    ><span class="icon"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m4 7 8-4 8 4-8 4-8-4Z" />
                            <path d="m4 12 8 4 8-4M4 17l8 4 8-4" />
                        </svg
                    ></span>
                    <h3>Seamless Store Operations</h3>
                    <p>Keep transactions moving while stock levels, product updates, and buyer history update in real time.</p>
                </article>
                <article class="card restaurant photo-card" data-reveal>
                    <span class="card-photo"
                        ><img
                            src="{{ asset('images/landing/restaurant.webp') }}"
                            alt="Busy restaurant service"
                            loading="lazy" /><span class="wash"></span></span
                    ><span class="icon"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 11h18M5 11V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 11v8h14v-8" />
                            <path d="M9 15h6" />
                        </svg
                    ></span>
                    <h3>Continuous Service Flow</h3>
                    <p>Unify orders, floor tables, and payments into one seamless, fast-paced workflow.</p>
                </article>
                <article class="card service photo-card" data-reveal>
                    <span class="card-photo"
                        ><img
                            src="{{ asset('images/landing/service.webp') }}"
                            alt="Service business owner"
                            loading="lazy" /><span class="wash"></span></span
                    ><span class="icon"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 20V4h16v16H4Z" />
                            <path d="M8 8h8M8 12h5M8 16h3" />
                        </svg
                    ></span>
                    <h3>Client Management Made Easy</h3>
                    <p>Handle appointments, invoices, and customer communications in one clean workspace.</p>
                </article>
                <article class="card enterprise" id="growth">
                    <h3>One view for every location, team and next decision.</h3>
                    <p>See the whole picture without slowing down the people doing the work. CraftSalesPOS gives growing businesses a clearer operating rhythm.</p>
                    <a class="text-link" href="{{ route('business.getRegister') }}"
                        >Set up your business <span aria-hidden="true">&rarr;</span></a
                    >
                    <div class="location-stats">
                        <span><small>Locations</small><b>03 active</b></span
                        ><span><small>Today</small><b>KSh 24.6k</b></span
                        ><span><small>Team</small><b>12 members</b></span>
                    </div>
                </article>
            </div>
        </section>
        <section class="shell workflow" id="features">
            <div class="workflow-head">
                <h2>Every handoff, clearer.</h2>
                <p>One connected rhythm for the counter, back office and the decisions that follow a busy day.</p>
            </div>
            <div class="workflow-grid">
                <article class="workflow-card focus">
                    <span class="workflow-badge"><i></i> Live workspace</span>
                    <h3>See the day take shape in real time.</h3>
                    <p>Sales, payments and stock signals stay visible without making the screen feel busy.</p>
                    <svg viewBox="0 0 360 210" fill="none" aria-hidden="true">
                        <rect x="43" y="23" width="286" height="164" rx="18" fill="#fff" fill-opacity=".12" stroke="#fff" stroke-opacity=".22" />
                        <rect x="62" y="44" width="76" height="9" rx="4.5" fill="#d9f0e4" />
                        <rect x="62" y="70" width="116" height="67" rx="11" fill="#fff" fill-opacity=".14" />
                        <rect x="194" y="70" width="116" height="67" rx="11" fill="#fff" fill-opacity=".14" />
                        <path d="M70 122c18-2 19-28 39-17 14 8 17 1 31-20" stroke="#aef0d1" stroke-width="5" stroke-linecap="round" />
                        <path d="M202 120c17-6 23-33 39-22 14 10 21-6 53-21" stroke="#ffe189" stroke-width="5" stroke-linecap="round" />
                        <rect x="62" y="151" width="248" height="17" rx="8.5" fill="#fff" fill-opacity=".14" />
                    </svg>
                </article>
                <article class="workflow-card">
                    <span class="workflow-badge"><i></i> Stock signal</span>
                    <h3>Know what needs attention.</h3>
                    <p>Surface the details that help you replenish, prioritise and keep serving.</p>
                    <svg viewBox="0 0 270 190" fill="none" aria-hidden="true">
                        <rect x="39" y="29" width="174" height="121" rx="18" fill="#eef8f0" stroke="#d8e8de" />
                        <path d="m67 83 59-30 58 30-58 30-59-30Z" fill="#1d5f3d33" fill-opacity=".18" stroke="#1d5f3d" stroke-width="3" />
                        <path d="m67 104 59 30 58-30M126 113v21" stroke="#1d5f3d" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="202" cy="53" r="22" fill="#fff0c6" />
                        <path d="M202 42v13m0 8h.01" stroke="#a96d00" stroke-width="4" stroke-linecap="round" />
                    </svg>
                </article>
                <article class="workflow-card">
                    <span class="workflow-badge"><i></i> Customer care</span>
                    <h3>Make every return visit feel familiar.</h3>
                    <p>Keep the context that lets teams give better service at every interaction.</p>
                    <svg viewBox="0 0 270 190" fill="none" aria-hidden="true">
                        <circle cx="135" cy="93" r="64" fill="#ddf6eb" />
                        <circle cx="135" cy="75" r="25" fill="#fff" stroke="#47b987" stroke-width="3" />
                        <path d="M86 143c7-28 28-42 49-42s42 14 49 42" fill="#fff" stroke="#47b987" stroke-width="3" stroke-linecap="round" />
                        <path d="M194 57h24M194 69h16M194 81h20" stroke="#47b987" stroke-width="4" stroke-linecap="round" />
                    </svg>
                </article>
            </div>
        </section>
        <section class="shell mobile-app" data-reveal>
            <div class="mobile-grid">
                <div class="mobile-copy">
                    <span class="eyebrow"><i></i> On the go</span>
                    <h2>Run your business from your pocket.</h2>
                    <p>Check sales, approve requests and keep an eye on stock wherever you are, with the same clarity as your front desk.</p>
                    <ul class="mobile-points">
                        <li>Live sales and payment alerts</li>
                        <li>Owner approvals and stock notifications</li>
                        <li>Full reporting, condensed for mobile</li>
                    </ul>
                    <a class="button primary" href="{{ route('business.getRegister') }}"
                        >Get started <span aria-hidden="true">&rarr;</span></a
                    >
                </div>
                <div class="mobile-visual" aria-label="CraftSalesPOS mobile dashboard">
                    <div class="mobile-orbit" aria-hidden="true"><i></i><i></i><i></i></div>
                    <div class="mobile-shadow" aria-hidden="true"></div>
                    <img
                        class="mobile-shot"
                        src="{{ asset('images/landing/phone_dashboard.webp') }}"
                        alt="CraftSalesPOS dashboard on a phone"
                        loading="lazy"
                    />
                    <div class="mobile-ping">
                        <i></i><span><b>New sale</b> · KSh 1,450</span>
                    </div>
                </div>
            </div>
        </section>
        <section class="gallery" data-reveal>
            <div class="shell gallery-head">
                <h2>Built for every business, from startup to enterprise.</h2>
                <p>CraftSalesPOS adapts to the way you do business, whether you're running a single outlet or managing multiple locations.</p>
            </div>
        </section>
        <section class="shell quote" data-reveal>
            <div class="quote-box">
                <div class="avatar">C</div>
                <div>
                    <blockquote>
                        &ldquo;The day feels less frantic when your sales, stock and team are all
                        looking at the same clear picture.&rdquo;
                    </blockquote>
                    <cite>Built for the business owners who make a lot happen.</cite>
                </div>
            </div>
        </section>
        <section class="shell how">
            <div class="how-grid">
                <article class="process">
                    <h2>Set up once. Run every day with more clarity.</h2>
                    <div class="steps">
                        <div class="step">
                            <span class="step-number">01</span>
                            <div>
                                <b>Shape your workspace</b>
                                <p>Add the products, people and locations that make your business yours.</p>
                            </div>
                        </div>
                        <div class="step">
                            <span class="step-number">02</span>
                            <div>
                                <b>Keep the counter moving</b>
                                <p>Turn sales and service into a smooth experience for your team and customers.</p>
                            </div>
                        </div>
                        <div class="step">
                            <span class="step-number">03</span>
                            <div>
                                <b>See the next smart move</b>
                                <p>Use live numbers to spot what is working and act before small gaps become big ones.</p>
                            </div>
                        </div>
                    </div>
                </article>
                <div class="outcomes">
                    <article class="outcome">
                        <h3>Less admin. More attention where it counts.</h3>
                        <p>Give your team shared context without adding more spreadsheets or back-and-forth.</p>
                    </article>
                    <article class="outcome">
                        <h3>Confidence at every close of day.</h3>
                        <p>Your sales, payments and stock story are ready when you need them.</p>
                    </article>
                </div>
            </div>
        </section>
        <section class="numbers-strip">
            <div class="shell inner">
                <div class="fact">
                    <b>1 workspace</b><span>for your sales, stock and service</span>
                </div>
                <div class="fact">
                    <b>Real-time</b><span>visibility when the day gets busy</span>
                </div>
                <div class="fact">
                    <b>Multi-location</b><span>control as your business expands</span>
                </div> 
                <div class="fact">
                    <b>One clear view</b><span>for every meaningful next move</span>
                </div>
            </div>
        </section>
        <section class="shell trusted">
            <p>A polished foundation for every kind of business</p>
            <div class="brand-row">
                <span>Social Proof & Trust</span><span>Versatility & Industry Focus</span><span>Outcome & Performance Focused</span
                ><span>Direct & Concise</span><span>Action & Community Driven</span>
            </div>
        </section>
        <section class="pricing" id="pricing" style="margin-bottom: 80px">
            <div class="shell">
                <div class="pricing-head">
                    <h2>Pricing that reflects your setup.</h2>
                    <p>Choose from the plans currently available for your business. You can select a package while creating your workspace.</p>
                </div>
                <div class="plans">
                    @forelse ($landingPackages as $package)
                        <article
                            class="plan {{ $package->mark_package_as_popular ? 'highlight' : '' }}"
                        >
                            <span class="plan-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z" />
                                </svg>
                            </span>
                            @if ($package->mark_package_as_popular)
                                <span class="plan-badge">Most chosen</span>
                            @endif

                            <div class="plan-head">
                                <h3>{{ $package->name }}</h3>
                                <p>{{ $package->description ?: 'A practical package for a more connected business day.' }}</p>
                            </div>

                            <div class="price">
                                @if ((float) $package->price === 0)
                                    Free
                                    <small
                                        >for {{ $package->interval_count }} {{ Str::singular($package->interval) }}</small
                                    >
                                @else
                                    {{ number_format((float) $package->price, 2) }}
                                    <small
                                        >per {{ $package->interval_count }} {{ Str::singular($package->interval) }}</small
                                    >
                                @endif
                            </div>
                            <ul>
                                <li>{{ $package->location_count ?: 'Unlimited' }} locations</li>
                                <li>{{ $package->user_count ?: 'Unlimited' }} users</li>
                                <li>{{ $package->product_count ?: 'Unlimited' }} products</li>
                                <li>{{ $package->invoice_count ?: 'Unlimited' }} invoices</li>
                                @if ($package->trial_days)
                                    <li>{{ $package->trial_days }} trial days</li>
                                @endif
                            </ul>
                            @if ($package->enable_custom_link)
                                <a
                                    class="button {{ $package->mark_package_as_popular ? '' : 'secondary' }}"
                                    href="{{ $package->custom_link }}"
                                >
                                    <span
                                        class="button-copy"
                                        >{{ $package->custom_link_text }}</span
                                    >
                                    <span class="button-icon">→</span>
                                </a>
                            @else
                                <a
                                    class="button {{ $package->mark_package_as_popular ? '' : 'secondary' }}"
                                    href="{{ route('business.getRegister', ['package' => $package->id]) }}"
                                >
                                    <span
                                        class="button-copy"
                                        >{{ (float) $package->price === 0 ? 'Choose free plan' : 'Choose this plan' }}</span
                                    >
                                    <span class="button-icon">→</span>
                                </a>
                            @endif
                        </article>
                    @empty
                        <article class="plan highlight" style="grid-column: 1/-1">
                            <div class="plan-head">
                                <h3>Plans are being prepared</h3>
                                <p>Your administrator has not published a package yet. You can still create a workspace and choose the right setup with your team.</p>
                            </div>
                            <a class="button secondary" href="{{ route('business.getRegister') }}"
                                ><span class="button-copy">Create a workspace</span>
                                <span class="button-icon">→</span></a
                            >
                        </article>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
    @include ('layouts.partials.footer')
    <script>
        const header = document.querySelector('.top');
        const updateHeader = () => header.classList.toggle('scrolled', window.scrollY > 8);
        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
        const navToggle = document.querySelector('.nav-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        if (navToggle && mobileNav) {
            navToggle.addEventListener('click', () => {
                const open = mobileNav.classList.toggle('open');
                mobileNav.setAttribute('aria-hidden', String(!open));
                navToggle.setAttribute('aria-expanded', String(open));
            });
            document.addEventListener('click', (event) => {
                if (!mobileNav.contains(event.target) && !navToggle.contains(event.target)) {
                    mobileNav.classList.remove('open');
                    navToggle.setAttribute('aria-expanded', 'false');
                    mobileNav.setAttribute('aria-hidden', 'true');
                }
            });
        }
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const observer = new IntersectionObserver(
                (entries) =>
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in');
                            observer.unobserve(entry.target);
                        }
                    }),
                { threshold: 0.12 }
            );
            document
                .querySelectorAll('[data-reveal]')
                .forEach((element) => observer.observe(element));
        } else {
            document
                .querySelectorAll('[data-reveal]')
                .forEach((element) => element.classList.add('in'));
        }
    </script>
</body>
</html>
