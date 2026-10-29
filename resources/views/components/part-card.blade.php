@props(['part'])
<article @class(['product-card', 'product-card--part', 'is-out' => ! $part->in_stock])>
    <a class="product-card__link" href="{{ $part->url }}">
        <div @class(['product-card__media', 'img-missing' => ! $part->photo])>
            @if ($part->photo)
                <img src="{{ $part->photo }}" alt="{{ $part->name }}" loading="lazy" decoding="async" width="480" height="480">
            @endif
            @unless ($part->in_stock)<span class="badge">Out of stock</span>@endunless
        </div>
        <div class="product-card__body">
            <div class="product-card__head">
                <h3 class="product-card__title">{{ $part->name }}</h3>
                @if ($part->in_stock)
                    <span class="product-card__ok" title="In stock"><x-icon name="check" stroke-width="2.4" /><span class="visually-hidden">In stock</span></span>
                @endif
            </div>
            <p class="product-card__specs">{{ $part->category }}{{ $part->brand ? ' · '.$part->brand : '' }}</p>
            <div class="product-card__foot">
                <span @class(['product-card__price', 'is-ask' => ! $part->has_price])>{{ $part->price_label }}</span>
                <span class="product-card__cta" aria-hidden="true"><span>View</span> <x-icon name="arrow" stroke-width="2" /></span>
            </div>
        </div>
    </a>
</article>
