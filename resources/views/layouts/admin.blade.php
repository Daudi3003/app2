@php
    use App\Support\LearnHubData;

    $me = $admin ?? LearnHubData::administrator();

    $portalRole = 'Administrator';
    $sidebarVariant = 'admin';

    $portalUser = [
        'name'         => $me->name,
        'email'        => $me->email,
        'initials'     => $me->initials,
        'profile_url'  => route('admin.settings'),
        'settings_url' => route('admin.settings'),
    ];

    $navGroups = [
        'Overview' => [
            ['label' => 'Dashboard', 'icon' => 'home', 'url' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
        ],
        'People' => [
            ['label' => 'Users',       'icon' => 'users',      'url' => route('admin.users'),       'active' => request()->routeIs('admin.users')],
            ['label' => 'Students',    'icon' => 'graduation', 'url' => route('admin.students'),    'active' => request()->routeIs('admin.students')],
            ['label' => 'Instructors', 'icon' => 'user',       'url' => route('admin.instructors'), 'active' => request()->routeIs('admin.instructors'), 'badge' => 1, 'badge_tone' => 'warning'],
        ],
        'Content' => [
            ['label' => 'Courses',     'icon' => 'book',      'url' => route('admin.courses'),     'active' => request()->routeIs('admin.courses'), 'badge' => 2, 'badge_tone' => 'warning'],
            ['label' => 'Lessons',     'icon' => 'layers',    'url' => route('admin.lessons'),     'active' => request()->routeIs('admin.lessons')],
            ['label' => 'Materials',   'icon' => 'folder',    'url' => route('admin.materials'),   'active' => request()->routeIs('admin.materials')],
            ['label' => 'Assignments', 'icon' => 'clipboard', 'url' => route('admin.assignments'), 'active' => request()->routeIs('admin.assignments')],
            ['label' => 'Enrollments', 'icon' => 'user-plus', 'url' => route('admin.enrollments'), 'active' => request()->routeIs('admin.enrollments')],
        ],
        'System' => [
            ['label' => 'Reports',  'icon' => 'bar-chart', 'url' => route('admin.reports'),  'active' => request()->routeIs('admin.reports')],
            ['label' => 'Settings', 'icon' => 'settings',  'url' => route('admin.settings'), 'active' => request()->routeIs('admin.settings')],
        ],
    ];

    $sidebarPromo = '<div class="sidebar__promo">'
        .'<strong>🛡️ System healthy</strong>'
        .'<p>All services operational. Last backup 2 hours ago.</p>'
        .'</div>';
@endphp

@extends('layouts.portal')
