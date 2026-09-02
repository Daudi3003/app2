@extends('layouts.admin')

@section('title', 'Manage Assignments')
@section('page_title', 'Assignments')
@section('page_subtitle', 'Assessment across all courses')

@section('content')

<div class="pane-head">
    <div>
        <h2>Assignments 📝</h2>
        <p>{{ $assignments->count() }} assignments · {{ number_format($assignments->sum('submissions')) }} submissions received</p>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="Assignments" :value="$assignments->count()" emoji="📝" />
    <x-stat-card label="Open" :value="$assignments->where('status', 'open')->count()" emoji="🟢" tone="success" />
    <x-stat-card label="Submissions" :value="number_format($assignments->sum('submissions'))" emoji="📤" tone="info" />
    <x-stat-card label="Awaiting Grading"
                 :value="$assignments->sum('submissions') - $assignments->sum('graded')"
                 emoji="⏳" tone="warning" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search assignments…"
                       aria-label="Search assignments" data-table-search="#adminAssignments">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adAssignStatus">Filter by status</label>
            <select id="adAssignStatus" class="select" style="width:auto"
                    data-row-filter="#adminAssignments" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="open">Open</option>
                <option value="grading">Grading</option>
                <option value="closed">Closed</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminAssignments">
                <thead>
                    <tr>
                        <th scope="col">Assignment</th>
                        <th scope="col">Course</th>
                        <th scope="col">Instructor</th>
                        <th scope="col">Due date</th>
                        <th scope="col" style="min-width:150px">Grading progress</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($assignments as $assignment)
                        <tr data-row data-status="{{ $assignment->status }}"
                            data-row-text="{{ $assignment->title }} {{ $assignment->course_name }} {{ $assignment->instructor }}">
                            <td>
                                <span class="table__user-name t-clamp-2">{{ $assignment->title }}</span><br>
                                <span class="table__user-sub">Max {{ $assignment->max_score }} points</span>
                            </td>
                            <td class="t-sm t-clamp-2">{{ $assignment->course_name }}</td>
                            <td class="t-sm t-nowrap">{{ $assignment->instructor }}</td>
                            <td class="t-nowrap t-sm t-muted">{{ $assignment->due_date }}</td>
                            <td>
                                <div class="progress-meta">
                                    <span>{{ $assignment->graded }} / {{ $assignment->submissions }}</span>
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
                                            data-toast="Assignment details would open here" data-toast-type="info"
                                            aria-label="View {{ $assignment->title }}">
                                        <x-icon name="eye" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="{{ $assignment->title }}"
                                            aria-label="Delete {{ $assignment->title }}">
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

@endsection
