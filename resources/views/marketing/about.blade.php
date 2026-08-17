@extends ('marketing.layout')
@section ('title', 'About')
@section ('content')
    <section class="page-hero">
        <div class="shell animate-fade-in">
            <span class="eyebrow">Our point of view</span>
            <h1>Commerce tools should make good work feel lighter.</h1>
            <p>CraftSalesPOS is built around the everyday decisions that turn busy teams into composed, confident businesses.</p>
        </div>
    </section>

    <div class="shell">
        <section class="story-grid animate-fade-in animate-delay-1">
            <div class="visual-wrapper">
                <img
                    src="{{ asset('images/landing/team.webp') }}"
                    alt="Team collaborating in a modern store environment"
                />
            </div>
            <div class="story-copy animate-delay-2">
                <h2>Built for everything that happens before and after the sale.</h2>
                <p>Great businesses run best when technology gets out of the way. By eliminating system friction and providing clear operational insights, we help your team spend less time troubleshooting and more time serving customers.</p>
                <p>We focus on reliable workflows, practical details, and software that feels effortless to use—from day one to your next stage of growth.</p>
            </div>
        </section>

        <section class="stats-band animate-fade-in animate-delay-3">
            <div class="stat-item">
                <h3>99.9%</h3>
                <p>Uptime Reliability</p>
            </div>
            <div class="stat-item">
                <h3>2x</h3>
                <p>Faster Checkout Speed</p>
            </div>
            <div class="stat-item">
                <h3>1000+</h3>
                <p>Active Workflows daily</p>
            </div>
        </section>

        <section class="mission-grid animate-fade-in animate-delay-1">
            <article class="card">
                <span class="icon">&rarr;</span>
                <h3>Our mission</h3>
                <p>Our mission is to equip independent teams and growing organizations with a practical, fully connected operating foundation that simplifies daily workflows, unifies retail operations, and turns everyday decisions into steady business growth.</p>
            </article>
            <article class="card">
                <span class="icon mint">&#9733;</span>
                <h3>Our vision</h3>
                <p>To empower everyday businesses with intuitive, unified commerce tools that eliminate operational friction and turn daily operations into effortless, sustainable growth.</p>
            </article>
        </section>

        <section class="values-section animate-fade-in animate-delay-2">
            <div class="section-header">
                <h2>Built on clear principles</h2>
                <p>How we approach software development and business design every single day.</p>
            </div>
            <div class="values-grid">
                <div class="value-box">
                    <h4>Intentional Simplicity</h4>
                    <p>We strip away clutter so your team can focus on what matters most: serving customers.</p>
                </div>
                <div class="value-box">
                    <h4>Uncompromising Speed</h4>
                    <p>Every millisecond saved at checkout translates to a smoother customer experience.</p>
                </div>
                <div class="value-box">
                    <h4>Quiet Reliability</h4>
                    <p>Our tools work predictably in the background, so you never have to worry about downtime.</p>
                </div>
                <div class="value-box">
                    <h4>Human-Centric Design</h4>
                    <p>Software made for people, eliminating steep learning curves for new team members.</p>
                </div>
            </div>
        </section>

        <section class="band animate-fade-in animate-delay-3">
            <div class="band-inner">
                <div>
                    <h2>Ready to build a calmer, more capable business day?</h2>
                    <p>Start with the tools that matter now and keep the door open for what comes next.</p>
                </div>
                <a class="button-primary" href="{{ route('business.getRegister') }}">
                    Get started <span>&rarr;</span>
                </a>
            </div>
        </section>
    </div>
@endsection

@push ('styles')
    <link rel="stylesheet" href="{{ asset('css/marketing-about.css') }}" />
@endpush
