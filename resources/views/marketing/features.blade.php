@extends ('marketing.layout')
@section ('title', 'Features')
@section ('content')
    {{-- ================= HERO ================= --}}
    <section class="page-hero">
        <div class="hero-glow" aria-hidden="true"></div>
        <div class="shell" data-reveal>
            <span class="eyebrow">Enterprise-grade control, without the drag</span>
            <h1>Everything your business needs to move with confidence.</h1>
            <p class="lead">One connected workspace for checkout, stock, customers, teams and the decisions that keep your day on track.</p>

            <div class="hero-stats" data-reveal data-reveal-delay="1">
                <div class="hero-stat">
                    <strong>1,200+</strong>
                    <span>businesses running on CraftSalesPOS</span>
                </div>
                <div class="hero-stat-divider" aria-hidden="true"></div>
                <div class="hero-stat">
                    <strong>40+</strong>
                    <span>countries with active workspaces</span>
                </div>
                <div class="hero-stat-divider" aria-hidden="true"></div>
                <div class="hero-stat">
                    <strong>99.9%</strong>
                    <span>uptime across the last 12 months</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= CORE FEATURE GRID ================= --}}
    <section class="shell section">
        <div class="grid-3">
            <article class="card" data-reveal data-reveal-delay="0">
                <span class="icon">$</span>
                <h3>Checkout that keeps pace</h3>
                <p>Move from cart to payment smoothly, with the flexibility to support the way your customers prefer to buy.</p>
            </article>
            <article class="card" data-reveal data-reveal-delay="1">
                <span class="icon mint">#</span>
                <h3>Stock you can trust</h3>
                <p>See what is available, what is moving and what needs attention before a missing item becomes a lost sale.</p>
            </article>
            <article class="card" data-reveal data-reveal-delay="2">
                <span class="icon gold">+</span>
                <h3>Customers remembered</h3>
                <p>Build better service with the details, history and follow-up context your team needs at the counter.</p>
            </article>
            <article class="card dark" data-reveal data-reveal-delay="0">
                <span class="icon">01</span>
                <h3>Multi-location clarity</h3>
                <p>Bring stock, people and performance together across every location without losing the local detail.</p>
            </article>
            <article class="card" data-reveal data-reveal-delay="1">
                <span class="icon">%</span>
                <h3>Reports that answer back</h3>
                <p>Turn the activity of a busy day into useful signals for sales, payments, profit and purchasing.</p>
            </article>
            <article class="card" data-reveal data-reveal-delay="2">
                <span class="icon mint">*</span>
                <h3>Permissioned teamwork</h3>
                <p>Give each role the access it needs while keeping business data purposeful, organised and secure.</p>
            </article>
        </div>
    </section>

    {{-- ================= FEATURE SPOTLIGHTS ================= --}}
    <section class="shell section spotlight-section">
        <div class="spotlight" data-reveal>
            <div class="spotlight-media">
                <img
                    src="{{ asset('images/landing/checkout.svg') }}"
                    alt="Cashier using CraftSalesPOS checkout"
                    loading="lazy"
                />
            </div>
            <div class="spotlight-copy">
                <span class="eyebrow small">Checkout</span>
                <h2>A checkout your team can run on autopilot.</h2>
                <p>Barcode, card, mobile money or split payment — every sale routes through the same fast, familiar flow, so new staff are productive on day one and busy queues stay short.</p>
                <ul class="check-list">
                    <li>Offline-ready, so a dropped connection never stops a sale.</li>
                    <li>Custom discounts, holds and returns handled in a couple of taps.</li>
                    <li>Receipts by print, SMS or email, without extra hardware.</li>
                </ul>
            </div>
        </div>

        <div class="spotlight reverse" data-reveal>
            <div class="spotlight-media">
                <img
                    src="{{ asset('images/landing/stock_report.svg') }}"
                    alt="Stockroom shelves tracked in CraftSalesPOS"
                    loading="lazy"
                />
            </div>
            <div class="spotlight-copy">
                <span class="eyebrow small">Inventory</span>
                <h2>Know what's on the shelf before your customer asks.</h2>
                <p>Live stock counts, reorder alerts and supplier history sit in one place, so low stock becomes a scheduled reorder instead of an apology at the counter.</p>
                <ul class="check-list">
                    <li>Automatic low-stock and expiry alerts.</li>
                    <li>Transfer stock between locations in a few clicks.</li>
                    <li>Full audit trail on every adjustment.</li>
                </ul>
            </div>
        </div>

        <div class="spotlight" data-reveal>
            <div class="spotlight-media">
                <img
                    src="{{ asset('images/landing/laptop_no_background.svg') }}"
                    alt="Business owner reviewing CraftSalesPOS reports on a tablet"
                    loading="lazy"
                />
            </div>
            <div class="spotlight-copy">
                <span class="eyebrow small">Reports</span>
                <h2>The numbers that matter, without the spreadsheet.</h2>
                <p>Sales, margin, staff performance and purchasing trends are summarised automatically, so decisions are based on what's actually happening — not a hunch.</p>
                <ul class="check-list">
                    <li>Daily, weekly and custom-range summaries.</li>
                    <li>Exportable reports for accountants and investors.</li>
                    <li>Alerts when a metric moves outside its usual range.</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= HOW IT WORKS ================= --}}
    <section class="shell section steps-section">
        <div class="section-head" data-reveal>
            <span class="eyebrow small">How it works</span>
            <h2>Set up once. Sell with confidence every day after.</h2>
            <p>No trial-and-error onboarding — a guided path from empty workspace to your first sale.</p>
        </div>

        <div class="steps-grid">
            <article class="step-card" data-reveal data-reveal-delay="0">
                <span class="step-number">01</span>
                <h3>Set up your workspace</h3>
                <p>Add your locations, products and team in a guided setup — most businesses are ready to sell within an afternoon.</p>
            </article>
            <span class="step-connector" aria-hidden="true"></span>
            <article class="step-card" data-reveal data-reveal-delay="1">
                <span class="step-number">02</span>
                <h3>Sell, restock, repeat</h3>
                <p>Checkout, inventory and customer records stay in sync automatically, across every device and every location.</p>
            </article>
            <span class="step-connector" aria-hidden="true"></span>
            <article class="step-card" data-reveal data-reveal-delay="2">
                <span class="step-number">03</span>
                <h3>Review and refine</h3>
                <p>Use built-in reports to spot what's working, adjust pricing or staffing, and grow with a clearer picture.</p>
            </article>
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section class="shell section faq-section">
        <div class="section-head" data-reveal>
            <span class="eyebrow small">Questions</span>
            <h2>Answers before you ask.</h2>
        </div>

        <div class="faq-list" data-reveal>
            <details class="faq-item" open>
                <summary>
                    Does CraftSalesPOS work without internet?<span
                        class="faq-toggle"
                        aria-hidden="true"
                    ></span>
                </summary>
                <p>Yes. Checkout, stock lookups and basic reporting continue offline, and everything syncs automatically once connection is restored.</p>
            </details>
            <details class="faq-item">
                <summary>
                    Can I run more than one location?<span
                        class="faq-toggle"
                        aria-hidden="true"
                    ></span>
                </summary>
                <p>Multi-location is built in from the start — stock, staff and reports can be viewed per-location or rolled up across the whole business.</p>
            </details>
            <details class="faq-item">
                <summary>
                    How long does setup take?<span class="faq-toggle" aria-hidden="true"></span>
                </summary>
                <p>Most single-location businesses are fully set up and selling within a few hours, guided step by step through products, pricing and staff accounts.</p>
            </details>
            <details class="faq-item">
                <summary>
                    Is my business data secure?<span class="faq-toggle" aria-hidden="true"></span>
                </summary>
                <p>Role-based permissions control exactly what each team member can see or change, and every sensitive action is logged for review.</p>
            </details>
        </div>
    </section>

    {{-- ================= CLOSING CTA ================= --}}
    <section class="band">
        <div class="shell band-inner">
            <div>
                <h2>
                    Built for the people at the counter and the people planning what comes next.
                </h2>
                <p>CraftSalesPOS gives both groups the same dependable picture of the business.</p>
            </div>
            <div class="band-actions">
                <a class="button primary" href="{{ route('business.getRegister') }}"
                    >Start your workspace &rarr;</a
                >
                <a class="button button-ghost" href="{{ route('pricing') ?? '#' }}">See pricing</a>
            </div>
        </div>
    </section>
@endsection

@push ('styles')
    <link rel="stylesheet" href="{{ asset('css/marketing-features.css') }}" />
@endpush

@push ('scripts')
    <script src="{{ asset('js/marketing-features.js') }}" defer></script>
@endpush
