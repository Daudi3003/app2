/* =========================================================================
   LearnHub — dashboard-only behaviour
   Message threads · notification read state · bulk table selection
   confirm-before-delete · lesson notes
   ========================================================================= */
(function (LH) {
    'use strict';

    /* --------------------------------------------------------------------
       Message threads
       -------------------------------------------------------------------- */
    LH.register('messages', function () {
        var list = LH.$('[data-thread-list]');
        if (!list) { return; }

        LH.$$('[data-thread]', list).forEach(function (item) {
            LH.on(item, 'click', function () {
                LH.$$('[data-thread]', list).forEach(function (i) {
                    i.classList.toggle('is-active', i === item);
                });
                var name = LH.$('[data-thread-name]');
                var avatar = LH.$('[data-thread-avatar]');
                if (name) { name.textContent = item.getAttribute('data-thread-name') || ''; }
                if (avatar) { avatar.textContent = item.getAttribute('data-thread-initials') || ''; }
                item.classList.remove('is-unread');
            });
        });

        var composer = LH.$('[data-message-form]');
        LH.on(composer, 'submit', function (e) {
            e.preventDefault();
            var input = LH.$('input, textarea', composer);
            var body  = LH.$('[data-message-body]');
            if (!input || !input.value.trim() || !body) { return; }

            var bubble = document.createElement('div');
            bubble.className = 'bubble bubble--out';
            bubble.innerHTML = LH.escape(input.value.trim()) +
                '<span class="bubble__time">Just now</span>';
            body.appendChild(bubble);
            body.scrollTop = body.scrollHeight;
            input.value = '';
            LH.toast('Message sent', { type: 'success', duration: 2200 });
        });

        var body = LH.$('[data-message-body]');
        if (body) { body.scrollTop = body.scrollHeight; }
    });

    /* --------------------------------------------------------------------
       Notifications — mark read / mark all read
       -------------------------------------------------------------------- */
    LH.register('notifications-panel', function () {
        LH.delegate('click', '[data-mark-read]', function (e) {
            e.preventDefault();
            var row = this.closest('.list__item');
            if (row) { row.classList.remove('is-unread'); }
            LH.toast('Marked as read', { type: 'info', duration: 1800 });
        });

        LH.delegate('click', '[data-mark-all-read]', function (e) {
            e.preventDefault();
            var n = LH.$$('.list__item.is-unread').length;
            LH.$$('.list__item.is-unread').forEach(function (r) { r.classList.remove('is-unread'); });
            LH.$$('.bell__dot').forEach(function (d) { d.style.display = 'none'; });
            LH.toast(n + ' notification(s) marked as read', { type: 'success' });
        });
    });

    /* --------------------------------------------------------------------
       Table bulk selection
       -------------------------------------------------------------------- */
    LH.register('bulk-select', function () {
        LH.$$('[data-select-all]').forEach(function (master) {
            var scope = LH.$(master.getAttribute('data-select-all'));
            if (!scope) { return; }

            function boxes() { return LH.$$('[data-select-row]', scope); }

            function syncBar() {
                var n = boxes().filter(function (b) { return b.checked; }).length;
                var bar = LH.$('[data-bulk-bar]');
                if (bar) {
                    bar.hidden = n === 0;
                    var count = LH.$('[data-bulk-count]', bar);
                    if (count) { count.textContent = n; }
                }
            }

            LH.on(master, 'change', function () {
                boxes().forEach(function (b) {
                    if (!b.closest('[data-row]').hidden) { b.checked = master.checked; }
                });
                syncBar();
            });

            boxes().forEach(function (b) {
                LH.on(b, 'change', function () {
                    var all = boxes();
                    master.checked = all.every(function (x) { return x.checked; });
                    master.indeterminate = !master.checked &&
                        all.some(function (x) { return x.checked; });
                    syncBar();
                });
            });
        });
    });

    /* --------------------------------------------------------------------
       Delete confirmation modal (frontend phase: removes the row visually)
       -------------------------------------------------------------------- */
    LH.register('confirm-delete', function () {
        var pendingRow = null;

        LH.delegate('click', '[data-confirm-delete]', function (e) {
            e.preventDefault();
            pendingRow = this.closest('[data-row]') || this.closest('[data-course-card]');
            var modal = document.getElementById('confirmDeleteModal');
            if (!modal) { return; }
            var label = LH.$('[data-confirm-label]', modal);
            if (label) { label.textContent = this.getAttribute('data-confirm-delete') || 'this item'; }
            LH.modalOpen('confirmDeleteModal');
        });

        LH.delegate('click', '[data-confirm-accept]', function (e) {
            e.preventDefault();
            var btn = this;
            btn.classList.add('is-loading');
            setTimeout(function () {
                btn.classList.remove('is-loading');
                LH.modalClose('confirmDeleteModal');
                if (pendingRow) {
                    pendingRow.style.transition = 'opacity .3s, transform .3s';
                    pendingRow.style.opacity = '0';
                    pendingRow.style.transform = 'translateX(-16px)';
                    var row = pendingRow;
                    setTimeout(function () { row.hidden = true; }, 300);
                    pendingRow = null;
                }
                LH.toast('Deleted successfully', { type: 'success' });
            }, 700);
        });
    });

    /* --------------------------------------------------------------------
       Lesson notes (kept per lesson in localStorage during the mock phase)
       -------------------------------------------------------------------- */
    LH.register('lesson-notes', function () {
        var form = LH.$('[data-notes-form]');
        if (!form) { return; }
        var area = LH.$('textarea', form);
        var list = LH.$('[data-notes-list]');
        var key  = 'learnhub.notes.' + (form.getAttribute('data-notes-form') || 'default');

        function load() {
            try { return JSON.parse(localStorage.getItem(key)) || []; } catch (e) { return []; }
        }
        function save(notes) {
            try { localStorage.setItem(key, JSON.stringify(notes)); } catch (e) { /* ignore */ }
        }
        function render() {
            var notes = load();
            if (!list) { return; }
            if (!notes.length) {
                list.innerHTML = '<p class="t-sm t-muted mb-0">' +
                    'No notes yet. Jot down anything you want to remember.</p>';
                return;
            }
            list.innerHTML = notes.map(function (n, i) {
                return '<div class="note-item">' +
                       '<div class="note-item__time">' + LH.escape(n.at) +
                       ' <button type="button" class="btn-icon btn-icon--sm btn-icon--plain is-danger" ' +
                       'data-note-remove="' + i + '" aria-label="Delete note">&times;</button></div>' +
                       LH.escape(n.text) + '</div>';
            }).join('');
        }

        LH.on(form, 'submit', function (e) {
            e.preventDefault();
            if (!area || !area.value.trim()) { return; }
            var notes = load();
            notes.unshift({
                text: area.value.trim(),
                at: new Date().toLocaleString(undefined, {
                    month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                })
            });
            save(notes);
            area.value = '';
            render();
            LH.toast('Note saved', { type: 'success', duration: 2200 });
        });

        LH.delegate('click', '[data-note-remove]', function (e) {
            e.preventDefault();
            var notes = load();
            notes.splice(parseInt(this.getAttribute('data-note-remove'), 10), 1);
            save(notes);
            render();
        });

        render();
    });
})(window.LearnHub);
