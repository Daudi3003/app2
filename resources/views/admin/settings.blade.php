@extends('layouts.admin')

@section('title', 'Platform Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Platform configuration')

@section('content')

<div class="pane-head">
    <div>
        <h2>Settings ⚙️</h2>
        <p>Platform information, your profile, notifications, security and preferences.</p>
    </div>
</div>

<div class="settings-layout">

    <nav class="settings-nav" data-settings-nav aria-label="Settings sections">
        <button type="button" class="is-active" data-pane="platform">
            <x-icon name="globe" :size="17" /> Platform
        </button>
        <button type="button" data-pane="profile">
            <x-icon name="user" :size="17" /> My Profile
        </button>
        <button type="button" data-pane="notifications">
            <x-icon name="bell" :size="17" /> Notifications
        </button>
        <button type="button" data-pane="security">
            <x-icon name="shield" :size="17" /> Security
        </button>
        <button type="button" data-pane="preferences">
            <x-icon name="sliders" :size="17" /> Preferences
        </button>
    </nav>

    <div>
        {{-- PLATFORM --}}
        <div data-pane-panel="platform">
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Platform information</h3>
                        <p>Shown in the navbar, footer, page titles and outgoing email.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Platform settings saved ✓">
                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="plName">Platform name</label>
                                <input id="plName" type="text" class="input" value="{{ config('learnhub.name') }}">
                            </div>
                            <div class="field">
                                <label class="field__label" for="plEmail">Support email</label>
                                <input id="plEmail" type="email" class="input" value="{{ config('learnhub.support_email') }}">
                            </div>
                            <div class="field">
                                <label class="field__label" for="plPhone">Support phone</label>
                                <input id="plPhone" type="tel" class="input" value="{{ config('learnhub.support_phone') }}">
                            </div>
                            <div class="field">
                                <label class="field__label" for="plCurrency">Default currency</label>
                                <select id="plCurrency" class="select">
                                    <option>USD — US Dollar</option>
                                    <option>TZS — Tanzanian Shilling</option>
                                    <option>KES — Kenyan Shilling</option>
                                </select>
                            </div>
                            <div class="field is-full">
                                <label class="field__label" for="plTagline">Tagline</label>
                                <input id="plTagline" type="text" class="input" value="{{ config('learnhub.tagline') }}">
                            </div>
                            <div class="field is-full">
                                <label class="field__label" for="plAddress">Registered address</label>
                                <input id="plAddress" type="text" class="input" value="{{ config('learnhub.address') }}">
                            </div>
                            <div class="field is-full">
                                <label class="field__label" for="plDescription">Meta description</label>
                                <textarea id="plDescription" class="textarea" rows="3" maxlength="200"
                                          data-count-target="plDescCount">{{ config('learnhub.description') }}</textarea>
                                <span class="field__hint" id="plDescCount"></span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn--ghost">Reset</button>
                            <button type="submit" class="btn btn--primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-6">
                <div class="card__head">
                    <div>
                        <h3>Platform controls</h3>
                        <p>Switches that affect every user.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="switch-list">
                        @foreach ([
                            ['Allow new registrations', 'Turn off to temporarily close public sign-ups.', true],
                            ['Instructor applications open', 'Accept new applications to teach on the platform.', true],
                            ['Require course review', 'New courses must be approved before going live.', true],
                            ['Issue certificates', 'Automatically issue certificates on course completion.', true],
                            ['Maintenance mode', 'Show the maintenance page to everyone except administrators.', false],
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

        {{-- PROFILE --}}
        <div data-pane-panel="profile" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Administrator profile</h3>
                        <p>Your own account details.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Profile saved ✓">
                        <div class="row" style="gap:var(--sp-4)">
                            <span class="avatar avatar--xl">{{ $admin->initials }}</span>
                            <div>
                                <strong style="display:block">{{ $admin->name }}</strong>
                                <span class="badge badge--danger">{{ $admin->role_label }}</span>
                                <p class="t-xs t-muted mt-4 mb-0">Administrator since {{ $admin->joined }}</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="adName">Full name</label>
                                <input id="adName" type="text" class="input" value="{{ $admin->name }}" required>
                            </div>
                            <div class="field">
                                <label class="field__label" for="adEmail">Email</label>
                                <input id="adEmail" type="email" class="input" value="{{ $admin->email }}" required>
                            </div>
                            <div class="field">
                                <label class="field__label" for="adPhone">Phone</label>
                                <input id="adPhone" type="tel" class="input" value="{{ $admin->phone }}">
                            </div>
                        </div>

                        <div class="form-actions">
                            <span></span>
                            <button type="submit" class="btn btn--primary">Save Profile</button>
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
                        <h3>Administrator notifications</h3>
                        <p>What the admin team is alerted about.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="switch-list">
                        @foreach ([
                            ['New instructor application', 'Alert me when someone applies to teach.', true],
                            ['Course submitted for review', 'Alert me when a course needs approval.', true],
                            ['Course updates', 'Notify me about course updates and learner activity.', true],
                            ['Content reports', 'Alert me when a student reports course content.', true],
                            ['Weekly platform digest', 'A Monday summary of key metrics.', true],
                            ['System alerts', 'Errors, downtime and failed background jobs.', true],
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

        {{-- SECURITY --}}
        <div data-pane-panel="security" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Security</h3>
                        <p>Protect administrator access to the platform.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Password updated ✓">
                        <div class="field">
                            <label class="field__label" for="adCurPw">Current password</label>
                            <div class="input-group input-group--trail">
                                <input id="adCurPw" type="password" class="input" required>
                                <button type="button" class="input-group__action"
                                        data-password-toggle="adCurPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field__label" for="adNewPw">New password</label>
                            <div class="input-group input-group--trail">
                                <input id="adNewPw" type="password" class="input" required
                                       data-password-strength="adMeter">
                                <button type="button" class="input-group__action"
                                        data-password-toggle="adNewPw" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                            <div>
                                <div class="pw-meter" id="adMeter">
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

                    <hr>

                    <div class="switch-list">
                        <label class="switch">
                            <span class="switch__text">
                                <span class="switch__title">Two-factor authentication</span>
                                <span class="switch__sub">Require a second factor for every admin login.</span>
                            </span>
                            <input type="checkbox" checked><span class="switch__track"></span>
                        </label>
                        <label class="switch">
                            <span class="switch__text">
                                <span class="switch__title">Force password rotation</span>
                                <span class="switch__sub">Require administrators to change passwords every 90 days.</span>
                            </span>
                            <input type="checkbox"><span class="switch__track"></span>
                        </label>
                        <label class="switch">
                            <span class="switch__text">
                                <span class="switch__title">Log admin actions</span>
                                <span class="switch__sub">Keep an audit trail of every administrative change.</span>
                            </span>
                            <input type="checkbox" checked><span class="switch__track"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREFERENCES --}}
        <div data-pane-panel="preferences" hidden>
            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Preferences</h3>
                        <p>Defaults applied across the admin interface.</p>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form" data-simulate-form="Preferences saved ✓">
                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="adLang">Interface language</label>
                                <select id="adLang" class="select">
                                    <option>English</option>
                                    <option>Kiswahili</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="adTz">Time zone</label>
                                <select id="adTz" class="select">
                                    <option>Africa/Dar_es_Salaam (EAT)</option>
                                    <option>UTC</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="adRows">Rows per table page</label>
                                <select id="adRows" class="select">
                                    <option>10</option>
                                    <option selected>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="adDate">Date format</label>
                                <select id="adDate" class="select">
                                    <option>31 Aug 2026</option>
                                    <option>2026-08-31</option>
                                    <option>31/08/2026</option>
                                </select>
                            </div>
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
