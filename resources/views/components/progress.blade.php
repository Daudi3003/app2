@props(['value' => 0, 'label' => null, 'size' => '', 'tone' => ''])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <div class="progress-meta">
            <span>{{ $label }}</span>
            <strong>{{ (int) $value }}%</strong>
        </div>
    @endisset

    <div class="progress {{ $size ? 'progress--'.$size : '' }}"
         role="progressbar" aria-valuenow="{{ (int) $value }}"
         aria-valuemin="0" aria-valuemax="100"
         aria-label="{{ $label ?? 'Progress' }}">
        <div class="progress__bar {{ $tone ? 'progress__bar--'.$tone : '' }}"
             data-progress="{{ (int) $value }}"></div>
    </div>
</div>
