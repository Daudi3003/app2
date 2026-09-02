@php
    use App\Support\LearnHubData;

    $me = $instructor ?? LearnHubData::instructorProfile();

    $portalRole = 'Instructor';
    $sidebarVariant = null;

    $portalUser = [
        'name'         => $me->name,
        'email'        => $me->email,
        'initials'     => $me->initials,
        'profile_url'  => route('instructor.profile'),
        'settings_url' => route('instructor.settings'),
    ];

    $navGroups = [
        'Teaching' => [
            ['label' => 'Dashboard',   'icon' => 'home',      'url' => route('instructor.dashboard'),   'active' => request()->routeIs('instructor.dashboard')],
            ['label' => 'My Courses',  'icon' => 'book',      'url' => route('instructor.courses'),     'active' => request()->routeIs('instructor.courses') || request()->routeIs('instructor.courses.*')],
            ['label' => 'Lessons',     'icon' => 'layers',    'url' => route('instructor.lessons'),     'active' => request()->routeIs('instructor.lessons')],
            ['label' => 'Materials',   'icon' => 'folder',    'url' => route('instructor.materials'),   'active' => request()->routeIs('instructor.materials')],
            ['label' => 'Assignments', 'icon' => 'clipboard', 'url' => route('instructor.assignments'), 'active' => request()->routeIs('instructor.assignments'), 'badge' => 4, 'badge_tone' => 'warning'],
        ],
        'People' => [
            ['label' => 'Students',    'icon' => 'users',     'url' => route('instructor.students'),    'active' => request()->routeIs('instructor.students')],
            ['label' => 'Enrollments', 'icon' => 'user-plus', 'url' => route('instructor.enrollments'), 'active' => request()->routeIs('instructor.enrollments')],
            ['label' => 'Messages',    'icon' => 'message',   'url' => route('instructor.messages'),    'active' => request()->routeIs('instructor.messages'), 'badge' => 2],
        ],
        'Account' => [
            ['label' => 'Reports',  'icon' => 'bar-chart', 'url' => route('instructor.reports'),  'active' => request()->routeIs('instructor.reports')],
            ['label' => 'Profile',  'icon' => 'user',      'url' => route('instructor.profile'),  'active' => request()->routeIs('instructor.profile')],
            ['label' => 'Settings', 'icon' => 'settings',  'url' => route('instructor.settings'), 'active' => request()->routeIs('instructor.settings')],
        ],
    ];

    $sidebarPromo = '<div class="sidebar__promo">'
        .'<strong>📚 Grow your catalogue</strong>'
        .'<p>Courses with eight or more lessons earn roughly twice as much.</p>'
        .'<a href="'.route('instructor.courses.create').'" class="btn btn--primary btn--sm btn--block">Create a Course</a>'
        .'</div>';
@endphp

@extends('layouts.portal')
