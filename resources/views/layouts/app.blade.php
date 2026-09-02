@extends('layouts.base')

@section('body_class', 'page')

@section('body')
    @include('partials.navbar')

    <main id="main" class="page__main">
        @yield('content')
    </main>

    @include('partials.footer')
@endsection
