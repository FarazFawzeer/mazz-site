{{--
    Product gallery: main photo with arrows, counter and fullscreen view; thumbnails below; swipe on phones.
    $photos: list of URLs, $alt: product name, $square: true for parts (photos on white), $badge: optional HTML,
--}}
@php $count = count($photos); @endphp
<div @class(['gallery', 'gallery--square' => $square ?? false]) data-gallery>
    <div @class(['gallery__main', 'img-missing' => ! $count])>
        @if ($count)
            <img src="{{ $photos[0] }}" alt="{{ $alt }}" width="1200" height="900" data-gallery-main>
            <button type="button" class="gallery__zoom" data-gallery-zoom aria-label="View photo full screen"><x-icon name="expand" /></button>
            @if ($count > 1)
                <button type="button" class="gallery__nav gallery__nav--prev" data-gallery-step="-1" aria-label="Previous photo"><x-icon name="chevron-left" /></button>
                <button type="button" class="gallery__nav gallery__nav--next" data-gallery-step="1" aria-label="Next photo"><x-icon name="chevron-right" /></button>
                <span class="gallery__count"><span data-gallery-index>1</span> / {{ $count }}</span>
            @endif
        @endif
        {!! $badge ?? '' !!}
    </div>

    @if ($count > 1)
        <div class="gallery__thumbs">
            @foreach ($photos as $i => $src)
                <button type="button" @class(['is-active' => $i === 0]) data-gallery-thumb="{{ $src }}" aria-label="Photo {{ $i + 1 }} of {{ $count }}">
                    <img src="{{ $src }}" alt="" loading="lazy" width="160" height="120">
                </button>
            @endforeach
        </div>
    @endif

    @if ($count)
        <dialog class="lightbox" data-lightbox aria-label="Photos of {{ $alt }}">
            <button type="button" class="lightbox__close" data-lightbox-close aria-label="Close"><x-icon name="close" /></button>
            <img src="{{ $photos[0] }}" alt="{{ $alt }}" data-lightbox-img>
            @if ($count > 1)
                <button type="button" class="lightbox__nav lightbox__nav--prev" data-gallery-step="-1" aria-label="Previous photo"><x-icon name="chevron-left" /></button>
                <button type="button" class="lightbox__nav lightbox__nav--next" data-gallery-step="1" aria-label="Next photo"><x-icon name="chevron-right" /></button>
                <span class="lightbox__count"><span data-gallery-index>1</span> / {{ $count }}</span>
            @endif
        </dialog>
    @endif
</div>
