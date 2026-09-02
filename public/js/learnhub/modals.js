/* =========================================================================
   LearnHub — modals, tabs, accordions
   ========================================================================= */
(function (LH) {
    'use strict';

    /* --------------------------------------------------------------------
       Modals
       -------------------------------------------------------------------- */
    LH.register('modals', function () {
        var lastFocused = null;

        function open(modal) {
            if (!modal) { return; }
            lastFocused = document.activeElement;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            var focusable = LH.$('input, textarea, select, button, [href]', modal);
            if (focusable) { setTimeout(function () { focusable.focus(); }, 120); }
        }

        function close(modal) {
            if (!modal) { return; }
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            if (!LH.$('.modal.is-open')) { document.body.style.overflow = ''; }
            if (lastFocused && lastFocused.focus) { lastFocused.focus(); }
        }

        LH.modalOpen = function (id) { open(document.getElementById(id)); };
        LH.modalClose = function (id) { close(document.getElementById(id)); };

        LH.delegate('click', '[data-modal-open]', function (e) {
            e.preventDefault();
            open(document.getElementById(this.getAttribute('data-modal-open')));
        });

        LH.delegate('click', '[data-modal-close]', function (e) {
            e.preventDefault();
            close(this.closest('.modal'));
        });

        /* Backdrop click closes; clicks inside the dialog do not bubble here. */
        LH.delegate('click', '.modal', function (e) {
            if (e.target === this) { close(this); }
        });

        LH.on(document, 'keydown', function (e) {
            if (e.key === 'Escape') {
                var openModal = LH.$('.modal.is-open');
                if (openModal) { close(openModal); }
            }
        });

        /* Keep focus inside an open dialog. */
        LH.on(document, 'keydown', function (e) {
            if (e.key !== 'Tab') { return; }
            var modal = LH.$('.modal.is-open');
            if (!modal) { return; }
            var items = LH.$$(
                'a[href], button:not([disabled]), input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])',
                modal
            ).filter(function (el) { return el.offsetParent !== null; });
            if (!items.length) { return; }
            var first = items[0], last = items[items.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault(); last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault(); first.focus();
            }
        });
    });

    /* --------------------------------------------------------------------
       Tabs
       Markup: [data-tabs] > button[data-tab="key"] … + [data-tab-panel="key"]
       -------------------------------------------------------------------- */
    LH.register('tabs', function () {
        LH.$$('[data-tabs]').forEach(function (group) {
            var scope = group.getAttribute('data-tabs');
            var buttons = LH.$$('[data-tab]', group);

            function activate(key) {
                buttons.forEach(function (b) {
                    var on = b.getAttribute('data-tab') === key;
                    b.classList.toggle('is-active', on);
                    b.setAttribute('aria-selected', on ? 'true' : 'false');
                    b.setAttribute('tabindex', on ? '0' : '-1');
                });
                LH.$$('[data-tab-panel][data-tab-scope="' + scope + '"]').forEach(function (p) {
                    p.classList.toggle('is-active', p.getAttribute('data-tab-panel') === key);
                });
            }

            buttons.forEach(function (btn, i) {
                LH.on(btn, 'click', function () { activate(btn.getAttribute('data-tab')); });

                /* Arrow-key navigation between tabs (WAI-ARIA pattern). */
                LH.on(btn, 'keydown', function (e) {
                    var dir = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
                    if (!dir) { return; }
                    e.preventDefault();
                    var next = buttons[(i + dir + buttons.length) % buttons.length];
                    next.focus();
                    activate(next.getAttribute('data-tab'));
                });
            });
        });
    });

    /* --------------------------------------------------------------------
       Accordions (course curriculum, FAQ)
       -------------------------------------------------------------------- */
    LH.register('accordion', function () {
        LH.delegate('click', '[data-accordion-trigger]', function () {
            var item = this.closest('.accordion__item');
            if (!item) { return; }
            var group = item.closest('[data-accordion]');
            var isOpen = item.classList.contains('is-open');

            /* data-accordion="single" behaves like an exclusive group. */
            if (group && group.getAttribute('data-accordion') === 'single' && !isOpen) {
                LH.$$('.accordion__item.is-open', group).forEach(function (other) {
                    other.classList.remove('is-open');
                    var t = LH.$('[data-accordion-trigger]', other);
                    if (t) { t.setAttribute('aria-expanded', 'false'); }
                });
            }

            item.classList.toggle('is-open', !isOpen);
            this.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
        });
    });
})(window.LearnHub);
