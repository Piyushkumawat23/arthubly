@extends('frontend.layout.app')

@section('title', 'Shop Handmade — Arthubly')

@section('content')
@verbatim
  <section class="page active" id="page-listing">
    <div class="wrap">
      <div class="crumbs" id="listCrumbs"></div>
      <div class="listing-head">
        <h1>Handmade Paintings & Wall Art</h1>
        <div class="meta">
          <span class="cnt"><b id="listCount">0</b> handcrafted pieces</span>
          <span class="cnt">·</span>
          <span class="cnt">from <b>340+</b> verified artisans</span>
        </div>
      </div>
      <div class="list-layout">
        <aside class="filters" id="filtersPanel"></aside>
        <div>
          <div class="toolbar">
            <div style="display:flex;gap:12px;align-items:center">
              <button class="btn btn-light btn-sm filter-mobile-btn" id="openFilters">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path d="M4 6h16M7 12h10m-7 6h4"/></svg> Filters
              </button>
              <div class="sel-box" id="sortBox">Sort: <b>Recommended</b> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></div>
            </div>
            <div class="view-toggle">
              <button class="on" aria-label="Grid view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg></button>
              <button aria-label="List view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
            </div>
          </div>
          <div class="chips-active" id="activeChips"></div>
          <div class="grid cols-3" id="listGrid"></div>
          <div class="pagination" id="listPagination"></div>
        </div>
      </div>
    </div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){renderListing({cat:@json($category ?? null),style:@json($style ?? null)});});
</script>
@endpush
