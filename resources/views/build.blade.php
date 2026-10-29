@extends('layouts.app', ['title' => 'Build your setup', 'description' => 'Pick a laptop and parts, see the total price straight away, and send the list to Mazz Laptop Hub on WhatsApp.', 'bodyClass' => 'page-build'])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Build your setup' => null],
        'title' => 'Build Your Setup',
        'text' => 'Pick a laptop and the parts you want, see the total straight away, then send the list to us on WhatsApp. We\'ll confirm prices and stock.',
    ])

    <section class="section section--flush">
        <div class="wrap builder" data-builder data-wa="{{ config('mazz.whatsapp') }}">
            <div class="builder__main">
                <div class="builder__top">
                    <div>
                        <h2 class="builder__title">Choose your items</h2>
                        <p class="builder__sub">One per row. Skip anything you don't need.</p>
                    </div>
                    <div class="progress" aria-live="polite">
                        <span class="progress__text"><b data-progress-count>0</b> of {{ count($slots) }} chosen</span>
                        <span class="progress__bar"><i data-progress-bar></i></span>
                    </div>
                </div>

                <ol class="slots">
                    @foreach ($slots as $slot)
                        @php $n = count($slot['items']); @endphp
                        <li @class(['slot', 'is-empty' => ! $n]) data-slot="{{ $slot['id'] }}">
                            <span class="slot__icon" aria-hidden="true">
                                <x-icon :name="$slot['icon']" stroke-width="1.5" />
                                <span class="slot__tick"><x-icon name="check" stroke-width="3" /></span>
                            </span>
                            <div class="slot__label">
                                <h3>{{ $slot['label'] }}</h3>
                                <p>
                                    @if ($slot['hint']){{ $slot['hint'] }}
                                    @elseif ($n){{ $n }} {{ Str::plural('option', $n) }}@if ($slot['from']) · from Rs. {{ number_format($slot['from']) }}@endif
                                    @else Out of stock right now
                                    @endif
                                </p>
                            </div>
                            <div class="slot__choice" data-slot-choice>
                                <button class="slot__add" type="button" data-open="{{ $slot['id'] }}" @disabled(! $n)>
                                    <x-icon name="plus" /> {{ $n ? 'Choose' : 'Unavailable' }}
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <aside class="summary" aria-labelledby="summary-title">
                <div class="summary__card">
                    <div class="summary__head">
                        <h2 id="summary-title">Your setup</h2>
                        <span class="summary__count" data-count>0 items</span>
                    </div>

                    <ul class="summary__rows" data-summary-rows></ul>
                    <div class="summary__empty" data-summary-empty>
                        <span class="summary__empty-icon"><x-icon name="layers" /></span>
                        <p>Your list is empty.<br>Choose a laptop or any part to start.</p>
                    </div>

                    <div class="summary__total">
                        <span>Estimated total</span>
                        <b data-total>Rs. 0</b>
                    </div>
                    <p class="summary__note" data-ask-note hidden>Some items aren't priced online. We'll quote them on WhatsApp.</p>

                    <a class="btn btn--wa btn--block btn--lg" href="https://wa.me/{{ config('mazz.whatsapp') }}" target="_blank" rel="noopener" data-send aria-disabled="true">
                        <x-icon name="whatsapp" /> Send on WhatsApp
                    </a>
                    <div class="summary__links" data-summary-links hidden>
                        <button type="button" data-share><x-icon name="link" /> <span>Copy link</span></button>
                        <button type="button" data-clear><x-icon name="trash" /> <span>Clear all</span></button>
                    </div>

                    <ul class="summary__trust">
                        <li><x-icon name="shield" /> Prices confirmed before you pay</li>
                        <li><x-icon name="store" /> Try everything at our Negombo shop</li>
                    </ul>
                </div>
            </aside>

            {{-- phones and tablets: total bar pinned to the bottom --}}
            <div class="buildbar" data-buildbar hidden>
                <div><span data-count>0 items</span><b data-total>Rs. 0</b></div>
                <a class="btn btn--wa btn--sm" href="#summary-title" data-bar-review>Review</a>
            </div>
        </div>
    </section>

    {{-- product picker: slides in from the right (bottom sheet on phones) --}}
    <dialog class="picker" data-picker aria-labelledby="picker-title">
        <div class="picker__head">
            <div>
                <p class="picker__kicker">Choose a</p>
                <h2 id="picker-title" data-picker-title></h2>
            </div>
            <button class="picker__close" type="button" data-picker-close aria-label="Close"><x-icon name="close" /></button>
        </div>
        <div class="picker__tools">
            <label class="picker__search">
                <x-icon name="search" />
                <span class="visually-hidden">Search</span>
                <input type="search" placeholder="Search" data-picker-search>
            </label>
            <label class="picker__sort">
                <span class="visually-hidden">Sort</span>
                <select data-picker-sort>
                    <option value="">Recommended</option>
                    <option value="asc">Price: low to high</option>
                    <option value="desc">Price: high to low</option>
                </select>
                <x-icon name="chevron-down" />
            </label>
        </div>
        <ul class="picker__list" data-picker-list></ul>
    </dialog>

    <div class="toast" data-toast role="status" aria-live="polite"></div>

    <script type="application/json" id="build-data">@json($slots)</script>
@endsection

@push('scripts')
    <script src="{{ asset('js/build.js') }}?v={{ filemtime(public_path('js/build.js')) }}" defer></script>
@endpush
