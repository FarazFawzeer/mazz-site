@php
    $nav = [
        ['route' => 'home', 'label' => 'Home', 'match' => 'home'],
        ['route' => 'laptops.index', 'label' => 'Laptops', 'match' => 'laptops.*'],
        ['route' => 'parts.index', 'label' => 'Parts', 'match' => 'parts.*'],
        ['route' => 'build', 'label' => 'Build your setup', 'match' => 'build'],
        ['route' => 'contact', 'label' => 'Contact', 'match' => 'contact'],
    ];
@endphp
<header class="site-header" data-header>
    <div class="wrap site-header__inner">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ config('mazz.name') }}, home">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('mazz.name') }}" width="160" height="101">
        </a>

        <nav class="site-nav" id="site-nav" aria-label="Main">
            <ul>
                @foreach ($nav as $item)
                    <li style="--i: {{ $loop->index }}">
                        <a href="{{ route($item['route']) }}" @class(['is-active' => request()->routeIs($item['match'])])
                           @if (request()->routeIs($item['match'])) aria-current="page" @endif>{{ $item['label'] }}<x-icon name="arrow" class="site-nav__go" /></a>
                    </li>
                @endforeach
            </ul>
            {{-- phone menu only: contact block at the bottom --}}
            <div class="site-nav__contact">
                <p class="site-nav__label">Talk to us</p>
                <div class="site-nav__btns">
                    <a class="btn btn--wa" href="https://wa.me/{{ config('mazz.whatsapp') }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> WhatsApp</a>
                    <a class="btn btn--line" href="tel:{{ config('mazz.phone_tel') }}"><x-icon name="phone" /> Call</a>
                </div>
                <a class="site-nav__addr" href="{{ route('contact') }}"><x-icon name="pin" /> <span>{{ config('mazz.address') }}</span></a>
            </div>
        </nav>

        <div class="site-header__tools">
            <a class="site-header__wa" href="https://wa.me/{{ config('mazz.whatsapp') }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><x-icon name="whatsapp" /></a>
            <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false" data-nav-toggle>
                <span class="visually-hidden">Menu</span>
                <span class="nav-toggle__bars" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</header>
