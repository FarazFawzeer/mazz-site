<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('mazz.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Refurbished business laptops and laptop parts in Negombo. Real photos, full specs and prices. WhatsApp '.config('mazz.phone_display').'.' }}">
    <meta name="theme-color" content="#0a0b0e">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <meta property="og:site_name" content="{{ config('mazz.name') }}">
    <meta property="og:title" content="{{ $title ?? config('mazz.name') }}">
    @isset($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endisset

    <script>document.documentElement.classList.add('js');</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300..800;1,400&display=swap">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    @stack('head')
</head>
<body class="{{ $bodyClass ?? '' }}">
    <a class="skip-link" href="#main">Skip to content</a>

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <a class="wa-float" href="https://wa.me/{{ config('mazz.whatsapp') }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <x-icon name="whatsapp" />
    </a>

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
