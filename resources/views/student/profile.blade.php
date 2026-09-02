@extends('layouts.student')

@section('title', 'My Profile')
@section('page_title', 'Profile')
@section('page_subtitle', 'How you appear on LearnHub')

@section('content')

<div class="profile-head">
    <div class="profile-head__cover"></div>

    <div class="profile-head__body">
        <span class="avatar avatar--2xl">{{ $student->initials }}</span>

        <div class="profile-head__info">
            <h2>{{ $student->name }}</h2>
            <p>{{ $student->registration_no }} · Student</p>

            <div class="profile-head__meta">
                <span><x-icon name="mail" :size="15" /> {{ $student->email }}</span>
                <span><x-icon name="phone" :size="15" /> {{ $student->phone }}</span>
                <span><x-icon name="map-pin" :size="15" /> {{ $student->location }}</span>
                <span><x-icon name="calendar" :size="15" /> Joined {{ $student->joined }}</span>
            </div>
        </div>

        <div class="profile-head__actions">
            <button type="button" class="btn btn--primary" data-modal-open="editProfileModal">
                <x-icon name="edit" :size="17" /> Edit Profile
            </button>
            <a href="{{ route('student.settings') }}" class="btn btn--secondary">
                <x-icon name="settings" :size="17" /> Settings
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
            <h3>About me</h3>
            <p class="mb-0">{{ $student->bio }}</p>
        </div>

        <div class="card">
            <div class="card__head">
                <div><h3>Recent courses</h3></div>
                <a href="{{ route('student.courses') }}" class="btn btn--ghost btn--sm">View all</a>
            </div>
            <div class="card__body card__body--flush">
                @foreach ($courses as $course)
                    <div class="continue-row">
                        <span class="continue-row__thumb" aria-hidden="true">{{ $course->emoji }}</span>
                        <div class="continue-row__body">
                            <div class="continue-row__title t-clamp-2">{{ $course->course_name }}</div>
                            <div class="continue-row__sub">{{ $course->instructor_name }}</div>
                            <x-progress :value="$course->progress" />
                        </div>
                        <div class="continue-row__end">
                            <x-status-badge :status="$course->status" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <aside class="stack" style="gap:var(--sp-6)">
        <div class="card">
            <div class="card__head"><div><h3>Certificates earned</h3></div></div>
            <div class="card__body card__body--flush">
                <div class="list">
                    @foreach ($certificates as $certificate)
                        <div class="list__item">
                            <span class="list__icon list__icon--success">{{ $certificate->emoji }}</span>
                            <div class="list__body">
                                <div class="list__title t-sm t-clamp-2">{{ $certificate->course_name }}</div>
                                <div class="list__sub">{{ $certificate->issued }} · {{ $certificate->grade }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card__foot">
                <a href="{{ route('student.certificates') }}" class="btn btn--secondary btn--block btn--sm">
                    View all certificates
                </a>
            </div>
        </div>

        <div class="card card--pad t-center">
            <div style="font-size:2.5rem;line-height:1" aria-hidden="true">🔥</div>
            <h3 class="mt-4" style="font-size:var(--fs-md)">{{ $student->learning_streak }}-day streak</h3>
            <p class="t-sm t-muted mb-0">
                Your longest streak so far is 21 days. Study today to keep this one alive.
            </p>
        </div>
    </aside>
</div>

{{-- ---------- EDIT PROFILE MODAL ---------- --}}
<div class="modal" id="editProfileModal" role="dialog" aria-modal="true"
     aria-labelledby="editProfileTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="editProfileTitle">Edit profile</h3>
                <p>Update how you appear across LearnHub.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog">
                <x-icon name="x" :size="18" />
            </button>
        </div>

        <form method="POST" action="{{ route('student.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="modal__body">
                <div class="form">
                    <div class="row" style="gap:var(--sp-4)">
                        <span class="avatar avatar--xl">{{ $student->initials }}</span>
                        <div>
                            <button type="button" class="btn btn--secondary btn--sm">
                                <x-icon name="upload" :size="15" /> Change photo
                            </button>
                            <p class="t-xs t-muted mt-4 mb-0">JPG or PNG, up to 2 MB.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="pfName">Full name</label>
                            <input id="pfName" name="name" type="text" class="input" value="{{ $student->name }}" required>
                        </div>
                        <div class="field">
                            <label class="field__label" for="pfEmail">Email</label>
                            <input id="pfEmail" name="email" type="email" class="input" value="{{ $student->email }}" required>
                        </div>
                        <div class="field">
                            <label class="field__label" for="pfPhone">Phone</label>
                            <input id="pfPhone" name="phone" type="tel" class="input" value="{{ $student->phone }}">
                        </div>
                        <div class="field">
                            <label class="field__label" for="pfLocation">Location</label>
                            <input id="pfLocation" type="text" class="input" value="{{ $student->location }}">
                        </div>
                        <div class="field is-full">
                            <label class="field__label" for="pfBio">Bio</label>
                            <textarea id="pfBio" class="textarea" rows="4" maxlength="400"
                                      data-count-target="bioCount">{{ $student->bio }}</textarea>
                            <span class="field__hint" id="bioCount"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection
