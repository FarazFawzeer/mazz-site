{{-- Slim dark title banner that continues the dark header. $crumbs: [label => url|null], $title, $mute (optional second part), $text --}}
<section class="page-head">
    <div class="wrap">
        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            @foreach ($crumbs as $label => $url)
                <span aria-hidden="true">/</span>
                @if ($url)<a href="{{ $url }}">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif
            @endforeach
        </nav>
        <h1 class="page-head__title">{{ $title }}@isset($mute) <span class="mute">{{ $mute }}</span>@endisset</h1>
        @isset($text)<p class="page-head__text">{{ $text }}</p>@endisset
    </div>
</section>
