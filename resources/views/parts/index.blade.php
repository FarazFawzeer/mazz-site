@extends('layouts.app', ['title' => 'Parts & accessories', 'description' => 'RAM, SSDs, chargers, processors, monitors and accessories at Mazz Laptop Hub, Negombo.'])

@section('content')
    @php
        $shop = [
            'route' => 'parts.index',
            'list' => ['title' => 'Categories', 'key' => 'category', 'all' => 'All parts', 'items' => $categories, 'current' => $category, 'total' => $total],
            'groups' => $groups,
            'price' => $price,
            'stockToggle' => true,
            'results' => $parts->total(),
        ];
    @endphp

    @include('partials.page-head', [
        'crumbs' => $category !== '' ? ['Parts' => route('parts.index'), $category => null] : ['Parts' => null],
        'title' => $category !== '' ? $category : 'Parts & Accessories',
        'text' => 'Upgrades and spares for your laptop or desk. Not sure what fits? Send us your model on WhatsApp.',
    ])

    <section class="section section--flush">
        <form class="wrap shop" method="get" action="{{ route('parts.index') }}" data-shop>
            @if ($category !== '')<input type="hidden" name="category" value="{{ $category }}">@endif

            @include('partials.shop-side', $shop)

            <div class="shop__main">
                @include('partials.shop-bar', $shop + ['noun' => 'item', 'searchLabel' => 'Search name, brand or model', 'defaultSort' => 'In stock first'])

                @if ($parts->isNotEmpty())
                    <div class="grid shop__grid">
                        @foreach ($parts as $part)
                            <x-part-card :part="$part" />
                        @endforeach
                    </div>
                    {{ $parts->links('partials.pagination') }}
                @else
                    <div class="empty">
                        <x-icon name="search" width="36" height="36" />
                        <h3>Nothing here right now.</h3>
                        <p>We may still be able to get it. Ask us on WhatsApp.</p>
                        <a class="btn btn--dark" href="{{ route('parts.index') }}">Show all parts</a>
                    </div>
                @endif
            </div>
        </form>
    </section>
@endsection
