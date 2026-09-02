{{-- Dashboard top bar: mobile menu button, page title, search, alerts, profile. --}}
<header class="topbar">

    <button type="button" class="btn-icon topbar__toggle" data-sidebar-toggle
            aria-label="Open navigation menu" aria-expanded="false">
        <x-icon name="menu" :size="20" />
    </button>

    <div class="topbar__title">
        <h1>@yield('page_title', 'Dashboard')</h1>
        <p>@yield('page_subtitle', '')</p>
    </div>

    <div class="topbar__actions">

        <form class="search topbar__search" role="search" onsubmit="return false">
            <span class="search__icon"><x-icon name="search" :size="16" /></span>
            <input type="search" class="search__input" placeholder="Search…" aria-label="Search">
            <button type="button" class="search__clear" aria-label="Clear search">
                <x-icon name="x" :size="15" />
            </button>
        </form>

        {{-- Notifications --}}
        <div class="dropdown">
            <button type="button" class="btn-icon bell" data-dropdown-toggle
                    aria-label="Notifications" aria-expanded="false" aria-haspopup="true">
                <x-icon name="bell" :size="19" />
                <span class="bell__dot">3</span>
            </button>

            <div class="dropdown__menu" style="min-width:320px">
                <div class="dropdown__head row row--between">
                    <strong>Notifications</strong>
                    <button type="button" class="btn btn--ghost btn--sm" data-mark-all-read>Mark all read</button>
                </div>

                @foreach (\App\Support\LearnHubData::notifications()->take(4) as $note)
                    <a href="#" class="dropdown__item {{ $note->unread ? 'is-unread' : '' }}" style="align-items:flex-start">
                        <span class="list__icon list__icon--{{ $note->tone }}" style="width:34px;height:34px;font-size:.95rem">
                            {{ $note->emoji }}
                        </span>
                        <span style="min-width:0">
                            <span class="list__title t-sm">{{ $note->title }}</span>
                            <span class="list__sub t-xs t-clamp-2">{{ $note->text }}</span>
                            <span class="t-xs t-muted">{{ $note->time }}</span>
                        </span>
                    </a>
                @endforeach

                <div class="dropdown__divider"></div>
                <a href="{{ route('student.notifications') }}" class="dropdown__item t-center" style="justify-content:center;font-weight:650">
                    View all notifications
                </a>
            </div>
        </div>

        {{-- Profile --}}
        <div class="dropdown">
            <button type="button" class="row row--nowrap" data-dropdown-toggle
                    aria-label="Account menu" aria-expanded="false" aria-haspopup="true"
                    style="background:none;border:0;cursor:pointer;padding:2px;gap:8px">
                <span class="avatar avatar--sm">{{ $portalUser['initials'] }}</span>
                <x-icon name="chevron-down" :size="15" class="icon t-muted" />
            </button>

            <div class="dropdown__menu">
                <div class="dropdown__head">
                    <strong style="display:block">{{ $portalUser['name'] }}</strong>
                    <small class="t-muted">{{ $portalUser['email'] }}</small>
                    <span class="badge badge--primary" style="margin-top:8px">{{ $portalRole }}</span>
                </div>

                <a href="{{ $portalUser['profile_url'] }}" class="dropdown__item">
                    <x-icon name="user" :size="17" /> My Profile
                </a>
                <a href="{{ $portalUser['settings_url'] }}" class="dropdown__item">
                    <x-icon name="settings" :size="17" /> Settings
                </a>
                <a href="{{ route('home') }}" class="dropdown__item">
                    <x-icon name="globe" :size="17" /> Visit Website
                </a>

                <div class="dropdown__divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown__item dropdown__item--danger">
                        <x-icon name="log-out" :size="17" /> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
