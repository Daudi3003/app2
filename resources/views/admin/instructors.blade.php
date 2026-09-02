@extends('layouts.admin')

@section('title', 'Manage Instructors')
@section('page_title', 'Instructors')
@section('page_subtitle', 'Teaching staff and applications')

@section('content')

<div class="pane-head">
    <div>
        <h2>Instructors 👨‍🏫</h2>
        <p>{{ $instructors->count() }} instructors · {{ $instructors->where('status', 'pending')->count() }} awaiting approval</p>
    </div>
    <div class="pane-head__actions">
        {{-- This uses the real backend route that already creates instructors. --}}
        <a href="{{ route('adminstrator.instructor.create') }}" class="btn btn--primary">
            <x-icon name="user-plus" :size="17" /> Add Instructor
        </a>
    </div>
</div>

@if ($instructors->where('status', 'pending')->count())
    <x-alert type="warning" title="Applications pending review">
        {{ $instructors->where('status', 'pending')->count() }} instructor application(s) need a decision.
    </x-alert>
@endif

<section class="stats-row">
    <x-stat-card label="Total Instructors" value="45" emoji="👨‍🏫" delta="+3 this month" direction="up" />
    <x-stat-card label="Active" :value="$instructors->where('status', 'active')->count()" emoji="🟢" tone="success" />
    <x-stat-card label="Total Courses" :value="$instructors->sum('courses_count')" emoji="📚" tone="info" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search instructors…"
                       aria-label="Search instructors" data-table-search="#adminInstructors">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adInstStatus">Filter by status</label>
            <select id="adInstStatus" class="select" style="width:auto"
                    data-row-filter="#adminInstructors" data-filter-key="status">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminInstructors">
                <thead>
                    <tr>
                        <th scope="col">Instructor</th>
                        <th scope="col">Specialisation</th>
                        <th scope="col" class="is-numeric">Courses</th>
                        <th scope="col" class="is-numeric">Students</th>
<th scope="col">Rating</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($instructors as $instructor)
                        <tr data-row data-status="{{ $instructor->status }}"
                            data-row-text="{{ $instructor->name }} {{ $instructor->email }} {{ $instructor->specialization }}">
                            <td>
                                <div class="table__user">
                                    <span class="avatar avatar--sm">{{ $instructor->initials }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name">{{ $instructor->name }}</span><br>
                                        <span class="table__user-sub">{{ $instructor->email }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="t-sm t-clamp-2">{{ $instructor->specialization }}</td>
                            <td class="is-numeric">{{ $instructor->courses_count }}</td>
                            <td class="is-numeric">{{ number_format($instructor->students_count) }}</td>
<td class="t-nowrap"><x-rating :score="$instructor->rating" /></td>
                            <td><x-status-badge :status="$instructor->status" /></td>
                            <td>
                                <div class="table__actions">
                                    @if ($instructor->status === 'pending')
                                        <button type="button" class="btn btn--success btn--sm"
                                                data-simulate="{{ $instructor->name }} approved"
                                                data-simulate-done="✓ Approved">
                                            Approve
                                        </button>
                                    @else
                                        <a href="{{ route('instructors.show', $instructor->id) }}"
                                           class="btn-icon btn-icon--sm" aria-label="View {{ $instructor->name }}">
                                            <x-icon name="eye" :size="15" />
                                        </a>
                                        <button type="button" class="btn-icon btn-icon--sm"
                                                data-toast="Edit form would open here" data-toast-type="info"
                                                aria-label="Edit {{ $instructor->name }}">
                                            <x-icon name="edit" :size="15" />
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--sm is-danger"
                                                data-confirm-delete="{{ $instructor->name }}"
                                                aria-label="Delete {{ $instructor->name }}">
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
            <x-empty-state emoji="👨‍🏫" title="No instructors match your search"
                           text="Try a different keyword or clear the status filter." />
        </div>
    </div>
</div>

@endsection
