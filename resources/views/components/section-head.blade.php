@props(['eyebrow' => null, 'title' => '', 'text' => null, 'align' => 'center'])

<div class="section-head {{ $align === 'left' ? 'section-head--left' : '' }}" data-reveal>
    @if ($eyebrow)
        <span class="eyebrow">{{ $eyebrow }}</span>
    @endif
    <h2>{{ $title }}</h2>
    @if ($text)
        <p>{{ $text }}</p>
    @endif
    {{ $slot }}
</div>
