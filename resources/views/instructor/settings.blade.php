@extends('layouts.instructor')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Profile and preferences')

@section('content')

<div class="pane-head">
    <div>
        <h2>Settings ⚙️</h2>
        <p>Manage your instructor profile, security and notifications.</p>
    </div>
</div>

<div class="settings-layout">

    <nav class="settings-nav" data-settings-nav aria-label="Settings sections">
        <button type="button" class="is-active" data-pane="profile">
            <x-icon name="user" :size="17" /> Profile
        </button>
        <button type="button" data-pane="password">
            <x-icon name="lock" :size="17" /> Password
        </button>
        <button type="button" data-pane="notifications">
            <x-icon name="bell" :size="17" /> Notifications
        </button>
    </nav>

    <div>
        {{-- PROFILE --}}
        <div data-pane-panel="profile">
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Instructor profile</h3>
                        <p>This information appears on your public instructor page.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Profile saved ✓">
                        <div class="row" style="gap:var(--sp-4)">
                            <span class="avatar avatar--xl">{{ $instructor->initials }}</span>
                            <div>
                                <button type="button" class="btn btn--secondary btn--sm">
                                    <x-icon name="upload" :size="15" /> Change photo
                                </button>
                                <p class="t-xs t-muted mt-4 mb-0">Square JPG or PNG, at least 400 × 400.</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="inName">Full name</label>
                                <input id="inName" type="text" class="input" value="{{ $instructor->name }}" required>
                            </div>
                            <div class="field">
                                <label class="field__label" for="inEmail">Email</label>
                                <input id="inEmail" type="email" class="input" value="{{ $instructor->email }}" required>
                            </div>
                            <div class="field">
                                <label class="field__label" for="inPhone">Phone</label>
                                <input id="inPhone" type="tel" class="input" value="{{ $instructor->phone }}">
                            </div>
                            <div class="field">
                                <label class="field__label" for="inSpec">Specialisation</label>
                                <input id="inSpec" name="specialization" type="text" class="input"
                                       value="{{ $instructor->specialization }}">
                            </div>
                            <div class="field is-full">
                                <label class="field__label" for="inHeadline">Headline</label>
                                <input id="inHeadline" type="text" class="input" maxlength="90"
                                       value="{{ $instructor->headline }}">
                                <span class="field__hint">Shown under your name on course pages.</span>
                            </div>
                            <div class="field is-full">
                                <label class="field__label" for="inBio">Biography</label>
                                <textarea id="inBio" class="textarea" rows="6" maxlength="800"
                                          data-count-target="inBioCount">{{ $instructor->bio }}</textarea>
                                <span class="field__hint" id="inBioCount"></span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn--ghost">Cancel</button>
                            <button type="submit" class="btn btn--primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- PASSWORD --}}
        <div data-pane-panel="password" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Change password</h3>
                        <p>Use a strong, unique password to keep your instructor account secure.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Password updated ✓">
                        <div class="field">
                            <label class="field__label" for="inCurPw">Current password</label>
                            <div class="input-group input-group--trail">
                                <input id="inCurPw" type="password" class="input" required>
                                <button type="button" class="input-group__action"
                                        data-password-toggle="inCurPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field__label" for="inNewPw">New password</label>
                            <div class="input-group input-group--trail">
                                <input id="inNewPw" type="password" class="input" required
                                       data-password-strength="inMeter">
                                <button type="button" class="input-group__action"
                                        data-password-toggle="inNewPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                            <div>
                                <div class="pw-meter" id="inMeter">
                                    <span class="pw-meter__seg"></span><span class="pw-meter__seg"></span>
                                    <span class="pw-meter__seg"></span><span class="pw-meter__seg"></span>
                                </div>
                                <div class="pw-meter-label"></div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <span></span>
                            <button type="submit" class="btn btn--primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- NOTIFICATIONS --}}
        <div data-pane-panel="notifications" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Notification preferences</h3>
                        <p>Choose what we tell you about.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="switch-list">
                        @foreach ([
                            ['New enrolment', 'Notify me each time a student enrols in one of my courses.', true],
                            ['New submission', 'Tell me when an assignment is submitted for grading.', true],
                            ['New question', 'Alert me when a student asks a question in Q&A.', true],
                            ['New review', 'Notify me when a student reviews one of my courses.', true],
                            ['Platform news', 'Product updates and instructor programme announcements.', false],
                        ] as [$title, $sub, $on])
                            <label class="switch">
                                <span class="switch__text">
                                    <span class="switch__title">{{ $title }}</span>
                                    <span class="switch__sub">{{ $sub }}</span>
                                </span>
                                <input type="checkbox" {{ $on ? 'checked' : '' }}>
                                <span class="switch__track"></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
