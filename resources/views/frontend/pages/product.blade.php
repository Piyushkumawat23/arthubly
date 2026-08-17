@extends('frontend.layout.app')

@section('title', 'Handmade Piece — Arthubly')

@section('content')
@verbatim
  <section class="page active" id="page-product">
    <div class="wrap">
      <div class="crumbs" id="pdpCrumbs"></div>
      <div class="pdp">
        <div class="pdp-grid">
          <div class="gallery" id="pdpGallery"></div>
          <div class="buybox" id="pdpBuybox"></div>
        </div>
        <div class="story" id="pdpStory"></div>
        <div class="detail-tabs">
          <div class="tab-strip" id="pdpTabStrip"></div>
          <div id="pdpTabBody"></div>
        </div>
        <section class="section tight" style="padding-bottom:0">
          <div class="sec-head"><div class="lhs"><span class="eyebrow">Styled together</span><h2>Complete the look</h2></div></div>
          <div class="grid cols-4" id="completeLook"></div>
        </section>
        <section class="section tight">
          <div class="sec-head">
            <div class="lhs"><span class="eyebrow">You may also like</span><h2>More from this artisan</h2></div>
            <div class="carousel-ctrl" data-carousel="related">
              <button class="c-prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
              <button class="c-next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></button>
            </div>
          </div>
          <div class="hscroll" id="relatedScroll"></div>
        </section>
      </div>
    </div>
    <div class="sticky-buy" id="stickyBuy"></div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){renderProduct(@json((int)$id));});
</script>
@endpush
