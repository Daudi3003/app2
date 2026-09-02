@extends('layouts.student')

@section('title', $course->course_name)
@section('page_title', 'Course')
@section('page_subtitle', $course->course_name)

@section('content')

<x-breadcrumbs :items="[
    'Dashboard'  => route('student.dashboard'),
    'My Courses' => route('student.courses'),
    $course->course_name => null,
]" />

{{-- ---------- COURSE HEADER ---------- --}}
<div class="card card--pad mb-6">
    <div class="row row--top" style="gap:var(--sp-5)">
        <span class="continue-row__thumb" style="width:72px;height:72px;font-size:2rem" aria-hidden="true">
            {{ $course->emoji }}
        </span>

        <div style="flex:1;min-width:240px">
            <span class="badge badge--primary mb-4">{{ $course->category }}</span>
            <h2 style="font-size:var(--fs-2xl)">{{ $course->course_name }}</h2>
            <p class="t-muted">{{ $course->summary }}</p>

            <div class="row" style="gap:var(--sp-5)">
                <span class="t-sm"><x-icon name="user" :size="15" /> {{ $course->instructor_name }}</span>
                <span class="t-sm"><x-icon name="play-circle" :size="15" /> {{ $course->lessons_count }} lessons</span>
                <span class="t-sm"><x-icon name="clock" :size="15" /> {{ $course->duration }}</span>
                <span class="t-sm"><x-rating :score="$course->rating" :count="$course->reviews_count" /></span>
            </div>
        </div>

        <div style="min-width:220px">
            <x-progress :value="$enrolment?->progress ?? 0" label="Your progress" />
            <a href="{{ route('student.lesson', 1) }}" class="btn btn--primary btn--block mt-4">
                {{ ($enrolment?->progress ?? 0) > 0 ? 'Continue Learning' : 'Start Course' }}
                <x-icon name="arrow-right" :size="17" class="icon icon--shift" />
            </a>
        </div>
    </div>
</div>

<div class="dash-grid">

    {{-- ---------- CURRICULUM ---------- --}}
    <div class="card">
        <div class="card__head">
            <div>
                <h3>Course curriculum</h3>
                <p>{{ $course->curriculum->count() }} sections · {{ $course->lessons_count }} lessons</p>
            </div>
        </div>

        <div class="card__body">
            <div class="accordion" data-accordion="multi">
                @foreach ($course->curriculum as $section)
                    <div class="accordion__item {{ $loop->first ? 'is-open' : '' }}">
                        <button type="button" class="accordion__trigger" data-accordion-trigger
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                            <span>{{ $loop->iteration }}. {{ $section->title }}</span>
                            <span class="accordion__meta">{{ $section->lessons_count }} lessons</span>
                            <span class="accordion__chevron"><x-icon name="chevron-down" :size="18" /></span>
                        </button>

                        <div class="accordion__panel">
                            <div class="accordion__inner">
                                @foreach ($section->lessons as $lesson)
                                    @php $done = $lesson->lesson_order <= 11; @endphp
                                    <a href="{{ route('student.lesson', $lesson->id) }}"
                                       class="curriculum-item {{ $done ? 'is-done' : '' }}">
                                        <span class="curriculum-item__mark"><x-icon name="check" :size="12" :stroke="3" /></span>
                                        <span class="curriculum-item__body">
                                            {{ $lesson->title }}
                                            <span class="curriculum-item__time">{{ $lesson->duration }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ---------- SIDE ---------- --}}
    <aside class="stack" style="gap:var(--sp-6)">

        <div class="card card--pad t-center">
            <h3 class="mb-6">Your completion</h3>
            <div class="ring" style="margin:0 auto">
                <svg viewBox="0 0 100 100" aria-hidden="true">
                    <circle class="ring__track" cx="50" cy="50" r="42"></circle>
                    <circle class="ring__value" cx="50" cy="50" r="42"
                            data-ring="{{ $enrolment?->progress ?? 0 }}"></circle>
                </svg>
                <span class="ring__label">{{ $enrolment?->progress ?? 0 }}%</span>
            </div>
            <p class="t-sm t-muted mt-6 mb-0">
                {{ $enrolment?->completed_lessons ?? 0 }} of {{ $course->lessons_count }} lessons complete
            </p>
        </div>

        <div class="card">
            <div class="card__head"><div><h3>Course details</h3></div></div>
            <div class="list">
                <div class="list__item">
                    <span class="list__icon">📅</span>
                    <div class="list__body">
                        <div class="list__sub">Enrolled on</div>
                        <div class="list__title">{{ $enrolment?->enrollment_date ?? '—' }}</div>
                    </div>
                </div>
                <div class="list__item">
                    <span class="list__icon list__icon--info">🎯</span>
                    <div class="list__body">
                        <div class="list__sub">Level</div>
                        <div class="list__title">{{ $course->level }}</div>
                    </div>
                </div>
                <div class="list__item">
                    <span class="list__icon list__icon--warning">📖</span>
                    <div class="list__body">
                        <div class="list__sub">Last lesson</div>
                        <div class="list__title t-clamp-2">{{ $enrolment?->last_lesson ?? 'Not started' }}</div>
                    </div>
                </div>
                <div class="list__item">
                    <span class="list__icon list__icon--success">🏆</span>
                    <div class="list__body">
                        <div class="list__sub">Certificate</div>
                        <div class="list__title">
                            {{ ($enrolment?->progress ?? 0) >= 100 ? 'Earned' : 'On completion' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card--pad">
            <h3 style="font-size:var(--fs-md)">Need help?</h3>
            <p class="t-sm t-muted">Ask {{ $course->instructor_name }} a question about any lesson.</p>
            <a href="{{ route('student.messages') }}" class="btn btn--secondary btn--block">
                <x-icon name="message" :size="17" /> Message Instructor
            </a>
        </div>
    </aside>
</div>

@endsection
