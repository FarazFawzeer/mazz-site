@extends('layouts.app', [
    'title' => $part->name,
    'description' => $part->name.' – '.$part->price_label.' at Mazz Laptop Hub, Negombo.',
    'ogImage' => $part->photo,
    'bodyClass' => 'page-detail',
])

@section('content')
    @php
        $wa = 'https://wa.me/'.config('mazz.whatsapp').'?text='.rawurlencode(($part->in_stock ? "Hi Mazz Laptop Hub, do you have the {$part->name}?" : "Hi Mazz Laptop Hub, when will the {$part->name} be back?")."\n".$part->url);
        $specs = array_filter(['Brand' => $part->brand, 'Model' => $part->model]) + $part->spec_list + array_filter(['Item code' => $part->sku]);
    @endphp

    <div class="wrap">
        <nav class="crumbs crumbs--page" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><a href="{{ route('parts.index') }}">Parts</a><span aria-hidden="true">/</span><a href="{{ route('parts.index', ['category' => $part->category]) }}">{{ $part->category }}</a><span aria-hidden="true">/</span><span aria-current="page">{{ $part->name }}</span>
        </nav>
    </div>

    <section class="wrap detail">
        @include('partials.gallery', [
            'photos' => $part->photos, 'alt' => $part->name, 'square' => true,
            'badge' => $part->in_stock ? '' : '<span class="badge">Out of stock</span>',
        ])

        <div class="buybox">
            <p class="kicker">{{ $part->category }}{{ $part->brand ? ' · '.$part->brand : '' }}</p>
            <h1 class="buybox__title">{{ $part->name }}</h1>

            <div class="buybox__status">
                @if ($part->in_stock)
                    <span class="pill pill--ok">In stock</span>
                    <span class="buybox__ready">At our Negombo shop</span>
                @else
                    <span class="pill pill--muted">Out of stock</span>
                @endif
            </div>

            <p @class(['buybox__price', 'is-ask' => ! $part->has_price])>{{ $part->price_label }}</p>
            @if (! $part->in_stock)
                <p class="buybox__note">We can usually get it again. Message us and we'll let you know.</p>
            @elseif (! $part->has_price)
                <p class="buybox__note">Message us for today's price.</p>
            @endif

            <div class="detail__actions">
                <a class="act act--wa" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> {{ ! $part->in_stock ? 'Ask when it’s back' : 'Ask on WhatsApp' }}</a>
                <a class="act" href="tel:{{ config('mazz.phone_tel') }}"><x-icon name="phone" /> Call us</a>
            </div>
            <div class="detail__links">
                @if ($part->in_stock)
                    <a class="text-link" href="{{ route('build') }}#add=p{{ $part->id }}"><x-icon name="plus" /> Add to a setup and see the total</a>
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
                <li><x-icon name="laptop" /> Not sure it fits? Send us your laptop model</li>
                <li><x-icon name="store" /> Pick it up at our shop in Negombo</li>
            </ul>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section">
            <div class="wrap">
                <div class="section-head">
                    <h2 class="display display--md">More {{ $part->category }}</h2>
                    <a class="text-link" href="{{ route('parts.index', ['category' => $part->category]) }}">View all <x-icon name="arrow" /></a>
                </div>
                <div class="grid grid--4">
                    @foreach ($related as $item)
                        <x-part-card :part="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="detail-bar">
        <div><small>{{ $part->name }}</small><b @class(['is-ask' => ! $part->has_price])>{{ $part->price_label }}</b></div>
        <a class="btn btn--wa btn--sm" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Ask</a>
    </div>
@endsection
