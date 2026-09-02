<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Materials</title>

    <link rel="stylesheet" href="{{ asset('css/student.css') }}">
</head>

<body>

<div class="container">

    <a href="{{ route('student.my-courses') }}" class="back">
        ← My Courses
    </a>

    <h1>{{ $course->title }}</h1>

    <p class="subtitle">
        Course Materials
    </p>

    <div class="material-list">

        @forelse($course->materials as $material)

            <div class="material">

                <h3>{{ $material->title }}</h3>

                <p>
                    {{ $material->description }}
                </p>

                <a
                    href="{{ asset('storage/' . $material->file) }}"
                    target="_blank"
                >
                    View Material
                </a>

            </div>

        @empty

            <p>No materials available for this course.</p>

        @endforelse

    </div>

</div>

</body>
</html>