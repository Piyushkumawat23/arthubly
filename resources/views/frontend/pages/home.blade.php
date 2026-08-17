@extends('frontend.layout.app')

@section('title', 'Arthubly — Handmade & Handicraft Marketplace')

@section('content')
@verbatim
  <section class="page active home-v2" id="page-home">

    <!-- HERO (full-bleed) -->
    <div class="hero">
      <div class="slides" id="heroSlides"></div>
      <div class="hero-dots" id="heroDots"></div>
      <div class="hero-arrows">
        <button id="heroPrev" aria-label="Previous slide"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
        <button id="heroNext" aria-label="Next slide"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></button>
      </div>
    </div>
    <div class="hero-stats"><div class="wrap" id="heroStats"></div></div>

    <!-- 01 CATEGORIES: editorial mosaic (asymmetric) -->
    <section class="ed ed-cats">
      <div class="wrap">
        <div class="ed-head ed-head--split">
          <div class="lhs">
            <span class="idx">01</span>
            <span class="eyebrow">Shop by craft</span>
            <h2 class="ed-title">Every category,<br><em>hand-selected</em></h2>
          </div>
          <div class="rhs">
            <p>From canvas to clay, discover work made by hand across a dozen living craft traditions.</p>
            <a class="sec-link" data-nav="listing">All categories <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
          </div>
        </div>
        <div class="cat-mosaic" id="catGrid"></div>
      </div>
    </section>

    <!-- 02 TRENDING: offset shelf -->
    <section class="ed ed-shelf">
      <div class="shelf-head wrap">
        <div class="lhs">
          <span class="idx">02</span>
          <span class="eyebrow">Loved this week</span>
          <h2 class="ed-title">Trending now</h2>
        </div>
        <div class="carousel-ctrl" data-carousel="trending">
          <button class="c-prev" aria-label="Scroll left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
          <button class="c-next" aria-label="Scroll right"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></button>
        </div>
      </div>
      <div class="shelf-rail"><div class="hscroll bleed-rail" id="trendingScroll"></div></div>
    </section>

    <!-- 03 SPLIT STORY (full-bleed dark, image + text) -->
    <section class="split split-dark" id="storyBand"></section>

    <!-- 04 AI DISCOVERY: floating panel -->
    <section class="ed ed-ai">
      <div class="wrap">
        <div class="ai-block">
          <div class="warp"></div>
          <div class="ai-inner">
            <div class="ai-top"><span class="ai-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1M7.7 16.3l-2.1 2.1"/></svg> Arthubly AI</span></div>
            <h2>Find something <em>made for you</em></h2>
            <p class="lede">Describe the piece you're imagining — the room, the mood, the budget — and let our AI curate from thousands of one-of-a-kind works.</p>
            <div class="ai-search-wrap">
              <div class="ai-input">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3m0 12v3M3 12h3m12 0h3"/><circle cx="12" cy="12" r="3.2"/></svg>
                <input id="aiHomeInput" type="text" placeholder="I want a handmade wall painting for a modern living room under 15,000...">
                <button class="go-ai" id="aiHomeGo">Discover <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></button>
              </div>
              <div class="ai-prompts" id="aiPrompts"><span>Try:</span></div>
            </div>
            <div class="ai-features" id="aiFeatures"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- 05 STYLES: full-bleed portrait rail -->
    <section class="ed ed-styles">
      <div class="wrap ed-head ed-head--center">
        <span class="idx">03</span>
        <span class="eyebrow">Curated by mood</span>
        <h2 class="ed-title">Shop by <em>style</em></h2>
      </div>
      <div class="style-rail"><div class="style-scroll bleed-rail" id="styleGrid"></div></div>
    </section>

    <!-- 06 PULL QUOTE (editorial full-width) -->
    <section class="pullquote" id="pullQuote"></section>

    <!-- 07 MAKERS -->
    <section class="ed ed-makers">
      <div class="wrap ed-head ed-head--split">
        <div class="lhs">
          <span class="idx">04</span>
          <span class="eyebrow">People behind the craft</span>
          <h2 class="ed-title">Meet the <em>makers</em></h2>
        </div>
        <div class="rhs">
          <p>Each piece carries a name. Follow the artisans whose hands and heritage shape our marketplace.</p>
          <a class="sec-link" data-nav="seller">Discover artisans <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
        </div>
      </div>
      <div class="maker-grid" id="makerGrid"></div>
    </section>

    <!-- 08 COLLECTIONS: magazine collage -->
    <section class="ed ed-coll">
      <div class="wrap ed-head ed-head--center">
        <span class="idx">05</span>
        <span class="eyebrow">Curated collections</span>
        <h2 class="ed-title">Stories worth <em>collecting</em></h2>
      </div>
      <div class="wrap"><div class="coll-collage" id="collGrid"></div></div>
    </section>

    <!-- 09 NEW ARRIVALS: offset shelf (mirrored) -->
    <section class="ed ed-shelf ed-shelf--alt">
      <div class="shelf-head wrap">
        <div class="lhs">
          <span class="idx">06</span>
          <span class="eyebrow">Straight from the studio</span>
          <h2 class="ed-title">New arrivals</h2>
        </div>
        <div class="carousel-ctrl" data-carousel="new">
          <button class="c-prev" aria-label="Scroll left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
          <button class="c-next" aria-label="Scroll right"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></button>
        </div>
      </div>
      <div class="shelf-rail"><div class="hscroll bleed-rail" id="newScroll"></div></div>
    </section>

    <!-- TRUST band -->
    <div class="trust"><div class="wrap" id="trustBar"></div></div>
  </section>
@endverbatim
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){renderHome();if(typeof renderHomeExtras==='function')renderHomeExtras();});
</script>
@endpush