@extends('layouts.app')

@section('title', $instructor->name)
@section('meta_description', $instructor->headline)

@section('content')

<section class="page-head">
    <div class="container container--wide">
        <div class="page-head__inner">
            <x-breadcrumbs light :items="[
                'Home' => route('home'),
                'Instructors' => route('instructors.index'),
                $instructor->name => null,
            ]" />

            <div class="instructor-hero">
                <span class="avatar avatar--2xl avatar--ring">{{ $instructor->initials }}</span>

                <div>
                    <h1 class="mb-0">{{ $instructor->name }}</h1>
                    <p style="color:#c4b5fd;font-weight:650">{{ $instructor->headline }}</p>

                    <div class="row" style="gap:var(--sp-3)">
                        @foreach ($instructor->expertise as $skill)
                            <span class="badge" style="background:rgba(255,255,255,.12);color:#ddd6fe">{{ $skill }}</span>
                        @endforeach
                    </div>

                    <div class="instructor-hero__meta">
                        <div><strong>{{ $instructor->courses_count }}</strong><span>Courses</span></div>
                        <div><strong>{{ number_format($instructor->students_count) }}</strong><span>Students</span></div>
                        <div><strong>{{ $instructor->rating }} ⭐</strong><span>Average rating</span></div>
                        <div><strong>{{ number_format($instructor->reviews_count) }}</strong><span>Reviews</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--wide">
        <div class="dash-grid">

            <div class="stack" style="gap:var(--sp-6)">
                <div class="card card--pad">
                    <h2 style="font-size:var(--fs-xl)">About {{ explode(' ', $instructor->name)[0] }}</h2>
                    <p>{{ $instructor->bio }}</p>
                    <p class="mb-0">
                        {{ explode(' ', $instructor->name)[0] }} specialises in {{ $instructor->specialization }}
                        and has been teaching on {{ config('learnhub.name') }} since {{ $instructor->joined }}.
                    </p>
                </div>

                <div>
                    <h2 class="mb-6" style="font-size:var(--fs-xl)">
                        Courses by {{ $instructor->name }}
                    </h2>

                    <div class="courses-grid">
                        @forelse ($courses as $course)
                            <x-course-card :course="$course" />
                        @empty
                            <x-empty-state emoji="📚" title="No published courses yet"
                                           text="This instructor is preparing their first course."
                                           action="Browse All Courses"
                                           :action-url="route('courses.index')" />
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="stack" style="gap:var(--sp-6)">
                <div class="card">
                    <div class="card__head"><h3>At a glance</h3></div>
                    <div class="list">
                        <div class="list__item">
                            <span class="list__icon">📍</span>
                            <div class="list__body">
                                <div class="list__sub">Location</div>
                                <div class="list__title">{{ $instructor->location }}</div>
                            </div>
                        </div>
                        <div class="list__item">
                            <span class="list__icon list__icon--info">🎯</span>
                            <div class="list__body">
                                <div class="list__sub">Specialisation</div>
                                <div class="list__title">{{ $instructor->specialization }}</div>
                            </div>
                        </div>
                        <div class="list__item">
                            <span class="list__icon list__icon--success">📅</span>
                            <div class="list__body">
                                <div class="list__sub">Teaching since</div>
                                <div class="list__title">{{ $instructor->joined }}</div>
                            </div>
                        </div>
                        <div class="list__item">
                            <span class="list__icon list__icon--warning">⭐</span>
                            <div class="list__body">
                                <div class="list__sub">Instructor rating</div>
                                <div class="list__title">{{ $instructor->rating }} out of 5</div>
                            </div>
                        </div>
                    </div>
                    <div class="card__foot">
                        <button type="button" class="btn btn--primary btn--block"
                                data-simulate="Message sent to {{ $instructor->name }}">
                            <x-icon name="message" :size="17" /> Send a Message
                        </button>
                    </div>
                </div>

                <div class="card card--pad">
                    <h3>Teach with us</h3>
                    <p class="t-sm t-muted">Have expertise worth sharing? Apply to become a LearnHub instructor.</p>
                    <a href="{{ route('register') }}" class="btn btn--secondary btn--block">Apply Now</a>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection
