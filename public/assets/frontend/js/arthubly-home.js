/* ======================================================================
   ARTHUBLY — HOME JS
   File: public/assets/frontend/js/arthubly-home.js

   SIRF index.blade.php ke liye — home ka rail carousel aur scroll reveal.
   Koi jQuery dependency nahi.

   CONTENTS
   --------
   A. SCROLL REVEAL — har card apni bari se
   B. RAIL CAROUSEL — arrows, drag, keyboard, progress bar
   ====================================================================== */
/* =========================================================================
   ARTHUBLY — HOME PAGE SECTIONS ONLY
   File: public/assets/frontend/js/home-sections.js

   index.blade.php ke @push('scripts') se load hoti hai.
   Product card ka partial ya global JS bilkul nahi chhua — ye file sirf
   .hm-section ke andar kaam karti hai.

   1. Rail carousel — arrows, mouse drag, keyboard, progress bar
   2. Scroll reveal — har card apni bari se fade + rise
   ========================================================================= */
(function () {
    'use strict';

    var reduced = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* =====================================================================
       1. SCROLL REVEAL — har card ko alag observe karte hain, isliye
          stagger row-by-row chalta hai, poora block ek saath nahi kudta.
       ===================================================================== */
    function initReveal() {
        var items = document.querySelectorAll('.hm-section .hm-slide, .hm-section .hm-cell');
        if (!items.length) return;

        if (reduced || !('IntersectionObserver' in window)) {
            Array.prototype.forEach.call(items, function (el) { el.classList.add('in'); });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;

                // ek hi screen me aane wale cards ko chhota sa stagger
                var i = parseInt(e.target.style.getPropertyValue('--i'), 10) || 0;
                e.target.style.setProperty('--d', i % 4);
                e.target.classList.add('in');
                io.unobserve(e.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

        Array.prototype.forEach.call(items, function (el) { io.observe(el); });
    }

    /* =====================================================================
       2. RAIL CAROUSEL
       ===================================================================== */
    function initRail(rail) {
        var section = rail.closest('.hm-section');
        if (!section) return;

        var prev = section.querySelector('[data-rail-prev]');
        var next = section.querySelector('[data-rail-next]');
        var bar = section.querySelector('[data-rail-bar]');

        function step() {
            var slide = rail.querySelector('.hm-slide');
            if (!slide) return rail.clientWidth * 0.8;
            var gap = parseFloat(getComputedStyle(rail).columnGap || getComputedStyle(rail).gap) || 22;
            // ek screen me jitne poore cards aate hain, utna scroll
            var perView = Math.max(1, Math.floor(rail.clientWidth / (slide.offsetWidth + gap)));
            return (slide.offsetWidth + gap) * perView;
        }

        function paint() {
            var max = rail.scrollWidth - rail.clientWidth;

            if (prev) prev.disabled = rail.scrollLeft <= 2;
            if (next) next.disabled = rail.scrollLeft >= max - 2;

            if (bar && max > 0) {
                var visible = rail.clientWidth / rail.scrollWidth;
                var pos = rail.scrollLeft / max;
                bar.style.width = Math.max(12, visible * 100) + '%';
                bar.style.transform = 'translateX(' + (pos * (100 / Math.max(visible, 0.12) - 100)) + '%)';
            } else if (bar) {
                bar.style.width = '100%';
            }
        }

        if (prev) prev.addEventListener('click', function () {
            rail.scrollBy({ left: -step(), behavior: reduced ? 'auto' : 'smooth' });
        });
        if (next) next.addEventListener('click', function () {
            rail.scrollBy({ left: step(), behavior: reduced ? 'auto' : 'smooth' });
        });

        rail.addEventListener('scroll', function () {
            window.requestAnimationFrame(paint);
        }, { passive: true });

        window.addEventListener('resize', paint);

        /* ---- keyboard ---- */
        rail.setAttribute('tabindex', '0');
        rail.setAttribute('role', 'region');
        rail.setAttribute('aria-label', 'New arrivals carousel');
        rail.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { e.preventDefault(); rail.scrollBy({ left: step(), behavior: 'smooth' }); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); rail.scrollBy({ left: -step(), behavior: 'smooth' }); }
        });

        /* ---- mouse drag (desktop) ----
           Mobile par native touch scroll hi chalta hai. */
        var down = false, startX = 0, startScroll = 0, moved = 0;

        rail.addEventListener('mousedown', function (e) {
            if (e.button !== 0) return;
            down = true;
            moved = 0;
            startX = e.pageX;
            startScroll = rail.scrollLeft;
        });

        window.addEventListener('mousemove', function (e) {
            if (!down) return;
            var dx = e.pageX - startX;
            if (Math.abs(dx) > 4) {
                moved = Math.abs(dx);
                rail.classList.add('is-dragging');   // link/button click block
            }
            rail.scrollLeft = startScroll - dx;
        });

        window.addEventListener('mouseup', function () {
            if (!down) return;
            down = false;
            // classList thodi der baad hatao, warna mouseup wala click
            // link par chala jata hai
            setTimeout(function () { rail.classList.remove('is-dragging'); }, 0);
        });

        // drag ke baad accidental click na ho
        rail.addEventListener('click', function (e) {
            if (moved > 6) {
                e.preventDefault();
                e.stopPropagation();
                moved = 0;
            }
        }, true);

        paint();
    }

    function init() {
        initReveal();
        Array.prototype.forEach.call(document.querySelectorAll('[data-rail]'), initRail);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
