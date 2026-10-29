@extends('layouts.app', ['title' => 'Laptops', 'description' => 'Refurbished laptops in stock at Mazz Laptop Hub, Negombo. Real photos, full specs and prices.'])

@section('content')
    @php
        $shop = [
            'route' => 'laptops.index',
            'list' => ['title' => 'Brands', 'key' => 'brand', 'all' => 'All laptops', 'items' => $brands, 'current' => $brand, 'total' => $total],
            'groups' => $groups,
            'price' => $price,
            'results' => $laptops->total(),
        ];
    @endphp

    @include('partials.page-head', [
        'crumbs' => ['Laptops' => null],
        'title' => 'Laptops',
        'text' => 'Business-grade laptops, ready today. Reserved ones are on hold for a customer, but ask us: plans change.',
    ])

    <section class="section section--flush">
        <form class="wrap shop" method="get" action="{{ route('laptops.index') }}" data-shop>
            @if ($brand !== '')<input type="hidden" name="brand" value="{{ $brand }}">@endif

            @include('partials.shop-side', $shop)

            <div class="shop__main">
                @include('partials.shop-bar', $shop + ['noun' => 'laptop', 'searchLabel' => 'Search model or processor', 'defaultSort' => 'Newest first'])

                @if ($laptops->isNotEmpty())
                    <div class="grid shop__grid">
                        @foreach ($laptops as $laptop)
                            <x-laptop-card :laptop="$laptop" />
                        @endforeach
                    </div>
                    {{ $laptops->links('partials.pagination') }}
                @else
                    <div class="empty">
                        <x-icon name="search" width="36" height="36" />
                        @if ($total)
                            <h3>Nothing matches those filters.</h3>
                            <p>Try fewer filters, or tell us what you're after and we'll look out for it.</p>
                            <a class="btn btn--dark" href="{{ route('laptops.index') }}">Show all laptops</a>
                        @else
                            <h3>New stock is being prepared.</h3>
                            <p>Message us what you need and we'll tell you when it's ready.</p>
                            <a class="btn btn--dark" href="https://wa.me/{{ config('mazz.whatsapp') }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Ask on WhatsApp</a>
                        @endif
                    </div>
                @endif
            </div>
        </form>
    </section>
@endsection
