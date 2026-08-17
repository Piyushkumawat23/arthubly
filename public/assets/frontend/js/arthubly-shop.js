/* ======================================================================
   ARTHUBLY — SHOP JS
   File: public/assets/frontend/js/arthubly-shop.js

   Purani 3 files merge: 4-drawers.js  +  3-quickview.js  +  ai-search.js
   Teeno alag IIFE hain, isliye ek doosre ke variables se clash nahi hota.

   CONTENTS
   --------
   A. BAG + WISHLIST DRAWERS, TOAST, AJAX ADD-TO-CART   (window.abToast yahin banta hai)
   B. QUICK VIEW                                        (window.openQuickView)
   C. AI SEARCH

   Zaroori: is file se PEHLE ye line honi chahiye —
       <script>window.ARTHUBLY_BASE = "{{ url('/') }}";</script>
   Aur ye file arthubly.js ke BAAD load ho, taaki card ki stock
   validation (preventDefault) pehle chale.
   ====================================================================== */


/* ==========================================================================
   A. DRAWERS + TOAST + AJAX CART
   tha: 4-drawers.js
   ========================================================================== */


/* ======================================================================
   Arthubly — BAG + WISHLIST drawers  (COMPLETE, v3)
   File: public/assets/frontend/js/4-drawers.js

   Load order (zaroori):
     jquery → bootstrap → arthubly.js → 3-quickview.js → 4-drawers.js

   Is file se PEHLE ye line honi chahiye:
       <script>window.ARTHUBLY_BASE = "{{ url('/') }}";</script>

   Kuch kaam na kare to browser console kholein — ye file khud bata deti
   hai ki kya missing hai.
   ====================================================================== */
