@extends('errors.layout')
@section('title', 'Page Expired')
@section('code', '419')
@section('heading', 'Your session expired ⏳')
@section('message', 'For your security we ended the session after a period of inactivity. Please log in again and retry what you were doing.')
@section('extra')
    <a href="{{ route('login') }}" class="btn btn--outline-light">Log in again</a>
@endsection
