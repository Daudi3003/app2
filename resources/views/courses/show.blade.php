@extends('layouts.app')

@section('title', $course->course_name)
@section('meta_description', $course->summary)

@section('content')

<section class="course-hero">
    <div class="container container--wide">
        <div class="course-hero__inner">
            <x-breadcrumbs light :items="[
                'Home'    => route('home'),
                'Courses' => route('courses.index'),
                $course->category => null,
            ]" />

            <div class="row">
                <span class="badge badge--primary">{{ $course->category }}</span>
                @if ($course->is_bestseller)
                    <span class="badge badge--warning">🏆 Bestseller</span>
                @endif
                <span class="badge">{{ $course->level }}</span>
            </div>

            <h1>{{ $course->course_name }}</h1>
            <p class="course-hero__lead">{{ $course->summary }}</p>

            <div class="course-hero__meta">
                <span><x-rating :score="$course->rating" :count="$course->reviews_count" /></span>
                <span><x-icon name="users" :size="16" /> {{ number_format($course->students_count) }} students</span>
                <span><x-icon name="clock" :size="16" /> {{ $course->duration }}</span>
                <span><x-icon name="play-circle" :size="16" /> {{ $course->lessons_count }} lessons</span>
                <span><x-icon name="globe" :size="16" /> {{ $course->language }}</span>
                <span><x-icon name="refresh" :size="16" /> {{ $course->updated_at_label }}</span>
            </div>

            <div class="row mt-6">
                <span class="avatar avatar--sm">{{ $course->instructor->initials }}</span>
                <span style="color:#cbd5e1">
                    Created by
                    <a href="{{ route('instructors.show', $course->instructor->id) }}"
                       style="color:#c4b5fd;font-weight:650">{{ $course->instructor_name }}</a>
                </span>
            </div>
        </div>
    </div>
</section>

