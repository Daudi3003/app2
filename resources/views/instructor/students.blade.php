@extends('layouts.instructor')

@section('title', 'My Students')
@section('page_title', 'Students')
@section('page_subtitle', 'Everyone enrolled in your courses')

@section('content')

<div class="pane-head">
    <div>
        <h2>Students 👨‍🎓</h2>
        <p>{{ $students->count() }} students · {{ $students->where('status', 'active')->count() }} currently active</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary"
                data-toast="Student list exported to CSV" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export CSV
        </button>
        <a href="{{ route('instructor.messages') }}" class="btn btn--primary">
            <x-icon name="message" :size="17" /> Message Students
        </a>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="Total Students" :value="$students->count()" emoji="👥" />
    <x-stat-card label="Active" :value="$students->where('status', 'active')->count()" emoji="🟢" tone="success" />
    <x-stat-card label="Completed" :value="$students->where('status', 'completed')->count()" emoji="🏆" tone="accent" />
    <x-stat-card label="Average Progress" :value="round($students->avg('progress')).'%'" emoji="📈" tone="info" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search by name, email or course…"
                       aria-label="Search students" data-table-search="#studentsTable">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="studentStatus">Filter by status</label>
            <select id="studentStatus" class="select" style="width:auto"
                    data-row-filter="#studentsTable" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="studentsTable">
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Course</th>
                        <th scope="col" style="min-width:160px">Progress</th>
                        <th scope="col">Enrolled</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($students as $student)
                        <tr data-row data-status="{{ $student->status }}"
                            data-row-text="{{ $student->name }} {{ $student->email }} {{ $student->course_name }}">
                            <td>
                                <div class="table__user">
                                    <span class="avatar avatar--sm">{{ $student->initials }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name">{{ $student->name }}</span><br>
                                        <span class="table__user-sub">{{ $student->email }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="t-sm t-clamp-2">{{ $student->course_name }}</td>
                            <td>
                                <x-progress :value="$student->progress"
                                            :tone="$student->progress >= 100 ? 'success' : ''" size="sm" />
                                <span class="t-xs t-muted">{{ $student->progress }}%</span>
                            </td>
                            <td class="t-nowrap t-sm t-muted">{{ $student->enrollment_date }}</td>
                            <td><x-status-badge :status="$student->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <a href="{{ route('instructor.messages') }}" class="btn-icon btn-icon--sm"
                                       aria-label="Message {{ $student->name }}">
                                        <x-icon name="message" :size="15" />
                                    </a>
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Student profile would open here" data-toast-type="info"
                                            aria-label="View {{ $student->name }}">
                                        <x-icon name="eye" :size="15" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-table-empty hidden>
            <x-empty-state emoji="👨‍🎓" title="No students match your search"
                           text="Try a different keyword or clear the status filter." />
        </div>
    </div>
</div>

@endsection
