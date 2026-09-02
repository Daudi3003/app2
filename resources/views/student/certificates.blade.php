@extends('layouts.student')

@section('title', 'My Certificates')
@section('page_title', 'Certificates')
@section('page_subtitle', 'Proof of what you have completed')

@section('content')

<div class="pane-head">
    <div>
        <h2>Certificates 🏆</h2>
        <p>{{ $certificates->count() }} earned · each one has a unique verification code</p>
    </div>
</div>

@if ($certificates->isEmpty())
    <div class="card">
        <x-empty-state emoji="🏆" title="No certificates yet"
                       text="Complete every lesson and assignment in a course to earn your first certificate."
                       action="Continue Learning"
                       :action-url="route('student.courses')" />
    </div>
@else
    <div class="grid grid--3">
        @foreach ($certificates as $certificate)
            <article class="certificate" data-reveal data-reveal-delay="{{ $loop->index * 70 }}">
                <div class="certificate__seal" aria-hidden="true">{{ $certificate->emoji }}</div>
                <span class="badge badge--success">Certificate of Completion</span>
                <h3 class="certificate__title">{{ $certificate->course_name }}</h3>
                <p class="certificate__meta mb-0">
                    Awarded to <strong>{{ $student->name }}</strong><br>
                    {{ $certificate->instructor }} · {{ $certificate->issued }}
                </p>
                <span class="badge badge--primary" style="align-self:center">{{ $certificate->grade }}</span>
                <p class="certificate__id mb-0">{{ $certificate->code }}</p>

                <div class="row row--center mt-4" style="gap:var(--sp-2)">
                    <button type="button" class="btn btn--secondary btn--sm"
                            data-toast="Certificate download would start here" data-toast-type="info">
                        <x-icon name="download" :size="15" /> Download
                    </button>
                    <button type="button" class="btn btn--ghost btn--sm"
                            data-toast="Share link copied to clipboard" data-toast-type="success">
                        <x-icon name="external" :size="15" /> Share
                    </button>
                </div>
            </article>
        @endforeach
    </div>
@endif

<div class="card card--pad mt-8" style="background:var(--gradient-brand-soft);border-color:var(--primary-soft)">
    <div class="row row--between">
        <div style="min-width:240px">
            <h3 style="font-size:var(--fs-md)">🎯 Almost there</h3>
            <p class="t-sm mb-0">
                You are 85% through <strong>UI/UX Design Fundamentals</strong>.
                Finish the last two lessons to earn your fourth certificate.
            </p>
        </div>
        <a href="{{ route('student.course', 2) }}" class="btn btn--primary">Continue Course</a>
    </div>
</div>

@endsection
