@extends('layouts.admin')

@section('title', 'Manage Courses')
@section('page_title', 'Courses')
@section('page_subtitle', 'Catalogue and moderation')

@section('content')

<div class="pane-head">
    <div>
        <h2>Courses 📚</h2>
        <p>{{ $courses->count() }} courses · {{ $courses->where('status', 'pending')->count() }} awaiting review</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary"
                data-toast="Course catalogue exported" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export
        </button>
        <button type="button" class="btn btn--primary" data-modal-open="adminCourseModal">
            <x-icon name="plus" :size="17" /> Add Course
        </button>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="Total Courses" value="85" emoji="📚" delta="+6 this month" direction="up" />
    <x-stat-card label="Published" :value="$courses->where('status', 'published')->count()" emoji="🟢" tone="success" />
    <x-stat-card label="Pending Review" :value="$courses->where('status', 'pending')->count()" emoji="🟡" tone="warning" />
    <x-stat-card label="Total Enrolments" :value="number_format($courses->sum('students_count'))" emoji="🎟️" tone="info" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search courses or instructors…"
                       aria-label="Search courses" data-table-search="#adminCourses">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adCourseCat">Filter by category</label>
            <select id="adCourseCat" class="select" style="width:auto"
                    data-row-filter="#adminCourses" data-filter-key="category">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label class="sr-only" for="adCourseStatus">Filter by status</label>
            <select id="adCourseStatus" class="select" style="width:auto"
                    data-row-filter="#adminCourses" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="published">Published</option>
                <option value="pending">Pending</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminCourses">
                <thead>
                    <tr>
                        <th scope="col">Course</th>
                        <th scope="col">Instructor</th>
                        <th scope="col">Category</th>
                        <th scope="col" class="is-numeric">Students</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($courses as $course)
                        <tr data-row data-status="{{ $course->status }}" data-category="{{ $course->category }}"
                            data-row-text="{{ $course->course_name }} {{ $course->instructor_name }} {{ $course->category }}">
                            <td>
                                <div class="table__user">
                                    <span class="list__icon">{{ $course->emoji }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name t-clamp-2">{{ $course->course_name }}</span><br>
                                        <span class="table__user-sub">{{ $course->level }} · {{ $course->duration }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="t-sm t-nowrap">{{ $course->instructor_name }}</td>
                            <td><span class="badge">{{ $course->category }}</span></td>
                            <td class="is-numeric">{{ number_format($course->students_count) }}</td>
                            
                            <td class="t-nowrap"><x-rating :score="$course->rating" /></td>
                            <td><x-status-badge :status="$course->status" /></td>
                            <td>
                                <div class="table__actions">
                                    @if ($course->status === 'pending')
                                        <button type="button" class="btn btn--success btn--sm"
                                                data-simulate="Course approved and published"
                                                data-simulate-done="✓ Approved">
                                            Approve
                                        </button>
                                    @else
                                        <a href="{{ route('courses.show', $course->id) }}"
                                           class="btn-icon btn-icon--sm" aria-label="View {{ $course->course_name }}">
                                            <x-icon name="eye" :size="15" />
                                        </a>
                                        <button type="button" class="btn-icon btn-icon--sm"
                                                data-modal-open="adminCourseModal"
                                                aria-label="Edit {{ $course->course_name }}">
                                            <x-icon name="edit" :size="15" />
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--sm is-danger"
                                                data-confirm-delete="{{ $course->course_name }}"
                                                aria-label="Delete {{ $course->course_name }}">
                                            <x-icon name="trash" :size="15" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-table-empty hidden>
            <x-empty-state emoji="📚" title="No courses match your search"
                           text="Try a different keyword or clear the filters." />
        </div>

        <x-pagination :current="1" :last="4" />
    </div>
</div>

{{-- ---------- COURSE MODAL ---------- --}}
<div class="modal" id="adminCourseModal" role="dialog" aria-modal="true"
     aria-labelledby="adminCourseTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="adminCourseTitle">Course details</h3>
                <p>Administrators can create or amend any course.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="Course saved successfully ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="field">
                        <label class="field__label" for="acName">Course name <span class="req">*</span></label>
                        <input id="acName" name="course_name" type="text" class="input" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="acDescription">Description <span class="req">*</span></label>
                        <textarea id="acDescription" name="description" class="textarea" rows="4" required></textarea>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="acCategory">Category</label>
                            <select id="acCategory" class="select">
                                @foreach ($categories as $category)
                                    <option>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label class="field__label" for="acDuration">Duration</label>
                            <input id="acDuration" name="duration" type="text" class="input" placeholder="e.g. 32 hours">
                        </div>
</div>
                        <div class="field">
                            <label class="field__label" for="acStatus">Status</label>
                            <select id="acStatus" name="status" class="select">
                                <option value="published">Published</option>
                                <option value="pending">Pending review</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">Save Course</button>
            </div>
        </form>
    </div>
</div>

@endsection
