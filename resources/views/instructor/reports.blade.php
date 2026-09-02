@extends('layouts.instructor')

@section('title', 'Reports')
@section('page_title', 'Reports')
@section('page_subtitle', 'How your courses are performing')

@section('content')

<div class="pane-head">
    <div>
        <h2>Reports 📊</h2>
        <p>Enrolments and course performance across your catalogue.</p>
    </div>
    <div class="pane-head__actions">
        <label class="sr-only" for="reportRange">Reporting period</label>
        <select id="reportRange" class="select" style="width:auto">
            <option>Last 6 months</option>
            <option>Last 12 months</option>
            <option>Year to date</option>
        </select>
        <button type="button" class="btn btn--secondary"
                data-toast="Report exported as PDF" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export
        </button>
    </div>
</div>

<section class="stats-row">
    @foreach ($stats as $stat)
        <x-stat-card :label="$stat->label" :value="$stat->value" :emoji="$stat->emoji"
                     :tone="$stat->tone" :delta="$stat->delta" :direction="$stat->direction" />
    @endforeach
</section>

<div class="dash-grid dash-grid--even">

    {{-- Enrolments bar chart --}}
    <div class="card">
        <div class="card__head">
            <div>
                <h3>Enrolments per month</h3>
                <p>Across all published courses</p>
            </div>
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
            <div class="chart-legend"><span><i></i> New enrolments</span></div>
        </div>
    </div>

</div>

<div class="card mt-6">
    <div class="card__head">
        <div>
            <h3>Course performance</h3>
            <p>Ranked by enrolments</p>
        </div>
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

@endsection
