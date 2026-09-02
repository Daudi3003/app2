@props(['type' => 'info', 'title' => null, 'dismissible' => true])

@php
    $icon = match ($type) {
        'success' => 'check-circle',
        'warning' => 'alert',
        'danger'  => 'x',
        default   => 'info',
    };
@endphp

<div {{ $attributes->merge(['class' => 'alert alert--'.$type]) }} role="alert">
    <span class="alert__icon"><x-icon :name="$icon" :size="19" /></span>

    <div class="alert__body">
        @if ($title)
            <div class="alert__title">{{ $title }}</div>
        @endif
        {{ $slot }}
    </div>

    @if ($dismissible)
        <button type="button" class="alert__close" data-alert-close aria-label="Dismiss message">
            <x-icon name="x" :size="16" />
        </button>
    @endif
</div>
