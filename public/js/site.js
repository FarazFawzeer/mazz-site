/* Mazz Laptop Hub — shared behaviour: menu, header state, gallery, filters. */
(function () {
    'use strict';

    // Mobile menu
    var toggle = document.querySelector('[data-nav-toggle]');
    if (toggle) {
        var setOpen = function (open) {
            document.body.classList.toggle('nav-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        };
        toggle.addEventListener('click', function () {
            setOpen(!document.body.classList.contains('nav-open'));
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') setOpen(false);
        });
        window.matchMedia('(min-width: 1061px)').addEventListener('change', function () { setOpen(false); });
    }

    // Product photos come from the admin site; if one fails, show a clean placeholder
    var PHOTO_BOXES = '.product-card__media, .brand-card__img, .gallery__main, .gallery__thumbs button, .chosen__img, .picker__img, .summary__thumb';
    function markMissing(img) {
        var box = img.closest(PHOTO_BOXES);
        if (box) box.classList.add('img-missing');
    }
    document.addEventListener('error', function (e) {
        if (e.target.tagName === 'IMG') markMissing(e.target);
    }, true);
    document.querySelectorAll('img').forEach(function (img) {
        if (img.complete && img.naturalWidth === 0 && img.getAttribute('src')) markMissing(img);
    });

    // Header gets a hairline once the page scrolls
    var header = document.querySelector('[data-header]');
    if (header) {
        var updateHeader = function () { header.classList.toggle('is-scrolled', window.scrollY > 4); };
        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

    // Product gallery: thumbnails, arrows, arrow keys, swipe, and a fullscreen viewer
    document.querySelectorAll('[data-gallery]').forEach(function (gallery) {
        var main = gallery.querySelector('[data-gallery-main]');
        if (!main) return;
        var thumbs = Array.prototype.slice.call(gallery.querySelectorAll('[data-gallery-thumb]'));
        var srcs = thumbs.length ? thumbs.map(function (b) { return b.getAttribute('data-gallery-thumb'); }) : [main.getAttribute('src')];
        var box = gallery.querySelector('[data-lightbox]');
        var boxImg = box && box.querySelector('[data-lightbox-img]');
        var counters = gallery.querySelectorAll('[data-gallery-index]');
        var index = 0;

        function swap(img, src) {
            if (img.getAttribute('src') === src) return;
            img.classList.add('is-fading');
            var next = new Image();
            // show the "No image" placeholder only for photos that fail to load
            var done = function (ok) {
                img.src = src;
                img.classList.remove('is-fading');
                if (img === main) main.parentNode.classList.toggle('img-missing', !ok);
            };
            next.onload = function () { done(true); };
            next.onerror = function () { done(false); };
            next.src = src;
        }
        function show(i) {
            index = (i + srcs.length) % srcs.length;
            swap(main, srcs[index]);
            if (boxImg) swap(boxImg, srcs[index]);
            thumbs.forEach(function (b, n) { b.classList.toggle('is-active', n === index); });
            if (thumbs[index]) thumbs[index].scrollIntoView({ block: 'nearest', inline: 'nearest' });
            counters.forEach(function (el) { el.textContent = index + 1; });
        }

        thumbs.forEach(function (btn, n) { btn.addEventListener('click', function () { show(n); }); });
        gallery.querySelectorAll('[data-gallery-step]').forEach(function (btn) {
            btn.addEventListener('click', function (e) { e.stopPropagation(); show(index + parseInt(btn.getAttribute('data-gallery-step'), 10)); });
        });

        // swipe left/right on touch screens
        [main.parentNode, box].forEach(function (area) {
            if (!area) return;
            var x0 = null, y0 = null;
            area.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; y0 = e.touches[0].clientY; }, { passive: true });
            area.addEventListener('touchend', function (e) {
                if (x0 === null || srcs.length < 2) return;
                var dx = e.changedTouches[0].clientX - x0, dy = e.changedTouches[0].clientY - y0;
                if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) show(index + (dx < 0 ? 1 : -1));
                x0 = null;
            }, { passive: true });
        });

        if (box) {
            var open = function () { boxImg.src = srcs[index]; if (box.showModal) box.showModal(); else box.setAttribute('open', ''); };
            main.addEventListener('click', open);
            var zoom = gallery.querySelector('[data-gallery-zoom]');
            if (zoom) zoom.addEventListener('click', open);
            box.querySelector('[data-lightbox-close]').addEventListener('click', function () { box.close(); });
            box.addEventListener('click', function (e) { if (e.target === box) box.close(); });
        }

        document.addEventListener('keydown', function (e) {
            if (srcs.length < 2 || /INPUT|TEXTAREA|SELECT/.test(document.activeElement.tagName)) return;
            if (e.key === 'ArrowLeft') show(index - 1);
            if (e.key === 'ArrowRight') show(index + 1);
        });
    });

    // Share a product page (native share sheet on phones, copy link elsewhere)
    document.querySelectorAll('[data-share-page]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var data = { title: document.title, url: location.href };
            if (navigator.share) { navigator.share(data).catch(function () {}); return; }
            var done = function () {
                var label = btn.lastChild;
                var old = label.textContent;
                btn.classList.add('is-done');
                label.textContent = ' Link copied';
                setTimeout(function () { btn.classList.remove('is-done'); label.textContent = old; }, 1800);
            };
            if (navigator.clipboard) navigator.clipboard.writeText(location.href).then(done, function () { prompt('Copy this link:', location.href); });
            else prompt('Copy this link:', location.href);
        });
    });

    // Shop listings (laptops, parts): sidebar filters, a drawer on phones and tablets
    document.querySelectorAll('[data-shop]').forEach(function (form) {
        var wide = window.matchMedia('(min-width: 1061px)');
        var opener = form.querySelector('[data-shop-open]');
        var submit = function () {
            // leave the price out of the URL when the slider is at its full range
            var lo = form.querySelector('[data-price-min]');
            var hi = form.querySelector('[data-price-max]');
            if (lo && hi && lo.value === lo.min && hi.value === hi.max) { lo.disabled = true; hi.disabled = true; }
            // and empty search / default sort
            form.querySelectorAll('input[name="q"], select[name="sort"]').forEach(function (el) { if (!el.value) el.disabled = true; });
            form.submit();
        };
        form.addEventListener('submit', function (e) { e.preventDefault(); submit(); });

        // sort applies at once; checkboxes and price apply at once on desktop, via "Show results" in the drawer
        form.querySelectorAll('[data-autosubmit]').forEach(function (el) { el.addEventListener('change', submit); });
        form.querySelectorAll('[data-shop-auto]').forEach(function (el) {
            el.addEventListener('change', function () { if (wide.matches) submit(); });
        });
        var search = form.querySelector('input[type="search"]');
        if (search && search.value) {
            search.addEventListener('input', function () { if (search.value === '') submit(); });
        }

        var setOpen = function (open) {
            document.body.classList.toggle('filters-open', open);
            if (opener) opener.setAttribute('aria-expanded', open ? 'true' : 'false');
        };
        if (opener) opener.addEventListener('click', function () { setOpen(true); });
        form.querySelectorAll('[data-shop-close]').forEach(function (el) { el.addEventListener('click', function () { setOpen(false); }); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
        wide.addEventListener('change', function () { setOpen(false); });

        form.querySelectorAll('[data-more]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var open = btn.closest('[data-more-wrap]').classList.toggle('is-open');
                btn.firstChild.textContent = open ? 'Show less ' : 'Show more ';
            });
        });

        // two-handle price slider
        var range = form.querySelector('[data-price]');
        if (range) {
            var lo = range.querySelector('[data-price-min]');
            var hi = range.querySelector('[data-price-max]');
            var money = function (n) { return 'Rs. ' + Number(n).toLocaleString('en-US'); };
            var paint = function (moved) {
                if (+lo.value > +hi.value) { if (moved === lo) lo.value = hi.value; else hi.value = lo.value; }
                var span = hi.max - lo.min;
                range.style.setProperty('--from', ((lo.value - lo.min) / span * 100) + '%');
                range.style.setProperty('--to', ((hi.value - lo.min) / span * 100) + '%');
                range.querySelector('[data-price-from]').textContent = money(lo.value);
                range.querySelector('[data-price-to]').textContent = money(hi.value);
            };
            [lo, hi].forEach(function (input) {
                input.addEventListener('input', function () { paint(input); });
                input.addEventListener('change', function () { if (wide.matches) submit(); });
            });
        }
    });

    // Listing filters: selects submit at once; search submits on Enter (native) or when cleared
    document.querySelectorAll('[data-filters]').forEach(function (form) {
        form.querySelectorAll('[data-autosubmit]').forEach(function (el) {
            el.addEventListener('change', function () { form.submit(); });
        });
        var search = form.querySelector('input[type="search"]');
        if (search && search.value) {
            search.addEventListener('input', function () {
                if (search.value === '') form.submit();
            });
        }
    });
})();
