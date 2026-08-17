@extends ('marketing.layout')

@section ('title', 'Industries')

@section ('content')
    <section class="page-hero">
        <div class="shell animate-fade-in">
            <span class="eyebrow">Built for the work you do</span>
            <h1>Industries powered by CraftSalesPOS.</h1>
            <p>From retail counters to busy kitchens, our point-of-sale platform is designed to support the people, processes and payments that keep your business moving.</p>
        </div>
    </section>

    <section class="shell section">
        <div class="grid-3">
            <article class="card" data-reveal>
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <h3>Retail</h3>
                <p>Product scanning, stock tracking and multi-location visibility for shops, supermarkets and boutiques.</p>
            </article>

            <article class="card" data-reveal>
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                        <line x1="6" y1="1" x2="6" y2="4"></line>
                        <line x1="10" y1="1" x2="10" y2="4"></line>
                        <line x1="14" y1="1" x2="14" y2="4"></line>
                    </svg>
                </div>
                <h3>Restaurants</h3>
                <p>Table management, kitchen routing and split payments to keep service smooth, even during rush hour.</p>
            </article>

            <article class="card" data-reveal>
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24">
                        <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"></path>
                        <line x1="6" y1="2" x2="6" y2="4"></line>
                        <line x1="10" y1="2" x2="10" y2="4"></line>
                        <line x1="14" y1="2" x2="14" y2="4"></line>
                    </svg>
                </div>
                <h3>Cafés & Bars</h3>
                <p>Fast orders, quick payments and easy staff handoffs for coffee shops, bars and quick-service counters.</p>
            </article>

            <article class="card" data-reveal>
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <h3>Service Providers</h3>
                <p>Appointments, invoices and customer records for salons, workshops, repairs and professional services.</p>
            </article>

            <article class="card" data-reveal>
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <h3>Wholesale</h3>
                <p>Bulk pricing, order management and inventory coordination for warehouses, suppliers and trade customers.</p>
            </article>

            <article class="card" data-reveal>
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 21h18"></path>
                        <path d="M19 21v-4a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v4"></path>
                        <path d="M5 11V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8"></path>
                    </svg>
                </div>
                <h3>Hospitality</h3>
                <p>Flexible checkout, room billing and integrated guest experiences for hotels, inns and event venues.</p>
            </article>
        </div>
    </section>

    <section class="shell section spotlight-section">
        <div class="spotlight" data-reveal>
            <div class="spotlight-copy">
                <h2>One platform, many workflows.</h2>
                <p>CraftSalesPOS is built to adapt to your business, whether you need fast retail checkout, multi-location stock control or stronger service workflows.</p>
                <div class="spotlight-features">
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Fast Checkout</span>
                    </div>
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Multi-Location Sync</span>
                    </div>
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Live Stock Control</span>
                    </div>
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Unified Reporting</span>
                    </div>
                </div>
            </div>
            <div class="spotlight-media">
                <div class="spotlight-image-frame">
                    <div class="spotlight-plate"></div>
                    <img
                        class="spotlight-image"
                        src="{{ asset('images/landing/stock_report.webp') }}"
                        alt="CraftSalesPOS stock report"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection

@push ('styles')
    <link rel="stylesheet" href="{{ asset('css/marketing-industries.css') }}" />
@endpush

@push ('scripts')
    <script src="{{ asset('js/marketing-industries.js') }}" defer></script>
@endpush
