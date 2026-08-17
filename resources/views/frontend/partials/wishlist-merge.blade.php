{{-- =====================================================================
     WISHLIST MERGE PROMPT
     File: resources/views/frontend/partials/wishlist-merge.blade.php

     Login ke turant baad dikhta hai — sirf tab jab guest ne kuch
     wishlist kiya ho. Layout me include karein (drawers ke paas hi).
     ===================================================================== --}}

@auth
    @php
        $__mergeIds   = session('guest_wishlist', []);
        
        // CHANGE: Ab hum 'wishlist_merge_ask' variable par depend nahi kar rahe
        // Agar guest_wishlist me items hain, to direct popup dikhayega
        $__mergeAsk   = is_array($__mergeIds) && count($__mergeIds) > 0;
        $__mergeItems = collect();

        if ($__mergeAsk) {
            $__mergeItems = \App\Models\Product::with('variations')
                ->whereIn('id', $__mergeIds)
                ->take(4)
                ->get();
        }
    @endphp

    @if ($__mergeAsk)
        <div class="ab-mergewrap" id="wishMergePrompt">
            <div class="ab-merge">
                <button type="button" class="ab-merge-x" data-merge-discard aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>

                <div class="ab-merge-ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
                    </svg>
                </div>

                <h4>Saved items before signing in</h4>
                <p>
                    You saved <b>{{ count($__mergeIds) }}</b>
                    {{ count($__mergeIds) == 1 ? 'item' : 'items' }} wishlist me rakhe the.
                    Add these to your account?
                </p>

                @if ($__mergeItems->count())
                    <div class="ab-merge-thumbs">
                        @foreach ($__mergeItems as $mp)
                            @php
                                $mv   = $mp->variations->first();
                                $mImg = asset('public/uploads/products/no-image.jpg');
                                if ($mv && !empty($mv->image)) {
                                    $mi   = pathinfo($mv->image);
                                    $mImg = asset('public/uploads/products/variations/thumbs/' . $mi['filename'] . '_thumb.' . $mi['extension']);
                                } elseif (!empty($mp->thumbnail_image)) {
                                    $mImg = asset('public/uploads/products/' . $mp->thumbnail_image);
                                }
                            @endphp
                            <img src="{{ $mImg }}" alt="{{ $mp->name }}" title="{{ $mp->name }}"
                                 onerror="this.src='{{ asset('public/uploads/products/no-image.jpg') }}'">
                        @endforeach

                        @if (count($__mergeIds) > $__mergeItems->count())
                            <span class="ab-merge-more">+{{ count($__mergeIds) - $__mergeItems->count() }}</span>
                        @endif
                    </div>
                @endif

                <div class="ab-merge-actions">
                    <button type="button" class="ab-btn ab-btn-ghost" data-merge-discard>No, leave it</button>
                    <button type="button" class="ab-btn ab-btn-primary" data-merge-yes>Yes, add them</button>
                </div>
            </div>
        </div>
    @endif
@endauth
