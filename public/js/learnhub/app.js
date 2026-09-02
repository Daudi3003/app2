/* =========================================================================
   LearnHub — core namespace & shared helpers
   Plain ES5+/ES2015 browser JS. No bundler, no framework, no dependencies.
   Loaded with `defer`, so the DOM is ready when these run.
   ========================================================================= */
(function (window, document) {
    'use strict';

    var LearnHub = window.LearnHub || {};

    /* ---- tiny DOM helpers ---- */
    LearnHub.$  = function (sel, root) { return (root || document).querySelector(sel); };
    LearnHub.$$ = function (sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    };

    LearnHub.on = function (el, evt, handler, opts) {
        if (el) { el.addEventListener(evt, handler, opts || false); }
    };

    /**
     * Event delegation — survives DOM that is filtered/re-rendered.
     */
    LearnHub.delegate = function (evt, selector, handler) {
        document.addEventListener(evt, function (e) {
            var target = e.target.closest(selector);
            if (target && document.contains(target)) {
                handler.call(target, e, target);
            }
        });
    };

    LearnHub.debounce = function (fn, wait) {
        var timer;
        return function () {
            var ctx = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () { fn.apply(ctx, args); }, wait || 200);
        };
    };

    /** Escape user-supplied text before injecting into innerHTML. */
    LearnHub.escape = function (str) {
        var d = document.createElement('div');
        d.textContent = str == null ? '' : String(str);
        return d.innerHTML;
    };

    LearnHub.formatNumber = function (n) {
        return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    LearnHub.prefersReducedMotion = function () {
        return window.matchMedia &&
               window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    };

    /* ---- registry of init functions ---- */
    LearnHub._modules = [];
    LearnHub.register = function (name, fn) {
        LearnHub._modules.push({ name: name, fn: fn });
    };

    LearnHub.boot = function () {
        LearnHub._modules.forEach(function (m) {
            try {
                m.fn();
            } catch (err) {
                /* One broken widget must never take the whole page down. */
                if (window.console) {
                    console.error('[LearnHub] module "' + m.name + '" failed:', err);
                }
            }
        });
    };

    window.LearnHub = LearnHub;

    /*
     * Every module file is loaded with `defer`, so they all execute — and
     * therefore register — before DOMContentLoaded fires. Booting must wait
     * for that event, not for readyState alone: when this first deferred
     * script runs, readyState is already 'interactive', so booting here
     * would run an empty module registry.
     */
    if (document.readyState === 'complete') {
        LearnHub.boot();
    } else {
        document.addEventListener('DOMContentLoaded', LearnHub.boot);
    }
})(window, document);
