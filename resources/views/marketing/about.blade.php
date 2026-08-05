@extends ('marketing.layout')
@section ('title', 'About')
@section ('content')
    <section class="page-hero">
        <div class="shell">
            <span class="eyebrow">Our point of view</span>
            <h1>Commerce tools should make good work feel lighter.</h1>
            <p>CraftSalesPOS is built around the everyday decisions that turn busy teams into composed, confident businesses.</p>
        </div>
    </section>
    <section class="shell section">
        <div class="story">
            <div class="visual" aria-hidden="true"></div>
            <div class="copy">
                <h2>Designed for the work behind every sale.</h2>
                <p>Businesses do their best work when systems quietly support the people using them. That means fewer loose ends, clearer answers and more attention left for customers.</p>
                <p>We care about useful detail, dependable workflows and software that feels understandable from the first day through the next stage of growth.</p>
            </div>
        </div>
        <div class="mission">
            <article class="card">
                <span class="icon">&rarr;</span>
                <h3>Our mission</h3>
                <p>Give independent teams and growing organisations a practical, connected operating foundation.</p>
            </article>
            <article class="card">
                <span class="icon mint">*</span>
                <h3>Our vision</h3>
                <p>Make powerful commerce tools feel approachable enough to support better work everywhere.</p>
            </article>
        </div>
    </section>
    <section class="band">
        <div class="shell band-inner">
            <div>
                <h2>Ready to build a calmer, more capable business day?</h2>
                <p>Start with the tools that matter now and keep the door open for what comes next.</p>
            </div>
            <a class="button" href="{{ route('business.getRegister') }}">Get started &rarr;</a>
        </div>
    </section>
@endsection
