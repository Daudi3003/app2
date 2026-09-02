@props(['score' => 0, 'count' => null, 'showScore' => true])

@php $rounded = (int) round((float) $score); @endphp

<span {{ $attributes->merge(['class' => 'rating']) }}>
    @if ($showScore)
        <span class="rating__score">{{ number_format((float) $score, 1) }}</span>
    @endif

    <span class="rating__stars" role="img"
          aria-label="{{ number_format((float) $score, 1) }} out of 5 stars">
        @for ($i = 1; $i <= 5; $i++)
            <svg viewBox="0 0 24 24" width="15" height="15"
                 fill="{{ $i <= $rounded ? 'currentColor' : 'none' }}"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        @endfor
    </span>

    @isset($count)
        <span class="rating__count">({{ number_format($count) }})</span>
    @endisset
</span>