(function ($) {
    'use strict';

    var BASE = (window.ARTHUBLY_BASE || '').replace(/\/$/, '');
    var CSRF = $('meta[name="csrf-token"]').attr('content') || '';

    function u(p) { return BASE + '/' + String(p).replace(/^\//, ''); }

    /* =================================================================
       SELF CHECK — setup galat hai to console me saaf-saaf bata do
       ================================================================= */
    $(function () {
        var bad = [];
        if (!window.ARTHUBLY_BASE) bad.push('window.ARTHUBLY_BASE is not set (missing <script> line before 4-drawers.js)');
        if (!CSRF)                 bad.push('<meta name="csrf-token"> is missing in <head>');
        if (!$('#bagDrawer').length)  bad.push('#bagDrawer nahi mila — @include(\'frontend.partials.drawers\') layout me add nahi hua');
        if (!$('#wishDrawer').length) bad.push('#wishDrawer not found — same include is missing');
        if (!$('.ab-panel').length || $('.ab-panel').css('position') !== 'absolute') {
            bad.push('4-drawers.css did not load (ab-panel position is not absolute)');
        }
        if (bad.length) {
            console.warn('%c[Arthubly drawers] Setup is incomplete:', 'color:#b4432f;font-weight:bold');
            bad.forEach(function (m) { console.warn('  • ' + m); });
        }
    });

    /* =================================================================
       TOAST
       ================================================================= */
    function toast(msg, type) {
        var $wrap = $('#abToast');
        if (!$wrap.length) $wrap = $('<div id="abToast" class="ab-toast-wrap"></div>').appendTo('body');

        var icon = (type === 'error')
            ? '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>'
            : '<circle cx="12" cy="12" r="9"/><path d="m9 12 2 2 4-4"/>';

        var $t = $('<div class="ab-toast ' + (type === 'error' ? 'is-error' : 'is-ok') + '">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' + icon + '</svg>' +
            '<span></span></div>');

        $t.find('span').text(msg);
        $wrap.append($t);
        requestAnimationFrame(function () { $t.addClass('show'); });
        setTimeout(function () {
            $t.removeClass('show');
            setTimeout(function () { $t.remove(); }, 350);
        }, 2600);
    }
    window.abToast = toast;

    /* =================================================================
       COUNT BADGES
       ================================================================= */
    function setCartCount(n) {
        n = parseInt(n, 10) || 0;
        var $els = $('.cart-count, #cartDot');
        $els.text(n).each(function () { $(this).css('display', n > 0 ? '' : 'none'); });
        $els.removeClass('ab-bump');
        if ($els.length) void $els[0].offsetWidth;
        $els.addClass('ab-bump');
        $('#bag-count-label').text(n ? '(' + n + ')' : '');
    }

    function setWishCount(n) {
        n = parseInt(n, 10) || 0;
        $('.wish-count, #wishDot').text(n).each(function () {
            $(this).css('display', n > 0 ? '' : 'none');
        });
        $('#wish-count-label').text(n ? '(' + n + ')' : '');
    }

    /* =================================================================
       OPEN / CLOSE
       ================================================================= */
    function openDrawer(id) {
        if (!$('#' + id).length) {
            console.error('[Arthubly] #' + id + ' is not on the page.');
            return;
        }
        $('.ab-drawer.open').removeClass('open').attr('aria-hidden', 'true');
        if (typeof window.closeQuickView === 'function') window.closeQuickView();
        $('#' + id).addClass('open').attr('aria-hidden', 'false');
        $('body').addClass('ab-open');
    }

    function closeDrawers() {
        $('.ab-drawer').removeClass('open').attr('aria-hidden', 'true');
        $('body').removeClass('ab-open');
    }

    window.openBagDrawer  = function () { openDrawer('bagDrawer'); refreshBag(); };
    window.openWishDrawer = function () { openDrawer('wishDrawer'); refreshWish(); };
    window.closeDrawers   = closeDrawers;

    $(document).on('click', '[data-ab-close]', function (e) {
        e.preventDefault();
        closeDrawers();
    });
    $(document).on('keydown', function (e) {
        if ((e.key === 'Escape' || e.keyCode === 27) && $('.ab-drawer.open').length) closeDrawers();
    });

    /* =================================================================
       REFRESH
       ================================================================= */
    function skeleton(n) {
        var one = '<div class="ab-sk"><i class="s1"></i><div><i class="s2"></i><i class="s3"></i></div></div>';
        var out = '';
        for (var i = 0; i < (n || 3); i++) out += one;
        return '<div class="ab-loading">' + out + '</div>';
    }

    function refreshBag(cb) {
        return $.getJSON(u('bag-drawer'))
            .done(function (res) {
                $('#bagBody').html(res.items);
                $('#bagFoot').html(res.foot);
                setCartCount(res.count);
                if (cb) cb(res);
            })
            .fail(function (xhr) {
                console.error('[Arthubly] /bag-drawer fail:', xhr.status);
                $('#bagBody').html('<div class="ab-empty"><p>Bag could not be loaded.</p></div>');
            });
    }

    function refreshWish(cb) {
        return $.getJSON(u('wish-drawer'))
            .done(function (res) {
                $('#wishBody').html(res.items);
                setWishCount(res.count);
                if (cb) cb(res);
            })
            .fail(function (xhr) {
                console.error('[Arthubly] /wish-drawer fail:', xhr.status);
                $('#wishBody').html('<div class="ab-empty"><p>Wishlist could not be loaded.</p></div>');
            });
    }

    window.refreshBag  = refreshBag;
    window.refreshWish = refreshWish;

    /* =================================================================
       HEADER / MOBILE NAV ICONS
       ================================================================= */
    $(document).on('click', '[data-open-bag], .mobile-nav [data-nav="cart"]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#bagBody').html(skeleton());
        openDrawer('bagDrawer');
        refreshBag();
    });

    $(document).on('click', '[data-open-wish]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#wishBody').html(skeleton());
        openDrawer('wishDrawer');
        refreshWish();
    });

    /* =================================================================
       BUTTON STATES
       ================================================================= */
    function btnBusy($btn) {
        if (!$btn || !$btn.length) return;
        if (!$btn.data('ab-html')) $btn.data('ab-html', $btn.html());
        $btn.prop('disabled', true).addClass('is-loading')
            .html('<span class="ab-spin"></span> Adding…');
    }

    function btnReset($btn) {
        if (!$btn || !$btn.length) return;
        $btn.prop('disabled', false).removeClass('is-loading')
            .html($btn.data('ab-html') || 'Add to Cart');
    }

    function btnDone($btn) {
        if (!$btn || !$btn.length) return;
        $btn.prop('disabled', false).removeClass('is-loading').addClass('is-added')
            .html('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:16px;height:16px"><path d="m5 12 5 5L20 7"/></svg> Added');
        setTimeout(function () {
            $btn.removeClass('is-added').html($btn.data('ab-html') || 'Add to Cart');
        }, 1600);
    }

    /* =================================================================
       ADD TO CART — AJAX (page reload nahi)
       ================================================================= */
    function ajaxAddToCart($form, $btn) {
        btnBusy($btn);

        function done(res) {
            btnDone($btn);
            if (typeof window.closeQuickView === 'function') window.closeQuickView();
            if (res && typeof res.count !== 'undefined') setCartCount(res.count);

            $('#bagBody').html(skeleton(1));
            openDrawer('bagDrawer');
            refreshBag();
            toast((res && res.message) ? res.message : 'Added to bag');
        }

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            success: function (res) {
                if (res && res.success === false) {
                    btnReset($btn);
                    toast(res.message || 'Could not add.', 'error');
                    return;
                }
                done(res);
            },
            error: function (xhr) {
                if (xhr.status === 419) {
                    btnReset($btn);
                    toast('Session expired — please refresh the page.', 'error');
                    return;
                }
                if (xhr.status === 401) {
                    btnReset($btn);
                    $('#signin-modal').modal('show');
                    return;
                }
                // controller ne JSON ki jagah redirect/HTML diya — add to ho hi gaya
                if (xhr.status === 200 || xhr.status === 302 || xhr.status === 0) {
                    done(null);
                    return;
                }
                btnReset($btn);
                console.error('[Arthubly] add-to-cart fail:', xhr.status, $form.attr('action'));
                toast('Something went wrong.', 'error');
            }
        });
    }

    // Product card ka form + quick view ka form
    $(document).on('submit', '.pc-cartform, #qv-form, form[data-ajax-cart]', function (e) {
        if (e.isDefaultPrevented()) return;   // arthubly.js ki validation ne rok diya
        e.preventDefault();

        var $form = $(this);
        var $btn  = $form.find('button[type="submit"]');
        if (!$btn.length) $btn = $('.qv-submit');   // qv ka button form ke bahar hai

        ajaxAddToCart($form, $btn);
    });

    // Bina form wala button
    $(document).on('click', '.add-bag-btn', function (e) {
        e.preventDefault();
        var $b = $(this), id = $b.data('id');
        if (!id) return;

        var $tmp = $('<form>', { action: u('add-to-cart/' + id) })
            .append($('<input>', { type: 'hidden', name: '_token',   value: CSRF }))
            .append($('<input>', { type: 'hidden', name: 'quantity', value: 1 }));

        ajaxAddToCart($tmp, $b);
    });

    /* =================================================================
       BAG — qty +/- aur remove
       ================================================================= */
    $(document).on('click', '[data-bag-qty]', function () {
        var $b     = $(this);
        var delta  = parseInt($b.data('bag-qty'), 10);
        var cartId = $b.data('cart-id');
        var $lbl   = $b.siblings('span');
        var next   = (parseInt($lbl.text(), 10) || 1) + delta;
        if (next < 1) return;

        $lbl.text(next);
        $b.closest('.ab-item').addClass('is-busy');

        $.ajax({
            url: u('cart-update'),
            type: 'POST',
            data: { cart_id: cartId, quantity: next, _token: CSRF },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        }).always(function () { refreshBag(); });
    });

    $(document).on('click', '[data-bag-remove]', function () {
        var cartId = $(this).data('bag-remove');
        $(this).closest('.ab-item').addClass('is-busy');

        $.ajax({
            url: u('cart/remove'),
            type: 'POST',
            data: { cart_id: cartId, _token: CSRF },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        }).always(function () {
            refreshBag();
            toast('Item removed');
        });
    });

    /* =================================================================
       WISHLIST — heart toggle
       ================================================================= */
    $(document).on('click', '.pc-fav, #qv-fav, .wish-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $b = $(this);
        if ($b.hasClass('is-busy')) return;

        var href = $b.attr('href');
        var id   = $b.attr('data-id');

        if (!href || href === '#' || href.indexOf('javascript:') === 0) {
            if (!id) return;
            href = u('wishlist/toggle/' + id);
        }

        $b.addClass('is-busy');

        // optimistic UI
        var willAdd = !$b.hasClass('on');
        $b.toggleClass('on', willAdd).toggleClass('is-active', willAdd);

        $.ajax({
            url: href,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                $b.removeClass('is-busy');

               

                if (res && typeof res.added !== 'undefined') {
                    willAdd = !!res.added;
                    $b.toggleClass('on', willAdd).toggleClass('is-active', willAdd);
                    if (id) $('.pc-fav[data-id="' + id + '"]').toggleClass('on', willAdd);
                }
                if (res && typeof res.count !== 'undefined') setWishCount(res.count);
                else refreshWish();

                toast(willAdd ? 'Saved to wishlist' : 'Removed from wishlist');

                if (willAdd && $b.is('#qv-fav')) {
                    $('#wishBody').html(skeleton(1));
                    openDrawer('wishDrawer');
                    refreshWish();
                }
            },
            error: function (xhr) {
                $b.removeClass('is-busy');
                if (xhr.status === 401 || xhr.status === 419) {
                    $b.removeClass('on is-active');
                    $('#signin-modal').modal('show');
                    return;
                }
                refreshWish();
                toast(willAdd ? 'Saved to wishlist' : 'Removed from wishlist');
            }
        });
    });

    $(document).on('click', '[data-wish-remove]', function () {
        var id = $(this).data('wish-remove');
        $(this).closest('.ab-item').addClass('is-busy');

        $.ajax({
            url: u('wishlist/remove'),
            type: 'POST',
            data: { id: id, _token: CSRF },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        }).always(function () {
            refreshWish();
            toast('Removed from wishlist');
        });
    });

    // wishlist drawer se quick view kholte waqt drawer band
    $(document).on('click', '#wishDrawer .quick-view-btn', function () {
        closeDrawers();
    });

    /* =================================================================
       MERGE PROMPT — login ke turant baad
       "Guest me jo wishlist kiya tha, account me add kar dein?"
       ================================================================= */
    function closeMergePrompt() {
        var $p = $('#wishMergePrompt');
        if (!$p.length) return;
        $p.addClass('closing');
        setTimeout(function () { $p.remove(); }, 240);
    }

    $(document).on('click', '[data-merge-yes]', function () {
        var $b = $(this);
        btnBusy($b);
        $b.html('<span class="ab-spin"></span> Adding...');

        $.ajax({
            url: u('wishlist/merge'),
            type: 'POST',
            data: { _token: CSRF },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
            success: function (res) {
                closeMergePrompt();
                if (res && typeof res.count !== 'undefined') setWishCount(res.count);
                toast((res && res.message) ? res.message : 'Added to wishlist');
            },
            error: function () {
                btnReset($b);
                toast('Could not add, please try again.', 'error');
            }
        });
    });

    $(document).on('click', '[data-merge-discard]', function () {
        closeMergePrompt();
        $.ajax({
            url: u('wishlist/discard'),
            type: 'POST',
            data: { _token: CSRF },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        });
    });

    // backdrop click = discard
    $(document).on('click', '.ab-mergewrap', function (e) {
        if (e.target === this) $('[data-merge-discard]').first().trigger('click');
    });

})(jQuery);


