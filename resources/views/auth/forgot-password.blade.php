@extends('layouts.guest')

@section('title', 'Forgot Your Password')
@section('aside_title', 'It happens to everyone.')
@section('aside_text', 'Enter the email address on your account and we will send you a secure link to set a new password.')

@section('content')

<h1 class="auth__title">Forgot your password? 🔑</h1>
<p class="auth__sub">
    Enter the email address linked to your account and we will send you a reset link.
</p>

<x-form-errors />

<x-alert type="info" :dismissible="false">
    Reset links expire after 60 minutes for your security.
</x-alert>

<form class="form" data-simulate-form="Reset link sent — check your inbox 📧">
    @csrf

    <div class="field">
        <label class="field__label" for="forgotEmail">Email address <span class="req">*</span></label>
        <div class="input-group">
            <span class="input-group__icon"><x-icon name="mail" :size="17" /></span>
            <input id="forgotEmail" name="email" type="email" class="input"
                   placeholder="you@example.com" autocomplete="email" required autofocus>
        </div>
    </div>

    <button type="submit" class="btn btn--primary btn--lg btn--block">
        Send Reset Link <x-icon name="send" :size="17" />
    </button>
</form>

<p class="auth__foot">
    Remembered it? <a href="{{ route('login') }}">Back to login</a>
</p>

@endsection
