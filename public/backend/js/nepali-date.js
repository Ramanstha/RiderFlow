/*
 * Bikram Sambat (Nepali) date picker.
 *
 * Markup comes from the <x-nepali-date> / <x-nepali-month> Blade components:
 * the person reads and types BS dates, and a hidden input carries the AD date
 * (YYYY-MM-DD) the server stores — so no controller ever sees a BS value.
 *
 * Month lengths come from window.NEPALI_CALENDAR (App\Support\NepaliDate),
 * injected by the layout, so PHP and JS convert with the same table.
 */
(function () {
    'use strict';

    var TABLE = window.NEPALI_CALENDAR || {};
    var YEARS = Object.keys(TABLE).map(Number).sort(function (a, b) { return a - b; });
    var MIN_YEAR = YEARS[0];
    var MAX_YEAR = YEARS[YEARS.length - 1];
    var EPOCH = Date.UTC(1943, 3, 14); // 1 Baisakh 2000 BS
    var DAY = 86400000;
    var MONTHS = ['Baisakh', 'Jestha', 'Asar', 'Shrawan', 'Bhadra', 'Ashwin',
        'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
    var MONTHS_NE = ['बैशाख', 'जेठ', 'असार', 'साउन', 'भदौ', 'असोज',
        'कात्तिक', 'मंसिर', 'पुस', 'माघ', 'फागुन', 'चैत'];
    var WEEKDAYS_NE = ['आइत', 'सोम', 'मंगल', 'बुध', 'बिहि', 'शुक्र', 'शनि'];

    function pad(n) { return (n < 10 ? '0' : '') + n; }

    function daysIn(y, m) { return TABLE[y] ? TABLE[y][m - 1] : 0; }

    function isValid(y, m, d) { return !!TABLE[y] && m >= 1 && m <= 12 && d >= 1 && d <= daysIn(y, m); }

    /** 'YYYY-MM-DD' (AD) → {y, m, d} in BS, or null. */
    function adToBs(ad) {
        var parts = /^(\d{4})-(\d{2})-(\d{2})$/.exec(ad || '');
        if (!parts) return null;
        var left = Math.round((Date.UTC(+parts[1], +parts[2] - 1, +parts[3]) - EPOCH) / DAY);
        if (left < 0) return null;
        for (var i = 0; i < YEARS.length; i++) {
            var y = YEARS[i];
            for (var m = 1; m <= 12; m++) {
                var len = TABLE[y][m - 1];
                if (left < len) return { y: y, m: m, d: left + 1 };
                left -= len;
            }
        }
        return null;
    }

    /** BS {y, m, d} → 'YYYY-MM-DD' (AD), or null. */
    function bsToAd(y, m, d) {
        if (!isValid(y, m, d)) return null;
        var days = d - 1;
        for (var yy = MIN_YEAR; yy < y; yy++) {
            for (var i = 0; i < 12; i++) days += TABLE[yy][i];
        }
        for (var mm = 1; mm < m; mm++) days += TABLE[y][mm - 1];
        var date = new Date(EPOCH + days * DAY);
        return date.getUTCFullYear() + '-' + pad(date.getUTCMonth() + 1) + '-' + pad(date.getUTCDate());
    }

    function formatBs(bs) { return bs ? bs.y + '-' + pad(bs.m) + '-' + pad(bs.d) : ''; }

    function parseBs(text) {
        var parts = /^\s*(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})\s*$/.exec(text || '');
        if (!parts) return null;
        var bs = { y: +parts[1], m: +parts[2], d: +parts[3] };
        return isValid(bs.y, bs.m, bs.d) ? bs : null;
    }

    function todayAd() {
        var now = new Date();
        return now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
    }

    function weekdayOf(ad) {
        var p = ad.split('-');
        return new Date(Date.UTC(+p[0], +p[1] - 1, +p[2])).getUTCDay();
    }

    /* ---------------------------------------------------------------- */
    /* Single shared popup                                                */
    /* ---------------------------------------------------------------- */

    var popup = null;
    var active = null; // { display, hidden, view: {y, m}, min, max }

    function el(tag, cls, text) {
        var node = document.createElement(tag);
        if (cls) node.className = cls;
        if (text !== undefined) node.textContent = text;
        return node;
    }

    function buildPopup() {
        popup = el('div', 'bs-picker');
        popup.setAttribute('role', 'dialog');
        popup.setAttribute('aria-label', 'Choose a Nepali date');
        popup.addEventListener('mousedown', function (e) { e.preventDefault(); }); // keep focus on the input
        document.body.appendChild(popup);

        document.addEventListener('mousedown', function (e) {
            if (!active) return;
            if (popup.contains(e.target) || e.target === active.display) return;
            close();
        });
        window.addEventListener('resize', position);
        window.addEventListener('scroll', position, true);
    }

    function close() {
        if (popup) popup.classList.remove('is-open');
        active = null;
    }

    function position() {
        if (!active || !popup) return;
        var rect = active.display.getBoundingClientRect();
        var width = popup.offsetWidth;
        var left = Math.min(rect.left, window.innerWidth - width - 8);
        var top = rect.bottom + 6;
        if (top + popup.offsetHeight > window.innerHeight && rect.top - popup.offsetHeight - 6 > 0) {
            top = rect.top - popup.offsetHeight - 6;
        }
        popup.style.left = Math.max(8, left) + 'px';
        popup.style.top = top + 'px';
    }

    function outOfRange(ad) {
        return (active.min && ad < active.min) || (active.max && ad > active.max);
    }

    function shift(delta) {
        var v = active.view;
        var m = v.m + delta;
        var y = v.y;
        if (m < 1) { m = 12; y--; }
        if (m > 12) { m = 1; y++; }
        if (y < MIN_YEAR || y > MAX_YEAR) return;
        active.view = { y: y, m: m };
        render();
    }

    function select(ad) {
        var bs = adToBs(ad);
        active.display.value = formatBs(bs);
        setValue(active, ad);
        close();
    }

    function render() {
        var v = active.view;
        var selected = active.hidden.value;
        var today = todayAd();
        popup.innerHTML = '';

        // Header: ‹ [month] [year] ›
        var head = el('div', 'bs-picker-head');
        var prev = el('button', 'bs-picker-nav', '‹');
        prev.type = 'button';
        prev.setAttribute('aria-label', 'Previous month');
        prev.onclick = function () { shift(-1); };

        var monthSel = el('select', 'bs-picker-select');
        monthSel.setAttribute('aria-label', 'Month');
        MONTHS.forEach(function (name, i) {
            var opt = el('option', null, name);
            opt.value = i + 1;
            if (i + 1 === v.m) opt.selected = true;
            monthSel.appendChild(opt);
        });
        monthSel.onchange = function () { active.view.m = +monthSel.value; render(); };

        var yearSel = el('select', 'bs-picker-select');
        yearSel.setAttribute('aria-label', 'Year');
        YEARS.forEach(function (y) {
            var opt = el('option', null, String(y));
            opt.value = y;
            if (y === v.y) opt.selected = true;
            yearSel.appendChild(opt);
        });
        yearSel.onchange = function () { active.view.y = +yearSel.value; render(); };

        var next = el('button', 'bs-picker-nav', '›');
        next.type = 'button';
        next.setAttribute('aria-label', 'Next month');
        next.onclick = function () { shift(1); };

        head.appendChild(prev);
        head.appendChild(monthSel);
        head.appendChild(yearSel);
        head.appendChild(next);
        popup.appendChild(head);

        var sub = el('div', 'bs-picker-sub', MONTHS_NE[v.m - 1]);
        popup.appendChild(sub);

        // Grid
        var grid = el('div', 'bs-picker-grid');
        WEEKDAYS_NE.forEach(function (w) { grid.appendChild(el('span', 'bs-picker-weekday', w)); });

        var first = bsToAd(v.y, v.m, 1);
        for (var blank = weekdayOf(first); blank > 0; blank--) grid.appendChild(el('span'));

        for (var d = 1, len = daysIn(v.y, v.m); d <= len; d++) {
            var ad = bsToAd(v.y, v.m, d);
            var btn = el('button', 'bs-picker-day', String(d));
            btn.type = 'button';
            btn.title = ad + ' (AD)';
            if (ad === selected) btn.classList.add('is-selected');
            if (ad === today) btn.classList.add('is-today');
            if (outOfRange(ad)) {
                btn.disabled = true;
            } else {
                btn.onclick = (function (value) { return function () { select(value); }; })(ad);
            }
            grid.appendChild(btn);
        }
        popup.appendChild(grid);

        // Footer
        var foot = el('div', 'bs-picker-foot');
        var todayBtn = el('button', 'bs-picker-link', 'Today');
        todayBtn.type = 'button';
        todayBtn.disabled = outOfRange(today);
        todayBtn.onclick = function () { select(today); };
        foot.appendChild(todayBtn);

        if (!active.display.required) {
            var clear = el('button', 'bs-picker-link', 'Clear');
            clear.type = 'button';
            clear.onclick = function () {
                active.display.value = '';
                setValue(active, '');
                close();
            };
            foot.appendChild(clear);
        }
        popup.appendChild(foot);
    }

    function open(state) {
        if (!popup) buildPopup();
        var bs = adToBs(state.hidden.value) || adToBs(todayAd());
        state.view = { y: bs.y, m: bs.m };
        active = state;
        render();
        popup.classList.add('is-open');
        position();
    }

    function setValue(state, ad) {
        if (state.hidden.value === ad) return;
        state.hidden.value = ad;
        state.hidden.dispatchEvent(new Event('change', { bubbles: true }));
    }

    /* ---------------------------------------------------------------- */
    /* Wiring                                                             */
    /* ---------------------------------------------------------------- */

    function initDate(root) {
        if (root.dataset.bsReady) return;
        root.dataset.bsReady = '1';

        var display = root.querySelector('[data-bs-display]');
        var hidden = root.querySelector('[data-bs-value]');
        var state = { display: display, hidden: hidden, min: root.dataset.min || '', max: root.dataset.max || '' };

        display.addEventListener('focus', function () { if (!display.disabled) open(state); });
        display.addEventListener('click', function () { if (!display.disabled && active !== state) open(state); });
        display.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.key === 'Tab') close();
        });
        display.addEventListener('input', function () {
            var bs = parseBs(display.value);
            var ad = bs ? bsToAd(bs.y, bs.m, bs.d) : '';
            display.setCustomValidity(display.value && !bs ? 'Enter a Nepali date as YYYY-MM-DD, e.g. 2083-06-20.' : '');
            setValue(state, ad || '');
            if (bs && active === state) { state.view = { y: bs.y, m: bs.m }; render(); }
        });
        display.addEventListener('blur', function () {
            var bs = adToBs(hidden.value);
            if (bs) display.value = formatBs(bs);
        });
    }

    function initMonth(root) {
        if (root.dataset.bsReady) return;
        root.dataset.bsReady = '1';

        var year = root.querySelector('[data-bs-year]');
        var month = root.querySelector('[data-bs-month]');
        var hidden = root.querySelector('[data-bs-value]');
        var sync = function () {
            hidden.value = (year.value && month.value) ? bsToAd(+year.value, +month.value, 1) : '';
        };
        year.addEventListener('change', sync);
        month.addEventListener('change', sync);
    }

    function init(scope) {
        (scope || document).querySelectorAll('[data-bs-date]').forEach(initDate);
        (scope || document).querySelectorAll('[data-bs-month-picker]').forEach(initMonth);
    }

    window.NepaliDate = { adToBs: adToBs, bsToAd: bsToAd, format: formatBs, parse: parseBs, init: init };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { init(); });
    } else {
        init();
    }
})();