<section class="section section--tight">
    <div class="container container--wide">
        <div class="course-layout">

            {{-- ---------- MAIN COLUMN ---------- --}}
            <div class="stack" style="gap:var(--sp-6)">

                {{-- What you'll learn --}}
                <div class="card card--pad" data-reveal>
                    <h2 class="mb-6" style="font-size:var(--fs-xl)">What you'll learn</h2>
                    <ul class="learn-list">
                        @foreach ($course->what_you_learn as $outcome)
                            <li><x-icon name="check" :size="17" /> <span>{{ $outcome }}</span></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Tabs: overview / curriculum / instructor / reviews --}}
                <div class="card">
                    <div class="card__body card__body--tight" style="padding-bottom:0">
                        <div class="tabs" data-tabs="course" role="tablist" aria-label="Course details">
                            <button type="button" class="tabs__btn is-active" data-tab="overview"
                                    role="tab" aria-selected="true">Overview</button>
                            <button type="button" class="tabs__btn" data-tab="curriculum"
                                    role="tab" aria-selected="false">Curriculum</button>
                            <button type="button" class="tabs__btn" data-tab="instructor"
                                    role="tab" aria-selected="false">Instructor</button>
                            <button type="button" class="tabs__btn" data-tab="reviews"
                                    role="tab" aria-selected="false">
                                Reviews <span class="badge">{{ number_format($course->reviews_count) }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="card__body">

                        {{-- OVERVIEW --}}
                        <div class="tab-panel is-active" data-tab-panel="overview" data-tab-scope="course" role="tabpanel">
                            <h3>Course description</h3>
                            <p>{{ $course->description }}</p>

                            <h3 class="mt-8">Requirements</h3>
                            <ul class="req-list">
                                @foreach ($course->requirements as $requirement)
                                    <li>{{ $requirement }}</li>
                                @endforeach
                            </ul>

                            <h3 class="mt-8">This course includes</h3>
                            <ul class="learn-list">
                                <li><x-icon name="play-circle" :size="17" /> <span>{{ $course->duration }} of on-demand video</span></li>
                                <li><x-icon name="file" :size="17" /> <span>Downloadable resources for every section</span></li>
                                <li><x-icon name="clipboard" :size="17" /> <span>Practical assignments with instructor feedback</span></li>
                                <li><x-icon name="award" :size="17" /> <span>Certificate of completion</span></li>
                                <li><x-icon name="refresh" :size="17" /> <span>Lifetime access, including future updates</span></li>
                                <li><x-icon name="message" :size="17" /> <span>Direct Q&amp;A with the instructor</span></li>
                            </ul>
                        </div>

                        {{-- CURRICULUM --}}
                        <div class="tab-panel" data-tab-panel="curriculum" data-tab-scope="course" role="tabpanel">
                            <div class="row row--between mb-4">
                                <h3 class="mb-0">Course curriculum</h3>
                                <span class="t-sm t-muted">
                                    {{ $course->curriculum->count() }} sections ·
                                    {{ $course->lessons_count }} lessons ·
                                    {{ $course->duration }}
                                </span>
                            </div>

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
                                                    <div class="lesson-row">
                                                        <span class="lesson-row__icon">
                                                            <x-icon name="{{ $lesson->preview ? 'play-circle' : 'lock' }}" :size="16" />
                                                        </span>
                                                        <span class="lesson-row__title">{{ $lesson->title }}</span>
                                                        @if ($lesson->preview)
                                                            <span class="badge badge--primary">Preview</span>
                                                        @endif
                                                        <span class="lesson-row__time">{{ $lesson->duration }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- INSTRUCTOR --}}
                        <div class="tab-panel" data-tab-panel="instructor" data-tab-scope="course" role="tabpanel">
                            <div class="row row--top" style="gap:var(--sp-5)">
                                <span class="avatar avatar--xl avatar--ring">{{ $course->instructor->initials }}</span>
                                <div style="flex:1;min-width:200px">
                                    <h3 class="mb-0">
                                        <a href="{{ route('instructors.show', $course->instructor->id) }}">
                                            {{ $course->instructor_name }}
                                        </a>
                                    </h3>
                                    <p class="t-muted t-sm">{{ $course->instructor->headline }}</p>

                                    <div class="row" style="gap:var(--sp-5)">
                                        <span class="t-sm"><x-icon name="star" :size="15" /> {{ $course->instructor->rating }} rating</span>
                                        <span class="t-sm"><x-icon name="message" :size="15" /> {{ number_format($course->instructor->reviews_count) }} reviews</span>
                                        <span class="t-sm"><x-icon name="users" :size="15" /> {{ number_format($course->instructor->students_count) }} students</span>
                                        <span class="t-sm"><x-icon name="book" :size="15" /> {{ $course->instructor->courses_count }} courses</span>
                                    </div>

                                    <p class="mt-4">{{ $course->instructor->bio }}</p>

                                    <a href="{{ route('instructors.show', $course->instructor->id) }}" class="btn btn--secondary btn--sm">
                                        View full profile <x-icon name="arrow-right" :size="15" class="icon icon--shift" />
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- REVIEWS --}}
                        <div class="tab-panel" data-tab-panel="reviews" data-tab-scope="course" role="tabpanel">
                            <div class="rating-summary">
                                <div class="rating-summary__score">
                                    <strong>{{ number_format($course->rating, 1) }}</strong>
                                    <x-rating :score="$course->rating" :show-score="false" />
                                    <p class="t-xs t-muted mt-4 mb-0">{{ number_format($course->reviews_count) }} reviews</p>
                                </div>

                                <div class="rating-bars">
                                    @foreach ([5 => 78, 4 => 16, 3 => 4, 2 => 1, 1 => 1] as $stars => $pct)
                                        <div class="rating-bar">
                                            <span class="rating-bar__label">{{ $stars }} star</span>
                                            <div class="progress progress--sm">
                                                <div class="progress__bar" data-progress="{{ $pct }}"></div>
                                            </div>
                                            <span class="rating-bar__pct">{{ $pct }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @foreach ($course->reviews as $review)
                                <div class="review">
                                    <span class="avatar">{{ $review->initials }}</span>
                                    <div class="review__body">
                                        <div class="review__head">
                                            <span class="review__name">{{ $review->name }}</span>
                                            <x-rating :score="$review->rating" :show-score="false" />
                                            <span class="review__date">{{ $review->date }}</span>
                                        </div>
                                        <p class="review__text">{{ $review->text }}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="t-center mt-6">
                                <button type="button" class="btn btn--secondary"
                                        data-toast="All {{ number_format($course->reviews_count) }} reviews would load here"
                                        data-toast-type="info">
                                    Load more reviews
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ---------- ENROL SIDEBAR ---------- --}}
            <aside class="course-layout__aside">
                <div class="enroll-card">
                    <button type="button" class="enroll-card__preview" data-modal-open="previewModal"
                            aria-label="Play course preview">
                        <span class="emoji" aria-hidden="true">{{ $course->emoji }}</span>
                        <span class="enroll-card__play"><x-icon name="play" :size="24" /></span>
                    </button>

                    <div class="enroll-card__body">
                        <div class="enroll-card__price">
                            <strong>FREE 🎓</strong>
                            <span class="badge badge--success">100% Free</span>
                        </div>
