@extends('layouts.instructor')

@section('title', 'Instructor Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Your teaching at a glance')

@section('content')

<section class="welcome">
    <div class="welcome__body">
        <h2>Welcome back, {{ explode(' ', $instructor->name)[0] }}! 👨‍🏫</h2>
        <p>
            You have <strong style="color:#fff">4 assignments waiting to be graded</strong>
            and 28 new students enrolled this month.
        </p>
    </div>

    <div class="welcome__actions">
        <a href="{{ route('instructor.courses.create') }}" class="btn btn--white">
            <x-icon name="plus" :size="17" /> Create a Course
        </a>
        <a href="{{ route('instructor.assignments') }}" class="btn btn--outline-light">Grade Assignments</a>
    </div>

    <div class="welcome__art" aria-hidden="true">📚</div>
</section>

<section class="stats-row">
    @foreach ($stats as $stat)
        <x-stat-card :label="$stat->label" :value="$stat->value" :emoji="$stat->emoji"
                     :tone="$stat->tone" :delta="$stat->delta" :direction="$stat->direction" />
    @endforeach
</section>

<div class="dash-grid">

    <div class="stack" style="gap:var(--sp-6)">

        {{-- Enrolment chart --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>New enrolments</h3>
                    <p>Last six months across all your courses</p>
                </div>
                <span class="badge badge--success"><x-icon name="trending-up" :size="13" /> +12.4%</span>
            </div>

            <div class="card__body">
                <div class="chart">
                    @foreach ($chart as $point)
                        <div class="chart__col">
                            <div class="chart__bars">
                                <div class="chart__bar" data-bar="{{ $point->pct }}"
                                     data-tip="{{ $point->value }} enrolments"></div>
                            </div>
                            <span class="chart__label">{{ $point->label }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="chart-legend">
                    <span><i></i> New enrolments per month</span>
                </div>
            </div>
        </div>

        {{-- Course performance --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Course performance</h3>
                    <p>Student enrolments per course</p>
                </div>
                <a href="{{ route('instructor.reports') }}" class="btn btn--ghost btn--sm">Full report</a>
            </div>

            <div class="card__body">
                <div class="hbar">
                    @foreach ($performance as $item)
                        <div class="hbar__row">
                            <div class="hbar__head">
                                <span class="hbar__name t-clamp-2">{{ $item->name }}</span>
                                <span class="hbar__value">
                                    {{ $item->students }} students · FREE
                                </span>
                            </div>
                            <div class="progress">
                                <div class="progress__bar" data-progress="{{ $item->pct }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent enrolments --}}
        <div class="card">
            <div class="card__head">
                <div><h3>Recent enrolments</h3></div>
                <a href="{{ route('instructor.enrollments') }}" class="btn btn--ghost btn--sm">View all</a>
            </div>

            <div class="card__body card__body--flush">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Student</th>
                                <th scope="col">Course</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrolment)
                                <tr>
                                    <td>
                                        <div class="table__user">
                                            <span class="avatar avatar--sm">{{ $enrolment->initials }}</span>
                                            <span style="min-width:0">
                                                <span class="table__user-name">{{ $enrolment->student }}</span><br>
                                                <span class="table__user-sub">{{ $enrolment->email }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="t-clamp-2">{{ $enrolment->course_name }}</td>
                                    <td class="t-nowrap t-sm t-muted">{{ $enrolment->enrollment_date }}</td>
                                    <td><x-status-badge :status="$enrolment->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <aside class="stack" style="gap:var(--sp-6)">

        {{-- Needs grading --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Needs grading</h3>
                    <p>Submissions waiting on you</p>
                </div>
            </div>

            <div class="card__body card__body--flush">
                <div class="list">
                    @foreach ($submissions as $submission)
                        <div class="list__item">
                            <span class="avatar avatar--sm">{{ $submission->initials }}</span>
                            <div class="list__body">
                                <div class="list__title t-sm">{{ $submission->student }}</div>
                                <div class="list__sub t-clamp-2">{{ $submission->assignment }}</div>
                                <div class="t-xs t-muted">{{ $submission->submitted_at }}</div>
                            </div>
                            <div class="list__end">
                                @if ($submission->status === 'graded')
                                    <span class="badge badge--success">{{ $submission->score }}</span>
                                @else
                                    <button type="button" class="btn btn--primary btn--sm"
                                            data-toast="Grading view would open here" data-toast-type="info">
                                        Grade
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card__foot">
                <a href="{{ route('instructor.assignments') }}" class="btn btn--secondary btn--block btn--sm">
                    View all submissions
                </a>
            </div>
        </div>

        {{-- Your courses --}}
        <div class="card">
            <div class="card__head">
                <div><h3>Your courses</h3></div>
                <a href="{{ route('instructor.courses') }}" class="btn btn--ghost btn--sm">Manage</a>
            </div>

            <div class="card__body card__body--flush">
                <div class="list">
                    @foreach ($courses as $course)
                        <div class="list__item">
                            <span class="list__icon">{{ $course->emoji }}</span>
                            <div class="list__body">
                                <div class="list__title t-sm t-clamp-2">{{ $course->course_name }}</div>
                                <div class="list__sub">{{ $course->enrolled }} students</div>
                            </div>
                            <x-status-badge :status="$course->status" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card card--pad" style="background:var(--gradient-brand-soft);border-color:var(--primary-soft)">
            <h3 style="font-size:var(--fs-md)">💡 Teaching tip</h3>
            <p class="t-sm mb-0">
                Courses where the instructor replies to Q&amp;A within 24 hours have roughly
                double the completion rate. You currently average 18 hours — well ahead of target.
            </p>
        </div>
    </aside>
</div>

@endsection
