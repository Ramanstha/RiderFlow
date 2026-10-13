/**
 * Stop number inputs from changing by accident: the arrow keys and the mouse
 * wheel would otherwise step the value (a scroll over a focused field quietly
 * turns 120 into 117). Listeners sit on the document, so inputs added to the
 * page later are covered too.
 */
(function () {
    function isNumberInput(el) {
        return el instanceof HTMLInputElement && el.type === 'number';
    }

    document.addEventListener('keydown', function (e) {
        if (isNumberInput(e.target) && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) {
            e.preventDefault();
        }
    });

    // Blurring rather than preventDefault keeps the page scrolling as normal;
    // the browser only steps the value of a focused input.
    document.addEventListener('wheel', function (e) {
        if (isNumberInput(e.target) && document.activeElement === e.target) {
            e.target.blur();
        }
    }, { passive: true });
})();
