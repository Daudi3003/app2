@props(['light' => false, 'size' => '', 'href' => null])

@php
    $classes = 'brand'
        .($light ? ' brand--light' : '')
        .($size ? ' brand--'.$size : '');
@endphp

<a href="{{ $href ?? route('home') }}" {{ $attributes->merge(['class' => $classes]) }}
   aria-label="{{ config('learnhub.name') }} — home">
    <span class="brand__mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10 12 5 2 10l10 5 10-5z"/>
            <path d="M6 12v5c3 2 9 2 12 0v-5"/>
        </svg>
    </span>
    <span class="brand__text">Learn<span class="accent">Hub</span></span>
</a>
