@extends ('marketing.layout')
@section ('title', 'Pricing')
@section ('content')
    <section class="page-hero">
        <div class="shell">
            <span class="eyebrow">Straightforward by design</span>
            <h1>Pricing that makes room for growth.</h1>
            <p>Choose a foundation that fits your operation today. Move forward when your team, locations or ambitions ask for more.</p>
        </div>
    </section>
    <section class="shell section">
        <div class="plans">
            @forelse ($landingPackages as $package)
                <article class="plan {{ $package->mark_package_as_popular ? 'highlight' : '' }}">
                    <h3>{{ $package->name }}</h3>
                    <p>{{ $package->description ?: 'A practical foundation for a more connected business day.' }}</p>
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
                            >{{ (float) $package->price === 0 ? 'Choose free plan' : 'Choose this plan' }} &rarr;</a
                        >
                    @endif
                </article>
            @empty
                <article class="plan highlight" style="grid-column: 1/-1">
                    <h3>Packages are being prepared</h3>
                    <p>Your administrator has not published a package yet. You can still create a workspace and choose a setup with your team.</p>
                    <a class="button" href="{{ route('business.getRegister') }}"
                        >Create a workspace &rarr;</a
                    >
                </article>
            @endforelse
        </div>
    </section>
    <section
        class="band"
        style="background: linear-gradient(180deg, #359060 0%, #207152 50%, #1b6047 100%)"
    >
        <div class="shell band-inner">
            <div>
                <h2>Questions before you choose?</h2>
                <p>Tell us how your business works and we will help you find a sensible next step.</p>
            </div>
            <a class="button" href="{{ route('marketing.contact') }}">Talk to our team &rarr;</a>
        </div>
    </section>
@endsection
