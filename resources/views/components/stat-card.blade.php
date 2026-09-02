@props(['label' => '', 'value' => '', 'emoji' => '📊', 'tone' => '', 'delta' => null, 'direction' => ''])

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <span class="stat-card__icon {{ $tone ? 'stat-card__icon--'.$tone : '' }}" aria-hidden="true">
        {{ $emoji }}
    </span>

    <div class="stat-card__body">
        <div class="stat-card__label">{{ $label }}</div>
        <div class="stat-card__value">{{ $value }}</div>

        @if ($delta)
            <div class="stat-card__delta {{ $direction ? 'stat-card__delta--'.$direction : '' }}">
                @if ($direction === 'up')
                    <x-icon name="trending-up" :size="14" />
                @elseif ($direction === 'down')
                    <x-icon name="trending-down" :size="14" />
                @endif
                {{ $delta }}
            </div>
        @endif
    </div>
</div>
