@extends('layouts.student')

@section('title', 'My Courses')
@section('page_title', 'My Courses')
@section('page_subtitle', 'Everything you are enrolled in')

@section('content')

<div class="pane-head">
    <div>
        <h2>My Courses 📚</h2>
        <p>{{ $courses->count() }} enrolled · {{ $courses->where('status', 'completed')->count() }} completed</p>
    </div>
    <div class="pane-head__actions">
        <a href="{{ route('courses.index') }}" class="btn btn--primary">
            <x-icon name="plus" :size="17" /> Enroll in a New Course
        </a>
    </div>
</div>

@php
    $tabs = [
        'all'         => ['label' => 'All',          'items' => $courses],
        'in_progress' => ['label' => 'In Progress',  'items' => $courses->where('status', 'in_progress')],
        'completed'   => ['label' => 'Completed',    'items' => $courses->where('status', 'completed')],
        'not_started' => ['label' => 'Not Started',  'items' => $courses->where('status', 'not_started')],
    ];
@endphp

<div class="tabs" data-tabs="mycourses" role="tablist" aria-label="Filter my courses">
    @foreach ($tabs as $key => $tab)
        <button type="button" class="tabs__btn {{ $loop->first ? 'is-active' : '' }}"
                data-tab="{{ $key }}" role="tab"
                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
            {{ $tab['label'] }}
            <span class="badge">{{ $tab['items']->count() }}</span>
        </button>
    @endforeach
</div>

@foreach ($tabs as $key => $tab)
    <div class="tab-panel {{ $loop->first ? 'is-active' : '' }}"
         data-tab-panel="{{ $key }}" data-tab-scope="mycourses" role="tabpanel">

        @if ($tab['items']->isEmpty())
            <div class="card">
                <x-empty-state
                    emoji="📚"
                    title="Nothing here yet"
                    :text="'You have no '.strtolower($tab['label']).' courses. Browse the catalogue to find your next one.'"
                    action="Browse All Courses"
                    :action-url="route('courses.index')" />
            </div>
        @else
            <div class="courses-grid">
                @foreach ($tab['items'] as $course)
                    <x-course-card :course="$course" show-progress />
                @endforeach
            </div>
        @endif
    </div>
@endforeach

@endsection
