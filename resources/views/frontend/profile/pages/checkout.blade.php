@extends('frontend.layout.app')

@section('title', 'Checkout — Arthubly')

@section('content')
@verbatim
  <section class="page active" id="page-checkout">
    <div class="wrap">
      <div class="crumbs"><a data-nav="cart">Bag</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Checkout</span></div>
      <div class="listing-head" style="border:none;padding-bottom:20px"><h1>Checkout</h1></div>
      <div class="checkout-layout">
        <div>
          <div class="steps" id="coSteps"></div>
          <div id="coBody"></div>
        </div>
        <div class="summary" id="coSummary"></div>
      </div>
    </div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){renderCheckout();});
</script>
@endpush
