{{--
    Shared dashboard sidebar.
    Expects: $navGroups (array), $portalUser (object|array), $portalRole (string),
             $sidebarVariant (string|null)
--}}
<aside class="sidebar {{ ($sidebarVariant ?? '') ? 'sidebar--'.$sidebarVariant : '' }}"
       data-sidebar aria-label="{{ $portalRole }} navigation">

    <div class="sidebar__head">
        <x-brand light size="sm" />
        <button type="button" class="btn-icon btn-icon--sm btn-icon--plain" data-sidebar-close
                aria-label="Close navigation" style="color:#94a3b8">
            <x-icon name="x" :size="18" />
        </button>
    </div>

    <div class="sidebar__scroll">
        @foreach ($navGroups as $groupLabel => $items)
            @if (! is_numeric($groupLabel))
                <p class="sidebar__section">{{ $groupLabel }}</p>
            @endif

            @foreach ($items as $item)
                <a href="{{ $item['url'] }}"
                   class="sidebar__link {{ $item['active'] ?? false ? 'is-active' : '' }}"
                   @if ($item['active'] ?? false) aria-current="page" @endif>
                    <x-icon :name="$item['icon']" :size="18" />
                    <span class="sidebar__label">{{ $item['label'] }}</span>
                    @isset($item['badge'])
                        <span class="badge badge--{{ $item['badge_tone'] ?? 'primary' }}">{{ $item['badge'] }}</span>
                    @endisset
                </a>
            @endforeach
        @endforeach
    </div>

    <div class="sidebar__foot">
        @isset($sidebarPromo)
            {!! $sidebarPromo !!}
        @endisset

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar__link" style="width:100%;background:none;border:0;cursor:pointer;font-family:inherit">
                <x-icon name="log-out" :size="18" />
                <span class="sidebar__label">Logout</span>
            </button>
        </form>
    </div>
</aside>

<div class="overlay" data-sidebar-overlay></div>
