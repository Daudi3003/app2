@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page_title', 'Reports')
@section('page_subtitle', 'Platform analytics')

@section('content')

<div class="pane-head">
    <div>
        <h2>Reports &amp; Analytics 📊</h2>
        <p>Enrolments, growth and instructor performance.</p>
    </div>
    <div class="pane-head__actions">
        <label class="sr-only" for="adReportRange">Reporting period</label>
        <select id="adReportRange" class="select" style="width:auto">
            <option>Last 12 months</option>
            <option>Last 6 months</option>
            <option>Year to date</option>
            <option>All time</option>
        </select>
        <button type="button" class="btn btn--primary"
                data-toast="Report exported as PDF" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export Report
        </button>
    </div>
</div>

<section class="stats-row">
    @foreach ($stats as $stat)
        <x-stat-card :label="$stat->label" :value="$stat->value" :emoji="$stat->emoji"
                     :tone="$stat->tone" :delta="$stat->delta" :direction="$stat->direction" />
    @endforeach
</section>

{{-- ---------- ENROLMENT TREND ---------- --}}
<div class="card mb-6">
    <div class="card__head">
        <div>
            <h3>Enrolment statistics</h3>
            <p>New enrolments per month across the platform</p>
        </div>
        <span class="badge badge--success"><x-icon name="trending-up" :size="13" /> +23.8% year on year</span>
    </div>

    <div class="card__body">
        <div class="chart">
            @foreach ($trend as $point)
                <div class="chart__col">
                    <div class="chart__bars">
                        <div class="chart__bar" data-bar="{{ $point->pct }}"
                             data-tip="{{ $point->label }}: {{ $point->value }}"></div>
                    </div>
                    <span class="chart__label">{{ $point->label }}</span>
                </div>
            @endforeach
        </div>
        <div class="chart-legend"><span><i></i> Enrolments</span></div>
    </div>
</div>

<div class="dash-grid dash-grid--even mb-6">


    {{-- ---------- STUDENT GROWTH ---------- --}}
    <div class="card">
        <div class="card__head">
            <div>
                <h3>Student growth</h3>
                <p>New student registrations per month</p>
            </div>
            <span class="badge badge--success">+126%</span>
        </div>

        <div class="card__body">
            <div class="chart">
                @foreach ($growth as $point)
                    <div class="chart__col">
                        <div class="chart__bars">
                            <div class="chart__bar chart__bar--alt" data-bar="{{ $point->pct }}"
                                 data-tip="{{ $point->value }} new students"></div>
                        </div>
                        <span class="chart__label">{{ $point->label }}</span>
                    </div>
                @endforeach
            </div>
            <div class="chart-legend"><span><i class="alt"></i> New registrations</span></div>
        </div>
    </div>
</div>

<div class="dash-grid mb-6">

    {{-- ---------- INSTRUCTOR PERFORMANCE ---------- --}}
    <div class="card">
        <div class="card__head">
            <div>
                <h3>Instructor performance</h3>
                <p>Ranked by student enrolments</p>
            </div>
            <a href="{{ route('admin.instructors') }}" class="btn btn--ghost btn--sm">Manage</a>
        </div>

        <div class="card__body card__body--flush">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Instructor</th>
                            <th scope="col" class="is-numeric">Courses</th>
                            <th scope="col" class="is-numeric">Students</th>
                            <th scope="col">Rating</th>
                            <th scope="col" style="min-width:140px">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($instructors as $instructor)
                            <tr>
                                <td>
                                    <div class="table__user">
                                        <span class="avatar avatar--sm">{{ $instructor->initials }}</span>
                                        <span class="table__user-name">{{ $instructor->name }}</span>
                                    </div>
                                </td>
                                <td class="is-numeric">{{ $instructor->courses }}</td>
                                <td class="is-numeric">{{ number_format($instructor->students) }}</td>
                                <td class="t-nowrap"><x-rating :score="$instructor->rating" /></td>
<td>
                                    <div class="progress progress--sm">
                                        <div class="progress__bar" data-progress="{{ $instructor->pct }}"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ---------- CATEGORY SPLIT ---------- --}}
    @php $circumference = 2 * M_PI * 45; $offset = 0; @endphp

    <div class="card">
        <div class="card__head">
            <div>
                <h3>Course categories</h3>
                <p>Share of enrolments</p>
            </div>
        </div>

        <div class="card__body">
            <div class="donut-wrap">
                <svg class="donut" viewBox="0 0 100 100" role="img" aria-label="Enrolments by category">
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
</div>

{{-- ---------- SAVED REPORTS (maps to the reports table) ---------- --}}
<div class="card">
    <div class="card__head">
        <div>
            <h3>Saved reports</h3>
            <p>Generated reports available to download</p>
        </div>
        <button type="button" class="btn btn--primary btn--sm"
                data-toast="New report queued for generation" data-toast-type="success">
            <x-icon name="plus" :size="15" /> Generate Report
        </button>
    </div>

    <div class="card__body card__body--flush">
        <div class="list">
            @forelse ($reports as $report)
                <div class="list__item">
                    <span class="list__icon">{{ $report->emoji }}</span>
                    <div class="list__body">
                        <div class="list__title">{{ $report->title }}</div>
                        <div class="list__sub">{{ $report->description }}</div>
                    </div>
                    <div class="list__end">
                        <span class="badge badge--primary">{{ $report->type }}</span>
                        <span class="t-xs t-muted t-nowrap">{{ $report->report_date }}</span>
                        <button type="button" class="btn-icon btn-icon--sm"
                                data-toast="Report download would start here" data-toast-type="info"
                                aria-label="Download {{ $report->title }}">
                            <x-icon name="download" :size="15" />
                        </button>
                    </div>
                </div>
            @empty
                <x-empty-state emoji="📊" title="No reports generated yet"
                               text="Generate your first report to see it listed here." />
            @endforelse
        </div>
    </div>
</div>

@endsection
