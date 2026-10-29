@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $pages = collect(range(max(1, $current - 2), min($last, $current + 2)));
    @endphp
    <nav class="pager" aria-label="Pages">
        @if ($paginator->onFirstPage())
            <span class="pager__btn pager__btn--wide is-disabled">Prev</span>
        @else
            <a class="pager__btn pager__btn--wide" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a>
        @endif

        @if ($pages->first() > 1)
            <a class="pager__btn" href="{{ $paginator->url(1) }}">1</a>
            @if ($pages->first() > 2)<span class="pager__gap">…</span>@endif
        @endif
        @foreach ($pages as $page)
            @if ($page === $current)
                <span class="pager__btn is-current" aria-current="page">{{ $page }}</span>
            @else
                <a class="pager__btn" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif
        @endforeach
        @if ($pages->last() < $last)
            @if ($pages->last() < $last - 1)<span class="pager__gap">…</span>@endif
            <a class="pager__btn" href="{{ $paginator->url($last) }}">{{ $last }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a class="pager__btn pager__btn--wide" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
        @else
            <span class="pager__btn pager__btn--wide is-disabled">Next</span>
        @endif
    </nav>
@endif
