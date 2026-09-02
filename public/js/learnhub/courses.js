/* =========================================================================
   LearnHub — course catalogue behaviour
   Live search · filters · sorting · grid/list toggle · favourites · enrol
   Works entirely client-side over the cards Blade already rendered, so the
   same markup keeps working once the data comes from MySQL.
   ========================================================================= */
(function (LH) {
    'use strict';

    /* --------------------------------------------------------------------
       Catalogue filter + search + sort
       Each card carries: data-title data-category data-level
                          data-rating data-students data-date
       -------------------------------------------------------------------- */
    LH.register('catalog', function () {
        var root = LH.$('[data-catalog]');
        if (!root) { return; }

        var grid    = LH.$('[data-catalog-grid]', root);
        var cards   = LH.$$('[data-course-card]', grid);
        var counter = LH.$('[data-catalog-count]', root);
        var empty   = LH.$('[data-catalog-empty]', root);
        var search  = LH.$('[data-catalog-search]', root);
        var sortSel = LH.$('[data-catalog-sort]', root);

        function checkedValues(name) {
            return LH.$$('[data-filter="' + name + '"]:checked', root)
                     .map(function (i) { return i.value; });
        }

        function matches(card) {
            var q = search && search.value.trim().toLowerCase();
            if (q) {
                var haystack = (card.getAttribute('data-title') + ' ' +
                                card.getAttribute('data-category') + ' ' +
                                (card.getAttribute('data-instructor') || '')).toLowerCase();
                if (haystack.indexOf(q) === -1) { return false; }
            }

            var cats = checkedValues('category');
            if (cats.length && cats.indexOf(card.getAttribute('data-category')) === -1) { return false; }

            var levels = checkedValues('level');
            if (levels.length && levels.indexOf(card.getAttribute('data-level')) === -1) { return false; }

            var ratings = checkedValues('rating');
            if (ratings.length) {
                var rating = parseFloat(card.getAttribute('data-rating')) || 0;
                var min = Math.min.apply(null, ratings.map(parseFloat));
                if (rating < min) { return false; }
            }
            return true;
        }

        function sortCards(list) {
            var mode = sortSel ? sortSel.value : 'popular';
            var num = function (el, attr) { return parseFloat(el.getAttribute(attr)) || 0; };

            return list.slice().sort(function (a, b) {
                switch (mode) {
                    case 'newest':      return num(b, 'data-date') - num(a, 'data-date');
                    case 'rating':      return num(b, 'data-rating') - num(a, 'data-rating');
                                                            case 'title':
                        return (a.getAttribute('data-title') || '')
                            .localeCompare(b.getAttribute('data-title') || '');
                    default:            return num(b, 'data-students') - num(a, 'data-students');
                }
            });
        }

        function apply() {
            var visible = cards.filter(matches);

            cards.forEach(function (c) { c.style.display = 'none'; });
            sortCards(visible).forEach(function (c, i) {
                c.style.display = '';
                c.style.animation = 'none';
                /* Force reflow so the stagger animation replays on every filter. */
                void c.offsetWidth;
                c.style.animation = 'lh-fade-up .4s var(--ease-out) both';
                c.style.animationDelay = Math.min(i, 8) * 0.035 + 's';
            });

            if (counter) { counter.textContent = visible.length; }
            if (empty) { empty.hidden = visible.length !== 0; }
            if (grid) { grid.hidden = visible.length === 0; }
        }

        LH.on(search, 'input', LH.debounce(apply, 180));
        LH.on(sortSel, 'change', apply);
        LH.$$('[data-filter]', root).forEach(function (input) {
            LH.on(input, 'change', apply);
        });

        /* Reset */
        LH.$$('[data-catalog-reset]', root).forEach(function (btn) {
            LH.on(btn, 'click', function (e) {
                e.preventDefault();
                if (search) { search.value = ''; }
                LH.$$('[data-filter]', root).forEach(function (i) { i.checked = false; });
                if (sortSel) { sortSel.value = 'popular'; }
                apply();
                LH.toast('Filters cleared', { type: 'info' });
            });
        });

        /* Grid / list view */
        LH.$$('[data-view]', root).forEach(function (btn) {
            LH.on(btn, 'click', function () {
                var mode = btn.getAttribute('data-view');
                LH.$$('[data-view]', root).forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                    b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
                });
                grid.classList.toggle('is-list', mode === 'list');
            });
        });

        /* Mobile filter drawer */
        var filterToggle = LH.$('[data-filters-toggle]', root);
        var aside = LH.$('[data-filters-panel]', root);
        LH.on(filterToggle, 'click', function () {
            var open = aside.classList.toggle('is-open');
            filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        apply();
    });

    /* --------------------------------------------------------------------
       Favourite (wishlist) buttons — persist per browser via localStorage
       -------------------------------------------------------------------- */
    LH.register('favourites', function () {
        var KEY = 'learnhub.favourites';
        var saved;
        try { saved = JSON.parse(localStorage.getItem(KEY)) || []; } catch (e) { saved = []; }

        function persist() {
            try { localStorage.setItem(KEY, JSON.stringify(saved)); } catch (e) { /* private mode */ }
        }

        LH.$$('[data-favourite]').forEach(function (btn) {
            if (saved.indexOf(btn.getAttribute('data-favourite')) !== -1) {
                btn.classList.add('is-active');
                btn.setAttribute('aria-pressed', 'true');
            }
        });

        LH.delegate('click', '[data-favourite]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = this.getAttribute('data-favourite');
            var on = !this.classList.contains('is-active');

            this.classList.toggle('is-active', on);
            this.setAttribute('aria-pressed', on ? 'true' : 'false');
            this.setAttribute('aria-label', on ? 'Remove from bookmarks' : 'Save to bookmarks');

            var idx = saved.indexOf(id);
            if (on && idx === -1) { saved.push(id); }
            if (!on && idx !== -1) { saved.splice(idx, 1); }
            persist();

            LH.toast(on ? 'Saved to your bookmarks' : 'Removed from bookmarks', {
                type: on ? 'success' : 'info'
            });
        });
    });

    /* --------------------------------------------------------------------
       Enrol / submit buttons — simulated feedback for the frontend phase
       -------------------------------------------------------------------- */
    LH.register('simulated-actions', function () {
        LH.delegate('click', '[data-simulate]', function (e) {
            e.preventDefault();
            var btn = this;
            if (btn.classList.contains('is-loading')) { return; }

            var label = btn.getAttribute('data-simulate') || 'Done';
            var done  = btn.getAttribute('data-simulate-done');
            btn.classList.add('is-loading');

            setTimeout(function () {
                btn.classList.remove('is-loading');
                if (done) { btn.innerHTML = done; btn.classList.add('is-disabled'); }
                LH.toast(label, {
                    type: btn.getAttribute('data-simulate-type') || 'success'
                });
            }, 850);
        });
    });

    /* --------------------------------------------------------------------
       Lesson completion toggles in the learning player
       -------------------------------------------------------------------- */
    LH.register('lesson-complete', function () {
        LH.delegate('click', '[data-lesson-toggle]', function (e) {
            e.preventDefault();
            var item = this.closest('.curriculum-item') || this;
            var done = item.classList.toggle('is-done');
            LH.toast(done ? 'Lesson marked complete 🎉' : 'Lesson marked incomplete', {
                type: done ? 'success' : 'info'
            });
        });
    });

    /* --------------------------------------------------------------------
       Generic table / list search (admin & instructor screens)
       Input: [data-table-search="#tableId"]  Rows: <tr data-row-text="...">
       -------------------------------------------------------------------- */
    LH.register('table-search', function () {
        LH.$$('[data-table-search]').forEach(function (input) {
            var scope = LH.$(input.getAttribute('data-table-search'));
            if (!scope) { return; }
            var rows  = LH.$$('[data-row]', scope);
            var empty = LH.$('[data-table-empty]', scope.parentNode) ||
                        LH.$('[data-table-empty]', scope);

            function run() {
                var q = input.value.trim().toLowerCase();
                var shown = 0;
                rows.forEach(function (row) {
                    var text = (row.getAttribute('data-row-text') || row.textContent).toLowerCase();
                    var hit = !q || text.indexOf(q) !== -1;
                    row.hidden = !hit;
                    if (hit) { shown++; }
                });
                if (empty) { empty.hidden = shown !== 0; }
            }
            LH.on(input, 'input', LH.debounce(run, 160));
        });
    });

    /* --------------------------------------------------------------------
       Generic select-based row filter: [data-row-filter="#id"] + data-filter-key
       -------------------------------------------------------------------- */
    LH.register('row-filter', function () {
        LH.$$('[data-row-filter]').forEach(function (select) {
            var scope = LH.$(select.getAttribute('data-row-filter'));
            if (!scope) { return; }
            var key = select.getAttribute('data-filter-key');

            LH.on(select, 'change', function () {
                var val = select.value;
                LH.$$('[data-row]', scope).forEach(function (row) {
                    row.hidden = !!val && row.getAttribute('data-' + key) !== val;
                });
            });
        });
    });

    /* --------------------------------------------------------------------
       Home-page live search suggestions
       -------------------------------------------------------------------- */
    LH.register('hero-search', function () {
        var box = LH.$('[data-hero-search]');
        if (!box) { return; }
        var input   = LH.$('input', box);
        var results = LH.$('[data-hero-results]', box);
        var data    = [];

        try {
            data = JSON.parse(LH.$('[data-course-index]').textContent) || [];
        } catch (e) { data = []; }

        function render(list, q) {
            if (!q) { results.classList.remove('is-open'); return; }
            if (!list.length) {
                results.innerHTML = '<div class="search-results__empty">No courses match “' +
                                    LH.escape(q) + '”. Try another keyword.</div>';
            } else {
                results.innerHTML = list.slice(0, 6).map(function (c) {
                    return '<a class="search-results__item" href="' + LH.escape(c.url) + '">' +
                           '<span class="search-results__thumb">' + LH.escape(c.emoji) + '</span>' +
                           '<span><span class="search-results__title">' + LH.escape(c.title) + '</span>' +
                           '<span class="search-results__meta">' + LH.escape(c.category) +
                           ' · ' + LH.escape(c.instructor) + '</span></span></a>';
                }).join('');
            }
            results.classList.add('is-open');
        }

        LH.on(input, 'input', LH.debounce(function () {
            var q = input.value.trim().toLowerCase();
            render(data.filter(function (c) {
                return (c.title + ' ' + c.category + ' ' + c.instructor).toLowerCase().indexOf(q) !== -1;
            }), q);
        }, 180));

        LH.on(document, 'click', function (e) {
            if (!box.contains(e.target)) { results.classList.remove('is-open'); }
        });

        LH.on(LH.$('form', box), 'submit', function (e) {
            if (!input.value.trim()) {
                e.preventDefault();
                LH.toast('Type something you would like to learn', { type: 'warning' });
            }
        });
    });
})(window.LearnHub);
