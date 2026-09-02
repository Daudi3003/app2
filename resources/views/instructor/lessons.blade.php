@extends('layouts.instructor')

@section('title', 'Lessons')
@section('page_title', 'Lessons')
@section('page_subtitle', 'Every lesson across your courses')

@section('content')

<div class="pane-head">
    <div>
        <h2>Lessons 🎥</h2>
        <p>{{ $lessons->count() }} lessons · {{ $lessons->where('status', 'draft')->count() }} still in draft</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--primary" data-modal-open="lessonModal">
            <x-icon name="plus" :size="17" /> Create Lesson
        </button>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search lessons…"
                       aria-label="Search lessons" data-table-search="#lessonsTable">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="lessonStatus">Filter by status</label>
            <select id="lessonStatus" class="select" style="width:auto"
                    data-row-filter="#lessonsTable" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="lessonsTable">
                <thead>
                    <tr>
                        <th scope="col">Lesson</th>
                        <th scope="col">Course</th>
                        <th scope="col" class="is-numeric">Order</th>
                        <th scope="col">Duration</th>
                        <th scope="col" class="is-numeric">Materials</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lessons as $lesson)
                        <tr data-row data-status="{{ $lesson->status }}"
                            data-row-text="{{ $lesson->title }} {{ $lesson->course_name }}">
                            <td>
                                <span class="table__user-name t-clamp-2">{{ $lesson->title }}</span><br>
                                <span class="table__user-sub t-clamp-2">{{ $lesson->description }}</span>
                            </td>
                            <td class="t-clamp-2 t-sm">{{ $lesson->course_name }}</td>
                            <td class="is-numeric">{{ $lesson->lesson_order }}</td>
                            <td class="t-nowrap t-sm t-muted">{{ $lesson->duration }}</td>
                            <td class="is-numeric">{{ $lesson->materials_count }}</td>
                            <td><x-status-badge :status="$lesson->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-modal-open="lessonModal" aria-label="Edit {{ $lesson->title }}">
                                        <x-icon name="edit" :size="15" />
                                    </button>
                                    <a href="{{ route('instructor.materials') }}" class="btn-icon btn-icon--sm"
                                       aria-label="Materials for {{ $lesson->title }}">
                                        <x-icon name="folder" :size="15" />
                                    </a>
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

{{-- ---------- LESSON MODAL ---------- --}}
<div class="modal" id="lessonModal" role="dialog" aria-modal="true"
     aria-labelledby="lessonModalTitle" aria-hidden="true">
    <div class="modal__dialog modal__dialog--lg">
        <div class="modal__head">
            <div>
                <h3 id="lessonModalTitle">Lesson details</h3>
                <p>Add the lesson content and attach it to a course.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="Lesson saved successfully ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="form-grid">
                        <div class="field is-full">
                            <label class="field__label" for="mlTitle">Title <span class="req">*</span></label>
                            <input id="mlTitle" name="title" type="text" class="input" required
                                   placeholder="e.g. Working with the DOM">
                        </div>

                        <div class="field">
                            <label class="field__label" for="mlCourse">Course <span class="req">*</span></label>
                            <select id="mlCourse" class="select" required>
                                <option value="">Choose a course…</option>
                                @foreach ($courses as $course)
                                    <option>{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label class="field__label" for="mlOrder">Lesson order <span class="req">*</span></label>
                            <input id="mlOrder" name="lesson_order" type="number" class="input" min="1" value="1" required>
                        </div>

                        <div class="field is-full">
                            <label class="field__label" for="mlDescription">Description</label>
                            <textarea id="mlDescription" name="description" class="textarea" rows="3"
                                      placeholder="A short summary shown in the curriculum."></textarea>
                        </div>

                        <div class="field is-full">
                            <label class="field__label" for="mlContent">Lesson content</label>
                            <textarea id="mlContent" name="content" class="textarea" rows="6"
                                      placeholder="Notes, transcript or written lesson body."></textarea>
                        </div>

                        <div class="field is-full">
                            <label class="field__label">Lesson video</label>
                            <label class="dropzone" data-dropzone>
                                <span class="dropzone__icon" aria-hidden="true">🎬</span>
                                <span class="dropzone__title">Drop your video here, or click to browse</span>
                                <span class="dropzone__hint">MP4 or MOV, up to 2 GB</span>
                                <input type="file" accept="video/*">
                            </label>
                            <div data-file-list class="file-list"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">Save Lesson</button>
            </div>
        </form>
    </div>
</div>

@endsection
