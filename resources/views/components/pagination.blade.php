@props(['current' => 1, 'last' => 4])

{{--
    Static pagination UI for the frontend phase.
    Phase 2: replace the whole component with {{ $courses->links() }} — the
    .pagination CSS classes already match Laravel's pagination markup closely,
    or publish the paginator views and reuse these classes verbatim.
--}}

<nav class="pagination" aria-label="Pagination">
    <a href="#" class="pagination__link {{ $current <= 1 ? 'is-disabled' : '' }}" aria-label="Previous page">
        <x-icon name="chevron-left" :size="16" />
    </a>

    @for ($page = 1; $page <= $last; $page++)
        <a href="#" class="pagination__link {{ $page === $current ? 'is-active' : '' }}"
           @if ($page === $current) aria-current="page" @endif>{{ $page }}</a>
    @endfor

    @if ($last > 4)
        <span class="pagination__gap">…</span>
        <a href="#" class="pagination__link">{{ $last + 4 }}</a>
    @endif

    <a href="#" class="pagination__link" aria-label="Next page">
        <x-icon name="chevron-right" :size="16" />
    </a>
</nav>
