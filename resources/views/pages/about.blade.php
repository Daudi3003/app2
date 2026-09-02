@extends('layouts.app')

@section('title', 'About Us')

@section('content')

<section class="page-head">
    <div class="container container--wide">
        <div class="page-head__inner">
            <x-breadcrumbs light :items="['Home' => route('home'), 'About' => null]" />
            <h1>About {{ config('learnhub.name') }}</h1>
            <p>We build the online learning platform we wished existed when we were starting out.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-split">
            <div data-reveal="left">
                <span class="eyebrow">Our mission</span>
                <h2>Learning that leads somewhere</h2>
                <p>
                    {{ config('learnhub.name') }} started with a simple observation: most people who
                    enrol in an online course never finish it. Not because they lack motivation, but
                    because the course was built to be watched rather than practised.
                </p>
                <p>
                    So we built a platform around doing. Every LearnHub course ends its sections with a
                    project brief instead of a quiz, every instructor is a working professional, and
                    every student gets feedback on the work they submit.
                </p>

                <ul class="value-list">
                    <li><x-icon name="check-circle" :size="19" /><span><strong>Practitioners, not presenters.</strong> Instructors are vetted on the work they do, not on their showreel.</span></li>
                    <li><x-icon name="check-circle" :size="19" /><span><strong>Projects over playlists.</strong> You finish with something you built, not a list of videos you watched.</span></li>
                    <li><x-icon name="check-circle" :size="19" /><span><strong>Access that lasts.</strong> Buy once, keep forever, including every future update.</span></li>
                    <li><x-icon name="check-circle" :size="19" /><span><strong>Free for everyone.</strong> Every course is available at no cost, so finances never stand between you and learning.</span></li>
                </ul>
            </div>

            <div class="about-art" data-reveal="right" aria-hidden="true">🎓</div>
        </div>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="stats-band" data-reveal="scale">
            @foreach ($stats as $stat)
                <div class="stats-band__item">
                    <div class="stats-band__value counter" data-count="{{ $stat->value }}" data-suffix="{{ $stat->suffix }}">0</div>
                    <div class="stats-band__label">{{ $stat->emoji }} {{ $stat->label }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <x-section-head eyebrow="What we stand for" title="Four commitments we hold ourselves to" />

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

<section class="section">
    <div class="container">
        <div class="about-split">
            <div data-reveal="left">
                <span class="eyebrow">Our story</span>
                <h2>How we got here</h2>

                <div class="timeline mt-6">
                    <div class="timeline__item">
                        <div class="timeline__year">2023</div>
                        <div class="timeline__title">A study group of eleven people</div>
                        <p class="timeline__text">Two engineers started teaching weekend web development classes in Dar es Salaam. Every seat filled within a day.</p>
                    </div>
                    <div class="timeline__item">
                        <div class="timeline__year">2024</div>
                        <div class="timeline__title">The first online cohort</div>
                        <p class="timeline__text">We moved online to reach students outside the city and finished the year with 1,400 enrolments across six courses.</p>
                    </div>
                    <div class="timeline__item">
                        <div class="timeline__year">2025</div>
                        <div class="timeline__title">LearnHub launches properly</div>
                        <p class="timeline__text">A real platform, a vetted instructor programme and certificates that employers could verify.</p>
                    </div>
                    <div class="timeline__item">
                        <div class="timeline__year">2026</div>
                        <div class="timeline__title">Twenty thousand students</div>
                        <p class="timeline__text">Over a thousand courses, five hundred instructors, and a 98% satisfaction rating across the catalogue.</p>
                    </div>
                </div>
            </div>

            <div data-reveal="right">
                <span class="eyebrow">The team</span>
                <h2>People behind the platform</h2>
                <p>A small team of educators, engineers and designers — plus five hundred instructors who make the catalogue what it is.</p>

                <div class="grid grid--2 mt-6">
                    @foreach ($instructors as $instructor)
                        <a href="{{ route('instructors.show', $instructor->id) }}" class="card card--pad-sm card--hover">
                            <div class="row row--nowrap">
                                <span class="avatar">{{ $instructor->initials }}</span>
                                <span style="min-width:0">
                                    <strong style="display:block">{{ $instructor->name }}</strong>
                                    <small class="t-muted t-clamp-2">{{ $instructor->specialization }}</small>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="cta" data-reveal="scale">
            <h2>Ready to start? 🚀</h2>
            <p>Browse the catalogue and pick the course that gets you to your next step.</p>
            <div class="cta__actions">
                <a href="{{ route('courses.index') }}" class="btn btn--white btn--lg">Explore Courses</a>
                <a href="{{ route('contact') }}" class="btn btn--outline-light btn--lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

@endsection
