@extends('layouts.app')

@section('title', 'Our Instructors')

@section('content')

<section class="page-head">
    <div class="container container--wide">
        <div class="page-head__inner">
            <x-breadcrumbs light :items="['Home' => route('home'), 'Instructors' => null]" />
            <h1>Meet Our Instructors</h1>
            <p>Every instructor on LearnHub is a working professional, reviewed by our academic team before their first course goes live.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--wide">
        <div class="grid grid--3">
            @forelse ($instructors as $instructor)
                <a href="{{ route('instructors.show', $instructor->id) }}" class="instructor-card"
                   data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                    <span class="avatar avatar--xl avatar--ring">{{ $instructor->initials }}</span>
                    <span class="instructor-card__name">{{ $instructor->name }}</span>
                    <span class="instructor-card__role">{{ $instructor->specialization }}</span>
                    <p class="instructor-card__bio t-clamp-3">{{ $instructor->bio }}</p>

                    <div class="row row--center mb-4" style="gap:6px">
                        @foreach ($instructor->expertise->take(3) as $skill)
                            <span class="badge">{{ $skill }}</span>
                        @endforeach
                    </div>

                    <div class="instructor-card__stats">
                        <div class="instructor-card__stat">
                            <strong>{{ $instructor->courses_count }}</strong><span>Courses</span>
                        </div>
                        <div class="instructor-card__stat">
                            <strong>{{ number_format($instructor->students_count / 1000, 1) }}K</strong><span>Students</span>
                        </div>
                        <div class="instructor-card__stat">
                            <strong>⭐ {{ $instructor->rating }}</strong><span>Rating</span>
                        </div>
                    </div>
                </a>
            @empty
                <x-empty-state emoji="👨‍🏫" title="No instructors yet"
                               text="Instructor profiles will appear here once they are published." />
            @endforelse
        </div>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="cta" data-reveal="scale">
            <h2>Share what you know 👨‍🏫</h2>
            <p>Teach on LearnHub and reach thousands of motivated learners. Our team helps you plan, record and publish your first course.</p>
            <div class="cta__actions">
                <a href="{{ route('register') }}" class="btn btn--white btn--lg">Become an Instructor</a>
                <a href="{{ route('contact') }}" class="btn btn--outline-light btn--lg">Talk to Our Team</a>
            </div>
        </div>
    </div>
</section>

@endsection
