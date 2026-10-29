{{--
    Listing sidebar (laptops and parts). Lives inside the page's filter <form>.
    $route: listing route name. $list: ['title', 'key', 'all', 'items' (value => count), 'current', 'total'].
    $groups: key => ['label', 'options' (value => count), 'selected']. $price: ShopFilter::price() or null.
    $stockToggle: show the "In stock only" box. $results: number of matching items.
--}}
@php
    $listUrl = fn ($value) => route($route, array_filter(array_merge(request()->except([$list['key'], 'page']), [$list['key'] => $value]), fn ($v) => $v !== null && $v !== ''));
    $fmt = fn ($n) => 'Rs. '.number_format($n);
@endphp

<aside class="shop__side" id="shop-filters" aria-label="Filters" data-shop-side>
    <div class="shop__side-head">
        <h2>Filters</h2>
        <button type="button" class="shop__close" data-shop-close aria-label="Close filters"><x-icon name="close" /></button>
    </div>

    <div class="shop__side-body">
        <section class="side-block">
            <h3 class="side-block__title">{{ $list['title'] }}</h3>
            <ul class="side-list">
                <li><a href="{{ $listUrl(null) }}" @class(['is-active' => $list['current'] === ''])>{{ $list['all'] }} <span>{{ $list['total'] }}</span></a></li>
                @foreach ($list['items'] as $value => $count)
                    <li><a href="{{ $listUrl($value) }}" @class(['is-active' => $list['current'] === (string) $value])>{{ $value }} <span>{{ $count }}</span></a></li>
                @endforeach
            </ul>
        </section>

        @if ($stockToggle ?? false)
            <section class="side-block">
                <h3 class="side-block__title">Availability</h3>
                <label class="check">
                    <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock')) data-shop-auto>
                    <span class="check__box"><x-icon name="check" stroke-width="3" /></span>
                    <span class="check__label">In stock only</span>
                </label>
            </section>
        @endif

        @foreach ($groups as $key => $group)
            @continue($group['options']->count() < 2 && ! $group['selected'])
            <details class="side-block side-group" open>
                <summary class="side-block__title">{{ $group['label'] }} <x-icon name="chevron-down" /></summary>
                <div @class(['side-group__opts', 'is-long' => $group['options']->count() > 6]) data-more-wrap>
                    @foreach ($group['options'] as $value => $count)
                        <label class="check">
                            <input type="checkbox" name="{{ $key }}[]" value="{{ $value }}" @checked(in_array((string) $value, $group['selected'], true)) data-shop-auto>
                            <span class="check__box"><x-icon name="check" stroke-width="3" /></span>
                            <span class="check__label">{{ $value }}</span>
                            <span class="check__count">{{ $count }}</span>
                        </label>
                    @endforeach
                    @if ($group['options']->count() > 6)
                        <button type="button" class="side-group__more" data-more>Show more <x-icon name="chevron-down" /></button>
                    @endif
                </div>
            </details>
        @endforeach

        @if ($price)
            <details class="side-block side-group" open>
                <summary class="side-block__title">Price <x-icon name="chevron-down" /></summary>
                <div class="price-range" data-price
                     style="--from: {{ ($price['from'] - $price['min']) / ($price['max'] - $price['min']) * 100 }}%; --to: {{ ($price['to'] - $price['min']) / ($price['max'] - $price['min']) * 100 }}%">
                    <div class="price-range__track"><span></span></div>
                    <input type="range" name="price_min" min="{{ $price['min'] }}" max="{{ $price['max'] }}" step="{{ $price['step'] }}" value="{{ $price['from'] }}" aria-label="Lowest price" data-price-min>
                    <input type="range" name="price_max" min="{{ $price['min'] }}" max="{{ $price['max'] }}" step="{{ $price['step'] }}" value="{{ $price['to'] }}" aria-label="Highest price" data-price-max>
                    <p class="price-range__out"><span data-price-from>{{ $fmt($price['from']) }}</span> <span aria-hidden="true">–</span> <span data-price-to>{{ $fmt($price['to']) }}</span></p>
                </div>
            </details>
        @endif
    </div>

    <div class="shop__side-foot">
        <button type="submit" class="btn btn--dark shop__apply">Show {{ $results }} {{ Str::plural('result', $results) }}</button>
        <a class="shop__reset" href="{{ route($route) }}"><x-icon name="refresh" /> Reset all</a>
    </div>
</aside>
<div class="shop__scrim" data-shop-close aria-hidden="true"></div>
