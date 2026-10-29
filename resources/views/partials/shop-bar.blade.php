{{--
    Listing top bar: result count, search, mobile "Filters" button, sort; then removable chips for active filters.
    Same variables as partials.shop-side, plus $noun ('laptop' / 'item'), $searchLabel, $defaultSort.
--}}
@php
    $query = request()->except('page');
    $without = function (string $key, ?string $value = null) use ($query, $route) {
        if ($value !== null && is_array($query[$key] ?? null)) {
            $query[$key] = array_values(array_diff($query[$key], [$value]));
        } else {
            unset($query[$key]);
        }
        if ($key === 'price_min' || $key === 'price_max') {
            unset($query['price_min'], $query['price_max']);
        }
        return route($route, $query);
    };

    $chips = [];
    if ($list['current'] !== '') $chips[] = [$list['current'], $without($list['key'])];
    foreach ($groups as $key => $group) {
        foreach ($group['selected'] as $value) $chips[] = [$value, $without($key, $value)];
    }
    if (request()->boolean('in_stock')) $chips[] = ['In stock', $without('in_stock')];
    if ($price && $price['active']) $chips[] = ['Rs. '.number_format($price['from']).' – '.number_format($price['to']), $without('price_min')];
    if (request('q')) $chips[] = ['“'.request('q').'”', $without('q')];
@endphp

<div class="shop__bar">
    <p class="shop__count"><b>{{ $results }}</b> {{ Str::plural($noun, $results) }} found</p>

    <label class="shop__search">
        <x-icon name="search" />
        <span class="visually-hidden">{{ $searchLabel }}</span>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $searchLabel }}">
    </label>

    <button type="button" class="shop__filters-btn" data-shop-open aria-controls="shop-filters" aria-expanded="false">
        <x-icon name="sliders" /> Filters @if (count($chips))<span>{{ count($chips) }}</span>@endif
    </button>

    <label class="shop__sort">
        <span>Sort by</span>
        <select name="sort" data-autosubmit>
            <option value="">{{ $defaultSort }}</option>
            <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: low to high</option>
            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: high to low</option>
        </select>
        <x-icon name="chevron-down" />
    </label>
</div>

@if ($chips)
    <div class="shop__chips">
        @foreach ($chips as [$label, $url])
            <a class="shop-chip" href="{{ $url }}">{{ $label }} <x-icon name="close" /><span class="visually-hidden">Remove</span></a>
        @endforeach
        <a class="shop__clear" href="{{ route($route) }}">Clear all</a>
    </div>
@endif
