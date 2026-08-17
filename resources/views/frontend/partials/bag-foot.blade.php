{{-- File: resources/views/frontend/partials/bag-foot.blade.php --}}
@php
    $__cart = session('cart', []);
    $__cart = is_array($__cart) ? $__cart : [];
    $__cartCount = count($__cart);
    $__cartTotal = 0;
    foreach ($__cart as $__d) {
        $__cartTotal += ($__d['price'] ?? 0) * ($__d['quantity'] ?? 1);
    }
@endphp

@if ($__cartCount > 0)
    <div class="ab-total">
        <span>Subtotal ({{ $__cartCount }} {{ $__cartCount == 1 ? 'item' : 'items' }})</span>
        <span class="ab-total-val">₹{{ number_format($__cartTotal, 2) }}</span>
    </div>
    <div class="ab-actions">
        <a href="{{ route('cart.index') }}" class="ab-btn ab-btn-ghost">View Bag</a>
        <a href="{{ route('checkout.index') }}" class="ab-btn ab-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="4" y="10" width="16" height="10" rx="2" />
                <path d="M8 10V7a4 4 0 0 1 8 0v3" />
            </svg> Checkout
        </a>
    </div>
    <div class="ab-note">Taxes &amp; shipping will be calculated at checkout</div>
@else
    <div class="ab-actions">
        <a href="{{ url('/') }}" class="ab-btn ab-btn-primary">Continue shopping</a>
    </div>
@endif