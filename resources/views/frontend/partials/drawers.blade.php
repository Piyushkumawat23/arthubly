{{-- =====================================================================
     BAG + WISHLIST DRAWERS
     File: resources/views/frontend/partials/drawers.blade.php
     arthubly.blade.php me QUICK VIEW DRAWER ke turant BAAD ye include karein:
         @include('frontend.partials.drawers')
     ===================================================================== --}}

{{-- ============ BAG (Add to Cart) DRAWER ============ --}}
<div class="ab-drawer" id="bagDrawer" aria-hidden="true">
    <div class="ab-backdrop" data-ab-close></div>

    <aside class="ab-panel" role="dialog" aria-modal="true" aria-labelledby="bag-title">
        <div class="ab-head">
            <h4 id="bag-title">
                Your Bag
                <span class="ab-count" id="bag-count-label"></span>
            </h4>
            <button type="button" class="ab-close" data-ab-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- JS isi ke andar bag-items partial daalta hai --}}
        <div class="ab-scroll" id="bagBody">
            @include('frontend.partials.bag-items')
        </div>

        <div class="ab-foot" id="bagFoot">
            @include('frontend.partials.bag-foot')
        </div>
    </aside>
</div>


{{-- ============ WISHLIST DRAWER ============ --}}
<div class="ab-drawer" id="wishDrawer" aria-hidden="true">
    <div class="ab-backdrop" data-ab-close></div>

    <aside class="ab-panel" role="dialog" aria-modal="true" aria-labelledby="wish-title">
        <div class="ab-head">
            <h4 id="wish-title">
                Wishlist
                <span class="ab-count" id="wish-count-label"></span>
            </h4>
            <button type="button" class="ab-close" data-ab-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="ab-scroll" id="wishBody">
            <div class="ab-loading">
                <div class="ab-sk"><i class="s1"></i><div><i class="s2"></i><i class="s3"></i></div></div>
                <div class="ab-sk"><i class="s1"></i><div><i class="s2"></i><i class="s3"></i></div></div>
                <div class="ab-sk"><i class="s1"></i><div><i class="s2"></i><i class="s3"></i></div></div>
            </div>
        </div>

        <div class="ab-foot">
            <div class="ab-actions">
                <a href="{{ route('wishlist.index') }}" class="ab-btn ab-btn-ghost">View all</a>
                <a href="{{ url('/') }}" class="ab-btn ab-btn-primary">Keep shopping</a>
            </div>
            <div class="ab-note">Saved items are only visible to you</div>
        </div>
    </aside>
</div>