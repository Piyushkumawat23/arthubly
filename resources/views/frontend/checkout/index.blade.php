@extends('frontend.layout.arthubly')

@section('title', 'Checkout — Arthubly')

@section('content')
<section class="page active" id="page-checkout">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><a href="{{ route('cart.index') }}">Bag</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Checkout</span></div>
        <div class="listing-head" style="border:none;padding-bottom:10px"><h1>Checkout</h1></div>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <div class="checkout-layout">
                {{-- Billing details --}}
                <div>
                    <div class="panel">
                        <div class="panel-head"><h4>Billing details</h4></div>
                        <div class="panel-body">
                            <div class="form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                                <div class="field full" style="grid-column:span 2">
                                    <label>Full name *</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required class="@error('name') is-invalid @enderror">
                                </div>
                                <div class="field"><label>Company (optional)</label><input type="text" name="company_name"></div>
                                <div class="field"><label>Country *</label><input type="text" name="country" value="India" required></div>
                                <div class="field full" style="grid-column:span 2"><label>Street address *</label><input type="text" name="address" placeholder="House number and street name" required></div>
                                <div class="field full" style="grid-column:span 2"><label>Apartment, suite, etc. (optional)</label><input type="text" name="apartment"></div>
                                <div class="field"><label>Town / City *</label><input type="text" name="city" required></div>
                                <div class="field"><label>State / County *</label><input type="text" name="state" required></div>
                                <div class="field"><label>Postcode / ZIP *</label><input type="text" name="pincode" required></div>
                                <div class="field"><label>Phone *</label><input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" required></div>
                                <div class="field full" style="grid-column:span 2"><label>Email address *</label><input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required></div>
                                <div class="field full" style="grid-column:span 2"><label>Order notes (optional)</label><textarea name="order_notes" rows="3" placeholder="Notes about your order, e.g. special delivery instructions"></textarea></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order summary --}}
                <aside class="summary">
                    <h3>Your order</h3>
                    @php $subtotal = 0; @endphp
                    @foreach($cart as $cartId => $details)
                        @php $itemTotal = $details['price'] * $details['quantity']; $subtotal += $itemTotal; @endphp
                        <div class="line" style="align-items:flex-start">
                            <span style="max-width:70%">{{ $details['name'] }}<br><small style="color:var(--ink-50)">{{ $details['quantity'] }} × ₹{{ number_format($details['price'], 2) }}</small></span>
                            <b>₹{{ number_format($itemTotal, 2) }}</b>
                        </div>
                    @endforeach

                    <div class="line" style="border-top:1px solid var(--line);margin-top:8px;padding-top:14px"><span>Subtotal</span><b>₹{{ number_format($subtotal, 2) }}</b></div>
                    @if(session()->has('coupon'))
                        <div class="line disc"><span>Discount ({{ session('coupon')['code'] }})</span><b>− ₹{{ number_format(session('coupon')['discount'], 2) }}</b></div>
                    @endif
                    <div class="line"><span>Shipping</span><b style="color:var(--celadon)">Free</b></div>
                    <div class="total"><span style="font-family:var(--font-d);font-size:18px">Total</span><b style="font-family:var(--font-d);font-size:24px;color:var(--brass-d)">₹{{ number_format(session()->has('coupon') ? session('coupon')['grand_total'] : $subtotal, 2) }}</b></div>

                    {{-- Dynamic payment methods (admin-enabled) --}}
                    <h4 style="font-family:var(--font-d);font-size:15px;margin:20px 0 12px">Payment method</h4>
                    @forelse($paymentGateways as $gateway)
                        <label class="pay-opt {{ $loop->first ? 'sel' : '' }}" style="margin-bottom:10px;cursor:pointer">
                            <span class="radio"></span>
                            <input type="radio" name="payment_method" value="{{ $gateway->slug }}" {{ $loop->first ? 'checked' : '' }} required style="display:none">
                            <span>
                                <b style="display:block;font-size:14px;color:var(--ink)">{{ $gateway->name }}</b>
                                @if($gateway->instructions)<small style="color:var(--ink-50);font-size:12px">{{ $gateway->instructions }}</small>@endif
                            </span>
                        </label>
                    @empty
                        <p style="color:var(--madder);font-size:13px">No payment method is available right now.</p>
                    @endforelse

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:16px">Place order</button>
                </aside>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    // payment radio selection styling
    $(document).on('change', 'input[name="payment_method"]', function(){
        $('.pay-opt').removeClass('sel');
        $(this).closest('.pay-opt').addClass('sel');
    });
});
</script>
@endpush