/* ==========================================================================
   B. QUICK VIEW
   tha: 3-quickview.js
   ========================================================================== */


/* =========================================================================
   ARTHUBLY — QUICK VIEW (v2, fully self-contained)
   File: public/assets/frontend/js/3-quickview.js

   ⚠️ IMPORTANT — is file me ab koi Blade tag NAHI hai.
   Purani file me {{ url(...) }} likha tha jo .js file me kabhi compile
   nahi hota tha — isi wajah se quick view aur drawers dono fail ho rahe the.

   Ye file apne saare variables khud banati hai. arthubly.blade.php ke inline
   <script> se quick view ka poora code hata dena hai (SETUP.md dekhein).

   Zaroori: is file se PEHLE ye line honi chahiye —
       <script>window.ARTHUBLY_BASE = "{{ url('/') }}";</script>
   ========================================================================= */
(function ($) {
    'use strict';

    var BASE = (window.ARTHUBLY_BASE || '').replace(/\/$/, '');
    function u(p) { return BASE + '/' + String(p).replace(/^\//, ''); }

    var ASSET = u('public/uploads/products');

    // ---- state (global bana rahe hain taaki debug kar sakein) ----
    var productVariations = [];
    var galleryImages = [];
    var basePrice = 0;
    var qvColor = '', qvSize = '', qvQty = 1, qvCompare = null;

    window.QV = {
        get vars() { return productVariations; },
        get gallery() { return galleryImages; },
        get asset() { return ASSET; }
    };

    /* ================= open / close ================= */
    function openQuickView() {
        $('.ab-drawer.open').removeClass('open').attr('aria-hidden', 'true');
        $('body').removeClass('ab-open');
        $('#quickViewModal').addClass('open').attr('aria-hidden', 'false');
        $('body').addClass('qv-open');
    }

    function closeQuickView() {
        $('#quickViewModal').removeClass('open').attr('aria-hidden', 'true');
        $('body').removeClass('qv-open');
    }

    window.openQuickView = openQuickView;
    window.closeQuickView = closeQuickView;

    $(document).on('click', '[data-qv-close]', function (e) {
        e.preventDefault();
        closeQuickView();
    });
    $(document).on('keydown', function (e) {
        if ((e.key === 'Escape' || e.keyCode === 27) && $('#quickViewModal').hasClass('open')) {
            closeQuickView();
        }
    });

    /* ================= helpers ================= */
    function qvFindVar(color, size) {
        return (productVariations || []).find(function (v) {
            var cOk = !color || (v.color && String(v.color).toLowerCase() === String(color).toLowerCase());
            var sOk = !size || (v.size && String(v.size).toLowerCase() === String(size).toLowerCase());
            return cOk && sOk;
        });
    }

    function qvPaintPrice(price) {
        price = parseFloat(price || 0);
        $('#qv-price').text('₹' + price.toFixed(2));

        var cmp = parseFloat(qvCompare || 0);
        if (cmp > price) {
            $('#qv-price-old').text('₹' + cmp.toFixed(2));
            $('#qv-price-off').text(Math.round(((cmp - price) / cmp) * 100) + '% OFF');
        } else {
            $('#qv-price-old').text('');
            $('#qv-price-off').text('');
        }
    }

    function qvPaintImage(variation, fallbackImage) {
        var src = (variation && variation.image)
            ? ASSET + '/variations/' + variation.image
            : (fallbackImage ? ASSET + '/' + fallbackImage : ASSET + '/no-image.jpg');
        $('#qv-image').attr('src', src);
        $('.qv-media').removeClass('zoomed');
    }

    /* ================= gallery thumbs ================= */
    function renderGallery(selectedColor) {
        var html = '';
        var colorLower = selectedColor ? String(selectedColor).toLowerCase() : '';

        (productVariations || []).forEach(function (v) {
            if (!v.image) return;
            if (colorLower && (!v.color || String(v.color).toLowerCase() !== colorLower)) return;

            var full  = ASSET + '/variations/' + v.image;
            var parts = String(v.image).split('.');
            var side  = ASSET + '/variations/side/' + parts[0] + '_side.' + (parts[1] || 'jpg');

            html += '<img src="' + side + '" data-full="' + full + '" class="qv-thumb" ' +
                    'onerror="this.src=\'' + full + '\'">';
        });

        (galleryImages || []).forEach(function (img) {
            var imgColor = img.product_color ? String(img.product_color).toLowerCase() : '';
            if (colorLower && imgColor && imgColor !== colorLower) return;

            var full  = ASSET + '/gallery/' + img.image;
            var thumb = img.side_image ? (ASSET + '/gallery/side/' + img.side_image) : full;

            html += '<img src="' + thumb + '" data-full="' + full + '" class="qv-thumb" ' +
                    'onerror="this.src=\'' + full + '\'">';
        });

        $('#qv-gallery').html(html);
        $('#qv-gallery .qv-thumb').first().addClass('active');
    }

    $(document).on('click', '.qv-thumb', function () {
        $('#qv-image').attr('src', $(this).data('full'));
        $('.qv-thumb').removeClass('active');
        $(this).addClass('active');
    });

    /* ================= colours (swatches) ================= */
    function qvRenderColors(activeColor) {
        var seen = {}, html = '';

        (productVariations || []).forEach(function (v) {
            if (!v.color) return;
            var key = String(v.color).toLowerCase();
            if (seen[key]) return;
            seen[key] = true;

            var bg = v.image
                ? 'background-image:url(' + ASSET + '/variations/' + v.image + ')'
                : 'background-color:' + key;

            html += '<button type="button" class="qv-sw" data-color="' + v.color +
                    '" title="' + v.color + '" style="' + bg + '"></button>';
        });

        $('#qv-color-wrap').html(html);
        $('#qv-color-group').toggle(html !== '');

        var pick = (activeColor && seen[String(activeColor).toLowerCase()])
            ? activeColor
            : Object.keys(seen)[0];

        if (pick) qvSelectColor(pick, true);
        else { qvColor = ''; $('#qv-color').val(''); }
    }

    /* ================= sizes (chips) ================= */
    function qvRenderSizes(color) {
        var seen = {}, html = '';

        (productVariations || []).forEach(function (v) {
            if (!v.size) return;
            if (color && v.color && String(v.color).toLowerCase() !== String(color).toLowerCase()) return;
            
            var key = String(v.size).toLowerCase();
            if (seen[key]) return;
            seen[key] = true;

            // Stock quantity check karo
            var hasStock = (v.quantity > 0 || v.stock > 0);
            
            // Out of stock hone par pc-off class aur disabled attribute lagao
            var cssClass = hasStock ? 'qv-chip' : 'qv-chip pc-off';
            var disabledAttr = hasStock ? '' : 'disabled="disabled" title="Out of Stock"';

            html += '<button type="button" class="' + cssClass + '" data-size="' + v.size + '" ' + disabledAttr + '>' + v.size + '</button>';
        });

        $('#qv-size-wrap').html(html);
        $('#qv-size-group').toggle(html !== '');

        var keys = Object.keys(seen);
        var keep = (qvSize && seen[String(qvSize).toLowerCase()])
            ? qvSize
            : (keys.length ? $('#qv-size-wrap .qv-chip').first().data('size') : '');

        if (keep) qvSelectSize(keep, true);
        else { qvSize = ''; $('#qv-size').val(''); }

        var any = $('#qv-size-wrap').children().length || $('#qv-color-wrap').children().length;
        $('#qv-varhead').toggle(!!any);
    }

    /* ================= selection ================= */
    function qvSelectColor(color, silent) {
        qvColor = color;
        $('#qv-color').val(color);

        $('#qv-color-wrap .qv-sw').removeClass('active').filter(function () {
            return String($(this).data('color')).toLowerCase() === String(color).toLowerCase();
        }).addClass('active');

        renderGallery(color);
        if (!silent) qvRenderSizes(color);

        var v = qvFindVar(color, qvSize) || qvFindVar(color, '');
        qvPaintImage(v);
        qvPaintPrice(v ? v.price : basePrice);
    }

    function qvSelectSize(size, silent) {
        qvSize = size;
        $('#qv-size').val(size);

        $('#qv-size-wrap .qv-chip').removeClass('active').filter(function () {
            return String($(this).data('size')).toLowerCase() === String(size).toLowerCase();
        }).addClass('active');

        var v = qvFindVar(qvColor, size);
        if (v) {
            qvPaintPrice(v.price);
            if (v.image) qvPaintImage(v);
        }
    }

    $(document).on('click', '#qv-color-wrap .qv-sw', function () {
        qvSelectColor($(this).data('color'), false);
    });
    $(document).on('click', '#qv-size-wrap .qv-chip', function () {
        qvSelectSize($(this).data('size'), false);
    });

    /* ================= quantity ================= */
    $(document).on('click', '.qv-stepper button', function () {
        qvQty = Math.max(1, qvQty + parseInt($(this).data('qty'), 10));
        $('#qv-qty').val(qvQty);
        $('#qv-qty-label').text(qvQty);
    });

    /* ================= zoom ================= */
    $(document).on('click', '#qv-zoom', function () {
        $('.qv-media').toggleClass('zoomed');
    });
    $(document).on('click', '.qv-media.zoomed .qv-main', function () {
        $('.qv-media').removeClass('zoomed');
    });

    /* =====================================================================
       MAIN — quick view button click
       ===================================================================== */
    $(document).on('click', '.quick-view-btn', function (e) {
        e.preventDefault();

        var productId = $(this).data('id');
        if (!productId) return;

        var $card = $(this).closest('.pcard, .product-2, .product');
        var activeColor = $card.find('.color-dot.active').data('color') ||
                          $card.find('.color-dot.active').attr('title');

        $('#qv-form').attr('action', u('add-to-cart/' + productId));
        $('#qv-fav').attr('href', u('wishlist/toggle/' + productId)).attr('data-id', productId);

        $.ajax({
            url: u('product-details/' + productId),
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                productVariations = response.variations || [];
                galleryImages     = response.images || [];
                basePrice         = response.price || 0;
                qvCompare         = response.compare_price || response.mrp || null;
                qvQty = 1; qvSize = ''; qvColor = '';

                $('#qv-title').text(response.name || 'Product');
                $('#qv-cat').text(response.category ? (response.category.name || '') : '');
                $('#qv-description').html(response.short_description || '');
                $('#qv-qty').val(1);
                $('#qv-qty-label').text(1);

                var rating = response.rating || response.reviews_avg_rating || null;
                if (rating) {
                    $('#qv-rating').show();
                    $('#qv-rating .qv-stars i').css('width',
                        (Math.min(5, Math.max(0, rating)) / 5 * 100) + '%');
                    $('#qv-rcount').text(response.reviews_count ? '(' + response.reviews_count + ')' : '');
                } else {
                    $('#qv-rating').hide();
                }

                qvRenderColors(activeColor);
                qvRenderSizes(qvColor);

                if (!productVariations.length) {
                    renderGallery('');
                    qvPaintImage(null, response.thumbnail_image || response.image);
                    qvPaintPrice(basePrice);
                    $('#qv-varhead').hide();
                }

                openQuickView();
            },
            error: function (xhr) {
                console.error('Quick View failed:', xhr.status, u('product-details/' + productId));
                if (window.abToast) window.abToast('Product load nahi ho paya.', 'error');
            }
        });
    });

})(jQuery);


/* ==========================================================================
   C. AI SEARCH
   tha: ai-search.js
   ========================================================================== */


/* =====================================================================
   FILE: public/assets/frontend/js/5-ai-search.js
   v5: har cheez null-safe. arthubly.js ke handler se clash nahi hoga.
       Modal open ka intezaar karke tab bind karta hai.
   ===================================================================== */
(function () {
    'use strict';

    // console.log('[AI SEARCH] script loaded');

    function el(id) { return document.getElementById(id); }

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function csrf() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    }

    // AI_SEARCH_URL blade se aata hai; na mile to route guess kar lo
    function endpoint() {
        if (typeof window.AI_SEARCH_URL === 'string' && window.AI_SEARCH_URL) {
            return window.AI_SEARCH_URL;
        }
        console.warn('[AI SEARCH] AI_SEARCH_URL missing — /ai-search use kar rahe hain');
        return '/ai-search';
    }

    var busy = false;
    var lastQuery = '';

    /* ---------- results container — jab bhi chahiye, bana ke do ---------- */
    function getResultsBox() {
        var box = el('aiResults');
        if (box) return box;

        var overlay = el('aiOverlay');
        var host = overlay
            ? (overlay.querySelector('.ai-modal-body') || overlay.querySelector('.ai-modal') || overlay)
            : null;
        if (!host) return null;

        box = document.createElement('div');
        box.id = 'aiResults';
        box.className = 'ais-results';
        host.appendChild(box);
        return box;
    }

    /* ---------- render ---------- */
    function skeleton() {
        var c = '';
        for (var i = 0; i < 4; i++) c += '<div class="ais-skel"></div>';
        return '<div class="ais-status">Arthubly AI is searching…</div><div class="ais-grid">' + c + '</div>';
    }

    function badge(data) {
        if (!data.debug) return '';
        var ai = data.debug.used_ai;
        return '<div class="ais-badge ' + (ai ? 'is-ai' : 'is-fb') + '">' +
            (ai ? '🤖 Gemini' : '🔤 Fallback (keyword)') +
            ' · ' + esc(data.debug.stage) + ' · ' + esc(data.debug.time_ms) + 'ms' +
            ' · ' + esc((data.debug.keywords || []).join(', ')) + '</div>';
    }

    function chips(f) {
        f = f || {};
        var out = [];
        if (f.category) out.push(f.category);
        (f.colors || []).forEach(function (c) { out.push(c); });
        (f.sizes || []).forEach(function (s) { out.push('Size ' + s); });
        if (f.min_price && f.max_price) out.push('₹' + f.min_price + '–₹' + f.max_price);
        else if (f.max_price) out.push('Under ₹' + f.max_price);
        else if (f.min_price) out.push('₹' + f.min_price + '+');
        if (!out.length) return '';
        return '<div class="ais-chips">' + out.map(function (c) {
            return '<span class="ais-chip">' + esc(c) + '</span>';
        }).join('') + '</div>';
    }

    function render(box, data) {
        // console.log('[AI SEARCH] data', data);

        if (!data || !data.products || !data.products.length) {
            box.innerHTML = badge(data || {}) +
                '<div class="ais-status">' + esc((data && data.reply) || 'Nothing found.') + '</div>' +
                '<div class="ais-empty">Try different keywords, or ' +
                '<a href="' + esc((data && data.search_url) || '/search') + '">view normal search</a>.</div>';
            return;
        }

        var cards = data.products.map(function (p) {
            return '<a class="ais-card" href="' + esc(p.url) + '">' +
                '<span class="ais-thumb"><img src="' + esc(p.image) + '" alt="' + esc(p.name) + '" loading="lazy"></span>' +
                '<span class="ais-cat">' + esc(p.category) + '</span>' +
                '<span class="ais-name">' + esc(p.name) + '</span>' +
                '<span class="ais-price">' + esc(p.price_fmt) + '</span></a>';
        }).join('');

        box.innerHTML = badge(data) +
            '<div class="ais-status">' + esc(data.reply) + '</div>' +
            chips(data.filters) +
            '<div class="ais-grid">' + cards + '</div>' +
            '<a class="ais-all" href="' + esc(data.search_url) + '">View all results →</a>';
    }

    /* ---------- call ---------- */
    function run(q) {
        q = (q || '').trim();
        if (q.length < 2) { console.warn('[AI SEARCH] query bahut chhoti'); return; }
        if (busy) return;

        var box = getResultsBox();
        if (!box) { console.error('[AI SEARCH] results box nahi bana — #aiOverlay missing?'); return; }

        if (q === lastQuery && box.innerHTML.indexOf('ais-card') > -1) return;

        busy = true;
        lastQuery = q;
        box.classList.add('show');
        box.innerHTML = skeleton();

        var url = endpoint();
        // console.log('[AI SEARCH] →', url, q);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ q: q })
        })
            .then(function (r) {
                // console.log('[AI SEARCH] ← HTTP', r.status);
                if (r.status === 419) throw new Error('Session expired — please refresh the page.');
                if (r.status === 429) throw new Error('Too many requests! Please wait a minute.');
                return r.text().then(function (t) {
                    if (!r.ok) throw new Error('HTTP ' + r.status + ': ' + t.slice(0, 200));
                    try { return JSON.parse(t); }
                    catch (e) { throw new Error('Server did not send JSON: ' + t.slice(0, 200)); }
                });
            })
            .then(function (data) { render(box, data); })
            .catch(function (err) {
                console.error('[AI SEARCH] fail:', err);
                box.innerHTML = '<div class="ais-empty">' + esc(err.message) +
                    ' <a href="/search?q=' + encodeURIComponent(q) + '">Normal search</a></div>';
            })
            .finally(function () { busy = false; });
    }

    /* ---------- bind ---------- */
    var bound = false;
    function bind() {
        if (bound) return;
        var overlay = el('aiOverlay');
        var input = el('aiModalInput');
        if (!overlay || !input) return;   // abhi DOM me nahi, baad me try hoga

        bound = true;

        // Form submit — capture phase me, taaki arthubly.js ka koi handler
        // pehle chal ke reload na kar de
        var form = overlay.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                run(input.value);
            }, true);
        }

        // Enter key seedha input par bhi (agar form na ho)
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                run(input.value);
            }
        });

        // "Search" button click bhi seedha bind (belt + suspenders)
        var goBtn = overlay.querySelector('.go-ai, button[type="submit"]');
        if (goBtn) {
            goBtn.addEventListener('click', function (e) {
                e.preventDefault();
                run(input.value);
            });
        }

        // "Try:" prompt chips
        overlay.querySelectorAll('.ai-prompts button').forEach(function (b) {
            b.addEventListener('click', function () {
                input.value = b.textContent.trim();
                run(input.value);
            });
        });

        // Modal band → purana result saaf
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay || (e.target.closest && e.target.closest('[data-close]'))) {
                var box = el('aiResults');
                if (box) { box.innerHTML = ''; box.classList.remove('show'); }
                lastQuery = '';
            }
        });

        // console.log('[AI SEARCH] bound ✅');
    }

    function init() {
        bind();
        // Agar modal markup baad me aata hai to thodi der baad dobara try
        if (!bound) {
            var tries = 0;
            var iv = setInterval(function () {
                bind();
                if (bound || ++tries > 20) clearInterval(iv);
            }, 300);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();