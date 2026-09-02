@extends('layouts.admin')

@section('title', 'Manage Lessons')
@section('page_title', 'Lessons')
@section('page_subtitle', 'Lesson content across all courses')

@section('content')

<div class="pane-head">
    <div>
        <h2>Lessons 🎥</h2>
        <p>{{ $lessons->count() }} lessons shown · {{ $lessons->where('status', 'draft')->count() }} in draft</p>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search lessons, courses or instructors…"
                       aria-label="Search lessons" data-table-search="#adminLessons">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adLessonStatus">Filter by status</label>
            <select id="adLessonStatus" class="select" style="width:auto"
                    data-row-filter="#adminLessons" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminLessons">
                <thead>
                    <tr>
                        <th scope="col">Lesson</th>
                        <th scope="col">Course</th>
                        <th scope="col">Instructor</th>
                        <th scope="col" class="is-numeric">Order</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lessons as $lesson)
                        <tr data-row data-status="{{ $lesson->status }}"
                            data-row-text="{{ $lesson->title }} {{ $lesson->course_name }} {{ $lesson->instructor }}">
                            <td>
                                <span class="table__user-name t-clamp-2">{{ $lesson->title }}</span><br>
                                <span class="table__user-sub t-clamp-2">{{ $lesson->description }}</span>
                            </td>
                            <td class="t-sm t-clamp-2">{{ $lesson->course_name }}</td>
                            <td class="t-sm t-nowrap">{{ $lesson->instructor }}</td>
                            <td class="is-numeric">{{ $lesson->lesson_order }}</td>
                            <td class="t-nowrap t-sm t-muted">{{ $lesson->duration }}</td>
                            <td><x-status-badge :status="$lesson->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Lesson preview would open here" data-toast-type="info"
                                            aria-label="View {{ $lesson->title }}">
                                        <x-icon name="eye" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Edit form would open here" data-toast-type="info"
                                            aria-label="Edit {{ $lesson->title }}">
                                        <x-icon name="edit" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="{{ $lesson->title }}"
                                            aria-label="Delete {{ $lesson->title }}">
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
            <x-empty-state emoji="🎥" title="No lessons match your search"
                           text="Try a different keyword or clear the status filter." />
        </div>
    </div>
</div>

@endsection
