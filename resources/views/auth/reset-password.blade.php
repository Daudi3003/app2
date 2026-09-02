@extends('layouts.guest')

@section('title', 'Set a New Password')
@section('aside_title', 'Choose something strong.')
@section('aside_text', 'A good password is long and unique. A password manager makes both effortless.')

@section('content')

<h1 class="auth__title">Set a new password 🔒</h1>
<p class="auth__sub">Choose a new password for your {{ config('learnhub.name') }} account.</p>

<x-form-errors />

<form class="form" data-simulate-form="Password updated — you can now log in ✓">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="field">
        <label class="field__label" for="resetEmail">Email address <span class="req">*</span></label>
        <div class="input-group">
            <span class="input-group__icon"><x-icon name="mail" :size="17" /></span>
            <input id="resetEmail" name="email" type="email" class="input"
                   value="{{ $email }}" placeholder="you@example.com"
                   autocomplete="email" required>
        </div>
    </div>

    <div class="field">
        <label class="field__label" for="newPassword">New password <span class="req">*</span></label>
        <div class="input-group input-group--trail">
            <input id="newPassword" name="password" type="password" class="input"
                   placeholder="Create a strong password" autocomplete="new-password"
                   required data-password-strength="resetMeter">
            <button type="button" class="input-group__action"
                    data-password-toggle="newPassword" aria-label="Show password">
                <x-icon name="eye" :size="18" />
            </button>
        </div>

        <div>
            <div class="pw-meter" id="resetMeter">
                <span class="pw-meter__seg"></span>
                <span class="pw-meter__seg"></span>
                <span class="pw-meter__seg"></span>
                <span class="pw-meter__seg"></span>
            </div>
            <div class="pw-meter-label"></div>
        </div>
    </div>

    <div class="field">
        <label class="field__label" for="newPasswordConfirm">Confirm new password <span class="req">*</span></label>
        <div class="input-group input-group--trail">
            <input id="newPasswordConfirm" name="password_confirmation" type="password"
                   class="input" placeholder="Repeat your new password"
                   autocomplete="new-password" required>
            <button type="button" class="input-group__action"
                    data-password-toggle="newPasswordConfirm" aria-label="Show password">
                <x-icon name="eye" :size="18" />
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn--primary btn--lg btn--block">
        Reset Password <x-icon name="check" :size="18" />
    </button>
</form>

<p class="auth__foot">
    <a href="{{ route('login') }}">Back to login</a>
</p>

@endsection
