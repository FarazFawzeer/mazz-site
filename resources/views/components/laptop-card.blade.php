@props(['laptop'])
<article @class(['product-card', 'is-sold' => $laptop->is_sold])>
    <a class="product-card__link" href="{{ $laptop->url }}">
        <div @class(['product-card__media', 'img-missing' => ! $laptop->photo])>
            @if ($laptop->photo)
                <img src="{{ $laptop->photo }}" alt="{{ $laptop->name }}" loading="lazy" decoding="async" width="600" height="450">
            @endif
            @if ($laptop->is_sold)
                <span class="badge badge--dark">Sold</span>
            @elseif ($laptop->is_reserved)
                <span class="badge badge--orange">Reserved</span>
            @endif
        </div>
        <div class="product-card__body">
            <div class="product-card__head">
                <h3 class="product-card__title">{{ $laptop->name }}</h3>
                @if ($laptop->status === 'in_stock')
                    <span class="product-card__ok" title="In stock"><x-icon name="check" stroke-width="2.4" /><span class="visually-hidden">In stock</span></span>
                @endif
            </div>
            @if ($laptop->spec_line)
                <p class="product-card__specs">{{ implode(' · ', $laptop->spec_line) }}</p>
            @endif
            <div class="product-card__foot">
                <span @class(['product-card__price', 'is-ask' => ! $laptop->has_price && ! $laptop->is_sold])>{{ $laptop->is_sold ? 'Sold' : $laptop->price_label }}</span>
                <span class="product-card__cta" aria-hidden="true"><span>View</span> <x-icon name="arrow" stroke-width="2" /></span>
            </div>
        </div>
    </a>
</article>
