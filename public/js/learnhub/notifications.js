/* =========================================================================
   LearnHub — toasts & dismissible alerts
   Public API:  LearnHub.toast('Saved', { type: 'success', title: 'Done' })
   ========================================================================= */
(function (LH) {
    'use strict';

    var ICONS = {
        success: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        warning: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        danger:  '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        info:    '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
    };

    function host() {
        var el = LH.$('.toast-host');
        if (!el) {
            el = document.createElement('div');
            el.className = 'toast-host';
            el.setAttribute('role', 'status');
            el.setAttribute('aria-live', 'polite');
            document.body.appendChild(el);
        }
        return el;
    }

    function dismiss(toast) {
        if (!toast || toast.classList.contains('is-leaving')) { return; }
        toast.classList.add('is-leaving');
        setTimeout(function () {
            if (toast.parentNode) { toast.parentNode.removeChild(toast); }
        }, 300);
    }

    /**
     * @param {string} message
     * @param {{type?:string, title?:string, duration?:number}} [opts]
     */
    LH.toast = function (message, opts) {
        opts = opts || {};
        var type = opts.type || 'info';
        var duration = typeof opts.duration === 'number' ? opts.duration : 4000;

        var el = document.createElement('div');
        el.className = 'toast toast--' + type;
        el.innerHTML =
            '<span class="toast__icon">' + (ICONS[type] || ICONS.info) + '</span>' +
            '<div class="toast__body">' +
                (opts.title ? '<div class="toast__title">' + LH.escape(opts.title) + '</div>' : '') +
                '<div class="toast__text">' + LH.escape(message) + '</div>' +
            '</div>' +
            '<button type="button" class="toast__close" aria-label="Dismiss notification">' +
                '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
            '</button>';

        host().appendChild(el);
        LH.on(LH.$('.toast__close', el), 'click', function () { dismiss(el); });
        if (duration > 0) { setTimeout(function () { dismiss(el); }, duration); }
        return el;
    };

    /* Dismissible alerts rendered server-side. */
    LH.register('alerts', function () {
        LH.delegate('click', '[data-alert-close]', function () {
            var alert = this.closest('.alert');
            if (!alert) { return; }
            alert.style.transition = 'opacity .25s, transform .25s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(function () {
                if (alert.parentNode) { alert.parentNode.removeChild(alert); }
            }, 250);
        });
    });

    /* Any element can fire a demo toast: <button data-toast="Saved!" data-toast-type="success"> */
    LH.register('toast-triggers', function () {
        LH.delegate('click', '[data-toast]', function (e) {
            if (this.tagName === 'A' && this.getAttribute('href') === '#') { e.preventDefault(); }
            LH.toast(this.getAttribute('data-toast'), {
                type:  this.getAttribute('data-toast-type') || 'success',
                title: this.getAttribute('data-toast-title') || ''
            });
        });
    });

    /* Server-flashed message → toast on load. */
    LH.register('flash-toast', function () {
        var flash = LH.$('[data-flash]');
        if (!flash) { return; }
        LH.toast(flash.getAttribute('data-flash'), {
            type: flash.getAttribute('data-flash-type') || 'success'
        });
    });
})(window.LearnHub);
