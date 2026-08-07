@extends ('marketing.layout')
@section ('title', 'Contact')

@push ('styles')
    <link rel="stylesheet" href="{{ asset('css/marketing-contact.css') }}" />
@endpush

@push ('scripts')
    <script src="{{ asset('js/marketing-contact.js') }}" defer></script>
@endpush

@section ('content')
    <section class="page-hero">
        <div class="shell">
            <h1>Your Next Solution Starts Here.</h1>
            <p>Whether you are exploring CraftSalesPOS, planning a rollout, or looking for support, start a conversation here.</p>
        </div>
    </section>

    <section class="shell section">
        <div
            id="successOverlay"
            class="success-overlay"
            data-success="{{ session('success') ? 'true' : 'false' }}"
        >
            <div class="success-card">
                <img
                    src="{{ asset('images/landing/Check Ok GIF by RainToMe.gif') }}"
                    alt="Success"
                    class="success-gif"
                />
                <p class="success-copy">{{ session('success') ?? 'Your message was sent successfully!' }}</p>
            </div>
        </div>

        <div class="split">
            <form class="contact-card" action="{{ route('contact.send') }}" method="POST">
                @csrf
                <h2>Send a message</h2>
                <p class="form-copy">Fill out the form below and our team will get back to you shortly.</p>

                @if (session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="name">Your name</label>
                    <input
                        id="name"
                        class="form-input"
                        required
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Jane Doe"
                        autocomplete="name"
                    />
                </div>

                <div class="form-group">
                    <label for="email">Work email</label>
                    <input
                        id="email"
                        class="form-input"
                        required
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="name@business.com"
                        autocomplete="email"
                    />
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" class="form-input" name="subject">
                        <option
                            value="Sales and pricing"
                            {{ old('subject') === 'Sales and pricing' ? 'selected' : '' }}
                        >
                            Sales and pricing
                        </option>
                        <option
                            value="Getting started"
                            {{ old('subject') === 'Getting started' ? 'selected' : '' }}
                        >
                            Getting started
                        </option>
                        <option
                            value="Product question"
                            {{ old('subject') === 'Product question' ? 'selected' : '' }}
                        >
                            Product question
                        </option>
                        <option
                            value="Technical support"
                            {{ old('subject') === 'Technical support' ? 'selected' : '' }}
                        >
                            Technical support
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Your message</label>
                    <textarea
                        id="message"
                        class="form-input"
                        name="message"
                        rows="5"
                        placeholder="Tell us a little about your business..."
                        >{{ old('message') }}</textarea
                    >
                </div>

                <button class="btn btn-primary" type="submit">Send Message &rarr;</button>
            </form>

            <aside class="form-note">
                <h2>A clear place to begin.</h2>
                <p>We built CraftSalesPOS for practical questions and busy teams. Share the context that matters and we will point you in the right direction.</p>

                <div class="item">
                    <b>Exploring the platform?</b>
                    Start with your business type, number of locations, and the workflow you want to
                    improve.
                </div>

                <div class="item">
                    <b>Need a quick answer?</b>
                    Start a workspace to access the tools and information available to your team.
                </div>
            </aside>
        </div>
    </section>
@endsection
