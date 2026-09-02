<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>My Courses</title>

    <link rel="stylesheet" href="{{ asset('css/student.css') }}">
</head>

<body>

<div class="container">

    <a href="{{ route('student.dashboard') }}" class="back">
        ← Dashboard
    </a>

    <h1>My Courses</h1>

    <div class="course-grid">

        @forelse($courses as $course)

            <div class="course-card">

                <h2>{{ $course->title }}</h2>

                <p>
                    {{ $course->description }}
                </p>

                <div class="actions">

                    <a href="{{ route('student.materials', $course->id) }}">
                        Materials
                    </a>

                    <a href="{{ route('student.assignments', $course->id) }}">
                        Assignments
                    </a>

                </div>

            </div>

        @empty

            <p>You have not enrolled in any course yet.</p>

        @endforelse

    </div>

</div>

</body>
</html>