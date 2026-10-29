@extends('layouts.app', ['title' => 'Page not found'])

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Page not found' => null],
        'title' => 'Page not found',
        'text' => 'This page has moved, or the laptop or part has already been sold.',
    ])

    <section class="section section--flush">
        <div class="wrap">
            <div class="empty">
                <x-icon name="search" width="36" height="36" />
                <h3>Let's find you something similar</h3>
                <p>Have a look at what's in stock today, or message us and we'll help you find the right one.</p>
                <div class="error-actions">
                    <a class="btn btn--dark" href="{{ route('laptops.index') }}">Browse laptops <x-icon name="arrow" /></a>
                    <a class="act act--wa" href="https://wa.me/{{ config('mazz.whatsapp') }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Ask on WhatsApp</a>
                </div>
            </div>
        </div>
    </section>
@endsection
