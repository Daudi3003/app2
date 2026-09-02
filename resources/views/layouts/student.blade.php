@php
    use App\Support\LearnHubData;

    $me = $student ?? LearnHubData::student();

    $portalRole = 'Student';
    $sidebarVariant = null;

    $portalUser = [
        'name'         => $me->name,
        'email'        => $me->email,
        'initials'     => $me->initials,
        'profile_url'  => route('student.profile'),
        'settings_url' => route('student.settings'),
    ];

    $navGroups = [
        'Learning' => [
            ['label' => 'Dashboard',    'icon' => 'home',       'url' => route('student.dashboard'),    'active' => request()->routeIs('student.dashboard')],
            ['label' => 'My Courses',   'icon' => 'book',       'url' => route('student.courses'),      'active' => request()->routeIs('student.courses', 'student.my-courses', 'student.course')],
            ['label' => 'Lessons',      'icon' => 'play-circle','url' => route('student.lesson', 1),    'active' => request()->routeIs('student.lesson')],
            ['label' => 'Assignments',  'icon' => 'clipboard',  'url' => route('student.assignments'),  'active' => request()->routeIs('student.assignments'), 'badge' => 3, 'badge_tone' => 'warning'],
            ['label' => 'Certificates', 'icon' => 'award',      'url' => route('student.certificates'), 'active' => request()->routeIs('student.certificates')],
            ['label' => 'Bookmarks',    'icon' => 'bookmark',   'url' => route('student.bookmarks'),    'active' => request()->routeIs('student.bookmarks')],
        ],
        'Account' => [
            ['label' => 'Messages',      'icon' => 'message',  'url' => route('student.messages'),      'active' => request()->routeIs('student.messages'), 'badge' => 1],
            ['label' => 'Notifications', 'icon' => 'bell',     'url' => route('student.notifications'), 'active' => request()->routeIs('student.notifications'), 'badge' => 3, 'badge_tone' => 'danger'],
            ['label' => 'Profile',       'icon' => 'user',     'url' => route('student.profile'),       'active' => request()->routeIs('student.profile')],
            ['label' => 'Settings',      'icon' => 'settings', 'url' => route('student.settings'),      'active' => request()->routeIs('student.settings')],
        ],
    ];

    $sidebarPromo = '<div class="sidebar__promo">'
        .'<strong>🚀 Keep your streak</strong>'
        .'<p>'.$me->learning_streak.' days in a row. Study today to keep it alive.</p>'
        .'<a href="'.route('student.courses').'" class="btn btn--primary btn--sm btn--block">Continue Learning</a>'
        .'</div>';
@endphp

@extends('layouts.portal')
