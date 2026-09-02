@extends('layouts.instructor')

@section('title', 'Assignments')
@section('page_title', 'Assignments')
@section('page_subtitle', 'Briefs, submissions and grading')

@section('content')

<div class="pane-head">
    <div>
        <h2>Assignments 📝</h2>
        <p>{{ $assignments->count() }} assignments · {{ $submissions->where('status', 'pending')->count() }} submissions awaiting a grade</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--primary" data-modal-open="assignmentModal">
            <x-icon name="plus" :size="17" /> Create Assignment
        </button>
    </div>
</div>

<div class="tabs" data-tabs="instrAssign" role="tablist" aria-label="Assignment views">
    <button type="button" class="tabs__btn is-active" data-tab="briefs" role="tab" aria-selected="true">
        Assignments <span class="badge">{{ $assignments->count() }}</span>
    </button>
    <button type="button" class="tabs__btn" data-tab="subs" role="tab" aria-selected="false">
        Submissions <span class="badge badge--warning">{{ $submissions->where('status', 'pending')->count() }}</span>
    </button>
</div>

{{-- ---------- BRIEFS ---------- --}}
<div class="tab-panel is-active" data-tab-panel="briefs" data-tab-scope="instrAssign" role="tabpanel">
    <div class="card">
        <div class="card__body">
            <div class="toolbar">
                <div class="search toolbar__search">
                    <span class="search__icon"><x-icon name="search" :size="16" /></span>
                    <input type="search" class="search__input" placeholder="Search assignments…"
                           aria-label="Search assignments" data-table-search="#assignTable">
                    <button type="button" class="search__clear" aria-label="Clear search">
                        <x-icon name="x" :size="15" />
                    </button>
                </div>

                <label class="sr-only" for="assignStatus">Filter by status</label>
                <select id="assignStatus" class="select" style="width:auto"
                        data-row-filter="#assignTable" data-filter-key="status">
                    <option value="">All statuses</option>
                    <option value="open">Open</option>
                    <option value="grading">Grading</option>
                    <option value="closed">Closed</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="table-wrap">
                <table class="table" id="assignTable">
                    <thead>
                        <tr>
                            <th scope="col">Assignment</th>
                            <th scope="col">Course</th>
                            <th scope="col">Due date</th>
                            <th scope="col">Submissions</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="text-align:right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($assignments as $assignment)
                            <tr data-row data-status="{{ $assignment->status }}"
                                data-row-text="{{ $assignment->title }} {{ $assignment->course_name }}">
                                <td>
                                    <span class="table__user-name t-clamp-2">{{ $assignment->title }}</span><br>
                                    <span class="table__user-sub">Max {{ $assignment->max_score }} points</span>
                                </td>
                                <td class="t-sm t-clamp-2">{{ $assignment->course_name }}</td>
                                <td class="t-nowrap t-sm t-muted">{{ $assignment->due_date }}</td>
                                <td style="min-width:150px">
                                    <div class="progress-meta">
                                        <span>{{ $assignment->graded }} / {{ $assignment->submissions }} graded</span>
                                    </div>
                                    <div class="progress progress--sm">
                                        <div class="progress__bar"
                                             data-progress="{{ $assignment->submissions ? round($assignment->graded / $assignment->submissions * 100) : 0 }}"></div>
                                    </div>
                                </td>
                                <td><x-status-badge :status="$assignment->status" /></td>
                                <td>
                                    <div class="table__actions">
                                        <button type="button" class="btn-icon btn-icon--sm"
                                                data-toast="Submission list would open here" data-toast-type="info"
                                                aria-label="View submissions">
                                            <x-icon name="eye" :size="15" />
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--sm"
                                                data-modal-open="assignmentModal" aria-label="Edit assignment">
                                            <x-icon name="edit" :size="15" />
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--sm is-danger"
                                                data-confirm-delete="{{ $assignment->title }}"
                                                aria-label="Delete assignment">
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
                <x-empty-state emoji="📝" title="No assignments match your search"
                               text="Try a different keyword or clear the status filter." />
            </div>
        </div>
    </div>
</div>

