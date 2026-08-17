@verbatim
<div class="overlay" id="authOverlay">
  <div class="modal auth-modal">
    <button class="modal-close" data-close><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    <div id="authContent"></div>
  </div>
</div>
<div class="overlay" id="qvOverlay">
  <div class="modal qv-modal">
    <button class="modal-close" data-close><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    <div id="qvContent"></div>
  </div>
</div>
<div class="overlay" id="aiOverlay">
  <div class="modal ai-modal">
    <button class="modal-close" data-close><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    <div class="ai-modal-head">
      <span class="ai-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1"/></svg> Arthubly AI Search</span>
      <h2>Describe what you're <em>looking for</em></h2>
    </div>
    <div class="ai-modal-body">
      <div class="ai-input">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input id="aiModalInput" type="text" placeholder="Show handmade blue pottery under ₹5,000…">
        <button class="go-ai" id="aiModalGo">Search</button>
      </div>
      <div class="ai-prompts" id="aiModalPrompts"><span>Try:</span></div>
      <div class="interp" id="aiInterp"></div>
    </div>
  </div>
</div>
<div class="drawer" id="filterDrawer">
  <div class="d-bg" data-close></div>
  <div class="d-panel">
    <div class="d-head"><b>Filters</b><button class="modal-close" style="position:static" data-close><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button></div>
    <div id="drawerFilters"></div>
    <div class="d-foot"><button class="btn btn-ghost" data-close style="flex:1">Clear</button><button class="btn btn-primary" data-close style="flex:2">Show results</button></div>
  </div>
</div>

<div class="toast-wrap" id="toastWrap"></div>
@endverbatim
