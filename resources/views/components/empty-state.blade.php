@props([
    'emoji' => '📚',
    'title' => 'Nothing here yet',
    'text'  => null,
    'action' => null,
    'actionUrl' => null,
])

<div {{ $attributes->merge(['class' => 'empty']) }}>
    <div class="empty__icon" aria-hidden="true">{{ $emoji }}</div>
    <h3>{{ $title }}</h3>
    @if ($text)
        <p>{{ $text }}</p>
    @endif

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif ($action && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn--primary">{{ $action }}</a>
    @endif
</div>
