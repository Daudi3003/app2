@extends('layouts.guest')

@section('title', 'Create Your Account')
@section('aside_title', 'Start learning in the next five minutes.')
@section('aside_text', 'Create a free LearnHub account and get instant access to every free course in the catalogue.')

@section('content')

<h1 class="auth__title">Create your account 🎓</h1>
<p class="auth__sub">Join more than twenty thousand learners on {{ config('learnhub.name') }}.</p>

<x-form-errors />

<form method="POST" action="{{ route('register.store') }}" class="form">
    @csrf

    <div class="field">
        <label class="field__label" for="name">Full name <span class="req">*</span></label>
        <div class="input-group">
            <span class="input-group__icon"><x-icon name="user" :size="17" /></span>
            <input id="name" name="name" type="text"
                   class="input @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Alex Mwangi"
                   autocomplete="name" required autofocus>
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
                   value="{{ old('email') }}" placeholder="you@example.com"
                   autocomplete="email" required>
        </div>
        @error('email')
            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
        @enderror
    </div>

    {{--
        registration_no and phone are required by the existing RegisterController
        and the students table (phone is unique and exactly 10 digits).
    --}}
    <div class="form-grid">
        <div class="field">
            <label class="field__label" for="registration_no">Registration number <span class="req">*</span></label>
            <input id="registration_no" name="registration_no" type="text"
                   class="input @error('registration_no') is-invalid @enderror"
                   value="{{ old('registration_no') }}" placeholder="LH-2026-0184" required>
            @error('registration_no')
                <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label class="field__label" for="phone">Phone number <span class="req">*</span></label>
            <input id="phone" name="phone" type="tel" inputmode="numeric"
                   class="input @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}" placeholder="0745213908"
                   pattern="[0-9]{10}" maxlength="10" required>
            <span class="field__hint">Ten digits, no spaces.</span>
            @error('phone')
                <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="field">
        <label class="field__label" for="reg-password">Password <span class="req">*</span></label>
        <div class="input-group input-group--trail">
            <input id="reg-password" name="password" type="password"
                   class="input @error('password') is-invalid @enderror"
                   placeholder="Create a strong password"
                   autocomplete="new-password" required
                   data-password-strength="pwMeter">
            <button type="button" class="input-group__action"
                    data-password-toggle="reg-password" aria-label="Show password">
                <x-icon name="eye" :size="18" />
            </button>
        </div>

        <div>
            <div class="pw-meter" id="pwMeter">
                <span class="pw-meter__seg"></span>
                <span class="pw-meter__seg"></span>
                <span class="pw-meter__seg"></span>
                <span class="pw-meter__seg"></span>
            </div>
            <div class="pw-meter-label"></div>
        </div>

        <span class="field__hint">
            At least 8 characters with an uppercase letter, a lowercase letter, a number and a symbol.
        </span>
        @error('password')
            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <label class="field__label" for="password_confirmation">Confirm password <span class="req">*</span></label>
        <div class="input-group input-group--trail">
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="input" placeholder="Repeat your password"
                   autocomplete="new-password" required>
            <button type="button" class="input-group__action"
                    data-password-toggle="password_confirmation" aria-label="Show password">
                <x-icon name="eye" :size="18" />
            </button>
        </div>
    </div>

    <label class="check">
        <input type="checkbox" required>
        <span>
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
            <span class="check__sub">You can delete your account at any time.</span>
        </span>
    </label>

    <button type="submit" class="btn btn--primary btn--lg btn--block">
        Create My Account <x-icon name="arrow-right" :size="18" class="icon icon--shift" />
    </button>
</form>

<p class="auth__foot">
    Already have an account?
    <a href="{{ route('login') }}">Log in</a>
</p>

@endsection
