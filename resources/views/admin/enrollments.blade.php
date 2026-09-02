@extends('layouts.admin')

@section('title', 'Manage Enrollments')
@section('page_title', 'Enrollments')
@section('page_subtitle', 'Every enrolment on the platform')

@section('content')

<div class="pane-head">
    <div>
        <h2>Enrollments 🎟️</h2>
        <p>{{ $enrollments->count() }} records · all courses are free</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary"
                data-toast="Enrolment report exported" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export
        </button>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="Total Enrolments" :value="$enrollments->count()" emoji="🎟️" />
    <x-stat-card label="Active" :value="$enrollments->where('status', 'active')->count()" emoji="🟢" tone="success" />
    <x-stat-card label="Completed" :value="$enrollments->where('status', 'completed')->count()" emoji="🏆" tone="accent" />
    <x-stat-card label="Cancelled" :value="$enrollments->where('status', 'cancelled')->count()" emoji="🔴" tone="danger" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search by student or course…"
                       aria-label="Search enrolments" data-table-search="#adminEnrolments">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adEnrolStatus">Filter by status</label>
            <select id="adEnrolStatus" class="select" style="width:auto"
                    data-row-filter="#adminEnrolments" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminEnrolments">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Student</th>
                        <th scope="col">Course</th>
                        <th scope="col">Enrolled on</th>
                        <th scope="col" style="min-width:150px">Progress</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($enrollments as $enrolment)
                        <tr data-row data-status="{{ $enrolment->status }}"
                            data-row-text="{{ $enrolment->student }} {{ $enrolment->email }} {{ $enrolment->course_name }}">
                            <td class="t-sm t-muted">#{{ $enrolment->id }}</td>
                            <td>
                                <div class="table__user">
                                    <span class="avatar avatar--sm">{{ $enrolment->initials }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name">{{ $enrolment->student }}</span><br>
                                        <span class="table__user-sub">{{ $enrolment->email }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="t-sm t-clamp-2">{{ $enrolment->course_name }}</td>
                            <td class="t-nowrap t-sm t-muted">{{ $enrolment->enrollment_date }}</td>
                            <td>
                                <x-progress :value="$enrolment->progress" size="sm"
                                            :tone="$enrolment->progress >= 100 ? 'success' : ''" />
                                <span class="t-xs t-muted">{{ $enrolment->progress }}%</span>
                            </td>
                            <td><x-status-badge :status="$enrolment->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Enrolment details would open here" data-toast-type="info"
                                            aria-label="View enrolment #{{ $enrolment->id }}">
                                        <x-icon name="eye" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="enrolment #{{ $enrolment->id }}"
                                            aria-label="Cancel enrolment #{{ $enrolment->id }}">
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
            <x-empty-state emoji="🎟️" title="No enrolments match your search"
                           text="Try a different keyword or clear the status filter." />
        </div>

        <x-pagination :current="1" :last="4" />
    </div>
</div>

@endsection
