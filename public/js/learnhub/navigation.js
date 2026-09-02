/* =========================================================================
   LearnHub — navigation
   Public mobile drawer · dashboard sidebar · dropdowns · sticky navbar
   ========================================================================= */
(function (LH) {
    'use strict';

    /* --------------------------------------------------------------------
       Public mobile drawer
       -------------------------------------------------------------------- */
    LH.register('mobile-nav', function () {
        var toggle  = LH.$('[data-nav-toggle]');
        var drawer  = LH.$('[data-nav-drawer]');
        var overlay = LH.$('[data-nav-overlay]');
        if (!toggle || !drawer) { return; }

        function setOpen(open) {
            drawer.classList.toggle('is-open', open);
            toggle.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (overlay) { overlay.classList.toggle('is-open', open); }
            document.body.style.overflow = open ? 'hidden' : '';
        }

        LH.on(toggle, 'click', function () {
            setOpen(!drawer.classList.contains('is-open'));
        });
        LH.on(overlay, 'click', function () { setOpen(false); });

        /* Close after tapping a link so the target section is visible. */
        LH.$$('a', drawer).forEach(function (a) {
            LH.on(a, 'click', function () { setOpen(false); });
        });

        LH.on(document, 'keydown', function (e) {
            if (e.key === 'Escape') { setOpen(false); }
        });

        /* A resize past the breakpoint must not leave the body locked. */
        LH.on(window, 'resize', LH.debounce(function () {
            if (window.innerWidth > 1024) { setOpen(false); }
        }, 150));
    });

    /* --------------------------------------------------------------------
       Dashboard sidebar (off-canvas below 1024px)
       -------------------------------------------------------------------- */
    LH.register('sidebar', function () {
        var sidebar = LH.$('[data-sidebar]');
        var overlay = LH.$('[data-sidebar-overlay]');
        if (!sidebar) { return; }

        function setOpen(open) {
            sidebar.classList.toggle('is-open', open);
            if (overlay) { overlay.classList.toggle('is-open', open); }
            document.body.style.overflow = open && window.innerWidth <= 1024 ? 'hidden' : '';
            LH.$$('[data-sidebar-toggle]').forEach(function (btn) {
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        LH.$$('[data-sidebar-toggle]').forEach(function (btn) {
            LH.on(btn, 'click', function () {
                setOpen(!sidebar.classList.contains('is-open'));
            });
        });
        LH.$$('[data-sidebar-close]').forEach(function (btn) {
            LH.on(btn, 'click', function () { setOpen(false); });
        });
        LH.on(overlay, 'click', function () { setOpen(false); });

        LH.on(document, 'keydown', function (e) {
            if (e.key === 'Escape') { setOpen(false); }
        });

        LH.on(window, 'resize', LH.debounce(function () {
            if (window.innerWidth > 1024) { setOpen(false); }
        }, 150));
    });

    /* --------------------------------------------------------------------
       Dropdowns (profile menu, notifications, row actions)
       -------------------------------------------------------------------- */
    LH.register('dropdowns', function () {
        function closeAll(except) {
            LH.$$('.dropdown.is-open').forEach(function (d) {
                if (d !== except) {
                    d.classList.remove('is-open');
                    var t = LH.$('[data-dropdown-toggle]', d);
                    if (t) { t.setAttribute('aria-expanded', 'false'); }
                }
            });
        }

        LH.delegate('click', '[data-dropdown-toggle]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var dd = this.closest('.dropdown');
            if (!dd) { return; }
            var willOpen = !dd.classList.contains('is-open');
            closeAll(dd);
            dd.classList.toggle('is-open', willOpen);
            this.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });

        /*
         * Click anywhere outside closes. Both this and the toggle handler are
         * bound to `document`, so stopPropagation() in the toggle does not
         * prevent this one from running — it must ignore toggle clicks
         * explicitly, or a dropdown would close in the same tick it opened.
         */
        LH.on(document, 'click', function (e) {
            if (e.target.closest('.dropdown__menu') ||
                e.target.closest('[data-dropdown-toggle]')) {
                return;
            }
            closeAll(null);
        });
        LH.on(document, 'keydown', function (e) {
            if (e.key === 'Escape') { closeAll(null); }
        });
    });

    /* --------------------------------------------------------------------
       Navbar shadow on scroll
       -------------------------------------------------------------------- */
    LH.register('navbar-scroll', function () {
        var navbar = LH.$('[data-navbar]');
        if (!navbar) { return; }
        var ticking = false;

        function update() {
            navbar.classList.toggle('is-scrolled', window.scrollY > 8);
            ticking = false;
        }
        LH.on(window, 'scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(update);
                ticking = true;
            }
        }, { passive: true });
        update();
    });

    /* --------------------------------------------------------------------
       Smooth in-page anchors
       -------------------------------------------------------------------- */
    LH.register('anchors', function () {
        LH.delegate('click', 'a[href^="#"]', function (e) {
            var href = this.getAttribute('href');
            if (!href || href === '#' || href.length < 2) { return; }
            var target = document.getElementById(href.slice(1));
            if (!target) { return; }
            e.preventDefault();
            target.scrollIntoView({
                behavior: LH.prefersReducedMotion() ? 'auto' : 'smooth',
                block: 'start'
            });
            history.replaceState(null, '', href);
        });
    });
})(window.LearnHub);
