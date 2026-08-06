@extends ('marketing.layout')
@section ('title', 'Pricing')
@section ('content')
    {{-- ================= HERO ================= --}}
    <section class="page-hero">
        <div class="shell" data-reveal>
            <h1>Pricing that makes room for growth.</h1>
            <p>Choose a foundation that fits your operation today. Move forward when your team, locations or ambitions ask for more.</p>

            <div class="trust-strip" data-reveal data-reveal-delay="1">
                <span><i class="trust-dot"></i> Bank-grade data security</span>
                <span><i class="trust-dot"></i> 24/7 dedicated support</span>
                <span><i class="trust-dot"></i> No hidden setup fees</span>
            </div>
        </div>
    </section>

    {{-- ================= PLANS (logic unchanged) ================= --}}
    <section class="shell section">
        <div class="plans">
            @forelse ($landingPackages as $package)
                <article
                    class="plan {{ $package->mark_package_as_popular ? 'highlight' : '' }}"
                    data-reveal
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
                        <p>{{ $package->description ?: 'A practical foundation for a more connected business day.' }}</p>
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
                            class="button {{ $package->mark_package_as_popular ? '' : 'primary' }}"
                            href="{{ $package->custom_link }}"
                            >{{ $package->custom_link_text }}</a
                        >
                    @else
                        <a
                            class="button {{ $package->mark_package_as_popular ? '' : 'primary' }}"
                            href="{{ route('business.getRegister', ['package' => $package->id]) }}"
                        >
                            <span class="button-copy">Choose this plan</span>
                            <span class="button-icon">→</span>
                        </a>
                    @endif
                </article>
            @empty
                <article class="plan highlight" style="grid-column: 1/-1">
                    <div class="plan-head">
                        <h3>Packages are being prepared</h3>
                        <p>Your administrator has not published a package yet. You can still create a workspace and choose a setup with your team.</p>
                    </div>
                    <a class="button" href="{{ route('business.getRegister') }}"
                        >Create a workspace &rarr;</a
                    >
                </article>
            @endforelse
        </div>

        @if ($landingPackages->isNotEmpty())
            <p class="plans-footnote" data-reveal>All prices shown exclude applicable taxes. Need something bespoke? <a href="{{ route('marketing.contact') }}">Talk to our team</a>.</p>
        @endif
    </section>

    {{-- ================= INCLUDED EVERYWHERE ================= --}}
    <section class="shell section included-section">
        <div class="section-head" data-reveal>
            <h2>Some things shouldn't be a paid extra.</h2>
            <p>Whichever plan fits today, these come standard â€” no add-on, no upsell.</p>
        </div>

        <div class="feature-grid">
            <article class="feature-card" data-reveal data-reveal-delay="0">
                <span class="feature-icon mint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z" /></svg>
                </span>
                <h3>Instant activation</h3>
                <p>Set up your workspace and start selling the same day, with no lengthy provisioning.</p>
            </article>
            <article class="feature-card" data-reveal data-reveal-delay="1">
                <span class="feature-icon gold">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2" />
                        <path d="M3 10h18M9 4v16" />
                    </svg>
                </span>
                <h3>Role granularity</h3>
                <p>Control exactly what cashiers, managers and accountants can see and change.</p>
            </article>
            <article class="feature-card" data-reveal data-reveal-delay="2">
                <span class="feature-icon mint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9M10 19V5M16 19v-7M22 19H2" /></svg>
                </span>
                <h3>Real-time analytics</h3>
                <p>Track sales, margin and stock velocity as they happen, not the next morning.</p>
            </article>
            <article class="feature-card" data-reveal data-reveal-delay="3">
                <span class="feature-icon gold">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.6-6.4M21 4v5h-5" /></svg>
                </span>
                <h3>Automated backups</h3>
                <p>Continuous cloud backups keep your business records safe without any manual step.</p>
            </article>
        </div>
    </section>

    {{-- ================= DETAILED FEATURE BREAKDOWN ================= --}}
    <section class="shell section">
        <div class="section-head" data-reveal>
            <h2>Detailed feature breakdown.</h2>
            <p>Compare capabilities side by side to find the right operational fit.</p>
        </div>

        <div class="compare-table-wrap" data-reveal>
            <table class="compare-table">
                <thead>
                    <tr>
                        <th scope="col">Operational module</th>
                        <th scope="col">Starter</th>
                        <th scope="col">Growth</th>
                        <th scope="col">Enterprise</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Multi-branch inventory sync</td>
                        <td class="muted">&mdash;</td>
                        <td><span class="tick">&#10003;</span></td>
                        <td><span class="tick">&#10003;</span></td>
                    </tr>
                    <tr>
                        <td>POS &amp; offline register mode</td>
                        <td><span class="tick">&#10003;</span></td>
                        <td><span class="tick">&#10003;</span></td>
                        <td><span class="tick">&#10003;</span></td>
                    </tr>
                    <tr>
                        <td>Custom quotations &amp; invoicing</td>
                        <td><span class="tick">&#10003;</span></td>
                        <td><span class="tick">&#10003;</span></td>
                        <td><span class="tick">&#10003;</span></td>
                    </tr>
                    <tr>
                        <td>Customer loyalty &amp; rewards</td>
                        <td class="muted">&mdash;</td>
                        <td><span class="tick">&#10003;</span></td>
                        <td><span class="tick">&#10003;</span></td>
                    </tr>
                    <tr>
                        <td>API &amp; webhook integrations</td>
                        <td class="muted">&mdash;</td>
                        <td class="muted">&mdash;</td>
                        <td><span class="tick">&#10003;</span></td>
                    </tr>
                    <tr>
                        <td>Dedicated account manager</td>
                        <td class="muted">&mdash;</td>
                        <td class="muted">&mdash;</td>
                        <td><span class="tick">&#10003;</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section class="shell section faq-section">
        <div class="section-head" data-reveal>
            <h2>Everything you need to know about plans and setup.</h2>
        </div>

        <div class="faq-list" data-reveal>
            <details class="faq-item" open>
                <summary>Can I upgrade or downgrade my plan later?</summary>
                <p>Yes. You can move to a different plan as your business grows, and your existing data carries over automatically.</p>
            </details>
            <details class="faq-item">
                <summary>Do you offer custom plans for larger teams?</summary>
                <p>Yes â€” reach out to our team and we'll put together a plan that fits your locations, user count and support needs.</p>
            </details>
            <details class="faq-item">
                <summary>Are there any hidden setup or implementation fees?</summary>
                <p>No. There are no hidden setup costs, and you can cancel at any time without penalty.</p>
            </details>
            <details class="faq-item">
                <summary>What payment methods are supported?</summary>
                <p>Plans can be paid by card or the payment methods enabled for your region, shown at checkout when you choose a plan.</p>
            </details>
        </div>
    </section>

    {{-- ================= CLOSING CTA (unchanged) ================= --}}
    <section
        class="band"
        style="background: linear-gradient(180deg, #359060 0%, #207152 50%, #1b6047 100%)"
    >
        <div class="shell band-inner" data-reveal>
            <div>
                <h2>Questions before you choose?</h2>
                <p>Tell us how your business works and we will help you find a sensible next step.</p>
            </div>
            <a class="button" style="color: #1b6047" href="{{ route('marketing.contact') }}"
                >Talk to our team &rarr;</a
            >
        </div>
    </section>

@endsection

@push ('styles')
    <link rel="stylesheet" href="{{ asset('css/marketing-pricing.css') }}" />
@endpush

@push ('scripts')
    <script src="{{ asset('js/marketing-pricing.js') }}" defer></script>
@endpush
