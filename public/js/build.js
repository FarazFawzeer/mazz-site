/* ==========================================================================
   Build your setup.
   One pick per slot (laptop, then one per part category), with a quantity.
   State is kept in localStorage and can be shared as #b=l2,p1x2 in the URL.
   Prices come from the page data; the total is an estimate sent on WhatsApp.
   ========================================================================== */
(function () {
    'use strict';

    var builder = document.querySelector('[data-builder]');
    var dataEl = document.getElementById('build-data');
    if (!builder || !dataEl) return;

    var STORE = 'mazzBuild';
    var slots = JSON.parse(dataEl.textContent);
    var items = {};      // key -> item
    var slotOf = {};     // key -> slot
    slots.forEach(function (slot) {
        slot.items.forEach(function (item) { items[item.key] = item; slotOf[item.key] = slot; });
    });

    var state = {};      // slot id -> { key, qty }

    var picker = document.querySelector('[data-picker]');
    var pickerTitle = picker.querySelector('[data-picker-title]');
    var pickerList = picker.querySelector('[data-picker-list]');
    var pickerSearch = picker.querySelector('[data-picker-search]');
    var pickerSort = picker.querySelector('[data-picker-sort]');
    var pickerTools = picker.querySelector('.picker__tools');
    var rowsEl = builder.querySelector('[data-summary-rows]');
    var emptyEl = builder.querySelector('[data-summary-empty]');
    var totalEls = document.querySelectorAll('[data-total]');
    var countEls = document.querySelectorAll('[data-count]');
    var progressCount = builder.querySelector('[data-progress-count]');
    var progressBar = builder.querySelector('[data-progress-bar]');
    var sendBtn = builder.querySelector('[data-send]');
    var links = builder.querySelector('[data-summary-links]');
    var clearBtn = builder.querySelector('[data-clear]');
    var shareBtn = builder.querySelector('[data-share]');
    var askNote = builder.querySelector('[data-ask-note]');
    var bar = document.querySelector('[data-buildbar]');
    var toast = document.querySelector('[data-toast]');
    var openSlot = null;
    var shownTotal = 0;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---- helpers ---- */

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }
    function money(n) { return 'Rs. ' + Math.round(n).toLocaleString('en-US'); }
    function maxQty(item) { return item.key.charAt(0) === 'l' ? 1 : Math.max(1, item.stock); }
    function svg(path, size, width) {
        return '<svg class="icon" viewBox="0 0 24 24" width="' + size + '" height="' + size + '" fill="none" stroke="currentColor" stroke-width="' + (width || 1.8) +
            '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + path + '</svg>';
    }
    var icon = {
        plus: svg('<path d="M12 5v14M5 12h14"/>', 16),
        minus: svg('<path d="M5 12h14"/>', 14),
        check: svg('<path d="m5 12.5 4.2 4.2L19 7"/>', 14, 2.6),
        trash: svg('<path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3"/>', 15),
        link: svg('<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>', 18)
    };
    function photo(item, cls) {
        // laptops are real shop photos: they fill the box (see .is-real in site.css)
        if (item.key.charAt(0) === 'l') cls += ' is-real';
        return item.photo
            ? '<span class="' + cls + '"><img src="' + esc(item.photo) + '" alt="" loading="lazy"></span>'
            : '<span class="' + cls + ' img-missing"></span>';
    }

    var toastTimer;
    function say(text) {
        if (!toast) return;
        toast.innerHTML = icon.check + '<span>' + esc(text) + '</span>';
        toast.classList.add('is-on');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toast.classList.remove('is-on'); }, 2200);
    }

    // total counts up/down to the new value
    var totalRun = 0;
    function setTotal(v) { totalEls.forEach(function (el) { el.textContent = money(v); }); }
    function showTotal(total) {
        var from = shownTotal;
        var run = ++totalRun;   // a newer change stops an older animation
        shownTotal = total;
        if (reduceMotion || from === total) { setTotal(total); return; }
        var start = null;
        function step(t) {
            if (run !== totalRun) return;
            if (start === null) start = t;
            var p = Math.min(1, (t - start) / 450);
            setTotal(from + (total - from) * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
        // frames pause in background tabs; make sure the final number lands anyway
        setTimeout(function () { if (run === totalRun) setTotal(total); }, 500);
    }

    /* ---- state: load / save / share ---- */

    function pick(key, qty) {
        var item = items[key];
        if (!item || item.stock < 1) return false;
        state[slotOf[key].id] = { key: key, qty: Math.min(Math.max(1, qty || 1), maxQty(item)) };
        return true;
    }

    function encode() {
        return Object.keys(state).map(function (id) {
            var s = state[id];
            return s.key + (s.qty > 1 ? 'x' + s.qty : '');
        }).join(',');
    }

    function decode(str) {
        (str || '').split(',').forEach(function (part) {
            var m = /^([lp]\d+)(?:x(\d+))?$/.exec(part.trim());
            if (m) pick(m[1], parseInt(m[2] || '1', 10));
        });
    }

    function save() {
        try { localStorage.setItem(STORE, encode()); } catch (e) { /* storage unavailable */ }
    }

    function load() {
        var hash = location.hash.replace(/^#/, '');
        var shared = /^b=(.*)$/.exec(hash);
        var add = /^add=([lp]\d+)$/.exec(hash);
        var added = null;

        if (shared) {
            state = {};
            decode(decodeURIComponent(shared[1]));
        } else {
            try { decode(localStorage.getItem(STORE)); } catch (e) { /* storage unavailable */ }
            if (add && pick(add[1], 1)) added = items[add[1]];
        }
        if (shared || add) history.replaceState(null, '', location.pathname + location.search);
        save();
        return { shared: !!shared, added: added };
    }

    function shareUrl() { return location.origin + location.pathname + '#b=' + encode(); }

    /* ---- render ---- */

    function renderSlot(slot) {
        var li = builder.querySelector('[data-slot="' + slot.id + '"]');
        var box = li.querySelector('[data-slot-choice]');
        var s = state[slot.id];
        li.classList.toggle('is-filled', !!s);

        if (!s) {
            box.innerHTML = '<button class="slot__add" type="button" data-open="' + esc(slot.id) + '"' + (slot.items.length ? '' : ' disabled') + '>' +
                icon.plus + ' ' + (slot.items.length ? 'Choose' : 'Unavailable') + '</button>';
            return;
        }

        var item = items[s.key];
        var max = maxQty(item);
        var price = item.price > 0 ? money(item.price * s.qty) : 'Ask for price';
        box.innerHTML =
            '<div class="chosen">' +
                photo(item, 'chosen__img') +
                '<div><a class="chosen__name" href="' + esc(item.url) + '">' + esc(item.name) + '</a>' +
                    (item.meta ? '<div class="chosen__meta">' + esc(item.meta) + '</div>' : '') + '</div>' +
                '<div class="chosen__side">' +
                    '<span class="chosen__price' + (item.price > 0 ? '' : ' is-ask') + '">' + price + '</span>' +
                    '<div class="chosen__tools">' +
                        (max > 1 ? '<span class="qty"><button type="button" data-qty="-1" aria-label="Fewer"' + (s.qty <= 1 ? ' disabled' : '') + '>' + icon.minus + '</button>' +
                            '<output aria-live="polite">' + s.qty + '</output>' +
                            '<button type="button" data-qty="1" aria-label="More"' + (s.qty >= max ? ' disabled' : '') + '>' + icon.plus + '</button></span>' : '') +
                        '<button class="chosen__btn" type="button" data-open="' + esc(slot.id) + '">Change</button>' +
                        '<button class="chosen__btn chosen__btn--icon" type="button" data-remove="' + esc(slot.id) + '" aria-label="Remove ' + esc(item.name) + '">' + icon.trash + '</button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function renderSummary() {
        var total = 0, count = 0, picked = 0, hasAsk = false, rows = [], lines = [];

        slots.forEach(function (slot) {
            var s = state[slot.id];
            if (!s) return;
            var item = items[s.key];
            var priced = item.price > 0;
            var line = priced ? item.price * s.qty : 0;
            total += line;
            count += s.qty;
            picked++;
            if (!priced) hasAsk = true;
            var label = (s.qty > 1 ? s.qty + ' × ' : '') + item.name;
            rows.push('<li>' + photo(item, 'summary__thumb') +
                '<span><small>' + esc(slot.label) + '</small><span class="name">' + esc(label) + '</span></span>' +
                '<b' + (priced ? '' : ' class="is-ask"') + '>' + (priced ? money(line) : 'Ask') + '</b></li>');
            lines.push('• ' + slot.label + ': ' + label + ' — ' + (priced ? money(line) : 'price please'));
        });

        rowsEl.innerHTML = rows.join('');
        emptyEl.hidden = rows.length > 0;
        showTotal(total);
        countEls.forEach(function (el) { el.textContent = count + (count === 1 ? ' item' : ' items'); });
        progressCount.textContent = picked;
        progressBar.style.width = (picked / slots.length * 100) + '%';
        askNote.hidden = !hasAsk;
        links.hidden = !rows.length;
        if (bar) bar.hidden = !rows.length;

        if (rows.length) {
            var text = 'Hi Mazz Laptop Hub, I\'d like a quote for this setup:\n\n' + lines.join('\n') +
                '\n\nEstimated total: ' + money(total) + (hasAsk ? ' + items to price' : '') +
                '\n\n' + shareUrl();
            sendBtn.href = 'https://wa.me/' + builder.dataset.wa + '?text=' + encodeURIComponent(text);
            sendBtn.removeAttribute('aria-disabled');
        } else {
            sendBtn.href = 'https://wa.me/' + builder.dataset.wa;
            sendBtn.setAttribute('aria-disabled', 'true');
        }
    }

    function render() {
        slots.forEach(renderSlot);
        renderSummary();
    }

    function update() { save(); render(); }

    /* ---- picker ---- */

    function renderPicker() {
        var slot = openSlot;
        var q = pickerSearch.value.trim().toLowerCase();
        var sort = pickerSort.value;
        var selected = state[slot.id] && state[slot.id].key;
        var list = slot.items.filter(function (item) {
            return !q || (item.name + ' ' + item.meta).toLowerCase().indexOf(q) !== -1;
        });
        if (sort) {
            // unpriced items ("Ask for price") stay at the end either way
            list = list.slice().sort(function (a, b) {
                if (!(a.price > 0) || !(b.price > 0)) return (b.price > 0) - (a.price > 0);
                return sort === 'asc' ? a.price - b.price : b.price - a.price;
            });
        }

        if (!list.length) {
            pickerList.innerHTML = '<li class="picker__none">Nothing matches. Ask us on WhatsApp, we may have it.</li>';
            return;
        }

        pickerList.innerHTML = list.map(function (item) {
            var out = item.stock < 1;
            var isSel = item.key === selected;
            var stock = item.key.charAt(0) === 'l' ? '<span class="picker__stock">In stock</span>' :
                '<span class="picker__stock' + (out ? ' is-out' : '') + '">' + (out ? 'Out of stock' : item.stock + ' in stock') + '</span>';
            return '<li><button type="button" class="picker__item' + (isSel ? ' is-selected' : '') + '" data-pick="' + esc(item.key) + '"' + (out ? ' disabled' : '') + '>' +
                photo(item, 'picker__img') +
                '<span><span class="picker__name">' + esc(item.name) + '</span>' +
                    (item.meta ? '<span class="picker__meta">' + esc(item.meta) + '</span>' : '') + stock + '</span>' +
                '<span class="picker__side"><span class="picker__price' + (item.price > 0 ? '' : ' is-ask') + '">' + (item.price > 0 ? money(item.price) : 'Ask for price') + '</span>' +
                    (out ? '' : '<span class="picker__pick">' + (isSel ? icon.check + ' Selected' : 'Select') + '</span>') + '</span>' +
            '</button></li>';
        }).join('');
    }

    function openPicker(slotId) {
        openSlot = slots.filter(function (s) { return s.id === slotId; })[0];
        if (!openSlot) return;
        pickerTitle.textContent = openSlot.label;
        pickerSearch.value = '';
        pickerSort.value = '';
        pickerTools.hidden = openSlot.items.length < 4;
        renderPicker();
        if (picker.showModal) picker.showModal(); else picker.setAttribute('open', '');
        pickerList.scrollTop = 0;
        // next frame so the slide-in transition runs
        requestAnimationFrame(function () { requestAnimationFrame(function () { picker.classList.add('is-open'); }); });
    }

    function closePicker() {
        if (!picker.open) return;
        picker.classList.remove('is-open');
        setTimeout(function () {
            if (picker.close) picker.close(); else picker.removeAttribute('open');
        }, reduceMotion ? 0 : 340);
    }

    /* ---- events ---- */

    builder.addEventListener('click', function (e) {
        var openBtn = e.target.closest('[data-open]');
        var removeBtn = e.target.closest('[data-remove]');
        var qtyBtn = e.target.closest('[data-qty]');

        if (openBtn) {
            openPicker(openBtn.getAttribute('data-open'));
        } else if (removeBtn) {
            var id = removeBtn.getAttribute('data-remove');
            var gone = state[id] && items[state[id].key];
            delete state[id];
            update();
            if (gone) say('Removed ' + gone.name);
        } else if (qtyBtn) {
            var slotId = qtyBtn.closest('[data-slot]').getAttribute('data-slot');
            var s = state[slotId];
            if (!s) return;
            s.qty = Math.min(Math.max(1, s.qty + parseInt(qtyBtn.getAttribute('data-qty'), 10)), maxQty(items[s.key]));
            update();
        }
    });

    pickerList.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-pick]');
        if (!btn || btn.disabled) return;
        var key = btn.getAttribute('data-pick');
        var prev = state[openSlot.id];
        pick(key, prev && prev.key === key ? prev.qty : 1);
        closePicker();
        update();
        say('Added ' + items[key].name);
        var row = builder.querySelector('[data-slot="' + openSlot.id + '"]');
        var focus = row && row.querySelector('[data-open]');
        if (focus) focus.focus({ preventScroll: true });
    });

    pickerSearch.addEventListener('input', renderPicker);
    pickerSort.addEventListener('change', renderPicker);
    picker.querySelector('[data-picker-close]').addEventListener('click', closePicker);
    picker.addEventListener('click', function (e) { if (e.target === picker) closePicker(); });
    // Esc: animate out instead of the dialog's instant close
    picker.addEventListener('cancel', function (e) { e.preventDefault(); closePicker(); });

    clearBtn.addEventListener('click', function () {
        if (!Object.keys(state).length) return;
        if (!window.confirm('Clear your whole setup?')) return;
        state = {};
        update();
        say('Setup cleared');
    });

    shareBtn.addEventListener('click', function () {
        var url = shareUrl();
        var done = function () { say('Link copied. Share it with anyone'); };
        if (navigator.clipboard) navigator.clipboard.writeText(url).then(done, function () { prompt('Copy this link:', url); });
        else prompt('Copy this link:', url);
    });

    // "Review" on the pinned bar scrolls smoothly to the summary
    var review = document.querySelector('[data-bar-review]');
    if (review) {
        review.addEventListener('click', function (e) {
            e.preventDefault();
            builder.querySelector('.summary').scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
        });
    }

    function boot() {
        var loaded = load();
        render();
        if (loaded.added) say('Added ' + loaded.added.name);
        else if (loaded.shared && Object.keys(state).length) say('Shared setup loaded');
    }
    boot();
    // a shared link or "add" link opened while already on this page
    window.addEventListener('hashchange', function () {
        if (/^#(b|add)=/.test(location.hash)) boot();
    });
})();
