<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', config('learnhub.description'))">
    <meta name="theme-color" content="#6d28d9">

    <title>@yield('title', config('learnhub.tagline')) — {{ config('learnhub.name') }}</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><text y='19' font-size='20'>🎓</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    {{-- Plain CSS3, served straight from public/ — no build step required. --}}
    <link rel="stylesheet" href="{{ asset('css/learnhub/app.css') }}">

    {{--
        Scroll-reveal keeps elements at opacity:0 until JavaScript observes
        them. With JavaScript disabled that would hide most of the page, so
        reveal is switched off entirely in that case.
    --}}
    <noscript>
        <style>
            [data-reveal] { opacity: 1 !important; transform: none !important; }
            .progress__bar[data-progress] { transition: none !important; }
            .chart__bar { transition: none !important; }
        </style>
    </noscript>

    @stack('styles')
</head>
<body class="@yield('body_class')">

    <a class="skip-link" href="#main">Skip to main content</a>

    @yield('body')

    {{-- Shared SVG gradient definitions used by progress rings and charts. --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="lh-ring-grad" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#8b5cf6"/>
                <stop offset="100%" stop-color="#6d28d9"/>
            </linearGradient>
            <linearGradient id="lh-area-grad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#8b5cf6" stop-opacity=".28"/>
                <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0"/>
            </linearGradient>
        </defs>
    </svg>

    <div class="toast-host" role="status" aria-live="polite"></div>

    @if (session('success') || session('status'))
        <span hidden data-flash="{{ session('success') ?? session('status') }}" data-flash-type="success"></span>
    @elseif (session('error'))
        <span hidden data-flash="{{ session('error') }}" data-flash-type="danger"></span>
    @endif

    <script src="{{ asset('js/learnhub/app.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/navigation.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/notifications.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/modals.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/forms.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/courses.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/dashboard.js') }}" defer></script>
    <script src="{{ asset('js/learnhub/animations.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
