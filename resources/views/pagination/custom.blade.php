@if ($paginator->hasPages())
@php
    // Show at most 5 page numbers, keeping the current page centred where possible.
    $max = 5;
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $start = max(1, min($current - intdiv($max, 2), $last - $max + 1));
    $end = min($last, $start + $max - 1);
@endphp
<nav class="pager" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="pg disabled" aria-disabled="true">&lsaquo; Previous</span>
    @else
        <a class="pg" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Previous</a>
    @endif

    @for ($page = $start; $page <= $end; $page++)
        @if ($page === $current)
            <span class="pg active" aria-current="page">{{ $page }}</span>
        @else
            <a class="pg" href="{{ $paginator->url($page) }}">{{ $page }}</a>
        @endif
    @endfor

    @if ($paginator->hasMorePages())
        <a class="pg" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &rsaquo;</a>
    @else
        <span class="pg disabled" aria-disabled="true">Next &rsaquo;</span>
    @endif
</nav>
@endif
