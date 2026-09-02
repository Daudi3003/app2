@extends('layouts.base')

@section('body')
    <main id="main" class="error-page">
        <x-brand light size="lg" />

        <div class="error-page__code" aria-hidden="true">@yield('code')</div>

        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>

        <div class="error-page__actions">
            <a href="{{ route('home') }}" class="btn btn--white btn--lg">
                <x-icon name="home" :size="18" /> Back to Home
            </a>
            <a href="{{ route('courses.index') }}" class="btn btn--outline-light btn--lg">
                Browse Courses
            </a>
        </div>

        @hasSection('extra')
            <div class="mt-8">@yield('extra')</div>
        @endif
    </main>
@endsection
