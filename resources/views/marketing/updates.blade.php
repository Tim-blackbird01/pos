@extends ('marketing.layout')
@section ('title', 'Updates')
@section ('content')
    <section class="page-hero">
        <div class="shell">
            <h1>News for businesses that keep moving.</h1>
            <p>Product releases, operational insights and the details that help your team get more from its workspace.</p>
        </div>
    </section>

    <section class="shell section">
        {{-- Topic filter tabs --}}
        <div class="topic-pills" role="tablist" aria-label="Filter updates by topic">
            <button
                class="pill pill--active"
                type="button"
                data-filter="all"
                role="tab"
                aria-selected="true"
            >
                All updates
            </button>
            <button
                class="pill"
                type="button"
                data-filter="operations"
                role="tab"
                aria-selected="false"
            >
                Operations
            </button>
            <button
                class="pill"
                type="button"
                data-filter="security"
                role="tab"
                aria-selected="false"
            >
                Security
            </button>
            <button
                class="pill"
                type="button"
                data-filter="product"
                role="tab"
                aria-selected="false"
            >
                Product
            </button>
            <button
                class="pill"
                type="button"
                data-filter="growth"
                role="tab"
                aria-selected="false"
            >
                Growth
            </button>
            <button
                class="pill"
                type="button"
                data-filter="support"
                role="tab"
                aria-selected="false"
            >
                Support
            </button>
        </div>

        {{-- Featured + secondary column --}}
        <div class="post-grid" data-post-section>
            <article class="post post--large" data-topic="product">
                <div class="post__media">
                    <img
                        src="images/landing/dj-image.svg"
                        alt="Dashboard showing activity across multiple business locations"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Featured update</span>
                    <h2>A clearer way to coordinate every location.</h2>
                    <p>Explore the principles behind a calmer view of sales, stock and team activity across the places your business operates. Every location reports into the same live dashboard, so managers stop reconciling spreadsheets and start making decisions with current numbers.</p>
                    <a href="{{ route('marketing.features') }}" class="post__link"
                        >Explore the features &rarr;</a
                    >
                </div>
            </article>

            <div class="post-stack">
                <article class="post" data-topic="operations">
                    <div class="post__media post__media--small">
                        <img
                            src="images/landing/code-laptop.svg"
                            alt="Weekly reporting summary screen"
                            loading="lazy"
                        />
                    </div>
                    <div class="post__body">
                        <span class="tag">Operations</span>
                        <h3>Close the day with more context.</h3>
                        <p>Small reporting habits that make weekly planning feel much less like a scramble.</p>
                        <a href="{{ route('marketing.features') }}" class="post__link"
                            >Read more &rarr;</a
                        >
                    </div>
                </article>

                <article class="post" data-topic="security">
                    <div class="post__media post__media--small">
                        <img
                            src="images/landing//handshake.svg"
                            alt="Role-based access permissions screen"
                            loading="lazy"
                        />
                    </div>
                    <div class="post__body">
                        <span class="tag">Security</span>
                        <h3>Access that reflects real roles.</h3>
                        <p>A practical approach to giving each teammate the access they need and no more.</p>
                        <a href="{{ route('marketing.contact') }}" class="post__link"
                            >Ask a question &rarr;</a
                        >
                    </div>
                </article>
            </div>
        </div>

        {{-- Second wave of updates --}}
        <div class="post-grid post-grid--tri" data-post-section>
            <article class="post" data-topic="product">
                <div class="post__media">
                    <img
                        src="images/landing/inventory-team.svg"
                        alt="Inventory syncing between two warehouses"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Product</span>
                    <h3>Stock counts that agree with each other.</h3>
                    <p>How real-time syncing keeps every location working from the same numbers, even during a busy restock.</p>
                    <a href="{{ route('marketing.features') }}" class="post__link"
                        >Read more &rarr;</a
                    >
                </div>
            </article>

            <article class="post" data-topic="growth">
                <div class="post__media">
                    <img
                        src="images/landing/dashboard-presentation.svg"
                        alt="Team activity feed on a tablet"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Growth</span>
                    <h3>Understanding your busiest hours.</h3>
                    <p>Turning raw activity data into staffing decisions you can actually act on, week over week.</p>
                    <a href="{{ route('marketing.features') }}" class="post__link"
                        >Read more &rarr;</a
                    >
                </div>
            </article>

            <article class="post" data-topic="support">
                <div class="post__media">
                    <img
                        src="images/landing/office-worker.svg"
                        alt="Support conversation between two teammates"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Support</span>
                    <h3>Faster answers when something breaks.</h3>
                    <p>What's changed in how our team helps yours get back on track, from first message to resolution.</p>
                    <a href="{{ route('marketing.contact') }}" class="post__link"
                        >Ask a question &rarr;</a
                    >
                </div>
            </article>

            <article class="post" data-topic="operations">
                <div class="post__media">
                    <img
                        src="{{ asset('images/landing/open-close.svg') }}"
                        alt="Opening and closing checklist illustration"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Operations</span>
                    <h3>Opening and closing checklists, done right.</h3>
                    <p>Consistent routines across every shift, with a record of what actually got done and when.</p>
                    <a href="{{ route('marketing.features') }}" class="post__link"
                        >Read more &rarr;</a
                    >
                </div>
            </article>

            <article class="post" data-topic="security">
                <div class="post__media">
                    <img
                        src="images/landing/history-monitor.svg"
                        alt="Audit log of account activity"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Security</span>
                    <h3>A full history of who changed what.</h3>
                    <p>Every sensitive action is now logged and searchable, so investigating an issue takes minutes, not days.</p>
                    <a href="{{ route('marketing.contact') }}" class="post__link"
                        >Ask a question &rarr;</a
                    >
                </div>
            </article>

            <article class="post" data-topic="growth">
                <div class="post__media">
                    <img
                        src="images/landing/sales-forecast.svg"
                        alt="Sales forecast chart trending upward"
                        loading="lazy"
                    />
                </div>
                <div class="post__body">
                    <span class="tag">Growth</span>
                    <h3>Forecasting that improves with every week.</h3>
                    <p>Projections now factor in seasonality and past performance per location, not just a flat average.</p>
                    <a href="{{ route('marketing.features') }}" class="post__link"
                        >Read more &rarr;</a
                    >
                </div>
            </article>
        </div>

        {{-- Empty state for filters with no matches (defensive, keeps UI honest) --}}
        <p class="no-results" data-no-results hidden>No updates in this category yet — check back soon.</p>

        {{-- CTA banner --}}
        <div class="updates-cta">
            <div>
                <h2>Have a question about a recent change?</h2>
                <p>Our team is happy to walk through anything you've read here.</p>
            </div>
            <a href="{{ route('marketing.contact') }}" class="btn btn--primary">Get in touch</a>
        </div>
    </section>

@endsection

@push ('styles')
    <link rel="stylesheet" href="{{ asset('css/marketing-updates.css') }}" />
@endpush

@push ('scripts')
    <script src="{{ asset('js/marketing-updates.js') }}" defer></script>
@endpush
