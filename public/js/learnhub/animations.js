/* =========================================================================
   LearnHub — motion
   Scroll reveal · counters · progress bars · rings · page progress bar
   All effects degrade to "already visible" when JS or IO is unavailable.
   ========================================================================= */
(function (LH) {
    'use strict';

    var reduced = LH.prefersReducedMotion();

    /**
     * Run `onEnter(el)` once, the first time each element is scrolled into
     * view — with a debounced sweep as a safety net.
     *
     * IntersectionObserver reports the state at sample time, so an element
     * jumped past in a single scroll (an anchor link, the End key, a fast
     * fling) may never be reported as intersecting. Without the sweep those
     * elements would keep their initial state — invisible content, or a
     * progress bar stuck at zero — for the life of the page.
     *
     * @param {Element[]} items
     * @param {function(Element):void} onEnter
     */
    function whenInView(items, onEnter) {
        if (!items.length) { return; }

        var done = typeof WeakSet === 'function' ? new WeakSet() : null;
        var pending = items.slice();

        function fire(el) {
            if (done) {
                if (done.has(el)) { return; }
                done.add(el);
            }
            onEnter(el);
        }

        if (reduced || !('IntersectionObserver' in window)) {
            items.forEach(fire);
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) { return; }
                fire(entry.target);
                io.unobserve(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        items.forEach(function (el) { io.observe(el); });

        function sweep() {
            pending = pending.filter(function (el) {
                if (el.getBoundingClientRect().top >= window.innerHeight) { return true; }
                fire(el);
                io.unobserve(el);
                return false;
            });

            if (!pending.length) {
                window.removeEventListener('scroll', onScroll);
                window.removeEventListener('resize', onScroll);
            }
        }

        var onScroll = LH.debounce(sweep, 120);
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        LH.on(window, 'load', sweep);
        sweep();
    }

    /* --------------------------------------------------------------------
       Scroll reveal
       -------------------------------------------------------------------- */
    LH.register('reveal', function () {
        whenInView(LH.$$('[data-reveal]'), function (el) {
            var delay = parseFloat(el.getAttribute('data-reveal-delay')) || 0;
            if (delay && !reduced) {
                setTimeout(function () { el.classList.add('is-visible'); }, delay);
            } else {
                el.classList.add('is-visible');
            }
        });
    });

    /* --------------------------------------------------------------------
       Animated counters — <span class="counter" data-count="20000" data-suffix="+">
       -------------------------------------------------------------------- */
    LH.register('counters', function () {
        var items = LH.$$('[data-count]');
        if (!items.length) { return; }

        function run(el) {
            var target = parseFloat(el.getAttribute('data-count')) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            var prefix = el.getAttribute('data-prefix') || '';
            var decimals = parseInt(el.getAttribute('data-decimals'), 10) || 0;

            if (reduced) {
                el.textContent = prefix + LH.formatNumber(target.toFixed(decimals)) + suffix;
                return;
            }

            var duration = 1500;
            var start = null;

            function frame(ts) {
                if (start === null) { start = ts; }
                var p = Math.min((ts - start) / duration, 1);
                /* easeOutCubic keeps the count fast at first, settling smoothly. */
                var eased = 1 - Math.pow(1 - p, 3);
                var value = (target * eased).toFixed(decimals);
                el.textContent = prefix + LH.formatNumber(value) + suffix;
                if (p < 1) { window.requestAnimationFrame(frame); }
            }
            window.requestAnimationFrame(frame);
        }

        whenInView(items, run);
    });

    /* --------------------------------------------------------------------
       Progress bars fill when scrolled into view
       -------------------------------------------------------------------- */
    LH.register('progress-bars', function () {
        var bars = LH.$$('[data-progress]');
        if (!bars.length) { return; }

        function fill(bar) {
            var pct = Math.max(0, Math.min(100, parseFloat(bar.getAttribute('data-progress')) || 0));
            bar.style.width = pct + '%';
        }

        whenInView(bars, function (bar) {
            setTimeout(function () { fill(bar); }, reduced ? 0 : 120);
        });
    });

    /* --------------------------------------------------------------------
       Circular progress rings
       -------------------------------------------------------------------- */
    LH.register('rings', function () {
        LH.$$('[data-ring]').forEach(function (circle) {
            var pct = Math.max(0, Math.min(100, parseFloat(circle.getAttribute('data-ring')) || 0));
            var r = circle.r.baseVal.value;
            var circumference = 2 * Math.PI * r;
            circle.style.strokeDasharray = circumference;
            circle.style.strokeDashoffset = circumference;
            setTimeout(function () {
                circle.style.strokeDashoffset = circumference * (1 - pct / 100);
            }, reduced ? 0 : 260);
        });
    });

    /* --------------------------------------------------------------------
       CSS bar charts grow into place
       -------------------------------------------------------------------- */
    LH.register('charts', function () {
        var bars = LH.$$('[data-bar]');
        if (!bars.length) { return; }

        function grow(bar) {
            bar.style.height = (parseFloat(bar.getAttribute('data-bar')) || 0) + '%';
        }
        whenInView(bars, function (bar) {
            var idx = bars.indexOf(bar);
            setTimeout(function () { grow(bar); }, reduced ? 0 : Math.min(idx, 14) * 45);
        });
    });

    /* --------------------------------------------------------------------
       Thin page-load progress bar on navigation
       -------------------------------------------------------------------- */
    LH.register('page-progress', function () {
        if (reduced) { return; }
        var bar = document.createElement('div');
        bar.className = 'page-progress';
        document.body.appendChild(bar);

        LH.delegate('click', 'a[href]', function (e) {
            var href = this.getAttribute('href');
            if (!href || href.charAt(0) === '#' || this.target === '_blank' ||
                href.indexOf('javascript:') === 0 || this.hasAttribute('download') ||
                e.metaKey || e.ctrlKey) {
                return;
            }
            bar.style.width = '18%';
            setTimeout(function () { bar.style.width = '62%'; }, 180);
        });

        LH.on(window, 'pageshow', function () {
            bar.style.width = '100%';
            setTimeout(function () { bar.style.opacity = '0'; bar.style.width = '0'; }, 260);
            setTimeout(function () { bar.style.opacity = '1'; }, 620);
        });
    });
})(window.LearnHub);
