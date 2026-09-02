/* =========================================================================
   LearnHub — form behaviour
   Password visibility & strength · multi-step wizard · search clear
   dropzone · character counters · settings panes
   ========================================================================= */
(function (LH) {
    'use strict';

    var EYE = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    var EYE_OFF = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

    /* --------------------------------------------------------------------
       Password show / hide
       -------------------------------------------------------------------- */
    LH.register('password-toggle', function () {
        LH.delegate('click', '[data-password-toggle]', function (e) {
            e.preventDefault();
            var input = document.getElementById(this.getAttribute('data-password-toggle'));
            if (!input) { return; }
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            this.innerHTML = show ? EYE_OFF : EYE;
            this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });

    /* --------------------------------------------------------------------
       Password strength meter
       -------------------------------------------------------------------- */
    LH.register('password-strength', function () {
        LH.$$('[data-password-strength]').forEach(function (input) {
            var meter = document.getElementById(input.getAttribute('data-password-strength'));
            if (!meter) { return; }
            var segs  = LH.$$('.pw-meter__seg', meter);
            var label = LH.$('.pw-meter-label', meter.parentNode);

            LH.on(input, 'input', function () {
                var v = input.value;
                var score = 0;
                if (v.length >= 8) { score++; }
                if (/[a-z]/.test(v) && /[A-Z]/.test(v)) { score++; }
                if (/[0-9]/.test(v)) { score++; }
                if (/[^A-Za-z0-9]/.test(v)) { score++; }
                if (v.length === 0) { score = 0; }

                var classes = ['is-weak', 'is-fair', 'is-good', 'is-strong'];
                var names   = ['Weak', 'Fair', 'Good', 'Strong'];

                segs.forEach(function (seg, i) {
                    classes.forEach(function (c) { seg.classList.remove(c); });
                    if (i < score) { seg.classList.add(classes[score - 1]); }
                });
                if (label) {
                    label.textContent = score ? 'Password strength: ' + names[score - 1] : '';
                }
            });
        });
    });

    /* --------------------------------------------------------------------
       Search input clear button
       -------------------------------------------------------------------- */
    LH.register('search-clear', function () {
        LH.$$('.search').forEach(function (wrap) {
            var input = LH.$('input', wrap);
            var clear = LH.$('.search__clear', wrap);
            if (!input) { return; }

            function sync() { wrap.classList.toggle('has-value', input.value.length > 0); }
            LH.on(input, 'input', sync);
            LH.on(clear, 'click', function (e) {
                e.preventDefault();
                input.value = '';
                sync();
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.focus();
            });
            sync();
        });
    });

    /* --------------------------------------------------------------------
       Multi-step wizard (create course, etc.)
       -------------------------------------------------------------------- */
    LH.register('wizard', function () {
        LH.$$('[data-wizard]').forEach(function (form) {
            var steps  = LH.$$('[data-step]', form);
            var panels = LH.$$('[data-step-panel]', form);
            var lines  = LH.$$('.step__line', form);
            var back   = LH.$('[data-wizard-back]', form);
            var next   = LH.$('[data-wizard-next]', form);
            var submit = LH.$('[data-wizard-submit]', form);
            var current = 0;

            function render() {
                steps.forEach(function (s, i) {
                    s.classList.toggle('is-active', i === current);
                    s.classList.toggle('is-done', i < current);
                });
                lines.forEach(function (l, i) {
                    l.style.background = i < current ? 'var(--success)' : 'var(--border)';
                });
                panels.forEach(function (p, i) { p.classList.toggle('is-active', i === current); });

                if (back)   { back.hidden = current === 0; }
                if (next)   { next.hidden = current === panels.length - 1; }
                if (submit) { submit.hidden = current !== panels.length - 1; }

                form.scrollIntoView({
                    behavior: LH.prefersReducedMotion() ? 'auto' : 'smooth',
                    block: 'start'
                });
            }

            /** HTML5 validity check limited to the visible step. */
            function validCurrentStep() {
                var panel = panels[current];
                var fields = LH.$$('input, select, textarea', panel);
                var ok = true;
                fields.forEach(function (f) {
                    if (f.hasAttribute('required') && !f.value.trim()) {
                        f.classList.add('is-invalid');
                        ok = false;
                    } else {
                        f.classList.remove('is-invalid');
                    }
                });
                if (!ok) { LH.toast('Please complete all required fields', { type: 'warning' }); }
                return ok;
            }

            LH.on(next, 'click', function (e) {
                e.preventDefault();
                if (!validCurrentStep()) { return; }
                if (current < panels.length - 1) { current++; render(); }
            });
            LH.on(back, 'click', function (e) {
                e.preventDefault();
                if (current > 0) { current--; render(); }
            });

            /* Clicking a completed step badge jumps back to it. */
            steps.forEach(function (s, i) {
                LH.on(s, 'click', function () {
                    if (i < current) { current = i; render(); }
                });
            });

            LH.on(form, 'submit', function (e) {
                /* Frontend phase: no backend write yet — simulate and report. */
                if (form.getAttribute('data-wizard') === 'simulate') {
                    e.preventDefault();
                    if (!validCurrentStep()) { return; }
                    if (submit) { submit.classList.add('is-loading'); }
                    setTimeout(function () {
                        if (submit) { submit.classList.remove('is-loading'); }
                        LH.toast('Course saved as a draft', {
                            type: 'success',
                            title: 'All steps complete 🎉'
                        });
                    }, 900);
                }
            });

            render();
        });
    });

    /* --------------------------------------------------------------------
       Simulated form submit (any form that has no backend yet)
       -------------------------------------------------------------------- */
    LH.register('simulated-forms', function () {
        LH.$$('form[data-simulate-form]').forEach(function (form) {
            LH.on(form, 'submit', function (e) {
                e.preventDefault();
                if (!form.checkValidity()) { form.reportValidity(); return; }
                var btn = LH.$('[type="submit"]', form);
                if (btn) { btn.classList.add('is-loading'); }
                setTimeout(function () {
                    if (btn) { btn.classList.remove('is-loading'); }
                    LH.toast(form.getAttribute('data-simulate-form'), { type: 'success' });
                    if (form.hasAttribute('data-simulate-reset')) { form.reset(); }
                    var modal = form.closest('.modal');
                    if (modal) { modal.classList.remove('is-open'); document.body.style.overflow = ''; }
                }, 900);
            });
        });
    });

    /* --------------------------------------------------------------------
       Dropzone (materials upload) — simulated in the frontend phase
       -------------------------------------------------------------------- */
    LH.register('dropzone', function () {
        LH.$$('[data-dropzone]').forEach(function (zone) {
            var input = LH.$('input[type="file"]', zone);
            var list  = LH.$(zone.getAttribute('data-dropzone-list') || '[data-file-list]',
                             zone.parentNode) || LH.$('[data-file-list]');

            function addFiles(files) {
                if (!list) { return; }
                Array.prototype.forEach.call(files, function (file) {
                    var row = document.createElement('div');
                    row.className = 'file-row';
                    row.innerHTML =
                        '<span class="file-row__icon">📄</span>' +
                        '<span class="file-row__body">' +
                            '<span class="file-row__name">' + LH.escape(file.name) + '</span>' +
                            '<span class="file-row__meta">' +
                                (file.size / 1024 / 1024).toFixed(2) + ' MB · ready to upload' +
                            '</span>' +
                        '</span>' +
                        '<button type="button" class="btn-icon btn-icon--sm is-danger" ' +
                            'aria-label="Remove file" data-file-remove>&times;</button>';
                    list.appendChild(row);
                });
                LH.toast(files.length + ' file(s) queued for upload', { type: 'success' });
            }

            LH.on(zone, 'click', function () { if (input) { input.click(); } });
            LH.on(input, 'change', function () { if (input.files.length) { addFiles(input.files); } });

            ['dragenter', 'dragover'].forEach(function (evt) {
                LH.on(zone, evt, function (e) {
                    e.preventDefault();
                    zone.classList.add('is-dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function (evt) {
                LH.on(zone, evt, function (e) {
                    e.preventDefault();
                    zone.classList.remove('is-dragover');
                });
            });
            LH.on(zone, 'drop', function (e) {
                if (e.dataTransfer && e.dataTransfer.files.length) { addFiles(e.dataTransfer.files); }
            });
        });

        LH.delegate('click', '[data-file-remove]', function (e) {
            e.preventDefault();
            var row = this.closest('.file-row');
            if (row && row.parentNode) { row.parentNode.removeChild(row); }
        });
    });

    /* --------------------------------------------------------------------
       Character counters
       -------------------------------------------------------------------- */
    LH.register('char-count', function () {
        LH.$$('[data-count-target]').forEach(function (field) {
            var out = document.getElementById(field.getAttribute('data-count-target'));
            if (!out) { return; }
            var max = field.getAttribute('maxlength');
            function run() {
                out.textContent = field.value.length + (max ? ' / ' + max : '') + ' characters';
            }
            LH.on(field, 'input', run);
            run();
        });
    });

    /* --------------------------------------------------------------------
       Settings side-nav panes
       -------------------------------------------------------------------- */
    LH.register('settings-panes', function () {
        var nav = LH.$('[data-settings-nav]');
        if (!nav) { return; }
        LH.$$('button', nav).forEach(function (btn) {
            LH.on(btn, 'click', function () {
                var key = btn.getAttribute('data-pane');
                LH.$$('button', nav).forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });
                LH.$$('[data-pane-panel]').forEach(function (p) {
                    p.hidden = p.getAttribute('data-pane-panel') !== key;
                });
            });
        });
    });

    /* --------------------------------------------------------------------
       Switch labels announce state changes
       -------------------------------------------------------------------- */
    LH.register('switches', function () {
        LH.delegate('change', '.switch input[type="checkbox"]', function () {
            var title = this.closest('.switch');
            var name = title ? (LH.$('.switch__title', title) || {}).textContent : 'Setting';
            LH.toast((name || 'Setting').trim() + (this.checked ? ' enabled' : ' disabled'), {
                type: 'info', duration: 2200
            });
        });
    });
})(window.LearnHub);
