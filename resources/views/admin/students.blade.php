@extends('layouts.admin')

@section('title', 'Manage Students')
@section('page_title', 'Students')
@section('page_subtitle', 'Learner accounts and progress')

@section('content')

<div class="pane-head">
    <div>
        <h2>Students 🎓</h2>
        <p>{{ $students->count() }} students shown · {{ $students->where('status', 'active')->count() }} active</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary"
                data-toast="Student report exported" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export
        </button>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="Total Students" value="1,100" emoji="🎓" delta="+74 this month" direction="up" />
    <x-stat-card label="Active" :value="$students->where('status', 'active')->count()" emoji="🟢" tone="success" />
    <x-stat-card label="Average Progress" :value="round($students->avg('progress')).'%'" emoji="📈" tone="info" />
    <x-stat-card label="Total Enrolments" :value="$students->sum('courses_count')" emoji="📚" tone="accent" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input"
                       placeholder="Search by name, email or registration number…"
                       aria-label="Search students" data-table-search="#adminStudents">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adStudentStatus">Filter by status</label>
            <select id="adStudentStatus" class="select" style="width:auto"
                    data-row-filter="#adminStudents" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminStudents">
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Registration no.</th>
                        <th scope="col">Phone</th>
                        <th scope="col" class="is-numeric">Courses</th>
                        <th scope="col" style="min-width:150px">Progress</th>
                        <th scope="col">Joined</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($students as $student)
                        <tr data-row data-status="{{ $student->status }}"
                            data-row-text="{{ $student->name }} {{ $student->email }} {{ $student->registration_no }}">
                            <td>
                                <div class="table__user">
                                    <span class="avatar avatar--sm">{{ $student->initials }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name">{{ $student->name }}</span><br>
                                        <span class="table__user-sub">{{ $student->email }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="t-sm t-nowrap">{{ $student->registration_no }}</td>
                            <td class="t-sm t-nowrap t-muted">{{ $student->phone }}</td>
                            <td class="is-numeric">{{ $student->courses_count }}</td>
                            <td>
                                <x-progress :value="$student->progress" size="sm" />
                                <span class="t-xs t-muted">{{ $student->progress }}%</span>
                            </td>
                            <td class="t-nowrap t-sm t-muted">{{ $student->joined }}</td>
                            <td><x-status-badge :status="$student->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Student profile would open here" data-toast-type="info"
                                            aria-label="View {{ $student->name }}">
                                        <x-icon name="eye" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Edit form would open here" data-toast-type="info"
                                            aria-label="Edit {{ $student->name }}">
                                        <x-icon name="edit" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="{{ $student->name }}"
                                            aria-label="Delete {{ $student->name }}">
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
            <x-empty-state emoji="🎓" title="No students match your search"
                           text="Try a different keyword or clear the status filter." />
        </div>

        <x-pagination :current="1" :last="4" />
    </div>
</div>

@endsection
