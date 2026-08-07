<footer class="site-footer" id="start">
    <div class="shell">
        <div class="footer-columns">
            <div class="footer-intro">
                <a class="brand" href="{{ url('/') }}">
                    @if (file_exists(public_path('uploads/logo.svg')))
                        <img src="/uploads/logo.svg" alt="{{ config('app.name', 'CraftSalesPOS') }}" class="brand-logo" style="height:38px;width:auto;object-fit:contain" />
                    @else
                        <span class="mark">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M7 3v8m10-8v8M6 21h12a2 2 0 0 0 2-2V7H4v12a2 2 0 0 0 2 2Z"/><path d="M8 15h3m2 0h3"/></svg>
                        </span>
                    @endif
                    {{ config('app.name', 'CraftSalesPOS') }}
                </a>
                <p>A clear point-of-sale workspace for selling, stocking, serving and growing with confidence.</p>
            </div>
            <div>
                <h4>Product</h4>
                <a href="{{ route('marketing.industries') }}">Industries</a>
                <a href="{{ route('marketing.features') }}">Features</a>
                <a href="{{ route('marketing.pricing') }}">Pricing</a>
                <a href="{{ route('marketing.updates') }}">Updates</a>
            </div>
            <div>
                <h4>Company</h4>
                <a href="{{ route('marketing.about') }}">About</a>
                <a href="{{ route('marketing.contact') }}">Contact</a>
                <a href="{{ route('business.getRegister') }}">Get started</a>
            </div>
            <div>
                <h4>Account</h4>
                <a href="{{ route('login') }}">Sign in</a>
                <a href="{{ route('business.getRegister') }}">Create workspace</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ config('app.name', 'CraftSalesPOS') }}. All rights reserved.</span>
            <div class="footer-bottom-links">
                <a href="{{ route('marketing.terms') }}">Terms of Service</a>
                <a href="{{ route('marketing.privacy') }}">Privacy Policy</a>
            </div>
        </div>
    </div>
    <style>
        .site-footer{padding:64px 0 23px;color:#71809a;background:linear-gradient(112deg,#f5f6fe,#f7fbfa);border-top:1px solid var(--line)}
        .footer-columns{display:grid;grid-template-columns:1.65fr repeat(3,1fr);gap:34px}
        .footer-intro .brand{color:var(--ink)}
        .footer-intro p{max-width:290px;margin:17px 0 20px;font-size:14px;line-height:1.65}
        .footer-columns h4{margin:5px 0 15px;color:#111c35;font-size:13px;font-weight:700}
        .footer-columns a{display:block;margin:9px 0;color:#71809a;font-size:13px}
        .footer-columns a:hover{color:var(--green)}
        .footer-bottom{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-top:48px;padding-top:24px;color:#8895b2;border-top:1px solid #ffffff17;font-size:12px}
        .footer-bottom-links{display:flex;gap:26px;margin-right:22px}
        .footer-bottom-links a{color:#aeb8ce;text-decoration:none}
        .footer-bottom-links a:hover{color:var(--mint)}
        @media (max-width:820px){.footer-columns{grid-template-columns:1.4fr 1fr}.footer-columns>.footer-intro{grid-column:span 2}}
        @media (max-width:540px){.footer-columns{grid-template-columns:1fr 1fr}.footer-columns>.footer-intro{grid-column:span 2}.footer-bottom{align-items:flex-start;flex-direction:column;margin-top:34px}}
    </style>
</footer>
