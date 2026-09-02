@extends('layouts.student')

@section('title', 'Student Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Your learning at a glance')

@section('content')

{{-- ---------- WELCOME ---------- --}}
<section class="welcome">
    <div class="welcome__body">
        <h2>Welcome back, {{ $student->first_name }}! 👋</h2>
        <p>
            Ready to continue your learning journey? You are on a
            <strong style="color:#fff">{{ $student->learning_streak }}-day streak</strong>
            and have studied {{ $student->hours_this_week }} hours this week.
        </p>
    </div>

    <div class="welcome__actions">
        <a href="{{ route('student.courses') }}" class="btn btn--white">
            Continue Learning <x-icon name="arrow-right" :size="17" class="icon icon--shift" />
        </a>
        <a href="{{ route('courses.index') }}" class="btn btn--outline-light">Browse Courses</a>
    </div>

    <div class="welcome__art" aria-hidden="true">🎓</div>
</section>

{{-- ---------- STATS ---------- --}}
<section class="stats-row">
    @foreach ($stats as $stat)
        <x-stat-card
            :label="$stat->label"
            :value="$stat->value"
            :emoji="$stat->emoji"
            :tone="$stat->tone"
            :delta="$stat->delta"
            :direction="$stat->direction" />
    @endforeach
</section>

<div class="dash-grid">

    {{-- ---------- LEFT COLUMN ---------- --}}
    <div class="stack" style="gap:var(--sp-6)">

        {{-- Continue learning --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Continue learning</h3>
                    <p>Pick up where you left off</p>
                </div>
                <a href="{{ route('student.courses') }}" class="btn btn--ghost btn--sm">View all</a>
            </div>

            <div class="card__body card__body--flush">
                @forelse ($continuing as $course)
                    <div class="continue-row">
                        <span class="continue-row__thumb" aria-hidden="true">{{ $course->emoji }}</span>

                        <div class="continue-row__body">
                            <div class="continue-row__title t-clamp-2">{{ $course->course_name }}</div>
                            <div class="continue-row__sub">
                                {{ $course->instructor_name }} ·
                                {{ $course->completed_lessons }} of {{ $course->lessons_count }} lessons
                            </div>
                            <x-progress :value="$course->progress" />
                        </div>

                        <div class="continue-row__end">
                            <a href="{{ route('student.course', $course->id) }}" class="btn btn--primary btn--sm">
                                Continue
                            </a>
                        </div>
                    </div>
                @empty
                    <x-empty-state emoji="📚" title="Nothing in progress"
                                   text="Enrol in a course to start learning."
                                   action="Browse Courses" :action-url="route('courses.index')" />
                @endforelse
            </div>
        </div>

        {{-- Upcoming assignments --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Upcoming assignments</h3>
                    <p>Due soon — do not let these slip</p>
                </div>
                <a href="{{ route('student.assignments') }}" class="btn btn--ghost btn--sm">View all</a>
            </div>

            <div class="card__body card__body--flush">
                <div class="list">
                    @forelse ($assignments as $assignment)
                        <div class="list__item">
                            <span class="list__icon list__icon--{{ $assignment->status === 'overdue' ? 'danger' : 'warning' }}">
                                {{ $assignment->emoji }}
                            </span>

                            <div class="list__body">
                                <div class="list__title t-clamp-2">{{ $assignment->title }}</div>
                                <div class="list__sub">{{ $assignment->course_name }}</div>
                            </div>

                            <div class="list__end">
                                <span class="t-xs t-muted t-nowrap">{{ $assignment->due_date }}</span>
                                <x-status-badge :status="$assignment->status" />
                            </div>
                        </div>
                    @empty
                        <x-empty-state emoji="✅" title="No assignments due"
                                       text="You are all caught up. Nice work." />
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recommended --}}
        <div class="card">
            <div class="card__head">
                <div>
                    <h3>Recommended for you</h3>
                    <p>Based on what you have been studying</p>
                </div>
            </div>
            <div class="card__body">
                {{-- auto-fit so the cards reflow instead of squeezing --}}
                <div class="grid grid--auto-sm">
                    @foreach ($recommended as $course)
                        <x-course-card :course="$course" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ---------- RIGHT COLUMN ---------- ---------- --}}
    <aside class="stack" style="gap:var(--sp-6)">

        {{-- Overall progress ring --}}
        <div class="card card--pad t-center">
            <h3 class="mb-6">Overall progress</h3>

            <div class="ring" style="margin:0 auto">
                <svg viewBox="0 0 100 100" aria-hidden="true">
                    <circle class="ring__track" cx="50" cy="50" r="42"></circle>
                    <circle class="ring__value" cx="50" cy="50" r="42" data-ring="61"></circle>
                </svg>
                <span class="ring__label">61%</span>
            </div>

            <p class="t-sm t-muted mt-6 mb-0">
                You have completed 4 of 12 enrolled courses. Keep going — you are over halfway
                through the full-stack track.
            </p>
        </div>

        {{-- Notifications --}}
        <div class="card">
            <div class="card__head">
                <div><h3>Notifications</h3></div>
                <button type="button" class="btn btn--ghost btn--sm" data-mark-all-read>Mark all read</button>
            </div>

            <div class="card__body card__body--flush">
                <div class="list">
                    @foreach ($notifications as $note)
                        <div class="list__item list__item--top {{ $note->unread ? 'is-unread' : '' }}">
                            <span class="list__icon list__icon--{{ $note->tone }}">{{ $note->emoji }}</span>
                            <div class="list__body">
                                <div class="list__title t-sm">{{ $note->title }}</div>
                                <div class="list__sub t-clamp-2">{{ $note->text }}</div>
                                <div class="t-xs t-muted">{{ $note->time }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card__foot">
                <a href="{{ route('student.notifications') }}" class="btn btn--secondary btn--block btn--sm">
                    View all notifications
                </a>
            </div>
        </div>

        {{-- Recent activity --}}
        <div class="card">
            <div class="card__head"><div><h3>Recent activity</h3></div></div>
            <div class="card__body card__body--flush">
                <div class="list">
                    @foreach ($activity as $item)
                        <div class="list__item">
                            <span class="list__icon list__icon--{{ $item->tone }}">{{ $item->emoji }}</span>
                            <div class="list__body">
                                <div class="list__title t-sm t-clamp-2">{{ $item->title }}</div>
                                <div class="list__sub">{{ $item->sub }}</div>
                            </div>
                            <span class="list__time">{{ $item->time }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Learning tip --}}
        <div class="card card--pad" style="background:var(--gradient-brand-soft);border-color:var(--primary-soft)">
            <h3 style="font-size:var(--fs-md)">💡 Learning tip</h3>
            <p class="t-sm mb-0">
                Study in 25-minute blocks with a five-minute break between them. Learners who do
                this finish courses roughly 40% more often than those who binge whole sections.
            </p>
        </div>
    </aside>
</div>

@endsection
