@extends('layouts.base')

@section('title', $lesson->title)

@section('body')

{{-- The learning player uses a focused, distraction-free chrome of its own. --}}
<header class="navbar" data-navbar>
    <div class="navbar__inner">
        <x-brand />

        <div class="navbar__actions" style="gap:var(--sp-4)">
            {{-- Course title and inline progress are repeated in the sidebar,
                 so they are dropped on narrow screens to keep the bar tidy. --}}
            <span class="t-sm t-muted hide-sm" style="min-width:0;max-width:240px">
                <span class="t-clamp-1">{{ $course->course_name }}</span>
            </span>

            <div style="width:150px" class="hide-sm">
                <x-progress :value="round($position / $total * 100)" size="sm" />
            </div>

            <span class="badge badge--primary t-nowrap hide-xs">{{ $position }} / {{ $total }}</span>

            <a href="{{ route('student.course', $course->id) }}" class="btn btn--secondary btn--sm">
                <x-icon name="x" :size="16" /> Exit
            </a>
        </div>
    </div>
</header>

<main id="main" class="learn">

    {{-- ---------- CURRICULUM SIDEBAR ---------- --}}
    <aside class="learn__aside">
        <div class="learn__aside-head">
            <h3>Course content</h3>
            <x-progress :value="round($position / $total * 100)" label="Completed" size="sm" />
        </div>

        <div class="accordion" data-accordion="multi" style="border:0;border-radius:0">
            @foreach ($course->curriculum as $section)
                <div class="accordion__item {{ $section->lessons->contains('id', $lesson->id) ? 'is-open' : '' }}">
                    <button type="button" class="accordion__trigger" data-accordion-trigger
                            aria-expanded="{{ $section->lessons->contains('id', $lesson->id) ? 'true' : 'false' }}">
                        <span class="t-sm">{{ $loop->iteration }}. {{ $section->title }}</span>
                        <span class="accordion__meta">{{ $section->lessons_count }}</span>
                        <span class="accordion__chevron"><x-icon name="chevron-down" :size="16" /></span>
                    </button>

                    <div class="accordion__panel">
                        <div class="accordion__inner" style="padding-inline:var(--sp-3)">
                            @foreach ($section->lessons as $item)
                                <a href="{{ route('student.lesson', $item->id) }}"
                                   class="curriculum-item
                                          {{ $item->id === $lesson->id ? 'is-current' : '' }}
                                          {{ $item->lesson_order < $lesson->lesson_order ? 'is-done' : '' }}">
                                    <span class="curriculum-item__mark">
                                        <x-icon name="check" :size="12" :stroke="3" />
                                    </span>
                                    <span class="curriculum-item__body">
                                        {{ $item->title }}
                                        <span class="curriculum-item__time">
                                            <x-icon name="play-circle" :size="11" /> {{ $item->duration }}
                                        </span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </aside>

    {{-- ---------- LESSON ---------- --}}
    <div class="learn__main">

        <div class="player">
            <button type="button" class="player__btn" aria-label="Play lesson video">
                <x-icon name="play" :size="32" />
            </button>
            <div class="player__caption">
                <strong>{{ $lesson->title }}</strong>
                <span>{{ $lesson->duration }} · Lesson {{ $position }} of {{ $total }}</span>
            </div>
            <div class="player__bar"><i></i></div>
        </div>

        <div class="row row--between mt-6 mb-4">
            <div style="min-width:0">
                <span class="badge badge--primary">Lesson {{ $lesson->lesson_order }}</span>
                <h1 style="font-size:var(--fs-2xl);margin:8px 0 0">{{ $lesson->title }}</h1>
            </div>

            <div class="row" style="gap:var(--sp-2)">
                <button type="button" class="btn-icon" data-favourite="lesson-{{ $lesson->id }}"
                        aria-pressed="false" aria-label="Bookmark this lesson">
                    <x-icon name="bookmark" :size="18" />
                </button>
                <button type="button" class="btn btn--secondary btn--sm" data-lesson-toggle>
                    <x-icon name="check" :size="16" /> Mark Complete
                </button>
            </div>
        </div>

        {{-- Tabs: description / notes / resources / Q&A --}}
        <div class="card">
            <div class="card__body card__body--tight" style="padding-bottom:0">
                <div class="tabs" data-tabs="lesson" role="tablist" aria-label="Lesson details">
                    <button type="button" class="tabs__btn is-active" data-tab="about" role="tab" aria-selected="true">Description</button>
                    <button type="button" class="tabs__btn" data-tab="notes" role="tab" aria-selected="false">My Notes</button>
                    <button type="button" class="tabs__btn" data-tab="resources" role="tab" aria-selected="false">Resources</button>
                    <button type="button" class="tabs__btn" data-tab="qa" role="tab" aria-selected="false">Q&amp;A</button>
                </div>
            </div>

            <div class="card__body">

                <div class="tab-panel is-active" data-tab-panel="about" data-tab-scope="lesson" role="tabpanel">
                    <p>
                        In this lesson we work through <strong>{{ $lesson->title }}</strong> step by step.
                        Follow along in your own editor rather than only watching — the exercise at the end
                        assumes you have typed the examples yourself.
                    </p>
                    <p class="mb-0">
                        If anything is unclear, ask in the Q&amp;A tab. {{ $course->instructor_name }}
                        usually replies within a day.
                    </p>
                </div>

                <div class="tab-panel" data-tab-panel="notes" data-tab-scope="lesson" role="tabpanel">
                    <form class="form" data-notes-form="lesson-{{ $lesson->id }}">
                        <div class="field">
                            <label class="field__label" for="lessonNote">Add a note</label>
                            <textarea id="lessonNote" class="textarea notes-area"
                                      placeholder="Something you want to remember from this lesson…"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn btn--primary">
                                <x-icon name="plus" :size="16" /> Save Note
                            </button>
                        </div>
                    </form>

                    <hr>
                    <div data-notes-list></div>
                </div>

                <div class="tab-panel" data-tab-panel="resources" data-tab-scope="lesson" role="tabpanel">
                    <div class="file-list" style="margin-top:0">
                        <div class="file-row">
                            <span class="file-row__icon">📕</span>
                            <span class="file-row__body">
                                <span class="file-row__name">Lesson slides (PDF)</span>
                                <span class="file-row__meta">1.4 MB · updated August 2026</span>
                            </span>
                            <button type="button" class="btn btn--ghost btn--sm"
                                    data-toast="Download would start here" data-toast-type="info">
                                <x-icon name="download" :size="16" /> Download
                            </button>
                        </div>
                        <div class="file-row">
                            <span class="file-row__icon">🗜️</span>
                            <span class="file-row__body">
                                <span class="file-row__name">Starter files</span>
                                <span class="file-row__meta">4.6 MB · ZIP archive</span>
                            </span>
                            <button type="button" class="btn btn--ghost btn--sm"
                                    data-toast="Download would start here" data-toast-type="info">
                                <x-icon name="download" :size="16" /> Download
                            </button>
                        </div>
                        <div class="file-row">
                            <span class="file-row__icon">🔗</span>
                            <span class="file-row__body">
                                <span class="file-row__name">Further reading</span>
                                <span class="file-row__meta">External link</span>
                            </span>
                            <button type="button" class="btn btn--ghost btn--sm">
                                <x-icon name="external" :size="16" /> Open
                            </button>
                        </div>
                    </div>
                </div>

                <div class="tab-panel" data-tab-panel="qa" data-tab-scope="lesson" role="tabpanel">
                    <form class="form" data-simulate-form="Your question was posted 💬" data-simulate-reset>
                        <div class="field">
                            <label class="field__label" for="qaInput">Ask a question about this lesson</label>
                            <textarea id="qaInput" class="textarea" rows="3"
                                      placeholder="What would you like to ask?" required></textarea>
                        </div>
                        <div><button type="submit" class="btn btn--primary">Post Question</button></div>
                    </form>

                    <hr>

                    <div class="review">
                        <span class="avatar">GM</span>
                        <div class="review__body">
                            <div class="review__head">
                                <span class="review__name">Grace Mollel</span>
                                <span class="review__date">3 days ago</span>
                            </div>
                            <p class="review__text">
                                At around 14:20 you use a different selector than the one on the slide. Is either fine?
                            </p>
                            <div class="note-item mt-4">
                                <div class="note-item__time">{{ $course->instructor_name }} · Instructor</div>
                                Both work. I used the shorter one live to save time; the slide shows the more
                                explicit version, which is easier to read in a team codebase.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lesson navigation --}}
        <nav class="lesson-nav" aria-label="Lesson navigation">
            @if ($previous)
                <a href="{{ route('student.lesson', $previous->id) }}" class="btn btn--secondary">
                    <x-icon name="arrow-left" :size="17" /> Previous
                </a>
            @else
                <span class="btn btn--secondary is-disabled">
                    <x-icon name="arrow-left" :size="17" /> Previous
                </span>
            @endif

            <span class="t-sm t-muted t-center">Lesson {{ $position }} of {{ $total }}</span>

            @if ($next)
                <a href="{{ route('student.lesson', $next->id) }}" class="btn btn--primary">
                    Next Lesson <x-icon name="arrow-right" :size="17" class="icon icon--shift" />
                </a>
            @else
                <a href="{{ route('student.certificates') }}" class="btn btn--success">
                    Finish Course 🏆
                </a>
            @endif
        </nav>
    </div>
</main>

@endsection
