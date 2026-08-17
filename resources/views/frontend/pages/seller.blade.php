@extends('frontend.layout.app')

@section('title', 'Artisan Storefront — Arthubly')

@section('content')
@verbatim
  <section class="page active" id="page-seller">
    <div class="shop-banner"><div class="art" id="shopBannerArt"></div></div>
    <div class="wrap">
      <div class="shop-header dark" id="shopHeader"></div>
      <div class="shop-stats-bar" id="shopStats"></div>
      <div class="shop-about" id="shopAbout"></div>
      <div class="shop-tabs" id="shopTabs"></div>
      <div class="grid cols-4" id="shopGrid" style="padding-bottom:60px"></div>
    </div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){renderSeller(@json($id));});
</script>
@endpush
