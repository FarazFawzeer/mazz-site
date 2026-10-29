@extends('layouts.app', ['title' => 'Contact', 'description' => 'Visit Mazz Laptop Hub at '.config('mazz.address').'. WhatsApp or call '.config('mazz.phone_display').'.'])

@section('content')
    @php
        $wa = 'https://wa.me/'.config('mazz.whatsapp');
        $directions = 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode(config('mazz.map_query'));
    @endphp

    @include('partials.page-head', [
        'crumbs' => ['Contact' => null],
        'title' => 'Contact Us',
        'text' => 'The quickest way to reach us is WhatsApp. Need a part? Send a photo of your laptop\'s model sticker and we\'ll tell you what fits.',
    ])

    <section class="section section--flush">
        <div class="wrap contact">
            <div class="contact__info">
                <p class="eyebrow">Get in touch</p>
                <h2 class="contact__title">Talk to a real person</h2>
                <p class="contact__lede">Ask about stock, prices or upgrades, or come in and try a laptop before you buy.</p>

                <div class="contact__actions">
                    <a class="act act--wa" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Chat on WhatsApp</a>
                    <a class="act" href="tel:{{ config('mazz.phone_tel') }}"><x-icon name="phone" /> Call us</a>
                </div>

                <ul class="contact-list">
                    <li>
                        <a href="tel:{{ config('mazz.phone_tel') }}">
                            <span class="contact-list__icon"><x-icon name="phone" /></span>
                            <span class="contact-list__text"><small>Phone &amp; WhatsApp</small><b>{{ config('mazz.phone_display') }}</b></span>
                            <x-icon name="arrow" class="contact-list__go" />
                        </a>
                    </li>
                    <li>
                        <a href="mailto:{{ config('mazz.email') }}">
                            <span class="contact-list__icon"><x-icon name="mail" /></span>
                            <span class="contact-list__text"><small>Email</small><b>{{ config('mazz.email') }}</b></span>
                            <x-icon name="arrow" class="contact-list__go" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ $directions }}" target="_blank" rel="noopener">
                            <span class="contact-list__icon"><x-icon name="pin" /></span>
                            <span class="contact-list__text"><small>Shop</small><b>{{ config('mazz.address') }}</b></span>
                            <x-icon name="arrow" class="contact-list__go" />
                        </a>
                    </li>
                </ul>

                <p class="contact__reg">Registered business · Reg. No. {{ config('mazz.registration') }}</p>
            </div>

            <div class="contact__map">
                <iframe title="Map to {{ config('mazz.name') }}" src="https://www.google.com/maps?q={{ rawurlencode(config('mazz.map_query')) }}&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <div class="contact__map-bar">
                    <span class="contact-list__icon"><x-icon name="store" /></span>
                    <span class="contact-list__text"><small>Visit the shop</small><b>Periyamulla, Negombo</b></span>
                    <a class="btn btn--dark" href="{{ $directions }}" target="_blank" rel="noopener">Get directions <x-icon name="arrow" /></a>
                </div>
            </div>
        </div>
    </section>
@endsection
