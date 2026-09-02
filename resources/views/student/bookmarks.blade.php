@extends('layouts.student')

@section('title', 'My Bookmarks')
@section('page_title', 'Bookmarks')
@section('page_subtitle', 'Courses you saved for later')

@section('content')

<div class="pane-head">
    <div>
        <h2>Bookmarks 🔖</h2>
        <p>Courses you saved while browsing. Enrol whenever you are ready.</p>
    </div>
    <div class="pane-head__actions">
        <a href="{{ route('courses.index') }}" class="btn btn--secondary">
            <x-icon name="search" :size="17" /> Find More Courses
        </a>
    </div>
</div>

@if ($courses->isEmpty())
    <div class="card">
        <x-empty-state emoji="🔖" title="No bookmarks yet"
                       text="Tap the heart on any course card to save it here for later."
                       action="Browse All Courses" :action-url="route('courses.index')" />
    </div>
@else
    <div class="courses-grid">
        @foreach ($courses as $course)
            <x-course-card :course="$course" />
        @endforeach
    </div>
@endif

@endsection
