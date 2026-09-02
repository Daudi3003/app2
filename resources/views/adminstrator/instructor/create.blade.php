@extends('layouts.admin')

@section('title', 'Add Instructor')
@section('page_title', 'Add Instructor')
@section('page_subtitle', 'Create a new instructor account')

@section('content')

{{--
    This form posts to the original AdminstratorController@storeInstructor
    action and writes a real record to the users and instructors tables.
    Field names are unchanged (name, email, phone, specialization, password,
    password_confirmation) so the existing validation keeps working.
--}}

<x-breadcrumbs :items="[
    'Dashboard'   => route('admin.dashboard'),
    'Instructors' => route('admin.instructors'),
    'Add'         => null,
]" />

<div class="pane-head">
    <div>
        <h2>Add a new instructor 👨‍🏫</h2>
        <p>The account is created immediately and the instructor can log in straight away.</p>
    </div>
    <div class="pane-head__actions">
        <a href="{{ route('admin.instructors') }}" class="btn btn--secondary">
            <x-icon name="arrow-left" :size="17" /> Back to Instructors
        </a>
    </div>
</div>

<div style="max-width:760px">
    <x-form-errors />

    <div class="card">
        <div class="card__head">
            <div>
                <h3>Instructor details</h3>
                <p>All fields are required.</p>
            </div>
        </div>

        <div class="card__body">
            <form method="POST" action="{{ route('adminstrator.instructor.store') }}" class="form">
                @csrf

                <div class="form-grid">
                    <div class="field">
                        <label class="field__label" for="name">Full name <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group__icon"><x-icon name="user" :size="17" /></span>
                            <input id="name" name="name" type="text"
                                   class="input @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Sarah Johnson"
                                   maxlength="255" required autofocus>
                        </div>
                        @error('name')
                            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="email">Email address <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group__icon"><x-icon name="mail" :size="17" /></span>
                            <input id="email" name="email" type="email"
                                   class="input @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="sarah@learnhub.test" required>
                        </div>
                        @error('email')
                            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="phone">Phone number <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group__icon"><x-icon name="phone" :size="17" /></span>
                            <input id="phone" name="phone" type="tel"
                                   class="input @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" placeholder="0712 903 118"
                                   maxlength="20" required>
                        </div>
                        @error('phone')
                            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="specialization">Specialisation <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group__icon"><x-icon name="target" :size="17" /></span>
                            <input id="specialization" name="specialization" type="text"
                                   class="input @error('specialization') is-invalid @enderror"
                                   value="{{ old('specialization') }}"
                                   placeholder="Product &amp; Interface Design"
                                   maxlength="255" required>
                        </div>
                        @error('specialization')
                            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="password">Password <span class="req">*</span></label>
                        <div class="input-group input-group--trail">
                            <input id="password" name="password" type="password"
                                   class="input @error('password') is-invalid @enderror"
                                   placeholder="At least 6 characters"
                                   autocomplete="new-password" required
                                   data-password-strength="instMeter">
                            <button type="button" class="input-group__action"
                                    data-password-toggle="password" aria-label="Show password">
                                <x-icon name="eye" :size="18" />
                            </button>
                        </div>
                        <div>
                            <div class="pw-meter" id="instMeter">
                                <span class="pw-meter__seg"></span><span class="pw-meter__seg"></span>
                                <span class="pw-meter__seg"></span><span class="pw-meter__seg"></span>
                            </div>
                            <div class="pw-meter-label"></div>
                        </div>
                        @error('password')
                            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="password_confirmation">
                            Confirm password <span class="req">*</span>
                        </label>
                        <div class="input-group input-group--trail">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="input" placeholder="Repeat the password"
                                   autocomplete="new-password" required>
                            <button type="button" class="input-group__action"
                                    data-password-toggle="password_confirmation" aria-label="Show password">
                                <x-icon name="eye" :size="18" />
                            </button>
                        </div>
                    </div>
                </div>

                <x-alert type="info" :dismissible="false">
                    The instructor is created with the <strong>instructor</strong> role and can sign in
                    immediately. Share the password with them securely and ask them to change it.
                </x-alert>

                <div class="form-actions">
                    <a href="{{ route('admin.instructors') }}" class="btn btn--ghost">Cancel</a>
                    <button type="submit" class="btn btn--primary btn--lg">
                        <x-icon name="user-plus" :size="17" /> Create Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
