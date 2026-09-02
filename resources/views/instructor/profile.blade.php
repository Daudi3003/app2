@extends('layouts.instructor')

@section('title', 'My Profile')
@section('page_title', 'Profile')
@section('page_subtitle', 'How students see you')

@section('content')

<div class="profile-head">
    <div class="profile-head__cover"></div>

    <div class="profile-head__body">
        <span class="avatar avatar--2xl">{{ $instructor->initials }}</span>

        <div class="profile-head__info">
            <h2>{{ $instructor->name }}</h2>
            <p>{{ $instructor->headline }}</p>

            <div class="profile-head__meta">
                <span><x-icon name="mail" :size="15" /> {{ $instructor->email }}</span>
                <span><x-icon name="phone" :size="15" /> {{ $instructor->phone }}</span>
                <span><x-icon name="map-pin" :size="15" /> {{ $instructor->location }}</span>
                <span><x-icon name="calendar" :size="15" /> Teaching since {{ $instructor->joined }}</span>
            </div>
        </div>

        <div class="profile-head__actions">
            <a href="{{ route('instructors.show', $instructor->id) }}" class="btn btn--secondary">
                <x-icon name="eye" :size="17" /> View Public Profile
            </a>
            <a href="{{ route('instructor.settings') }}" class="btn btn--primary">
                <x-icon name="edit" :size="17" /> Edit Profile
            </a>
        </div>
    </div>
</div>

<section class="stats-row">
    @foreach ($stats as $stat)
        <x-stat-card :label="$stat->label" :value="$stat->value" :emoji="$stat->emoji" :tone="$stat->tone" />
    @endforeach
</section>

<div class="dash-grid">
    <div class="stack" style="gap:var(--sp-6)">

        <div class="card card--pad">
            <h3>Biography</h3>
            <p class="mb-0">{{ $instructor->bio }}</p>
        </div>

        <div class="card">
            <div class="card__head">
                <div><h3>My courses</h3></div>
                <a href="{{ route('instructor.courses') }}" class="btn btn--ghost btn--sm">Manage all</a>
            </div>
            <div class="card__body">
                <div class="grid grid--auto-sm">
                    @foreach ($courses as $course)
                        <x-course-card :course="$course" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <aside class="stack" style="gap:var(--sp-6)">
        <div class="card card--pad">
            <h3 style="font-size:var(--fs-md)">Areas of expertise</h3>
            <div class="row mt-4">
                @foreach ($instructor->expertise as $skill)
                    <span class="badge badge--primary">{{ $skill }}</span>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card__head"><div><h3>Instructor rating</h3></div></div>
            <div class="card__body t-center">
                <div style="font-family:var(--font-display);font-size:3rem;font-weight:800;line-height:1">
                    {{ $instructor->rating }}
                </div>
                <x-rating :score="$instructor->rating" :show-score="false" />
                <p class="t-sm t-muted mt-4 mb-0">
                    From {{ number_format($instructor->reviews_count) }} student reviews
                </p>
            </div>
        </div>

        <div class="card card--pad" style="background:var(--gradient-brand-soft);border-color:var(--primary-soft)">
            <h3 style="font-size:var(--fs-md)">🏆 Top instructor</h3>
            <p class="t-sm mb-0">
                You are in the top 5% of instructors on {{ config('learnhub.name') }} by student
                satisfaction this quarter.
            </p>
        </div>
    </aside>
</div>

@endsection
