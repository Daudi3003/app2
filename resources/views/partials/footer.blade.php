<footer class="footer">
    <div class="container container--wide">

        <div class="footer__grid">

            <div class="footer__about">
                <x-brand light />
                <p>{{ config('learnhub.description') }}</p>
                <div class="footer__social">
                    <a href="#" aria-label="LearnHub on Facebook"><x-icon name="facebook" :size="18" /></a>
                    <a href="#" aria-label="LearnHub on X"><x-icon name="twitter" :size="18" /></a>
                    <a href="#" aria-label="LearnHub on LinkedIn"><x-icon name="linkedin" :size="18" /></a>
                    <a href="#" aria-label="LearnHub on Instagram"><x-icon name="instagram" :size="18" /></a>
                    <a href="#" aria-label="LearnHub on YouTube"><x-icon name="youtube" :size="18" /></a>
                </div>
            </div>

            <div>
                <h3 class="footer__title">Platform</h3>
                <ul class="footer__list">
                    <li><a href="{{ route('courses.index') }}">All Courses</a></li>
                    <li><a href="{{ route('instructors.index') }}">Instructors</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                    <li><a href="{{ route('register') }}">Become an Instructor</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer__title">Categories</h3>
                <ul class="footer__list">
                    <li><a href="{{ route('courses.index') }}">Web Development</a></li>
                    <li><a href="{{ route('courses.index') }}">Design</a></li>
                    <li><a href="{{ route('courses.index') }}">Data Science</a></li>
                    <li><a href="{{ route('courses.index') }}">Marketing</a></li>
                    <li><a href="{{ route('courses.index') }}">Business</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer__title">Support</h3>
                <ul class="footer__list">
                    <li><a href="{{ route('contact') }}">Help Center</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('contact') }}#faq">FAQs</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer__newsletter">
                <h3 class="footer__title">Newsletter</h3>
                <p>Get one practical learning tip and a free course pick every Friday. No spam.</p>
                <form data-simulate-form="You are subscribed. Look out for Friday's email 📬" data-simulate-reset>
                    <label class="sr-only" for="newsletterEmail">Email address</label>
                    <input id="newsletterEmail" type="email" class="input" placeholder="you@example.com" required>
                    <button type="submit" class="btn btn--primary" aria-label="Subscribe">
                        <x-icon name="send" :size="17" />
                    </button>
                </form>
            </div>
        </div>

        <div class="footer__bottom">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('learnhub.name') }}. All rights reserved.</p>
            <div class="footer__legal">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Cookies</a>
                <a href="#">Accessibility</a>
            </div>
        </div>
    </div>
</footer>