@auth
                            @if(auth()->user()->usertype === 'student')
                                @if($course->enrolled)
                                    <span class="btn btn--success btn--lg btn--block mb-4">✓ Enrolled</span>
                                @else
                                    <form method="POST" action="{{ route('student.enroll', $course->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn--primary btn--lg btn--block mb-4">Enroll Now</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn--primary btn--lg btn--block mb-4">Login as Student to Enroll</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn--primary btn--lg btn--block mb-4">Login to Enroll</a>
                        @endauth

                        <p class="t-xs t-muted t-center mt-4 mb-0">100% free — no payment required.</p>

                        <ul class="enroll-card__includes">
                            <li><x-icon name="play-circle" :size="17" /> {{ $course->duration }} on-demand video</li>
                            <li><x-icon name="file" :size="17" /> {{ $course->lessons_count }} lessons &amp; resources</li>
                            <li><x-icon name="clipboard" :size="17" /> Assignments with feedback</li>
                            <li><x-icon name="refresh" :size="17" /> Full lifetime access</li>
                            <li><x-icon name="globe" :size="17" /> Access on mobile and desktop</li>
                            @if ($course->certificate)
                                <li><x-icon name="award" :size="17" /> Certificate of completion</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ---------- RELATED ---------- --}}
@if ($related->isNotEmpty())
    <section class="section section--alt">
        <div class="container container--wide">
            <x-section-head align="left" eyebrow="More like this"
                            title="Related courses in {{ $course->category }}" />

            <div class="courses-grid">
                @foreach ($related as $item)
                    <x-course-card :course="$item" />
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ---------- PREVIEW MODAL ---------- --}}
<div class="modal" id="previewModal" role="dialog" aria-modal="true"
     aria-labelledby="previewTitle" aria-hidden="true">
    <div class="modal__dialog modal__dialog--lg">
        <div class="modal__head">
            <div>
                <h3 id="previewTitle">Course preview</h3>
                <p>{{ $course->course_name }}</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close preview">
                <x-icon name="x" :size="18" />
            </button>
        </div>
        <div class="modal__body">
            <div class="player">
                <button type="button" class="player__btn" aria-label="Play preview video">
                    <x-icon name="play" :size="28" />
                </button>
                <div class="player__caption">
                    <strong>Welcome to the Course</strong>
                    <span>Free preview · 6:12</span>
                </div>
                <div class="player__bar"><i></i></div>
            </div>
            <p class="t-sm t-muted mt-4 mb-0">
                Video hosting is connected during the media phase. This placeholder shows the
                finished player layout and controls.
            </p>
        </div>
        <div class="modal__foot">
            <button type="button" class="btn btn--secondary" data-modal-close>Close</button>
            <button type="button" class="btn btn--primary"
                    data-simulate="Course enrolled successfully 🎉">Enroll Now</button>
        </div>
    </div>
</div>

@endsection
