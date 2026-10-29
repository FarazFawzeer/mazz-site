@extends('layouts.app', [
    'title' => $laptop->name,
    'description' => $laptop->name.' – '.implode(', ', $laptop->spec_line).'. '.$laptop->price_label.' at Mazz Laptop Hub, Negombo.',
    'ogImage' => $laptop->photo,
    'bodyClass' => 'page-detail',
])

@section('content')
    @php
        $wa = 'https://wa.me/'.config('mazz.whatsapp').'?text='.rawurlencode("Hi Mazz Laptop Hub, is the {$laptop->name} still available?\n".$laptop->url);
        $specs = $laptop->main_specs + $laptop->spec_list;
        $badge = $laptop->is_sold ? '<span class="badge badge--dark">Sold</span>' : ($laptop->is_reserved ? '<span class="badge badge--orange">Reserved</span>' : '');
        $priceText = $laptop->is_sold ? 'Sold' : $laptop->price_label;
    @endphp

    <div class="wrap">
        <nav class="crumbs crumbs--page" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><a href="{{ route('laptops.index') }}">Laptops</a>@if ($laptop->brand)<span aria-hidden="true">/</span><a href="{{ route('laptops.index', ['brand' => $laptop->brand]) }}">{{ $laptop->brand }}</a>@endif<span aria-hidden="true">/</span><span aria-current="page">{{ $laptop->name }}</span>
        </nav>
    </div>

    <section class="wrap detail">
        @include('partials.gallery', ['photos' => $laptop->photos, 'alt' => $laptop->name, 'badge' => $badge])

        <div class="buybox">
            <p class="kicker">{{ $laptop->brand ?: 'Laptop' }}{{ $laptop->generation ? ' · '.$laptop->generation : '' }}</p>
            <h1 class="buybox__title">{{ $laptop->name }}</h1>

            <div class="buybox__status">
                @if ($laptop->is_sold)
                    <span class="pill pill--muted">Sold</span>
                @elseif ($laptop->is_reserved)
                    <span class="pill pill--orange">Reserved</span>
                @else
                    <span class="pill pill--ok">In stock</span>
                    <span class="buybox__ready">At our Negombo shop</span>
                @endif
            </div>

            <p @class(['buybox__price', 'is-ask' => ! $laptop->has_price && ! $laptop->is_sold])>{{ $priceText }}</p>
            @if ($laptop->is_reserved)
                <p class="buybox__note">On hold for a customer right now. Message us and we'll tell you if it comes free.</p>
            @elseif ($laptop->is_sold)
                <p class="buybox__note">This one has gone. Ask us for something similar.</p>
            @elseif (! $laptop->has_price)
                <p class="buybox__note">Message us for today's price.</p>
            @endif

            <div class="detail__actions">
                <a class="act act--wa" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> {{ $laptop->is_sold ? 'Ask for a similar one' : 'Ask on WhatsApp' }}</a>
                <a class="act" href="tel:{{ config('mazz.phone_tel') }}"><x-icon name="phone" /> Call us</a>
            </div>
            <div class="detail__links">
                @if ($laptop->status === 'in_stock')
                    <a class="text-link" href="{{ route('build') }}#add=l{{ $laptop->id }}"><x-icon name="plus" /> Add to a setup and see the total</a>
                @endif
                <button class="text-link" type="button" data-share-page><x-icon name="share" /> Share</button>
            </div>

            @if ($specs)
                <div class="specs">
                    <h2 class="specs__title">Specifications</h2>
                    <dl>
                        @foreach ($specs as $label => $value)
                            <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            @endif

            <ul class="assure">
                <li><x-icon name="wrench" /> Opened, cleaned and tested before listing</li>
                <li><x-icon name="store" /> Try it at our shop in Negombo</li>
            </ul>
        </div>
    </section>

    @if ($similar->isNotEmpty())
        <section class="section">
            <div class="wrap">
                <div class="section-head">
                    <h2 class="display display--md">You might also like</h2>
                    <a class="text-link" href="{{ route('laptops.index') }}">All laptops <x-icon name="arrow" /></a>
                </div>
                <div class="grid grid--4">
                    @foreach ($similar as $item)
                        <x-laptop-card :laptop="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- phones: price and WhatsApp pinned to the bottom --}}
    <div class="detail-bar">
        <div><small>{{ $laptop->name }}</small><b @class(['is-ask' => ! $laptop->has_price && ! $laptop->is_sold])>{{ $priceText }}</b></div>
        <a class="btn btn--wa btn--sm" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Ask</a>
    </div>
@endsection
