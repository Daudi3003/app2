<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Online Course Platform</title>

    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

</head>

<body>

    <!-- NAVBAR -->

    <header class="navbar">

        <div class="logo">
            Online Course Platform
        </div>

        <nav>

            <a href="#home">Home</a>

            <a href="#about">About Us</a>

            <a href="#services">Our Services</a>

            <a href="#contact">Contact</a>

            <a href="{{ route('login') }}" class="login-btn">
                Login
            </a>

            <a href="{{ route('register') }}" class="register-btn">
                Register
            </a>

        </nav>

    </header>


    <!-- HOME -->

    <section class="hero" id="home">

        <div class="hero-content">

            <h1>
                Learn. Grow. Achieve.
            </h1>

            <p>
                Welcome to our Online Course Platform.
                Learn new skills, access learning materials,
                complete assignments and track your progress.
            </p>

            <div class="hero-buttons">

                <a href="{{ route('register') }}" class="primary-btn">
                    Get Started
                </a>

                <a href="#services" class="secondary-btn">
                    Explore Services
                </a>

            </div>

        </div>

    </section>


    <!-- ABOUT US -->

    <section class="about" id="about">

        <div class="section-title">

            <h2>
                About Us
            </h2>

            <p>
                Empowering students and instructors through
                accessible online learning.
            </p>

        </div>


        <div class="about-content">

            <div>

                <h3>
                    About Our Platform
                </h3>

                <p>
                    Our Online Course Platform provides a simple
                    and effective environment where students can
                    learn online while instructors can create
                    and manage educational content.
                </p>

                <p>
                    Students can enroll in courses, access
                    learning materials, submit assignments and
                    view their results.
                </p>

            </div>


            <div class="about-card">

                <h3>
                    Our Goal
                </h3>

                <p>
                    To make online learning easier, organized
                    and accessible for everyone.
                </p>

            </div>

        </div>

    </section>


    <!-- SERVICES -->

    <section class="services" id="services">

        <div class="section-title">

            <h2>
                Our Services
            </h2>

            <p>
                Everything you need for effective online learning.
            </p>

        </div>


        <div class="service-container">

            <div class="service-card">

                <div class="service-icon">
                    📚
                </div>

                <h3>
                    Online Courses
                </h3>

                <p>
                    Access different courses and learn at your
                    own pace.
                </p>

            </div>


            <div class="service-card">

                <div class="service-icon">
                    📝
                </div>

                <h3>
                    Assignments
                </h3>

                <p>
                    Complete and submit assignments online.
                </p>

            </div>


            <div class="service-card">

                <div class="service-icon">
                    📖
                </div>

                <h3>
                    Learning Materials
                </h3>

                <p>
                    Access course materials provided by instructors.
                </p>

            </div>


            <div class="service-card">

                <div class="service-icon">
                    📊
                </div>

                <h3>
                    Results & Reports
                </h3>

                <p>
                    View your academic results and learning progress.
                </p>

            </div>

        </div>

    </section>


    <!-- CONTACT -->

    <section class="contact" id="contact">

        <div class="section-title">

            <h2>
                Contact Us
            </h2>

            <p>
                Have a question? Get in touch with us.
            </p>

        </div>


        <div class="contact-container">

            <div class="contact-info">

                <h3>
                    Get In Touch
                </h3>

                <p>
                    Email: info@example.com
                </p>

                <p>
                    Phone: +255 700 000 000
                </p>

                <p>
                    Location: Tanzania
                </p>

            </div>


            <form class="contact-form">

                <input
                    type="text"
                    placeholder="Your Name"
                >

                <input
                    type="email"
                    placeholder="Your Email"
                >

                <textarea
                    placeholder="Your Message"
                    rows="5"
                ></textarea>

                <button type="submit">
                    Send Message
                </button>

            </form>

        </div>

    </section>


    <!-- FOOTER -->

    <footer>

        <p>
            © 2026 Online Course Platform. All Rights Reserved.
        </p>

    </footer>


</body>

</html>