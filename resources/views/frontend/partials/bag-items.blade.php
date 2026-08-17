{{-- File: resources/views/frontend/partials/bag-items.blade.php
     Sirf bag ke line-items. Controller isko akela bhi render karta hai (AJAX refresh ke liye). --}}
@php
    $__cart = session('cart', []);
    $__cart = is_array($__cart) ? $__cart : [];
@endphp

@if (count($__cart) > 0)
    @foreach ($__cart as $cartId => $d)
        @php
            $isVar = !empty($d['is_variant']);
            $imgPath = $isVar
                ? 'public/uploads/products/variations/' . ($d['image'] ?? '')
                : 'public/uploads/products/' . ($d['image'] ?? '');
            $qty = (int) ($d['quantity'] ?? 1);
            $price = (float) ($d['price'] ?? 0);
        @endphp
        <div class="ab-item" data-cart-id="{{ $cartId }}">
            <div class="ab-thumb">
                <img src="{{ asset($imgPath) }}" alt="{{ $d['name'] ?? '' }}"
                     onerror="this.src='{{ asset('public/uploads/products/no-image.jpg') }}'">
            </div>

            <div>
                <h5 class="ab-name">{{ $d['name'] ?? 'Item' }}</h5>
                @if (!empty($d['color']) || !empty($d['size']))
                    <div class="ab-var">
                        {{ $d['color'] ?? '' }}{{ !empty($d['color']) && !empty($d['size']) ? ' · ' : '' }}{{ !empty($d['size']) ? strtoupper($d['size']) : '' }}
                    </div>
                @endif
                <div class="ab-price">₹{{ number_format($price * $qty, 2) }}</div>
            </div>

            <div class="ab-right">
                <button type="button" class="ab-rm" data-bag-remove="{{ $cartId }}" title="Remove">✕</button>
                <div class="ab-step">
                    <button type="button" data-bag-qty="-1" data-cart-id="{{ $cartId }}" aria-label="Decrease">−</button>
                    <span>{{ $qty }}</span>
                    <button type="button" data-bag-qty="1" data-cart-id="{{ $cartId }}" aria-label="Increase">+</button>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="ab-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
            <path d="M6 8h12l-1 12H7L6 8z" />
            <path d="M9 8V6a3 3 0 0 1 6 0v2" />
        </svg>
        <p>Your bag is empty right now.</p>
        <a href="{{ url('/') }}" class="ab-btn ab-btn-primary" style="display:inline-flex;padding:0 22px">Continue shopping</a>
    </div>
@endif