/*!
* Start Bootstrap - Coming Soon v6.0.7 (https://startbootstrap.com/theme/coming-soon)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-coming-soon/blob/master/LICENSE)
*/

// Sliders (Owl Carousel 2): testimonials, and the riders on the home page.
// This file is shared by every page, but only index.html loads jQuery and Owl,
// so both the plugin and the target element are checked before initialising.
window.addEventListener('DOMContentLoaded', function () {
    var $ = window.jQuery;
    if (!$ || !$.fn || !$.fn.owlCarousel) return;

    // Respect the OS "reduce motion" setting: keep the slider usable by drag,
    // arrows and dots, but don't move it on its own.
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function initCarousel(selector, label, responsive) {
        var $carousel = $(selector);
        if (!$carousel.length) return;

        // Looping duplicates slides in the DOM, which a screen reader would
        // otherwise read out twice. Owl rebuilds the clones on refresh, so this
        // is bound before init and re-applied afterwards.
        $carousel.on('initialized.owl.carousel refreshed.owl.carousel', function () {
            $carousel.find('.owl-item.cloned').attr('aria-hidden', 'true');
        });

        $carousel.owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            autoplay: !reduceMotion,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            smartSpeed: 700,
            navText: [
                '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Previous ' + label + '</span>',
                '<i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Next ' + label + '</span>'
            ],
            responsive: responsive
        });
    }

    // Slides per view, and no arrows below 992px - on phones and tablets the
    // dots plus swipe are enough, and side arrows would crowd the cards.
    initCarousel('.testimonials-carousel', 'testimonials', {
        0: { items: 1, margin: 16, nav: false },
        768: { items: 2, margin: 24, nav: false },
        992: { items: 3, margin: 30, nav: true }
    });

    // Rider cards are narrower than testimonials, so they fit four across on
    // large desktops.
    initCarousel('.riders-carousel', 'riders', {
        0: { items: 1, margin: 16, nav: false },
        576: { items: 2, margin: 20, nav: false },
        992: { items: 3, margin: 24, nav: true },
        1200: { items: 4, margin: 30, nav: true }
    });
});
