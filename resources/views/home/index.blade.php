@extends('layouts.app')

@section('title', 'Learn New Skills. Advance Your Future')

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="hero">
    <div class="container container--wide">
        <div class="hero__inner">

            <div class="hero__content">
                <span class="hero__eyebrow anim-fade-down">
                    🚀 Over 20,000 learners already started
                </span>

                <h1 class="anim-fade-up d-1">
                    Learn New Skills.<br>
                    <span class="t-gradient">Advance Your Future.</span>
                </h1>

                <p class="hero__lead anim-fade-up d-2">
                    Discover high-quality online courses taught by experienced instructors.
                    Study at your own pace, build real projects, and earn a certificate
                    that proves what you can do.
                </p>

                <div class="hero__actions anim-fade-up d-3">
                    <a href="{{ route('courses.index') }}" class="btn btn--primary btn--lg">
                        Explore Courses
                        <x-icon name="arrow-right" :size="18" class="icon icon--shift" />
                    </a>
                    <a href="{{ route('register') }}" class="btn btn--outline-light btn--lg">
                        Become an Instructor
                    </a>
                </div>

                <div class="hero__trust anim-fade-up d-4">
                    <div class="avatar-stack" aria-hidden="true">
                        <span class="avatar avatar--sm">AH</span>
                        <span class="avatar avatar--sm">JM</span>
                        <span class="avatar avatar--sm">FS</span>
                        <span class="avatar avatar--sm">BL</span>
                    </div>
                    <span>
                        <x-rating :score="4.8" :show-score="false" />
                        Rated <strong style="color:#fff">4.8 / 5</strong> by 12,400+ students
                    </span>
                </div>
            </div>

            <div class="hero__visual anim-scale-in d-3">
                <div class="hero__panel">
                    <div class="hero__panel-art" aria-hidden="true">🎓</div>
                </div>

                <div class="hero__float hero__float--1">
                    <span class="hero__float-icon" aria-hidden="true">📚</span>
                    <span>
                        <span class="hero__float-value counter" data-count="10" data-suffix="K+">0</span>
                        <span class="hero__float-label">Online Courses</span>
                    </span>
                </div>

                <div class="hero__float hero__float--2">
                    <span class="hero__float-icon" aria-hidden="true">👨‍🎓</span>
                    <span>
                        <span class="hero__float-value counter" data-count="20" data-suffix="K+">0</span>
                        <span class="hero__float-label">Happy Students</span>
                    </span>
                </div>

                <div class="hero__float hero__float--3">
                    <span class="hero__float-icon" aria-hidden="true">👨‍🏫</span>
                    <span>
                        <span class="hero__float-value counter" data-count="500" data-suffix="+">0</span>
                        <span class="hero__float-label">Expert Instructors</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== SEARCH BAND ===================== --}}
<section class="search-band">
    <div class="container">
        <div class="search-band__card" data-hero-search>
            <h2 class="search-band__title">What do you want to learn?</h2>

            <form class="search search--hero" action="{{ route('courses.index') }}" method="GET" role="search">
                <span class="search__icon"><x-icon name="search" :size="20" /></span>
                <label class="sr-only" for="heroSearch">Search courses</label>
                <input id="heroSearch" type="search" name="q" class="search__input"
                       placeholder="Try “web development”, “Python” or “design”…" autocomplete="off">
                <button type="submit" class="btn btn--primary search__submit">Search Courses</button>
            </form>

            <div style="position:relative">
                <div class="search-results" data-hero-results role="listbox" aria-label="Course suggestions"></div>
            </div>

            <div class="search-band__tags">
                <span>Popular:</span>
                <a href="{{ route('courses.index') }}">Web Development</a>
                <a href="{{ route('courses.index') }}">Python</a>
                <a href="{{ route('courses.index') }}">UI/UX Design</a>
                <a href="{{ route('courses.index') }}">Data Science</a>
                <a href="{{ route('courses.index') }}">Laravel</a>
            </div>
        </div>
    </div>
</section>

<script type="application/json" data-course-index>{!! $searchIndex !!}</script>

{{-- ===================== FEATURES ===================== --}}
<section class="section section--tight">
    <div class="container">
        <x-section-head
            eyebrow="Why LearnHub"
            title="Everything you need to actually finish a course"
            text="Most people abandon online courses. We designed LearnHub around the things that stop that happening." />

        <div class="grid grid--4">
            @foreach ($features as $feature)
                <div class="feature-card" data-reveal data-reveal-delay="{{ $loop->index * 70 }}">
                    <div class="feature-card__icon" aria-hidden="true">{{ $feature->emoji }}</div>
                    <h3>{{ $feature->title }}</h3>
                    <p>{{ $feature->text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== CATEGORIES ===================== --}}
