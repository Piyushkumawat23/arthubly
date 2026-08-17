@extends('frontend.layout.app')

@section('title', 'Your Bag — Arthubly')

@section('content')
@verbatim
  <section class="page active" id="page-cart">
    <div class="wrap">
      <div class="crumbs"><a data-nav="home">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Shopping Bag</span></div>
      <div class="listing-head" style="border:none;padding-bottom:8px"><h1>Your shopping bag</h1><div class="meta"><span class="cnt"><b id="cartItemCount">4</b> items from <b id="cartSellerCount">3</b> artisans</span></div></div>
      <div class="cart-layout">
        <div id="cartList"></div>
        <div class="summary" id="cartSummary"></div>
      </div>
    </div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){renderCart();});
</script>
@endpush
