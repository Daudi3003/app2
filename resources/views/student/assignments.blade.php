@extends('layouts.student')

@section('title', 'My Assignments')
@section('page_title', 'Assignments')
@section('page_subtitle', 'Submissions, grades and deadlines')

@section('content')

@php
    $tabs = [
        'all'       => ['label' => 'All',       'items' => $assignments],
        'pending'   => ['label' => 'Pending',   'items' => $assignments->whereIn('status', ['pending', 'overdue'])],
        'submitted' => ['label' => 'Submitted', 'items' => $assignments->where('status', 'submitted')],
        'graded'    => ['label' => 'Graded',    'items' => $assignments->where('status', 'graded')],
    ];
    $graded = $assignments->where('status', 'graded');
@endphp

<div class="pane-head">
    <div>
        <h2>Assignments 📝</h2>
        <p>{{ $assignments->count() }} total · {{ $tabs['pending']['items']->count() }} awaiting submission</p>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="Total" :value="$assignments->count()" emoji="📋" />
    <x-stat-card label="Pending" :value="$assignments->where('status', 'pending')->count()" emoji="🟡" tone="warning" />
    <x-stat-card label="Submitted" :value="$assignments->where('status', 'submitted')->count()" emoji="🔵" tone="info" />
    <x-stat-card label="Average Score"
                 :value="$graded->count() ? round($graded->avg('score')).'%' : '—'"
                 emoji="🟢" tone="success" />
</section>

<div class="tabs" data-tabs="assignments" role="tablist" aria-label="Filter assignments">
    @foreach ($tabs as $key => $tab)
        <button type="button" class="tabs__btn {{ $loop->first ? 'is-active' : '' }}"
                data-tab="{{ $key }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
            {{ $tab['label'] }} <span class="badge">{{ $tab['items']->count() }}</span>
        </button>
    @endforeach
</div>

@foreach ($tabs as $key => $tab)
    <div class="tab-panel {{ $loop->first ? 'is-active' : '' }}"
         data-tab-panel="{{ $key }}" data-tab-scope="assignments" role="tabpanel">

        @if ($tab['items']->isEmpty())
            <div class="card">
                <x-empty-state emoji="✅" title="No {{ strtolower($tab['label']) }} assignments"
                               text="Nothing to do here right now. Check back after your next lesson." />
            </div>
        @else
            <div class="card">
                <div class="card__body card__body--flush">
                    <div class="list">
                        @foreach ($tab['items'] as $assignment)
                            <div class="list__item list__item--top">
                                <span class="list__icon list__icon--{{ $assignment->status === 'overdue' ? 'danger' : ($assignment->status === 'graded' ? 'success' : ($assignment->status === 'submitted' ? 'info' : 'warning')) }}">
                                    {{ $assignment->emoji }}
                                </span>

                                <div class="list__body">
                                    <div class="list__title">{{ $assignment->title }}</div>
                                    <div class="list__sub">{{ $assignment->course_name }} · {{ $assignment->instructor }}</div>
                                    <p class="t-xs t-muted mt-4 mb-0 t-clamp-2">{{ $assignment->description }}</p>

                                    <div class="row mt-4" style="gap:var(--sp-4)">
                                        <span class="t-xs t-muted">
                                            <x-icon name="calendar" :size="13" /> Due {{ $assignment->due_date }}
                                        </span>
                                        <span class="t-xs t-muted">
                                            <x-icon name="target" :size="13" /> Max {{ $assignment->max_score }} points
                                        </span>
                                        @if ($assignment->score !== null)
                                            <span class="badge badge--success">
                                                Scored {{ $assignment->score }} / {{ $assignment->max_score }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="list__end" style="flex-direction:column;align-items:flex-end;gap:8px">
                                    <x-status-badge :status="$assignment->status" />

                                    @if (in_array($assignment->status, ['pending', 'overdue']))
                                        <form method="POST" action="{{ route('student.assignment.submit', $assignment->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <label class="btn btn--primary btn--sm" style="cursor:pointer">
                                                <x-icon name="upload" :size="15" /> Choose File
                                                <input type="file" name="file" required hidden onchange="this.form.submit()">
                                            </label>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn--secondary btn--sm"
                                                data-toast="Opening submission details" data-toast-type="info">
                                            View Details
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endforeach

{{-- Assignment submission is handled directly from each assignment row. --}}

@endsection
