@php $wa = 'https://wa.me/'.config('mazz.whatsapp'); @endphp

<footer class="site-footer">
    {{-- "Chat or visit" band with the coffee photo (public/images/band-coffee*.webp) --}}
    <div class="wrap">
        <div class="band">
            <div class="band__media">
                <picture>
                    <source media="(max-width: 680px)" srcset="{{ asset('images/band-coffee-sm.webp') }}">
                    <img src="{{ asset('images/band-coffee.webp') }}" width="1200" height="1466" loading="lazy"
                         alt="Coffee being poured into a mug on a desk next to a laptop and mouse.">
                </picture>
            </div>
            <div class="band__copy">
                <p class="eyebrow">Chat or visit</p>
                <h2>Grab a coffee.<br>We'll find your <span>next laptop.</span></h2>
                <p>Tell us your budget and what you need it for. We'll suggest the right laptop and upgrades, on WhatsApp or at the shop. No pressure.</p>
                <div class="band__actions">
                    <a class="act act--wa" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Chat on WhatsApp</a>
                    <a class="act" href="tel:{{ config('mazz.phone_tel') }}"><x-icon name="phone" /> {{ config('mazz.phone_display') }}</a>
                </div>
                <p class="band__address"><x-icon name="pin" /> <span>{{ config('mazz.address') }}</span> <a href="{{ route('contact') }}">Directions</a></p>
            </div>
        </div>
    </div>

    <div class="wrap site-footer__cols">
        <div class="site-footer__brand">
            <span class="site-footer__logo"><img src="{{ asset('images/logo.png') }}" alt="{{ config('mazz.name') }}" width="160" height="101" loading="lazy"></span>
            <p>Refurbished business laptops, upgrades and accessories in Negombo. Cleaned, tested and priced honestly.</p>
        </div>
        <div>
            <h2>Shop</h2>
            <ul>
                <li><a href="{{ route('laptops.index') }}">Laptops</a></li>
                <li><a href="{{ route('parts.index') }}">Parts &amp; accessories</a></li>
                <li><a href="{{ route('build') }}">Build your setup</a></li>
            </ul>
        </div>
        <div>
            <h2>Contact</h2>
            <ul>
                <li><a href="{{ $wa }}" target="_blank" rel="noopener">WhatsApp {{ config('mazz.phone_display') }}</a></li>
                <li><a href="tel:{{ config('mazz.phone_tel') }}">Call {{ config('mazz.phone_display') }}</a></li>
                <li><a href="mailto:{{ config('mazz.email') }}">{{ config('mazz.email') }}</a></li>
            </ul>
        </div>
        <div>
            <h2>Visit</h2>
            <address>{{ config('mazz.address') }}</address>
            <a class="text-link" href="{{ route('contact') }}">Get directions <x-icon name="arrow" /></a>
        </div>
    </div>
    <div class="wrap site-footer__bottom">
        <span>&copy; {{ date('Y') }} {{ config('mazz.name') }}</span>
        <span>Reg. No. {{ config('mazz.registration') }}</span>
    </div>
</footer>
