@extends('layouts.app', ['bodyClass' => 'page-home'])

@section('content')
    @php $wa = 'https://wa.me/'.config('mazz.whatsapp'); @endphp

    {{-- Hero: dark Dell desk photo (public/images/hero-dell-dark*.webp) on a near-black background fills the right side and fades into the background behind the text. --}}
    <section class="hero">
        <div class="hero__media">
            <picture>
                <source media="(max-width: 680px)" srcset="{{ asset('images/hero-dell-dark-sm.webp') }}">
                <img src="{{ asset('images/hero-dell-dark.webp') }}" width="1536" height="1024" fetchpriority="high"
                     alt="A Dell laptop on a stand at a dark desk, with a backlit keyboard, mouse and headphones against a dark brick wall.">
            </picture>
        </div>
        <div class="wrap hero__grid">
            <div class="hero__copy">
                <h1 class="hero__title">Laptops That Help<br>You <span>Do More.</span></h1>

                <p class="hero__lede">Business-grade laptops from Dell, HP and Lenovo, plus the upgrades and accessories to go with them. Every unit is cleaned, tested and fairly priced.</p>

                <div class="hero__actions">
                    <a class="btn-pill btn-pill--accent" href="{{ route('laptops.index') }}">Shop Laptops <x-icon name="arrow" stroke-width="2" /></a>
                    <a class="btn-pill btn-pill--line" href="{{ route('build') }}">Build Your Setup</a>
                </div>

                <a class="hero__chat" href="{{ $wa }}" target="_blank" rel="noopener">
                    <span class="hero__chat-icon"><x-icon name="whatsapp" /></span>
                    <span><b>Questions? Chat with us</b><small>WhatsApp {{ config('mazz.phone_display') }}</small></span>
                </a>
            </div>
        </div>
    </section>

    {{-- Laptops --}}
    <section class="section">
        <div class="wrap">
            <div class="heading">
                <div>
                    <p class="eyebrow">In stock now</p>
                    <h2 class="title">Featured Laptops</h2>
                    <p>{{ $laptopCount }} {{ Str::plural('laptop', $laptopCount) }} ready today, with real photos and full specs.</p>
                </div>
                @if ($laptops->isNotEmpty())
                    <a class="text-link" href="{{ route('laptops.index') }}">View all laptops <x-icon name="arrow" /></a>
                @endif
            </div>

            @if ($laptops->isNotEmpty())
                <div class="grid grid--4">
                    @foreach ($laptops as $laptop)
                        <x-laptop-card :laptop="$laptop" />
                    @endforeach
                </div>
            @else
                <div class="empty">
                    <x-icon name="laptop" width="36" height="36" />
                    <h3>New stock is being prepared.</h3>
                    <p>Tell us what you're looking for and we'll message you when it's ready.</p>
                    <a class="btn btn--dark" href="{{ $wa }}?text={{ rawurlencode('Hi Mazz Laptop Hub, I\'m looking for a laptop. What do you have?') }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Ask on WhatsApp</a>
                </div>
            @endif
        </div>
    </section>

    {{-- How it works: four steps on a line (vertical on phones) --}}
    <section class="section section--flush">
        <div class="wrap">
            <div class="steps-wrap">
                <div class="center-head">
                    <p class="tag-pill"><span></span> How it works</p>
                    <h2 class="title">Buying from Mazz is easy</h2>
                    <p>From browsing to taking it home, we keep it simple and honest.</p>
                </div>
                <ol class="steps">
                    <li class="step">
                        <span class="step__icon"><x-icon name="laptop" stroke-width="1.4" /></span>
                        <span class="step__node">1</span>
                        <h3>Browse online</h3>
                        <p>Real photos and full specs of every laptop on our shelf.</p>
                    </li>
                    <li class="step">
                        <span class="step__icon"><x-icon name="whatsapp" stroke-width="1.4" /></span>
                        <span class="step__node">2</span>
                        <h3>Ask on WhatsApp</h3>
                        <p>Check stock, price or upgrades. We reply quickly.</p>
                    </li>
                    <li class="step">
                        <span class="step__icon"><x-icon name="store" stroke-width="1.4" /></span>
                        <span class="step__node">3</span>
                        <h3>Visit &amp; try it</h3>
                        <p>Come to our Negombo shop and test it before you pay.</p>
                    </li>
                    <li class="step">
                        <span class="step__icon"><x-icon name="shield" stroke-width="1.4" /></span>
                        <span class="step__node">4</span>
                        <h3>Take it home</h3>
                        <p>Opened, cleaned and tested, ready to use from day one.</p>
                    </li>
                </ol>
                <div class="steps-panel__cta">
                    <a class="btn btn--dark" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Start on WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Build your setup --}}
    <section class="section section--flush">
        <div class="wrap">
            <div class="promo">
                <div class="promo__text">
                    <p class="eyebrow">Build your setup</p>
                    <h2>Pick a laptop.<br>Add upgrades.<br>See the total.</h2>
                    <p>Start with a laptop, add more RAM, a bigger SSD or a spare charger, and watch the total update as you go. Happy with it? Send the list to us on WhatsApp.</p>
                    <a class="btn btn--dark" href="{{ route('build') }}">Start building <x-icon name="arrow" /></a>
                </div>
                <div class="promo__slip" aria-hidden="true">
                    <p class="promo__slip-head">Example setup</p>
                    <span><i>Lenovo ThinkPad T480</i><b>Rs. 175,000</b></span>
                    <span><i>+ Kingston 8GB RAM</i><b>Rs. 5,000</b></span>
                    <span><i>+ Kingston 240GB SSD</i><b>Rs. 6,500</b></span>
                    <span class="is-total"><i>Estimated total</i><b>Rs. 186,500</b></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Parts & accessories: short text on the left, the parts photo (public/images/parts) on the right --}}
    @if ($partCategories->isNotEmpty())
        <section class="section section--flush">
            <div class="wrap parts-promo" id="parts">
                <div class="parts-promo__text">
                    <p class="eyebrow">Parts &amp; accessories</p>
                    <h2 class="title">We also stock parts &amp; accessories</h2>
                    <p class="parts-promo__lede">RAM, SSDs, chargers and more to upgrade your laptop.</p>
                    <a class="text-link parts-promo__link" href="{{ route('parts.index') }}">Browse parts <x-icon name="arrow" /></a>
                </div>
                <a class="parts-promo__media" href="{{ route('parts.index') }}" tabindex="-1" aria-hidden="true">
                    <picture>
                        <source media="(max-width: 640px)" srcset="{{ asset('images/parts/parts-set-sm.webp') }}">
                        <img src="{{ asset('images/parts/parts-set.webp') }}" alt="" width="1000" height="556" loading="lazy">
                    </picture>
                </a>
            </div>
        </section>
    @endif
@endsection
