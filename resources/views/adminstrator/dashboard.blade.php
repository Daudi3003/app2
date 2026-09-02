<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrator Dashboard</title>

    <link rel="stylesheet"
          href="{{ asset('css/administrator.css') }}">

</head>

<body>

<div class="layout">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>Course System</h2>


        <nav>

            <a href="{{ route('adminstrator.dashboard') }}"
               class="active">

                Dashboard

            </a>


            <a href="{{ route('adminstrator.instructor.create') }}">

                Add Instructor

            </a>


            <a href="#">

                Manage Instructors

            </a>


            <a href="#">

                Manage Students

            </a>


            <a href="#">

                Manage Courses

            </a>

        </nav>


        <form method="POST"
              action="{{ route('logout') }}">

            @csrf

            <button type="submit"
                    class="logout">

                Logout

            </button>

        </form>

    </aside>



    <!-- CONTENT -->

    <main class="content">


        <div class="welcome">

            <h1>Administrator Dashboard</h1>

            <p>
                Manage instructors, students and courses.
            </p>

        </div>



        <div class="cards">


            <!-- ADD INSTRUCTOR -->

            <a href="{{ route('adminstrator.instructor.create') }}"
               class="card">

                <div class="icon">👨‍🏫</div>

                <h3>Add Instructor</h3>

                <p>
                    Create a new instructor account.
                </p>

            </a>



            <!-- INSTRUCTORS -->

            <a href="#"
               class="card">

                <div class="icon">👥</div>

                <h3>Manage Instructors</h3>

                <p>
                    View and manage instructors.
                </p>

            </a>



            <!-- STUDENTS -->

            <a href="#"
               class="card">

                <div class="icon">🎓</div>

                <h3>Manage Students</h3>

                <p>
                    View and manage registered students.
                </p>

            </a>



            <!-- COURSES -->

            <a href="#"
               class="card">

                <div class="icon">📚</div>

                <h3>Manage Courses</h3>

                <p>
                    View and manage courses.
                </p>

            </a>


        </div>

    </main>

</div>

</body>

</html>