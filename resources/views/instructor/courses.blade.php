@extends('layouts.instructor')

@section('title', 'My Courses')
@section('page_title', 'My Courses')
@section('page_subtitle', 'Create, edit and publish your catalogue')

@section('content')

<div class="pane-head">
    <div>
        <h2>My Courses 📚</h2>
        <p>{{ $courses->count() }} courses · {{ $courses->sum('enrolled') }} total enrolments</p>
    </div>
    <div class="pane-head__actions">
        <a href="{{ route('instructor.courses.create') }}" class="btn btn--primary">
            <x-icon name="plus" :size="17" /> Add Course
        </a>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search your courses…"
                       aria-label="Search courses" data-table-search="#instructorCourses">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="courseStatusFilter">Filter by status</label>
            <select id="courseStatusFilter" class="select" style="width:auto"
                    data-row-filter="#instructorCourses" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending review</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="instructorCourses">
                <thead>
                    <tr>
                        <th scope="col">Course</th>
                        <th scope="col" class="is-numeric">Students</th>
                        <th scope="col" class="is-numeric">Lessons</th>
                        <th scope="col" class="is-numeric">Students</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($courses as $course)
                        <tr data-row data-status="{{ $course->status }}"
                            data-row-text="{{ $course->course_name }} {{ $course->category }} {{ $course->status }}">
                            <td>
                                <div class="table__user">
                                    <span class="list__icon">{{ $course->emoji }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name t-clamp-2">{{ $course->course_name }}</span><br>
                                        <span class="table__user-sub">{{ $course->category }} · {{ $course->level }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="is-numeric">{{ number_format($course->enrolled) }}</td>
                            <td class="is-numeric">{{ $course->lessons_count }}</td>
                            <td class="is-numeric">{{ number_format($course->enrolled) }}</td>
                            <td><x-status-badge :status="$course->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <a href="{{ route('courses.show', $course->id) }}" class="btn-icon btn-icon--sm"
                                       aria-label="View {{ $course->course_name }}">
                                        <x-icon name="eye" :size="15" />
                                    </a>
                                    <a href="{{ route('instructor.courses.edit', $course->id) }}"
                                       class="btn-icon btn-icon--sm" aria-label="Edit {{ $course->course_name }}">
                                        <x-icon name="edit" :size="15" />
                                    </a>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="{{ $course->course_name }}"
                                            aria-label="Delete {{ $course->course_name }}">
                                        <x-icon name="trash" :size="15" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-table-empty hidden>
            <x-empty-state emoji="🔍" title="No courses match your search"
                           text="Try a different keyword or clear the status filter." />
        </div>
    </div>
</div>

@endsection
