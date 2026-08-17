@extends('frontend.layout.arthubly')

@section('title', 'Your Bag — Arthubly')

@section('content')
<section class="page active" id="page-cart">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Shopping Bag</span></div>

        @php $subtotal = 0; @endphp
        @if(is_array($cart) && count($cart) > 0)
            <div class="listing-head" style="border:none;padding-bottom:8px">
                <h1>Your shopping bag</h1>
                <div class="meta"><span class="cnt"><b>{{ count($cart) }}</b> item(s) in your bag</span></div>
            </div>

            <div class="cart-layout">
                <div class="panel" style="margin-bottom:0">
                    @foreach($cart as $cartId => $details)
                        @php
                            $line = ($details['price'] ?? 0) * ($details['quantity'] ?? 1);
                            $subtotal += $line;
                            $imgPath = (isset($details['is_variant']) && $details['is_variant'])
                                ? 'public/uploads/products/variations/' . ($details['image'] ?? '')
                                : 'public/uploads/products/' . ($details['image'] ?? '');
                        @endphp
                        <div class="cart-item" data-cart-id="{{ $cartId }}" data-price="{{ $details['price'] ?? 0 }}">
                            <div class="ci-img"><img src="{{ asset($imgPath) }}" alt="{{ $details['name'] ?? '' }}" style="width:100%;height:100%;object-fit:cover" onerror="this.src='{{ asset('public/uploads/products/no-image.jpg') }}'"></div>
                            <div class="ci-info">
                                <h4>{{ $details['name'] ?? 'Item' }}</h4>
                                @if(!empty($details['color']) || !empty($details['size']))
                                    <div class="ci-meta">
                                        @if(!empty($details['color'])) Color: {{ $details['color'] }} @endif
                                        @if(!empty($details['color']) && !empty($details['size'])) &nbsp;·&nbsp; @endif
                                        @if(!empty($details['size'])) Size: {{ $details['size'] }} @endif
                                    </div>
                                @endif
                                <div class="qty" style="margin-top:12px;width:max-content">
                                    <button type="button" class="qty-dec" aria-label="Decrease">−</button>
                                    <input type="number" class="qty-input" value="{{ $details['quantity'] ?? 1 }}" min="1" readonly>
                                    <button type="button" class="qty-inc" aria-label="Increase">+</button>
                                </div>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value="{{ $cartId }}">
                                    <button type="submit" class="ci-remove"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path d="M18 6 6 18M6 6l12 12"/></svg> Remove</button>
                                </form>
                            </div>
                            <div style="text-align:right">
                                <div class="ci-price">₹<span class="ci-line-total">{{ number_format($line, 2) }}</span></div>
                                <div class="ci-meta">₹{{ number_format($details['price'] ?? 0, 2) }} each</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="summary" id="cartSummary">
                    <h3>Order summary</h3>
                    <div class="line"><span>Subtotal</span><b>₹<span id="sumSubtotal">{{ number_format($subtotal, 2) }}</span></b></div>
                    @if(session()->has('coupon'))
                        <div class="line disc"><span>Discount ({{ session('coupon')['code'] }})</span><b id="sumDiscount">− ₹{{ number_format(session('coupon')['discount'], 2) }}</b></div>
                    @else
                        <div class="line disc" id="discountLine" style="display:none"><span>Discount</span><b id="sumDiscount">− ₹0.00</b></div>
                    @endif
                    <div class="line"><span>Shipping</span><b style="color:var(--celadon)">Free</b></div>

                    <form class="coupon" id="couponForm" onsubmit="return false">
                        @csrf
                        <input type="text" name="coupon_code" id="couponCode" placeholder="Coupon code" value="{{ session('coupon')['code'] ?? '' }}">
                        <button type="submit" id="couponBtn">Apply</button>
                    </form>
                    <small id="couponMsg" style="display:block;min-height:16px;color:var(--celadon);font-size:12.5px;margin:-6px 0 6px"></small>

                    <div class="total"><span style="font-family:var(--font-d);font-size:18px">Total</span><b style="font-family:var(--font-d);font-size:24px;color:var(--brass-d)">₹<span id="sumTotal">{{ number_format(session()->has('coupon') ? session('coupon')['grand_total'] : $subtotal, 2) }}</span></b></div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:16px">Proceed to checkout <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
                    <a href="{{ url('/') }}" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:10px">Continue shopping</a>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="es-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg></div>
                <h3>Your bag is empty</h3>
                <p>Looks like you haven't added anything yet. Discover work made by hand.</p>
                <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Start shopping</a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    var token = '{{ csrf_token() }}';
    function inr(n){ return Number(n).toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    // ---- Qty steppers (AJAX -> cart.update_quantity) ----
    function updateQty($row, newQty){
        var cartId = $row.data('cart-id');
        if(newQty < 1) newQty = 1;
        $.ajax({
            url: "{{ route('cart.update_quantity') }}", type: 'POST',
            data: { _token: token, cart_id: cartId, quantity: newQty },
            success: function(res){
                if(res && res.success){
                    $row.find('.qty-input').val(newQty);
                    $row.find('.ci-line-total').text(res.item_total);
                    $('#sumSubtotal').text(res.cart_total);
                    // if no coupon, total = subtotal
                    if(!$('#discountLine').is(':visible') && !{{ session()->has('coupon') ? 'true' : 'false' }}){
                        $('#sumTotal').text(res.cart_total);
                    }
                }
            }
        });
    }
    $(document).on('click', '.qty-inc', function(){ var $r=$(this).closest('.cart-item'); updateQty($r, parseInt($r.find('.qty-input').val())+1); });
    $(document).on('click', '.qty-dec', function(){ var $r=$(this).closest('.cart-item'); updateQty($r, parseInt($r.find('.qty-input').val())-1); });

    // ---- Coupon (AJAX -> cart.apply_coupon) ----
    $('#couponForm').on('submit', function(){
        var code = $('#couponCode').val().trim();
        if(!code) return false;
        $('#couponBtn').prop('disabled', true).text('…');
        $.ajax({
            url: "{{ route('cart.apply_coupon') }}", type: 'POST',
            data: { _token: token, coupon_code: code },
            success: function(res){
                $('#couponBtn').prop('disabled', false).text('Apply');
                if(res.success){
                    $('#couponMsg').css('color','var(--celadon)').text(res.message);
                    $('#discountLine').show(); $('#sumDiscount').text('− ₹' + res.discount);
                    $('#sumTotal').text(res.grand_total);
                } else {
                    $('#couponMsg').css('color','var(--madder)').text(res.message);
                }
            },
            error: function(){ $('#couponBtn').prop('disabled', false).text('Apply'); $('#couponMsg').css('color','var(--madder)').text('Could not apply coupon.'); }
        });
        return false;
    });
});
</script>
@endpush
