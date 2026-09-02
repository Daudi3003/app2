@extends('layouts.guest')

@section('title', 'Log In')
@section('aside_title', 'Welcome back to LearnHub.')
@section('aside_text', 'Pick up exactly where you left off — your progress, notes and certificates are all waiting.')

@section('content')

<h1 class="auth__title">Welcome back 👋</h1>
<p class="auth__sub">Log in to continue your learning journey.</p>

<x-form-errors />

<form method="POST" action="{{ route('login.authenticate') }}" class="form">
    @csrf

    <div class="field">
        <label class="field__label" for="email">Email address <span class="req">*</span></label>
        <div class="input-group">
            <span class="input-group__icon"><x-icon name="mail" :size="17" /></span>
            <input id="email" name="email" type="email"
                   class="input @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="you@example.com"
                   autocomplete="email" required autofocus>
        </div>
        @error('email')
            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <div class="row row--between" style="gap:var(--sp-3)">
            <label class="field__label" for="password">Password <span class="req">*</span></label>
            <a href="{{ route('password.request') }}" class="t-sm">Forgot password?</a>
        </div>

        <div class="input-group input-group--trail">
            <input id="password" name="password" type="password"
                   class="input @error('password') is-invalid @enderror"
                   placeholder="Enter your password"
                   autocomplete="current-password" required>
            <button type="button" class="input-group__action"
                    data-password-toggle="password" aria-label="Show password">
                <x-icon name="eye" :size="18" />
            </button>
        </div>
        @error('password')
            <span class="field__error"><x-icon name="alert" :size="14" /> {{ $message }}</span>
        @enderror
    </div>

    <label class="check">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <span>Keep me logged in on this device</span>
    </label>

    <button type="submit" class="btn btn--primary btn--lg btn--block">
        Log In <x-icon name="arrow-right" :size="18" class="icon icon--shift" />
    </button>
</form>

<p class="auth__foot">
    Don't have an account?
    <a href="{{ route('register') }}">Create one free</a>
</p>

@endsection
