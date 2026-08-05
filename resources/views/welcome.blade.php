<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'CraftSalesPOS') }} | Run the day beautifully</title>
    <style>
        :root { --navy:#111c35; --ink:#1d2942; --muted:#667189; --paper:#f7f8fc; --line:#e1e5ee; --green:#1d5f3d; --mint:#baf0d9; --coral:#ffb9a7; --yellow:#ffe189; }
        * { box-sizing:border-box; } html { scroll-behavior:smooth; } body { margin:0; color:var(--ink); background:var(--paper); font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; } a { color:inherit; text-decoration:none; }
        .shell { width:min(1160px,calc(100% - 40px)); margin:auto; } .top { background:#fff; border-bottom:1px solid #edf0f5; } .nav { min-height:76px; display:flex; align-items:center; justify-content:space-between; gap:22px; }
        .brand { display:inline-flex; gap:10px; align-items:center; font-size:19px; font-weight:850; letter-spacing:-.04em; } .mark { width:35px; height:35px; display:grid; place-items:center; color:#fff; border-radius:11px; background:linear-gradient(135deg,#1d5f3d,var(--mint)); box-shadow:0 10px 23px #1d5f3d33; } .mark svg { width:20px; }
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
        .pricing { padding:96px 0; background:#fff; border-top:1px solid var(--line); }.pricing-head { text-align:center; margin:0 auto 34px; }.pricing-head h2 { margin:0 0 11px; font-size:clamp(31px,4vw,46px); letter-spacing:-.055em; }.pricing-head p { max-width:540px; margin:auto; color:var(--muted); line-height:1.6; }.plans { display:grid; grid-template-columns:repeat(2,1fr); max-width:810px; margin:auto; gap:18px; }.plan { display:flex; flex-direction:column; min-height:396px; padding:30px; border:1px solid var(--line); border-radius:21px; background:var(--paper); }.plan.featured { color:#fff; border-color:var(--navy); background:var(--navy); box-shadow:0 23px 44px #17213d24; }.plan h3 { margin:0 0 8px; font-size:23px; letter-spacing:-.045em; }.plan > p { min-height:47px; margin:0 0 22px; color:var(--muted); font-size:14px; line-height:1.55; }.plan.featured > p { color:#c4cee4; }.price { margin-bottom:24px; font-size:39px; font-weight:850; letter-spacing:-.06em; }.price small { color:var(--muted); font-size:13px; font-weight:650; letter-spacing:0; }.featured .price small { color:#b9c7e5; }.plan ul { display:grid; gap:11px; margin:0 0 25px; padding:20px 0 0; border-top:1px solid var(--line); list-style:none; color:var(--muted); font-size:13px; line-height:1.4; }.featured ul { border-color:#ffffff22; color:#d4ddf1; }.plan li:before { content:"+"; display:inline-block; width:20px; color:#278969; font-size:15px; font-weight:900; }.featured li:before { color:var(--mint); }.plan .button { margin-top:auto; }.featured .button { color:var(--navy); background:var(--mint); }
        .footer-cta { padding:61px 0 30px; color:#e7ecf8; background:#0f1930; }.footer-grid { display:grid; grid-template-columns:1.45fr 1fr 1fr; gap:30px; }.footer-grid .brand { color:#fff; }.footer-grid p { max-width:315px; color:#aeb8ce; font-size:14px; line-height:1.6; }.footer-grid h4 { margin:5px 0 13px; color:#fff; font-size:13px; }.footer-grid a { display:block; margin:9px 0; color:#aeb8ce; font-size:13px; }.footer-grid a:hover { color:var(--mint); }.legal { display:flex; justify-content:space-between; gap:15px; margin-top:44px; padding-top:18px; color:#8895b2; border-top:1px solid #ffffff17; font-size:12px; }
        @media(max-width:850px) { .navlinks { display:none; }.nav-actions { display:none; }.nav-toggle { display:grid; }.hero { padding-top:52px; }.hero-grid,.bento,.how-grid { grid-template-columns:1fr; }.hero-visual { min-height:365px; }.report { left:0; transform:none; }.receipt { left:9px; }.heading { align-items:start; flex-direction:column; }.enterprise { grid-column:auto; }.location-stats { right:25px; bottom:23px; }.footer-grid { grid-template-columns:1.3fr 1fr; }.footer-grid > :first-child { grid-column:span 2; } .numbers-strip .inner { grid-template-columns:1fr 1fr; }.fact:nth-child(2) { border-right:0; }.fact:nth-child(-n+2) { border-bottom:1px solid var(--line); } .brand-row { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:540px) { .shell { width:min(100% - 28px,1160px); }.nav { min-height:65px; }.signin,.language { display:none; }.brand { font-size:17px; }.button { padding:11px 14px; }.hero { padding-bottom:55px; }.lede { font-size:16px; }.hero-visual { min-height:326px; }.report { padding:13px; }.panel { grid-template-columns:72px 1fr; gap:10px; }.side { gap:8px; }.assurance div { flex-wrap:wrap; }.assurance span { width:45%; }.solutions { padding:69px 0 62px; }.enterprise { padding:28px 24px 145px; }.location-stats { left:24px; right:auto; grid-template-columns:repeat(3,1fr); }.location-stats span { padding:10px; }.quote { padding-bottom:61px; }.quote-box { grid-template-columns:1fr; gap:13px; padding:28px; }.how { padding-bottom:62px; }.process,.outcome { padding:26px; }.footer-grid { grid-template-columns:1fr 1fr; }.footer-grid > :first-child { grid-column:span 2; }.legal { flex-direction:column; }.numbers-strip { padding-bottom:65px; }.numbers-strip .inner { grid-template-columns:1fr; }.fact,.fact:nth-child(2) { border-right:0; border-bottom:1px solid var(--line); }.fact:last-child { border-bottom:0; }.trusted { padding-bottom:66px; }.brand-row { grid-template-columns:1fr 1fr; }.pricing { padding:68px 0; }.plans { grid-template-columns:1fr; }.plan { min-height:0; } }
        .card-photo{position:absolute;inset:0;z-index:0}.card-photo img{width:100%;height:100%;object-fit:cover;transform:scale(1.03);transition:transform .55s ease}.card-photo .wash{position:absolute;inset:0}.retail .wash{background:linear-gradient(195deg,#0c3121f2 8%,#1d5c3cc5 48%,#2f7f5d66)}.restaurant .wash{background:linear-gradient(195deg,#221607f2 8%,#5a3a10c7 48%,#8a5a1858)}.service .wash{background:linear-gradient(195deg,#04241af2 8%,#0e4a34c7 48%,#1a6f4f58)}.photo-card{color:#fff}.photo-card p{color:#d7e7d4}.photo-card .icon{position:relative;z-index:1;color:#fff;background:#ffffff27;backdrop-filter:blur(5px)}.card:hover .card-photo img{transform:scale(1.1)}.workflow { padding:0 0 96px; }.workflow-head { max-width:650px; margin:0 auto 32px; text-align:center; }.workflow-head h2 { margin:0 0 12px; font-size:clamp(31px,4vw,47px); line-height:1.05; letter-spacing:-.055em; }.workflow-head p { margin:0; color:var(--muted); line-height:1.65; }.workflow-grid { display:grid; grid-template-columns:1.15fr .85fr .85fr; gap:17px; }.workflow-card { min-height:345px; position:relative; overflow:hidden; padding:25px; border:1px solid var(--line); border-radius:21px; background:#fff; }.workflow-card h3 { position:relative; z-index:1; margin:15px 0 7px; font-size:21px; letter-spacing:-.045em; }.workflow-card p { position:relative; z-index:1; max-width:285px; margin:0; color:var(--muted); font-size:14px; line-height:1.6; }.workflow-card svg { position:absolute; width:100%; max-width:230px; height:118px; right:12px; bottom:14px; }.workflow-card.focus { color:#fff; border:0; background:linear-gradient(145deg,#1d5f3d,#5ebf89); }.workflow-card.focus p { color:#e6f4e9; }.workflow-card.focus svg { max-width:290px; height:130px; right:-6px; bottom:12px; }.workflow-badge { position:relative; z-index:1; display:inline-flex; align-items:center; gap:7px; padding:6px 9px; border-radius:999px; color:#1f6a47; background:#e3f9eb; font-size:11px; font-weight:850; }.focus .workflow-badge {  color:#dce5ff; background:#ffffff19; }.workflow-badge i { width:6px; height:6px; border-radius:50%; background:#4eca8b; }.workflow-card:hover svg { transform:translateY(-4px); transition:transform .25s ease; }.gallery{padding:0 0 92px}.gallery-head{max-width:650px;margin:0 auto 31px;text-align:center}.gallery-head h2{margin:0 0 11px;font-size:clamp(31px,4vw,47px);letter-spacing:-.055em}.gallery-head p{margin:0;color:var(--muted);line-height:1.65}.gallery-grid{display:grid;grid-template-columns:1.25fr 1fr 1fr;gap:17px}.gallery-item{position:relative;min-height:250px;overflow:hidden;border-radius:21px;background:#dbe3f5}.gallery-item.tall{grid-row:span 2;min-height:517px}.gallery-item img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:scale(1.03);transition:transform .55s ease}.gallery-item:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#0b1225a8 0%,transparent 47%)}.gallery-item:hover img{transform:scale(1.1)}.gallery-tag{position:absolute;z-index:1;left:16px;bottom:16px;display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border-radius:999px;color:var(--ink);background:#ffffffec;font-size:12px;font-weight:850}.gallery-tag i{width:7px;height:7px;border-radius:50%;background:#4eca8b}.top{position:sticky;top:0;z-index:40;transition:box-shadow .25s ease,border-color .25s ease}.top.scrolled{box-shadow:0 8px 24px #17213d14;border-color:transparent}[data-reveal]{opacity:0;transform:translateY(20px);transition:opacity .65s cubic-bezier(.2,.7,.2,1),transform .65s cubic-bezier(.2,.7,.2,1)}[data-reveal].in{opacity:1;transform:none}@media(max-width:850px){.workflow-grid{grid-template-columns:1fr 1fr}.workflow-card.focus{grid-column:span 2}.gallery-grid{grid-template-columns:1fr 1fr}.gallery-item.tall{grid-row:span 1;min-height:250px}}@media(max-width:540px){.workflow{padding-bottom:64px}.workflow-grid,.gallery-grid{grid-template-columns:1fr}.workflow-card.focus{grid-column:auto}.workflow-card{min-height:325px}.workflow-card.focus svg{max-width:270px}.gallery{padding-bottom:64px}.gallery-item,.gallery-item.tall{min-height:240px}}@media(prefers-reduced-motion:reduce){*,*:before,*:after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}}
        .hero{padding-top:74px}.hero:after{content:"";position:absolute;inset:0;pointer-events:none;background:radial-gradient(circle at 82% 22%,#cde7d888 0,transparent 23%),radial-gradient(circle at 11% 42%,#d6f3de88 0,transparent 21%)}.hero-grid{position:relative}.hero-visual{display:grid;place-items:center;min-height:430px}.hero-visual .report{display:none}.hero-laptop{position:relative;z-index:1;display:block;width:min(100%,560px);max-height:420px;object-fit:contain;filter:drop-shadow(0 30px 35px #24365a33);animation:hero-float 7s ease-in-out infinite}.hero-status{position:absolute;z-index:2;right:2%;bottom:18px;display:flex;align-items:center;gap:10px;padding:12px 15px;border:1px solid #ffffffc7;border-radius:15px;color:#fff;background:#17284ee8;box-shadow:0 18px 34px #17213e38;backdrop-filter:blur(8px);animation:hero-float 6s ease-in-out -1.5s infinite}.hero-status b{display:block;font-size:13px}.hero-status small{display:block;margin-bottom:3px;color:#c9d6f0;font-size:10px}.hero-status i{width:10px;height:10px;border-radius:50%;background:#aef0d9;box-shadow:0 0 0 5px #aef0d930}@keyframes hero-float{0%,100%{transform:translateY(0)}50%{transform:translateY(-13px)}}.ticker-wrap{position:relative;overflow:hidden;background:var(--navy)}.ticker-wrap:before,.ticker-wrap:after{content:"";position:absolute;top:0;bottom:0;width:75px;z-index:2;pointer-events:none}.ticker-wrap:before{left:0;background:linear-gradient(90deg,var(--navy),transparent)}.ticker-wrap:after{right:0;background:linear-gradient(-90deg,var(--navy),transparent)}.ticker{display:flex;width:max-content;animation:ticker-scroll 32s linear infinite}.ticker-wrap:hover .ticker{animation-play-state:paused}.ticker-item{display:flex;align-items:center;gap:9px;padding:13px 30px;color:#dbe2f7;font-size:12.5px;font-weight:650;white-space:nowrap;border-right:1px solid #ffffff14}.ticker-item i{width:7px;height:7px;border-radius:50%;flex:0 0 auto}.dot-sale{background:#6bd4a1;box-shadow:0 0 0 3px #6bd4a12b}.dot-stock{background:#ffb976;box-shadow:0 0 0 3px #ffb9762b}.dot-pay{background:#8d9fff;box-shadow:0 0 0 3px #8d9fff2b}@keyframes ticker-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}@media(max-width:850px){.hero-visual{min-height:360px}.hero-laptop{max-height:340px}.hero-status{right:6%;bottom:4px}}@media(max-width:540px){.hero-visual{min-height:280px}.hero-laptop{max-height:275px}.hero-status{padding:9px 11px;bottom:-2px}.ticker-item{padding:12px 20px}}@media(prefers-reduced-motion:reduce){.hero-laptop,.hero-status,.ticker{animation:none!important}}
        html,body{max-width:100%;overflow-x:hidden}.gallery{padding-bottom:96px}.business-marquee{position:relative;width:100%;max-width:100vw;overflow:hidden;padding-inline:max(20px,calc((100vw - 1160px)/2));padding-block:22px}.business-marquee:before,.business-marquee:after{content:"";position:absolute;z-index:2;top:0;bottom:0;width:72px;pointer-events:none}.business-marquee:before{left:0;background:linear-gradient(90deg,var(--paper),transparent)}.business-marquee:after{right:0;background:linear-gradient(-90deg,var(--paper),transparent)}.business-track{display:flex;align-items:center;gap:17px;width:max-content;animation:business-scroll 54s linear infinite}.business-marquee:hover .business-track{animation-play-state:paused}.business-track .gallery-item{position:relative;width:300px;height:240px;min-height:240px;flex:0 0 300px;border-radius:24px;overflow:hidden;border:1px solid #e7edf7;background:#fff;box-shadow:0 14px 40px rgba(17,28,53,.09);transition:transform .28s ease,box-shadow .28s ease,border-color .28s ease,opacity .28s ease;display:grid;place-items:center}.business-track .gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}.business-track .gallery-item .gallery-tag{position:absolute;left:16px;right:16px;bottom:16px;display:inline-flex;align-items:center;gap:10px;padding:13px 16px;border-radius:18px;background:rgba(17,28,53,.88);color:#fff;font-size:14px;font-weight:700;backdrop-filter:blur(10px);box-shadow:0 18px 38px rgba(17,28,53,.22)}.business-track .gallery-item .gallery-tag i{width:8px;height:8px;border-radius:999px;background:#6bd4a1;box-shadow:0 0 0 4px rgba(107,212,161,.25)}.business-track .gallery-item{transform:scale(0.92)}.business-track .gallery-item.active{transform:scale(1.18);border-color:rgba(109,212,161,.35);box-shadow:0 32px 72px rgba(17,28,53,.24);z-index:2}.business-track .gallery-item.active img{transform:scale(1.18)}.business-track .gallery-item.inactive{opacity:.92}:.88}.business-track .gallery-item.tall{min-height:240px}.business-track .gallery-item img{object-position:center 53%}.business-track .retail-shot img{object-position:53% 52%}.business-track .stock-shot img{object-position:32% 55%}.business-track .laptop-shot img{object-position:center 57%}.business-track .planning-shot img{object-position:center 70%}@keyframes business-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}.site-footer{padding:64px 0 23px;color:#71809a;background:linear-gradient(112deg,#f5f6fe,#f7fbfa);border-top:1px solid var(--line)}.footer-columns{display:grid;grid-template-columns:1.65fr repeat(3,1fr);gap:34px}.footer-intro .brand{color:var(--ink)}.footer-intro p{max-width:290px;margin:17px 0 20px;font-size:14px;line-height:1.65}.footer-pills{display:flex;gap:9px}.footer-pills span{display:grid;place-items:center;width:37px;height:37px;border:1px solid var(--line);border-radius:50%;color:#536280;background:#fff;font-size:13px;font-weight:850}.footer-columns h4{margin:5px 0 15px;color:var(--ink);font-size:14px}.footer-columns a{display:block;width:max-content;max-width:100%;margin:0 0 14px;color:#71809a;font-size:14px}.footer-columns a:hover{color:var(--green)}.footer-bottom{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-top:48px;padding-top:24px;border-top:1px solid #dfe5ed;font-size:12px}.footer-status{display:inline-flex;align-items:center;gap:7px;padding:6px 10px;border-radius:999px;color:#27845f;background:#e0f7eb}.footer-status i{width:7px;height:7px;border-radius:50%;background:#48c98d}@media(max-width:850px){.footer-columns{grid-template-columns:1.5fr 1fr 1fr}.footer-columns>div:last-child{grid-column:2}.footer-intro{grid-row:span 2}}@media(max-width:540px){.business-marquee{padding-inline:14px}.business-track .gallery-item{width:260px;height:215px;min-height:215px;flex-basis:260px}.site-footer{padding-top:46px}.footer-columns{grid-template-columns:1fr 1fr;gap:28px 22px}.footer-intro{grid-column:span 2;grid-row:auto}.footer-columns>div:last-child{grid-column:auto}.footer-bottom{align-items:flex-start;flex-direction:column;margin-top:34px}}@media(prefers-reduced-motion:reduce){.business-track{animation:none!important}}
    </style>
</head>
<body>
    <header class="top">
        <nav class="shell nav" aria-label="Primary navigation">
            <a
                class="brand"
                href="{{ url('/') }}"
                aria-label="{{ config('app.name', 'CraftSalesPOS') }} home"
                ><span class="mark"
                    ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 7h16M7 3v8m10-8v8M6 21h12a2 2 0 0 0 2-2V7H4v12a2 2 0 0 0 2 2Z" />
                        <path d="M8 15h3m2 0h3" />
                    </svg></span
                >{{ config('app.name', 'CraftSalesPOS') }}</a
            >
            <div class="navlinks">
                <a href="#solutions">Solutions</a
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
                    >Start selling</a
                >
            </div>
            <button class="nav-toggle" aria-expanded="false" aria-label="Open navigation">
                <span></span>
            </button>
            <div class="mobile-menu" id="mobile-nav" aria-hidden="true">
                <div class="navlinks">
                    <a href="#solutions">Solutions</a
                    ><a href="{{ route('marketing.features') }}">Features</a
                    ><a href="{{ route('marketing.pricing') }}">Pricing</a
                    ><a href="{{ route('marketing.about') }}">About</a
                    ><a href="{{ route('marketing.updates') }}">Updates</a
                    ><a href="{{ route('marketing.contact') }}">Contact</a>
                </div>
                <div class="nav-actions">
                    <a class="signin" href="{{ route('login') }}">Sign in</a
                    ><a class="button primary" href="{{ route('business.getRegister') }}"
                        >Start selling</a
                    >
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="hero">
            <div class="shell hero-grid">
                <div>
                    <h1>Sell more and worry less <span class="accent">with our POS.</span></h1>
                    <p class="lede">A calm, capable workspace for the real work of running a business: selling, stocking, serving and seeing what comes next.</p>
                    <div class="actions">
                        <a class="button primary" href="{{ route('business.getRegister') }}"
                            >Build your workspace <span aria-hidden="true">&rarr;</span></a
                        ><a class="button secondary" href="{{ route('login') }}">Open your POS</a>
                    </div>
                    <div class="note">
                        <span class="tick">&#10003;</span
                        ><span
                            >Designed to simplify operations, accelerate sales, and support your
                            growth..</span
                        >
                    </div>
                </div>
                <div class="hero-visual" aria-label="CraftSalesPOS on a laptop">
                    <img
                        class="hero-laptop"
                        src="{{ asset('images/landing/laptop_no_background.svg') }}"
                        alt="CraftSalesPOS running on a laptop"
                    />
                    <div class="hero-status">
                        <i></i><span><small>Workspace status</small><b>Everything in sync</b></span>
                    </div>
                </div>
            </div>
        </section>
        <div class="ticker-wrap" role="region" aria-label="Live activity example">
            <div class="ticker">
                <span class="ticker-item"
                    ><i class="dot-sale"></i>New sale — KSh 450 · Nairobi CBD</span
                ><span class="ticker-item"
                    ><i class="dot-stock"></i>Stock alert — Sugar 2kg running low</span
                ><span class="ticker-item"
                    ><i class="dot-pay"></i>Payment received — M-Pesa · KSh 1,200</span
                ><span class="ticker-item"
                    ><i class="dot-sale"></i>New sale — KSh 2,300 · Westlands branch</span
                ><span class="ticker-item"
                    ><i class="dot-sale"></i>Shift closed — Till 2 balanced</span
                ><span class="ticker-item"
                    ><i class="dot-stock"></i>Stock alert — Cooking oil 5L running low</span
                ><span class="ticker-item"
                    ><i class="dot-pay"></i>Payment received — Card · KSh 890</span
                ><span class="ticker-item"
                    ><i class="dot-sale"></i>New sale — KSh 6,150 · Kisumu branch</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-sale"></i>New sale — KSh 450 · Nairobi CBD</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-stock"></i>Stock alert — Sugar 2kg running low</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-pay"></i>Payment received — M-Pesa · KSh 1,200</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-sale"></i>New sale — KSh 2,300 · Westlands branch</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-sale"></i>Shift closed — Till 2 balanced</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-stock"></i>Stock alert — Cooking oil 5L running low</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-pay"></i>Payment received — Card · KSh 890</span
                ><span class="ticker-item" aria-hidden="true"
                    ><i class="dot-sale"></i>New sale — KSh 6,150 · Kisumu branch</span
                >
            </div>
        </div>
        <section class="assurance">
            <div class="shell">
                <span><i></i> Fast checkout</span><span><i></i> Stock confidence</span
                ><span><i></i> Clear reporting</span><span><i></i> Ready to grow</span>
            </div>
        </section>
        <section class="shell solutions" id="solutions">
            <div class="heading">
                <h2>Built around the way your business actually moves.</h2>
                <p>Choose the tools that fit today, then let CraftSalesPOS keep up as your operation gains momentum.</p>
            </div>
            <div class="bento">
                <article class="card retail photo-card" data-reveal>
                    <span class="card-photo"
                        ><img
                            src="{{ asset('images/landing/retail.jpg') }}"
                            alt="Retail workspace"
                            loading="lazy" /><span class="wash"></span></span
                    ><span class="icon"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m4 7 8-4 8 4-8 4-8-4Z" />
                            <path d="m4 12 8 4 8-4M4 17l8 4 8-4" />
                        </svg
                    ></span>
                    <h3>Retail with a steady pulse</h3>
                    <p>Make each sale simple while inventory, products and customer history stay quietly in sync.</p>
                </article>
                <article class="card restaurant photo-card" data-reveal>
                    <span class="card-photo"
                        ><img
                            src="{{ asset('images/landing/restaurant.jpg') }}"
                            alt="Busy restaurant service"
                            loading="lazy" /><span class="wash"></span></span
                    ><span class="icon"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 11h18M5 11V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 11v8h14v-8" />
                            <path d="M9 15h6" />
                        </svg
                    ></span>
                    <h3>Service that keeps flowing</h3>
                    <p>Bring orders, tables and payments into one confident rhythm.</p>
                </article>
                <article class="card service photo-card" data-reveal>
                    <span class="card-photo"
                        ><img
                            src="{{ asset('images/landing/service.jpg') }}"
                            alt="Service business owner"
                            loading="lazy" /><span class="wash"></span></span
                    ><span class="icon"
                        ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 20V4h16v16H4Z" />
                            <path d="M8 8h8M8 12h5M8 16h3" />
                        </svg
                    ></span>
                    <h3>Clients, organised</h3>
                    <p>Manage bookings, invoices and follow-ups without losing the personal touch.</p>
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
        <section class="gallery" data-reveal>
            <div class="shell gallery-head">
                <h2>Built for every business, from startup to enterprise.</h2>
                <p>CraftSalesPOS adapts to the way you do business, whether you're running a single outlet or managing multiple locations.</p>
            </div>

            <div
                class="business-marquee"
                role="region"
                aria-label="Industries served by CraftSalesPOS"
            >
                <div class="business-track">
                    <article class="gallery-item retail-shot">
                        <img
                            src="{{ asset('images/landing/african-retail.jpg') }}"
                            alt="Supermarket using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Supermarkets</span>
                    </article>

                    <article class="gallery-item stock-shot">
                        <img
                            src="{{ asset('images/landing/african-pharmacies.png') }}"
                            alt="Pharmacy using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Pharmacies</span>
                    </article>

                    <article class="gallery-item laptop-shot">
                        <img
                            src="{{ asset('images/landing/african-laptop.jpg') }}"
                            alt="Salon using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Salons &amp; Barbershops</span>
                    </article>

                    <article class="gallery-item planning-shot">
                        <img
                            src="{{ asset('images/landing/african-laptop.png') }}"
                            alt="Agrovet store using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Agrovet Stores</span>
                    </article>

                    <article class="gallery-item retail-shot">
                        <img
                            src="{{ asset('images/landing/african-retail.png') }}"
                            alt="Hardware and building supplies store using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Hardware &amp; Building Supplies</span>
                    </article>

                    <article class="gallery-item laptop-shot">
                        <img
                            src="{{ asset('images/landing/african-laptop.jpg') }}"
                            alt="Fashion boutique using CraftSalesPOS"
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Fashion Boutiques</span>
                    </article>

                    <!-- Duplicate items for seamless marquee -->

                    <article class="gallery-item retail-shot" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-retail.jpg') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Supermarkets</span>
                    </article>

                    <article class="gallery-item stock-shot" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-pharmacies.png') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Pharmacies</span>
                    </article>

                    <article class="gallery-item laptop-shot" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-laptop.jpg') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Salons &amp; Barbershops</span>
                    </article>

                    <article class="gallery-item planning-shot" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-laptop.png') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Agrovet Stores</span>
                    </article>

                    <article class="gallery-item retail-shot" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-retail.png') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Hardware &amp; Building Supplies</span>
                    </article>

                    <article class="gallery-item laptop-shot" aria-hidden="true">
                        <img
                            src="{{ asset('images/landing/african-laptop.jpg') }}"
                            alt=""
                            loading="lazy"
                        />
                        <span class="gallery-tag"><i></i> Fashion Boutiques</span>
                    </article>
                </div>
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
                    <span class="eyebrow"><i></i> A better daily rhythm</span>
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
                <span>MERIDIAN</span><span>FIELD &amp; FORM</span><span>NORTHLINE</span
                ><span>COMMON GOODS</span><span>STUDIO 8</span>
            </div>
        </section>
        <section class="pricing" id="pricing">
            <div class="shell">
                <div class="pricing-head">
                    <h2>Pricing that reflects your setup.</h2>
                    <p>Choose from the plans currently available for your business. You can select a package while creating your workspace.</p>
                </div>
                <div class="plans">
                    @forelse ($landingPackages as $package)
                        <article
                            class="plan {{ $package->mark_package_as_popular ? 'featured' : '' }}"
                        >
                            <h3>{{ $package->name }}</h3>
                            <p>{{ $package->description ?: 'A practical package for a more connected business day.' }}</p>
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
                                <li>
                                    {{ $package->location_count ? $package->location_count : 'Unlimited' }} locations
                                </li>
                                <li>
                                    {{ $package->user_count ? $package->user_count : 'Unlimited' }} users
                                </li>
                                <li>
                                    {{ $package->product_count ? $package->product_count : 'Unlimited' }} products
                                </li>
                                <li>
                                    {{ $package->invoice_count ? $package->invoice_count : 'Unlimited' }} invoices
                                </li>
                                @if ($package->trial_days)
                                    <li>{{ $package->trial_days }} trial days</li>
                                @endif
                            </ul>
                            @if ($package->enable_custom_link)
                                <a
                                    class="button {{ $package->mark_package_as_popular ? '' : 'secondary' }}"
                                    href="{{ $package->custom_link }}"
                                    >{{ $package->custom_link_text }}</a
                                >
                            @else
                                <a
                                    class="button {{ $package->mark_package_as_popular ? '' : 'secondary' }}"
                                    href="{{ route('business.getRegister', ['package' => $package->id]) }}"
                                    >{{ (float) $package->price === 0 ? 'Choose free plan' : 'Choose this plan' }}
                                    <span aria-hidden="true">&rarr;</span></a
                                >
                            @endif
                        </article>
                    @empty
                        <article class="plan featured" style="grid-column: 1/-1">
                            <h3>Plans are being prepared</h3>
                            <p>Your administrator has not published a package yet. You can still create a workspace and choose the right setup with your team.</p>
                            <a class="button" href="{{ route('business.getRegister') }}"
                                >Create a workspace <span aria-hidden="true">&rarr;</span></a
                            >
                        </article>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
    <footer class="site-footer" id="start">
        <div class="shell">
            <div class="footer-columns">
                <div class="footer-intro">
                    <a class="brand" href="{{ url('/') }}"
                        ><span class="mark"
                            ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 7h16M7 3v8m10-8v8M6 21h12a2 2 0 0 0 2-2V7H4v12a2 2 0 0 0 2 2Z" />
                                <path d="M8 15h3m2 0h3" />
                            </svg></span
                        >CraftSales<span class="accent">POS</span></a
                    >
                    <p>A clear point-of-sale workspace for selling, stocking, serving and growing with confidence.</p>
                </div>
                <div>
                    <h4>Product</h4>
                    <a href="#solutions">Solutions</a
                    ><a href="{{ route('marketing.features') }}">Features</a
                    ><a href="{{ route('marketing.pricing') }}">Pricing</a
                    ><a href="{{ route('marketing.updates') }}">Updates</a>
                </div>
                <div>
                    <h4>Company</h4>
                    <a href="{{ route('marketing.about') }}">About</a
                    ><a href="{{ route('marketing.contact') }}">Contact</a
                    ><a href="{{ route('business.getRegister') }}">Get started</a>
                </div>
                <div>
                    <h4>Account</h4>
                    <a href="{{ route('login') }}">Sign in</a
                    ><a href="{{ route('business.getRegister') }}">Create workspace</a>
                </div>
            </div>
            <div class="footer-bottom">
                <span
                    >&copy; {{ date('Y') }} {{ config('app.name', 'CraftSalesPOS') }}. All rights
                    reserved.</span
                >
            </div>
        </div>
    </footer>
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

        const marquee = document.querySelector('.business-marquee');
        const track = document.querySelector('.business-track');
        const items = document.querySelectorAll('.business-track .gallery-item');
        if (marquee && track && items.length) {
            const updateActiveItem = () => {
                const containerCenter =
                    marquee.getBoundingClientRect().left + marquee.offsetWidth / 2;
                let closest = null;
                let minDistance = Infinity;
                items.forEach((item) => {
                    const rect = item.getBoundingClientRect();
                    const itemCenter = rect.left + rect.width / 2;
                    const distance = Math.abs(containerCenter - itemCenter);
                    if (distance < minDistance) {
                        minDistance = distance;
                        closest = item;
                    }
                });
                items.forEach((item) => {
                    item.classList.toggle('active', item === closest);
                    item.classList.toggle('inactive', item !== closest);
                });
            };
            const animateMarquee = () => {
                updateActiveItem();
                requestAnimationFrame(animateMarquee);
            };
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                requestAnimationFrame(animateMarquee);
            } else {
                updateActiveItem();
            }
        }
    </script>
</body>
</html>
