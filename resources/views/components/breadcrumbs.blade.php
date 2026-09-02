@props(['items' => [], 'light' => false])

<nav aria-label="Breadcrumb">
    <ol class="breadcrumbs {{ $light ? 'breadcrumbs--light' : '' }}">
        @foreach ($items as $label => $url)
            <li>
                @if ($url && ! $loop->last)
                    <a href="{{ $url }}">{{ $label }}</a>
                @else
                    <span aria-current="page">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
