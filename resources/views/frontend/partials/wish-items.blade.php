{{-- File: resources/views/frontend/partials/wish-items.blade.php
     WishlistController se `$items` aata hai — har item:
        ['key' => product_id, 'product' => Product]
     Guest aur logged-in dono ke liye shakal same hai. --}}

@if (isset($items) && count($items) > 0)
    @foreach ($items as $it)
        @php
            $p = $it['product'];
            $v = $p->variations->first();

            $pPrice = $v->price ?? ($p->sale_price ?? ($p->price ?? 0));

            $pImg = asset('public/uploads/products/no-image.jpg');
            if ($v && !empty($v->image)) {
                $vi = pathinfo($v->image);
                $pImg = asset('public/uploads/products/variations/thumbs/' . $vi['filename'] . '_thumb.' . $vi['extension']);
            } elseif (!empty($p->thumbnail_image)) {
                $pImg = asset('public/uploads/products/' . $p->thumbnail_image);
            }
        @endphp

        <div class="ab-item" data-wish-id="{{ $it['key'] }}">
            <div class="ab-thumb">
                <img src="{{ $pImg }}" alt="{{ $p->name }}"
                     onerror="this.src='{{ asset('public/uploads/products/no-image.jpg') }}'">
            </div>

            <div>
                <h5 class="ab-name">
                    <a href="{{ $p->slug ? url('product/' . $p->slug) : '#' }}">{{ $p->name }}</a>
                </h5>
                <div class="ab-price">₹{{ number_format((float) $pPrice, 2) }}</div>

                <button type="button" class="ab-btn ab-btn-ghost quick-view-btn" data-id="{{ $p->id }}"
                        style="height:32px;font-size:12px;padding:0 14px;margin-top:8px;flex:0 0 auto;display:inline-flex">
                    Move to Bag
                </button>
            </div>

            <div class="ab-right">
                <button type="button" class="ab-rm" data-wish-remove="{{ $it['key'] }}" title="Remove">✕</button>
            </div>
        </div>
    @endforeach

    @guest
        <div class="ab-guestnote">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 11v5M12 8h.01" />
            </svg>
            <span>
                These items are only saved in this browser.
                <a href="#" data-toggle="modal" data-target="#signin-modal" data-ab-close>Sign in</a>
                to save permanently.
            </span>
        </div>
    @endguest
@else
    <div class="ab-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
            <path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
        </svg>
        <p>Nothing saved yet.</p>
        <a href="{{ url('/') }}" class="ab-btn ab-btn-primary" style="display:inline-flex;padding:0 22px">Explore handmade</a>
    </div>
@endif