{{-- ---------- SUBMISSIONS ---------- --}}
<div class="tab-panel" data-tab-panel="subs" data-tab-scope="instrAssign" role="tabpanel">
    <div class="card">
        <div class="card__body card__body--flush">
            <div class="list">
                @foreach ($submissions as $submission)
                    <div class="list__item">
                        <span class="avatar avatar--sm">{{ $submission->initials }}</span>

                        <div class="list__body">
                            <div class="list__title">{{ $submission->student }}</div>
                            <div class="list__sub t-clamp-2">{{ $submission->assignment }}</div>
                            <div class="t-xs t-muted mt-4">
                                <x-icon name="file" :size="12" /> {{ $submission->file }}
                                · submitted {{ $submission->submitted_at }}
                            </div>
                        </div>

                        <div class="list__end">
                            @if ($submission->status === 'graded')
                                <span class="badge badge--success">{{ $submission->score }} / 100</span>
                                <button type="button" class="btn btn--ghost btn--sm"
                                        data-modal-open="gradeModal">Review</button>
                            @else
                                <x-status-badge :status="$submission->status" />
                                <button type="button" class="btn btn--primary btn--sm"
                                        data-modal-open="gradeModal">Grade</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ---------- CREATE ASSIGNMENT MODAL ---------- --}}
<div class="modal" id="assignmentModal" role="dialog" aria-modal="true"
     aria-labelledby="assignmentTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="assignmentTitle">Assignment details</h3>
                <p>Students see this brief on their assignments page.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="Assignment saved successfully ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="field">
                        <label class="field__label" for="aTitle">Title <span class="req">*</span></label>
                        <input id="aTitle" name="title" type="text" class="input" required
                               placeholder="e.g. Build a Responsive Landing Page">
                    </div>

                    <div class="field">
                        <label class="field__label" for="aDescription">Brief <span class="req">*</span></label>
                        <textarea id="aDescription" name="description" class="textarea" rows="5" required
                                  placeholder="What exactly should the student produce and hand in?"></textarea>
                    </div>

                    <div class="form-grid--3 form-grid">
                        <div class="field">
                            <label class="field__label" for="aCourse">Course <span class="req">*</span></label>
                            <select id="aCourse" class="select" required>
                                <option value="">Choose…</option>
                                @foreach ($courses as $course)
                                    <option>{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label class="field__label" for="aDue">Due date <span class="req">*</span></label>
                            <input id="aDue" name="due_date" type="date" class="input" required>
                        </div>

                        <div class="field">
                            <label class="field__label" for="aScore">Max score <span class="req">*</span></label>
                            <input id="aScore" name="max_score" type="number" class="input" min="1" value="100" required>
                        </div>
                    </div>

                    <label class="check">
                        <input type="checkbox" checked>
                        <span>Notify enrolled students when this assignment is published</span>
                    </label>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">Save Assignment</button>
            </div>
        </form>
    </div>
</div>

{{-- ---------- GRADE MODAL ---------- --}}
<div class="modal" id="gradeModal" role="dialog" aria-modal="true"
     aria-labelledby="gradeTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="gradeTitle">Grade submission</h3>
                <p>Your feedback is emailed to the student.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="Submission graded and feedback sent ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="file-row">
                        <span class="file-row__icon">📦</span>
                        <span class="file-row__body">
                            <span class="file-row__name">alex-mwangi-api.zip</span>
                            <span class="file-row__meta">2.8 MB · submitted 17 Aug 2026, 22:14</span>
                        </span>
                        <button type="button" class="btn btn--ghost btn--sm"
                                data-toast="Download would start here" data-toast-type="info">
                            <x-icon name="download" :size="15" /> Download
                        </button>
                    </div>

                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="gScore">Score <span class="req">*</span></label>
                            <input id="gScore" name="score" type="number" class="input" min="0" max="100"
                                   placeholder="0–100" required>
                        </div>
                        <div class="field">
                            <label class="field__label" for="gStatus">Result</label>
                            <select id="gStatus" class="select">
                                <option>Pass</option>
                                <option>Distinction</option>
                                <option>Resubmit required</option>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="gFeedback">Feedback <span class="req">*</span></label>
                        <textarea id="gFeedback" name="feedback" class="textarea" rows="5" required
                                  placeholder="What worked, what to improve, and what to try next."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--success">
                    <x-icon name="check" :size="16" /> Save Grade
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
