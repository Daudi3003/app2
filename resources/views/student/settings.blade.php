@extends('layouts.student')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Account, security and preferences')

@section('content')

<div class="pane-head">
    <div>
        <h2>Settings ⚙️</h2>
        <p>Manage your account, password, notifications and privacy.</p>
    </div>
</div>

<div class="settings-layout">

    <nav class="settings-nav" data-settings-nav aria-label="Settings sections">
        <button type="button" class="is-active" data-pane="account">
            <x-icon name="user" :size="17" /> Account
        </button>
        <button type="button" data-pane="password">
            <x-icon name="lock" :size="17" /> Password
        </button>
        <button type="button" data-pane="notifications">
            <x-icon name="bell" :size="17" /> Notifications
        </button>
        <button type="button" data-pane="privacy">
            <x-icon name="shield" :size="17" /> Privacy
        </button>
        <button type="button" data-pane="preferences">
            <x-icon name="sliders" :size="17" /> Preferences
        </button>
    </nav>

    <div>
        {{-- ACCOUNT --}}
        <div data-pane-panel="account">
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Account information</h3>
                        <p>This is what appears on your public profile.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" method="POST" action="{{ route('student.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="stName">Full name</label>
                                <input id="stName" name="name" type="text" class="input" value="{{ $student->name }}" required>
                            </div>
                            <div class="field">
                                <label class="field__label" for="stEmail">Email address</label>
                                <input id="stEmail" name="email" type="email" class="input" value="{{ $student->email }}" required>
                            </div>
                            <div class="field">
                                <label class="field__label" for="stPhone">Phone number</label>
                                <input id="stPhone" name="phone" type="tel" class="input" value="{{ $student->phone }}">
                            </div>
                            <div class="field">
                                <label class="field__label" for="stReg">Registration number</label>
                                <input id="stReg" type="text" class="input" value="{{ $student->registration_no }}" disabled>
                                <span class="field__hint">Assigned at registration and cannot be changed.</span>
                            </div>
                            <div class="field is-full">
                                <label class="field__label" for="stBio">Bio</label>
                                <textarea id="stBio" class="textarea" rows="4">{{ $student->bio }}</textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn--ghost">Cancel</button>
                            <button type="submit" class="btn btn--primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-6" style="border-color:var(--danger-soft)">
                <div class="card__head">
                    <div>
                        <h3 style="color:var(--danger)">Danger zone</h3>
                        <p>Deleting your account removes your progress and certificates permanently.</p>
                    </div>
                </div>
                <div class="card__body">
                    <button type="button" class="btn btn--danger"
                            data-confirm-delete="your LearnHub account">
                        <x-icon name="trash" :size="17" /> Delete My Account
                    </button>
                </div>
            </div>
        </div>

        {{-- PASSWORD --}}
        <div data-pane-panel="password" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Change password</h3>
                        <p>Use a long, unique password you do not use anywhere else.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" method="POST" action="{{ route('student.password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="field">
                            <label class="field__label" for="curPw">Current password</label>
                            <div class="input-group input-group--trail">
                                <input id="curPw" type="password" class="input" required>
                                <button type="button" class="input-group__action"
                                        data-password-toggle="curPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                        </div>

                        <div class="field">
                            <label class="field__label" for="setNewPw">New password</label>
                            <div class="input-group input-group--trail">
                                <input id="setNewPw" type="password" class="input" required
                                       data-password-strength="setMeter">
                                <button type="button" class="input-group__action"
                                        data-password-toggle="setNewPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                            <div>
                                <div class="pw-meter" id="setMeter">
                                    <span class="pw-meter__seg"></span><span class="pw-meter__seg"></span>
                                    <span class="pw-meter__seg"></span><span class="pw-meter__seg"></span>
                                </div>
                                <div class="pw-meter-label"></div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="field__label" for="setConfirmPw">Confirm new password</label>
                            <div class="input-group input-group--trail">
                                <input id="setConfirmPw" type="password" class="input" required>
                                <button type="button" class="input-group__action"
                                        data-password-toggle="setConfirmPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
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
                        <p>Choose what LearnHub tells you about, and where.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="switch-list">
                        @foreach ([
                            ['Assignment reminders', 'Get a reminder three days before an assignment is due.', true],
                            ['New lesson published', 'Tell me when an instructor adds a lesson to a course I am taking.', true],
                            ['Grades and feedback', 'Notify me as soon as an assignment is graded.', true],
                            ['Instructor messages', 'Email me when an instructor replies to my question.', true],
                            ['Course recommendations', 'Occasional suggestions based on what I am studying.', false],
                            ['Marketing and offers', 'Discounts, new launches and seasonal promotions.', false],
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

        {{-- PRIVACY --}}
        <div data-pane-panel="privacy" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Privacy</h3>
                        <p>Control what other people on LearnHub can see.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="switch-list">
                        @foreach ([
                            ['Public profile', 'Let other learners view your profile page.', true],
                            ['Show my certificates', 'Display earned certificates on your public profile.', true],
                            ['Show courses I am taking', 'Reveal your current enrolments to instructors.', false],
                            ['Allow direct messages', 'Let instructors start a conversation with you.', true],
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
                <div class="card__foot">
                    <button type="button" class="btn btn--secondary btn--sm"
                            data-toast="Your data export will be emailed to you" data-toast-type="info">
                        <x-icon name="download" :size="15" /> Download My Data
                    </button>
                </div>
            </div>
        </div>

        {{-- PREFERENCES --}}
        <div data-pane-panel="preferences" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Learning preferences</h3>
                        <p>Tune LearnHub to the way you like to study.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Preferences saved ✓">
                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="prefLang">Interface language</label>
                                <select id="prefLang" class="select">
                                    <option>English</option>
                                    <option>Kiswahili</option>
                                    <option>Français</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="prefTz">Time zone</label>
                                <select id="prefTz" class="select">
                                    <option>Africa/Dar_es_Salaam (EAT)</option>
                                    <option>Africa/Nairobi (EAT)</option>
                                    <option>UTC</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="prefGoal">Weekly learning goal</label>
                                <select id="prefGoal" class="select">
                                    <option>3 hours per week — casual</option>
                                    <option selected>6 hours per week — steady</option>
                                    <option>10 hours per week — intensive</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="prefSpeed">Default video speed</label>
                                <select id="prefSpeed" class="select">
                                    <option>0.75×</option>
                                    <option selected>1×</option>
                                    <option>1.25×</option>
                                    <option>1.5×</option>
                                </select>
                            </div>
                        </div>

                        <div class="switch-list">
                            <label class="switch">
                                <span class="switch__text">
                                    <span class="switch__title">Autoplay next lesson</span>
                                    <span class="switch__sub">Continue automatically when a lesson ends.</span>
                                </span>
                                <input type="checkbox" checked>
                                <span class="switch__track"></span>
                            </label>
                            <label class="switch">
                                <span class="switch__text">
                                    <span class="switch__title">Reduce motion</span>
                                    <span class="switch__sub">Minimise animations across the interface.</span>
                                </span>
                                <input type="checkbox">
                                <span class="switch__track"></span>
                            </label>
                        </div>

                        <div class="form-actions">
                            <span></span>
                            <button type="submit" class="btn btn--primary">Save Preferences</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