<section class="section section--alt">
    <div class="container">
        <x-section-head
            eyebrow="Browse by topic"
            title="Course categories"
            text="Eight subject areas, each maintained by instructors who work in the field." />

        <div class="grid grid--4">
            @foreach ($categories as $category)
                <a href="{{ route('courses.index') }}" class="category-card"
                   data-reveal data-reveal-delay="{{ $loop->index * 50 }}">
                    <span class="category-card__icon" aria-hidden="true">{{ $category->emoji }}</span>
                    <span class="category-card__name">{{ $category->name }}</span>
                    <span class="category-card__count">{{ $category->courses_count }} courses</span>
                    <span class="category-card__arrow"><x-icon name="arrow-right" :size="17" /></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== POPULAR COURSES ===================== --}}
<section class="section">
    <div class="container container--wide">
        <div class="row row--between mb-8" style="align-items:flex-end">
            <x-section-head
                align="left"
                eyebrow="Most popular"
                title="Courses students love"
                text="Ranked by enrolments over the last ninety days."
                class="mb-0" />

            <a href="{{ route('courses.index') }}" class="btn btn--secondary">
                View All Courses <x-icon name="arrow-right" :size="16" class="icon icon--shift" />
            </a>
        </div>

        <div class="courses-grid">
            @forelse ($featured as $course)
                <div data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                    <x-course-card :course="$course" />
                </div>
            @empty
                <x-empty-state
                    emoji="📚"
                    title="No courses found"
                    text="Try changing your search or filters."
                    action="Browse All Courses"
                    :action-url="route('courses.index')" />
            @endforelse
        </div>
    </div>
</section>

{{-- ===================== STATS ===================== --}}
<section class="section section--tight">
    <div class="container">
        <div class="stats-band" data-reveal="scale">
            @foreach ($stats as $stat)
                <div class="stats-band__item">
                    <div class="stats-band__value counter"
                         data-count="{{ $stat->value }}" data-suffix="{{ $stat->suffix }}">0</div>
                    <div class="stats-band__label">{{ $stat->emoji }} {{ $stat->label }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== INSTRUCTORS ===================== --}}
<section class="section section--alt">
    <div class="container">
        <x-section-head
            eyebrow="Meet the team"
            title="Learn from people who do the work"
            text="Every LearnHub instructor is a working professional, reviewed before their first course goes live." />

        <div class="grid grid--4">
            @foreach ($instructors as $instructor)
                <a href="{{ route('instructors.show', $instructor->id) }}" class="instructor-card"
                   data-reveal data-reveal-delay="{{ $loop->index * 70 }}">
                    <span class="avatar avatar--xl avatar--ring">{{ $instructor->initials }}</span>
                    <span class="instructor-card__name">{{ $instructor->name }}</span>
                    <span class="instructor-card__role">{{ $instructor->specialization }}</span>
                    <p class="instructor-card__bio t-clamp-3">{{ $instructor->bio }}</p>

                    <div class="instructor-card__stats">
                        <div class="instructor-card__stat">
                            <strong>{{ $instructor->courses_count }}</strong>
                            <span>Courses</span>
                        </div>
                        <div class="instructor-card__stat">
                            <strong>{{ number_format($instructor->students_count / 1000, 1) }}K</strong>
                            <span>Students</span>
                        </div>
                        <div class="instructor-card__stat">
                            <strong>⭐ {{ $instructor->rating }}</strong>
                            <span>Rating</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== TESTIMONIALS ===================== --}}
<section class="section">
    <div class="container">
        <x-section-head
            eyebrow="Student stories"
            title="What our students say"
            text="Real outcomes from people who finished a course and did something with it." />

        <div class="grid grid--3">
            @foreach ($testimonials as $testimonial)
                <blockquote class="testimonial" data-reveal data-reveal-delay="{{ $loop->index * 80 }}">
                    <x-rating :score="$testimonial->rating" :show-score="false" />
                    <p class="testimonial__text">{{ $testimonial->text }}</p>
                    <footer class="testimonial__author">
                        <span class="avatar">{{ $testimonial->initials }}</span>
                        <span>
                            <span class="testimonial__name">{{ $testimonial->name }}</span><br>
                            <span class="testimonial__meta">{{ $testimonial->role }}</span>
                        </span>
                    </footer>
                </blockquote>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== CTA ===================== --}}
<section class="section section--tight">
    <div class="container">
        <div class="cta" data-reveal="scale">
            <h2>Start Learning Today 🚀</h2>
            <p>
                Join thousands of students learning new skills with {{ config('learnhub.name') }}.
                Your first course can start in the next five minutes.
            </p>
            <div class="cta__actions">
                <a href="{{ route('courses.index') }}" class="btn btn--white btn--lg">
                    Explore Courses
                    <x-icon name="arrow-right" :size="18" class="icon icon--shift" />
                </a>
                <a href="{{ route('register') }}" class="btn btn--outline-light btn--lg">
                    Create a Free Account
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
