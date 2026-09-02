<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Instructor Dashboard</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/instructor.css') }}"
    >

</head>

<body>

<aside class="sidebar">

    <h2>Online Course Platform</h2>

    <div class="profile">

        <div class="avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <h3>{{ $user->name }}</h3>

        <p>Instructor</p>

    </div>

    <nav>

        <a href="#">Dashboard</a>

        <a href="#">My Courses</a>

        <a href="#">Create Course</a>

        <a href="#">Materials</a>

        <a href="#">Assignments</a>

        <a href="#">Submissions</a>

        <a href="#">Reports</a>

    </nav>

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button>
            Logout
        </button>

    </form>

</aside>


<main class="main">

    <h1>Instructor Dashboard</h1>

    <p>
        Welcome back, {{ $user->name }}
    </p>

    <div class="cards">

        <div class="card">
            <h3>My Courses</h3>
            <h2>0</h2>
        </div>

        <div class="card">
            <h3>Students</h3>
            <h2>0</h2>
        </div>

        <div class="card">
            <h3>Assignments</h3>
            <h2>0</h2>
        </div>

        <div class="card">
            <h3>Submissions</h3>
            <h2>0</h2>
        </div>

    </div>

</main>

</body>

</html>