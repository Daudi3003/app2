@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Platform overview')

@section('content')

<section class="welcome">
    <div class="welcome__body">
        <h2>Welcome back, {{ explode(' ', $admin->name)[0] }}! 🛠️</h2>
        <p>
            <strong style="color:#fff">2 courses</strong> and
            <strong style="color:#fff">1 instructor application</strong> are waiting for review.
            All LearnHub courses are 100% free for every learner.
        </p>
    </div>

    <div class="welcome__actions">
        <a href="{{ route('admin.courses') }}" class="btn btn--white">Review Courses</a>
        <a href="{{ route('admin.reports') }}" class="btn btn--outline-light">View Reports</a>
    </div>

    <div class="welcome__art" aria-hidden="true">📊</div>
</section>

<section class="stats-row">
    @foreach ($stats as $stat)
        <x-stat-card :label="$stat->label" :value="$stat->value" :emoji="$stat->emoji"
                     :tone="$stat->tone" :delta="$stat->delta" :direction="$stat->direction" />
    @endforeach
</section>

<div class="dash-grid">
    <div class="stack" style="gap:var(--sp-6)">

        {{-- Enrolment trend --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Enrolment trend</h3>
                    <p>New enrolments over the last twelve months</p>
                </div>
                <span class="badge badge--success"><x-icon name="trending-up" :size="13" /> +23.8%</span>
            </div>

            <div class="card__body">
                <div class="chart">
                    @foreach ($trend as $point)
                        <div class="chart__col">
                            <div class="chart__bars">
                                <div class="chart__bar" data-bar="{{ $point->pct }}"
                                     data-tip="{{ $point->value }} enrolments"></div>
                            </div>
                            <span class="chart__label">{{ $point->label }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="chart-legend"><span><i></i> Enrolments per month</span></div>
            </div>
        </div>

        {{-- Recent enrolments --}}
        <div class="card">
            <div class="card__head">
                <div><h3>Recent enrolments</h3></div>
                <a href="{{ route('admin.enrollments') }}" class="btn btn--ghost btn--sm">View all</a>
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
                                            <span class="table__user-name">{{ $enrolment->student }}</span>
                                        </div>
                                    </td>
                                    <td class="t-sm t-clamp-2">{{ $enrolment->course_name }}</td>
                                    <td class="t-nowrap t-sm t-muted">{{ $enrolment->enrollment_date }}</td>
                                    <td><x-status-badge :status="$enrolment->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top instructors --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Top instructors</h3>
                    <p>By student enrolments</p>
                </div>
                <a href="{{ route('admin.instructors') }}" class="btn btn--ghost btn--sm">Manage</a>
            </div>

            <div class="card__body">
                <div class="hbar">
                    @foreach ($instructors as $instructor)
                        <div class="hbar__row">
                            <div class="hbar__head">
                                <span class="hbar__name">
                                    {{ $instructor->name }}
                                    <span class="t-xs t-muted">· {{ $instructor->courses }} courses</span>
                                </span>
                                <span class="hbar__value">{{ number_format($instructor->students) }} students</span>
                            </div>
                            <div class="progress">
                                <div class="progress__bar" data-progress="{{ $instructor->pct }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <aside class="stack" style="gap:var(--sp-6)">

        {{-- Category donut --}}
        @php
            $circumference = 2 * M_PI * 45;
            $offset = 0;
        @endphp

        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Courses by category</h3>
                    <p>Share of the catalogue</p>
                </div>
            </div>

            <div class="card__body">
                <div class="donut-wrap">
                    <svg class="donut" viewBox="0 0 100 100" role="img"
                         aria-label="Course distribution by category">
                        @foreach ($breakdown as $slice)
                            @php
                                $dash = $slice->value / 100 * $circumference;
                                $thisOffset = $offset;
                                $offset += $dash;
                            @endphp
                            <circle cx="50" cy="50" r="45" stroke="{{ $slice->color }}"
                                    stroke-dasharray="{{ round($dash, 2) }} {{ round($circumference - $dash, 2) }}"
                                    stroke-dashoffset="{{ round(-$thisOffset, 2) }}"
                                    transform="rotate(-90 50 50)">
                                <title>{{ $slice->name }}: {{ $slice->value }}%</title>
                            </circle>
                        @endforeach
                    </svg>

                    <div class="donut-legend">
                        @foreach ($breakdown as $slice)
                            <div class="donut-legend__row">
                                <span class="donut-legend__dot" style="background:{{ $slice->color }}"></span>
                                <span class="donut-legend__name">{{ $slice->name }}</span>
                                <span class="donut-legend__val">{{ $slice->value }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity feed --}}
        <div class="card">
            <div class="card__head">
                <div><h3>Recent activity</h3></div>
            </div>
            <div class="card__body card__body--flush">
                <div class="list">
                    @foreach ($activity as $item)
                        <div class="list__item">
                            <span class="list__icon list__icon--{{ $item->tone }}">{{ $item->emoji }}</span>
                            <div class="list__body">
                                <div class="list__title t-sm">{{ $item->title }}</div>
                                <div class="list__sub t-clamp-2">{{ $item->sub }}</div>
                            </div>
                            <span class="list__time">{{ $item->time }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- System status --}}
        <div class="card card--pad">
            <h3 style="font-size:var(--fs-md)">System status</h3>
            <div class="stack mt-4" style="gap:var(--sp-3)">
                <div class="row row--between">
                    <span class="t-sm">Application</span>
                    <span class="badge badge--success badge--dot">Operational</span>
                </div>
                <div class="row row--between">
                    <span class="t-sm">Database</span>
                    <span class="badge badge--success badge--dot">Operational</span>
                </div>
                <div class="row row--between">
                    <span class="t-sm">Media storage</span>
                    <span class="badge badge--success badge--dot">Operational</span>
                </div>
                <div class="row row--between">
                    <span class="t-sm">Email delivery</span>
                    <span class="badge badge--warning badge--dot">Degraded</span>
                </div>
            </div>
        </div>
    </aside>
</div>

@endsection
