@php
    $links = [
        ['label' => 'Home',        'route' => 'home',              'icon' => 'home'],
        ['label' => 'Courses',     'route' => 'courses.index',     'icon' => 'book'],
        ['label' => 'Instructors', 'route' => 'instructors.index', 'icon' => 'users'],
        ['label' => 'About',       'route' => 'about',             'icon' => 'info'],
        ['label' => 'Blog',        'route' => 'blog',              'icon' => 'file'],
        ['label' => 'Contact',     'route' => 'contact',           'icon' => 'mail'],
    ];
@endphp

<header class="navbar" data-navbar>
    <div class="navbar__inner">

        <x-brand />

        <nav class="navbar__links" aria-label="Primary">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="navbar__link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}"
                   @if (request()->routeIs($link['route'])) aria-current="page" @endif>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="navbar__actions">
            <form class="search navbar__search" action="{{ route('courses.index') }}" method="GET" role="search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" name="q" class="search__input"
                       placeholder="Search courses…" aria-label="Search courses">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </form>

            @auth
                <a href="{{ route('student.dashboard') }}" class="btn btn--secondary btn--sm">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn--ghost btn--sm">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn--ghost">Login</a>
                <a href="{{ route('register') }}" class="btn btn--primary">Sign Up</a>
            @endauth

            <button type="button" class="hamburger" data-nav-toggle
                    aria-label="Open navigation menu" aria-expanded="false" aria-controls="mobileNav">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<div class="overlay" data-nav-overlay></div>

<nav id="mobileNav" class="mobile-nav" data-nav-drawer aria-label="Mobile navigation">
    @foreach ($links as $link)
        <a href="{{ route($link['route']) }}"
           class="mobile-nav__link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
            <x-icon :name="$link['icon']" :size="19" />
            {{ $link['label'] }}
        </a>
    @endforeach

    <div class="mobile-nav__divider"></div>

    <div class="mobile-nav__actions">
        @auth
            <a href="{{ route('student.dashboard') }}" class="btn btn--secondary btn--block">My Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn--secondary btn--block">Login</a>
            <a href="{{ route('register') }}" class="btn btn--primary btn--block">Sign Up Free</a>
        @endauth
    </div>
</nav>
