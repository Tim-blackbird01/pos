@extends ('marketing.layout')
@section ('title', 'Contact')
@section ('content')
    <section class="page-hero">
        <div class="shell">
            <span class="eyebrow">We are here to help</span>
            <h1>Let's make your next business day easier.</h1>
            <p>Whether you are exploring CraftSalesPOS, planning a rollout or looking for support, start a conversation here.</p>
        </div>
    </section>
    <section class="shell section">
        <div class="split">
            <form class="form" action="{{ route('business.getRegister') }}" method="get">
                <h2 style="margin: 0; letter-spacing: -0.05em">Send a message</h2>
                <label>Your name<input required name="name" placeholder="Your name" /></label
                ><label
                    >Work email<input
                        required
                        type="email"
                        name="email"
                        placeholder="name@business.com" /></label
                ><label
                    >What can we help with?<select name="topic">
                        <option>Sales and pricing</option>
                        <option>Getting started</option>
                        <option>Product question</option>
                        <option>Technical support</option>
                    </select></label
                ><label
                    >Your message<textarea
                        name="message"
                        placeholder="Tell us a little about your business"
                    ></textarea></label
                ><button class="button primary" type="submit">
                    Continue to registration &rarr;
                </button>
            </form>
            <aside class="form-note">
                <h2>A clear place to begin.</h2>
                <p>We built CraftSalesPOS for practical questions and busy teams. Share the context that matters and we will point you in the right direction.</p>
                <div class="item">
                    <b>Exploring the platform?</b>Start with your business type, number of locations
                    and the workflow you want to improve.
                </div>
                <div class="item">
                    <b>Need a quick answer?</b>Sign in to your workspace to access the tools and
                    information available to your team.
                </div>
                <a class="button primary" href="{{ route('login') }}">Sign in</a>
            </aside>
        </div>
    </section>
@endsection
