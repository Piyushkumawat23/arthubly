@verbatim
<div class="top-strip">
  <div class="wrap">
    <div class="marquee">
      <span id="marqueeText"></span>
    </div>
    <div class="strip-links">
      <a href="#" data-nav="seller">Sell on Arthubly</a>
      <a href="#">Track Order</a>
      <a href="#">Help</a>
      <a href="#">₹ INR</a>
    </div>
  </div>
</div>

<header class="site-header" id="siteHeader">
  <div class="wrap header-main">
    <a class="logo" href="#" data-nav="home" aria-label="Arthubly home">
      <span class="mark" id="logoMark"></span>
      <span>
        <span class="name">Art<b>hubly</b></span>
        <span class="tag">Handmade Marketplace</span>
      </span>
    </a>
    <div class="search">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input id="searchInput" type="text" placeholder="Search handmade crafts, artisans, collections…" autocomplete="off">
        <button class="search-ai" id="openAiSearch">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1M7.7 16.3l-2.1 2.1"/></svg>
          Ask AI
        </button>
      </div>
      <div class="suggest" id="suggestBox"></div>
    </div>
    <div class="header-actions">
      <button class="iconbtn" data-nav="listing" aria-label="Explore" title="Explore"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="m14.5 9.5-1.8 5.2-5.2 1.8 1.8-5.2z"/></svg></button>
      <button class="iconbtn" data-nav="account" aria-label="Wishlist" title="Wishlist"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z"/></svg><span class="dot" id="wishDot">3</span></button>
      <button class="iconbtn" data-nav="cart" aria-label="Shopping bag" title="Shopping bag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg><span class="dot" id="cartDot">4</span></button>
      <button class="acct-btn" id="openAuth">
        <span class="avatar">A</span>
        <span class="who"><small>Account</small><strong>Sign in</strong></span>
      </button>
    </div>
  </div>
  <nav class="catnav">
    <div class="wrap" id="catNav"></div>
  </nav>
</header>
@endverbatim
