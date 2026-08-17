/* ===================== ARTHUBLY — design interactions only =====================
   No data / no SPA routing. Safe to load alongside jQuery/Bootstrap.
   Handles: scroll-reveal, stat count-up, paper grain, sticky-header shadow,
            mobile menu drawer, back-to-top, nav dropdown tap-to-open. All progressive-enhancement. */
(function(){
  'use strict';
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- stat count-up ---- */
  function countUp(el){
    if(el.dataset.counted) return; el.dataset.counted='1';
    var raw=el.textContent.trim();
    var m=raw.match(/^([^\d]*)([\d,]*\.?\d+)(.*)$/); if(!m) return;
    var pre=m[1], numStr=m[2], suf=m[3];
    var hasComma=numStr.indexOf(',')>=0, dec=(numStr.split('.')[1]||'').length;
    var target=parseFloat(numStr.replace(/,/g,'')); if(isNaN(target)) return;
    var dur=1100,t0=null;
    function fmt(v){var s=dec?v.toFixed(dec):String(Math.round(v)); if(hasComma)s=Number(dec?v.toFixed(dec):Math.round(v)).toLocaleString('en-US'); return pre+s+suf;}
    function step(ts){if(!t0)t0=ts;var p=Math.min((ts-t0)/dur,1);var e=1-Math.pow(1-p,3);el.textContent=fmt(target*e);if(p<1)requestAnimationFrame(step);else el.textContent=pre+numStr+suf;}
    requestAnimationFrame(step);
  }

  /* ---- scroll reveal ---- */
  function initReveal(){
    if(!document.querySelector('.grain')){var g=document.createElement('div');g.className='grain';document.body.appendChild(g);}
    if(reduce){document.querySelectorAll('[data-countup] b,.js-count').forEach(countUp);return;}
    var blocks=['.reveal','.ed-head','.sec-head','.section-block'];
    var groups=['.reveal-group','[data-reveal-group]'];
    if(!('IntersectionObserver' in window)){
      document.querySelectorAll(blocks.concat(groups).join(',')).forEach(function(e){e.classList.add('in');});
      document.querySelectorAll('[data-countup] b,.js-count').forEach(countUp);return;
    }
    var io=new IntersectionObserver(function(ents){ents.forEach(function(en){if(!en.isIntersecting)return;
      en.target.classList.add('in');
      if(en.target.hasAttribute('data-countup'))en.target.querySelectorAll('b,.js-count').forEach(countUp);
      io.unobserve(en.target);});},{rootMargin:'0px 0px -8% 0px',threshold:.08});
    document.querySelectorAll(blocks.join(',')).forEach(function(e){e.classList.add('reveal');io.observe(e);});
    document.querySelectorAll(groups.join(',')).forEach(function(e){e.classList.add('reveal-group');io.observe(e);});
  }

  /* ---- sticky header shadow on scroll ---- */
  function initHeader(){
    var h=document.querySelector('.site-header'); if(!h)return;
    var on=function(){h.classList.toggle('scrolled',window.scrollY>10);}; on();
    window.addEventListener('scroll',on,{passive:true});
  }

  /* ---- mobile menu drawer ---- */
  function initMobileMenu(){
    var drawer=document.getElementById('mobileDrawer');
    var open=document.querySelectorAll('[data-open-menu]');
    var close=document.querySelectorAll('[data-close-menu]');
    if(!drawer)return;
    function set(o){drawer.classList.toggle('open',o);document.body.classList.toggle('menu-lock',o);}
    open.forEach(function(b){b.addEventListener('click',function(e){e.preventDefault();set(true);});});
    close.forEach(function(b){b.addEventListener('click',function(e){e.preventDefault();set(false);});});
    // expandable sub-menus
    drawer.querySelectorAll('.mm-has>.mm-row .mm-toggle').forEach(function(t){
      t.addEventListener('click',function(e){e.preventDefault();var li=t.closest('.mm-has');li.classList.toggle('exp');});
    });
  }

  /* ---- back to top ---- */
  function initTop(){
    var btn=document.getElementById('backTop'); if(!btn)return;
    var on=function(){btn.classList.toggle('show',window.scrollY>500);};on();
    window.addEventListener('scroll',on,{passive:true});
    btn.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});
  }

  /* ---- pill-nav dropdown: tap-to-open below 1080px ---- */
  function initNavDropdown(){
    var nav=document.querySelector('.a-nav'); if(!nav)return;

    nav.addEventListener('click',function(e){
      var caret=e.target.closest('.caret'); if(!caret)return;
      var li=caret.closest('li'); if(!li||!li.querySelector(':scope > ul'))return;

      e.preventDefault();
      e.stopPropagation();

      var isOpen=li.classList.contains('open');
      var parentList=li.parentElement;
      parentList.querySelectorAll(':scope > li.open').forEach(function(sib){
        if(sib!==li){
          sib.classList.remove('open');
          sib.querySelectorAll('li.open').forEach(function(n){n.classList.remove('open');});
        }
      });

      li.classList.toggle('open',!isOpen);
      if(isOpen) li.querySelectorAll('li.open').forEach(function(n){n.classList.remove('open');});
    });

    document.addEventListener('click',function(e){
      if(e.target.closest('.a-nav'))return;
      nav.querySelectorAll('li.open').forEach(function(li){li.classList.remove('open');});
    });
  }
  /* ---- custom sort dropdown ---- */
  function initSortDropdown(){
    var box = document.getElementById('sortBox');
    if(!box) return;
    var list = box.querySelector('.sel-list');
    var hidden = document.getElementById('sortValue');
    var form = document.getElementById('sortForm');
    if(!list || !hidden || !form) return; // markup incomplete on this page — bail safely

    function close(){ box.classList.remove('open'); box.setAttribute('aria-expanded','false'); }
    function open(){ box.classList.add('open'); box.setAttribute('aria-expanded','true'); }

    box.addEventListener('click', function(e){
      if(e.target.closest('li')) return;
      box.classList.contains('open') ? close() : open();
    });

    list.querySelectorAll('li').forEach(function(li){
      li.addEventListener('click', function(){
        hidden.value = li.dataset.value;
        form.submit();
      });
    });

    box.addEventListener('keydown', function(e){
      if(e.key === 'Enter' || e.key === ' '){ e.preventDefault(); box.classList.contains('open') ? close() : open(); }
      if(e.key === 'Escape') close();
    });

    document.addEventListener('click', function(e){
      if(!box.contains(e.target)) close();
    });
  }
  /* ---- live search dropdown (.suggest) ---- */
  function initLiveSearch(){
    var input = document.getElementById('q');
    var box = document.getElementById('suggestBox');
    if(!input || !box) return;

    var resultsEl = document.getElementById('suggestProducts');
    var aiRow = document.getElementById('suggestAiRow');
    var timer = null;

    function render(products){
      if(!products || !products.length){
        resultsEl.innerHTML = '<p style="padding:8px 10px;color:var(--ink-50);font-size:13px">No matches yet — try a different word.</p>';
        return;
      }
      resultsEl.innerHTML = products.map(function(p){
        var img = (p.variations && p.variations[0] && p.variations[0].image)
          ? '/public/uploads/products/variations/thumbs/' + p.variations[0].image
          : '/public/uploads/products/no-image.png';
        var price = p.variations && p.variations[0] ? p.variations[0].price : p.price;
        var cat = p.category ? p.category.name : '';
        return '<a class="row" href="/product/'+p.slug+'">' +
          '<span class="thumb"><img src="'+img+'" alt="" style="width:100%;height:100%;object-fit:cover"></span>' +
          '<span class="m"><b>'+p.name+'</b><small>'+cat+'</small></span>' +
          '<span class="price">₹'+Number(price).toLocaleString('en-IN')+'</span>' +
          '</a>';
      }).join('');
    }

    function fetchResults(q){
      fetch('{{ route("product.search") }}?q=' + encodeURIComponent(q) + '&live_search=1')
        .then(function(r){ return r.json(); })
        .then(render)
        .catch(function(){ resultsEl.innerHTML = ''; });
    }

    input.addEventListener('input', function(){
      var q = input.value.trim();
      box.classList.add('show');
      clearTimeout(timer);
      if(q.length < 2){ resultsEl.innerHTML=''; return; }
      timer = setTimeout(function(){ fetchResults(q); }, 250);
    });

    input.addEventListener('focus', function(){ box.classList.add('show'); });

    document.addEventListener('click', function(e){
      if(!box.contains(e.target) && e.target !== input) box.classList.remove('show');
    });

    box.querySelectorAll('.suggest-chip').forEach(function(chip){
      chip.addEventListener('click', function(){
        window.location.href = '/category/' + chip.dataset.cat;
      });
    });

    if(aiRow){
      aiRow.addEventListener('click', function(){
        box.classList.remove('show');
        openAi(input.value.trim());
      });
    }
  }

  /* ---- Ask AI modal ---- */
  var aiOverlayEl;
  function openAi(prefill){
    if(!aiOverlayEl) return;
    aiOverlayEl.classList.add('show');
    var aiInput = document.getElementById('aiModalInput');
    if(aiInput && prefill) aiInput.value = prefill;
  }
  function initAiModal(){
    aiOverlayEl = document.getElementById('aiOverlay');
    var openBtn = document.getElementById('openAiSearch');
    if(!aiOverlayEl || !openBtn) return;

    openBtn.addEventListener('click', function(){ openAi(); });

    aiOverlayEl.querySelectorAll('[data-close]').forEach(function(b){
      b.addEventListener('click', function(){ aiOverlayEl.classList.remove('show'); });
    });
    aiOverlayEl.addEventListener('click', function(e){
      if(e.target === aiOverlayEl) aiOverlayEl.classList.remove('show');
    });

    aiOverlayEl.querySelectorAll('.ai-prompts button').forEach(function(btn){
      btn.addEventListener('click', function(){
        document.getElementById('aiModalInput').value = btn.textContent;
      });
    });
  }

  /* ---- see it on your wall: room preview ---- */
  function initWallPreview(){
    var overlay = document.getElementById('wvOverlay');
    var openers = document.querySelectorAll('[data-wv-open]');
    if(!overlay || !openers.length) return;

    var stage   = document.getElementById('wvStage');
    var roomImg = document.getElementById('wvRoom');
    var video   = document.getElementById('wvLive');
    var art     = document.getElementById('wvArt');
    var artImg  = document.getElementById('wvArtImg');
    var artBox  = document.getElementById('wvArtBox');
    var fileIn  = document.getElementById('wvFile');
    var sizeIn  = document.getElementById('wvSize');
    var turnIn  = document.getElementById('wvTurn');
    var yawIn   = document.getElementById('wvYaw');
    var sizeOut = document.getElementById('wvSizeOut');
    var turnOut = document.getElementById('wvTurnOut');
    var yawOut  = document.getElementById('wvYawOut');
    var frameIn = document.getElementById('wvFrame');
    var blendIn = document.getElementById('wvBlend');
    var saveBtn = document.getElementById('wvSave');
    var resetBtn= document.getElementById('wvReset');
    var camBtns = document.querySelectorAll('[data-wv-cam]');
    var freezeBtn = document.getElementById('wvFreeze');
    var errEl   = document.getElementById('wvError');
    var errText = document.getElementById('wvErrorText');
    if(!stage || !art || !artImg || !fileIn) return;

    /* stage is assumed to span roughly 3 m of wall, so size reads in real units */
    var WALL_CM = 300;
    var TILT = 45;
    var start = { x:50, y:44, w:26, rot:0, yaw:0 };
    var s = { x:start.x, y:start.y, w:start.w, rot:start.rot, yaw:start.yaw };

    function apply(){
      art.style.left = s.x + '%';
      art.style.top = s.y + '%';
      art.style.width = s.w + '%';
      art.style.transform = 'translate(-50%,-50%) rotate(' + s.rot + 'deg)';
      art.style.setProperty('--wv-yaw', s.yaw + 'deg');

      /* shadow blur and offset scale with how big the piece reads on screen,
         and the offset leans opposite the wall angle so it stays plausible */
      var px = s.w / 100 * (stage.clientWidth || 800);
      art.style.setProperty('--wv-blur', Math.max(5, px * .035).toFixed(1) + 'px');
      art.style.setProperty('--wv-sx', (1.6 - s.yaw * .05).toFixed(2) + '%');
      art.style.setProperty('--wv-sy', '3.2%');

      if(sizeIn) sizeIn.value = s.w;
      if(turnIn) turnIn.value = s.rot;
      if(yawIn) yawIn.value = s.yaw;
      if(sizeOut) sizeOut.textContent = Math.round(s.w / 100 * WALL_CM) + ' cm wide';
      if(turnOut) turnOut.textContent = Math.round(s.rot) + '\u00B0';
      if(yawOut) yawOut.textContent = Math.round(s.yaw) + '\u00B0';
    }

    function reset(){
      s.x=start.x; s.y=start.y; s.w=start.w; s.rot=start.rot; s.yaw=start.yaw;
      apply();
    }

    function hasView(){ return stage.classList.contains('has-room') || stage.classList.contains('has-live'); }

    function setSaveState(){
      if(saveBtn) saveBtn.disabled = !hasView();
      if(freezeBtn) freezeBtn.hidden = !stage.classList.contains('has-live');
    }

    function showError(msg){
      if(!errEl || !errText) return;
      errText.textContent = msg;
      errEl.classList.add('show');
    }
    function clearError(){ if(errEl) errEl.classList.remove('show'); }

    function open(){
      /* always preview whatever image the gallery is currently showing */
      var current = document.getElementById('product-zoom');
      var src = (current && current.getAttribute('src')) || art.getAttribute('data-src');
      if(src){
        trimEdges(src, function(clean){
          if(artImg.getAttribute('src') !== clean){
            artImg.onload = function(){ regrade(); };
            artImg.setAttribute('src', clean);
          }
        });
      }
      overlay.classList.add('show');
      document.body.classList.add('menu-lock');
      setSaveState();
      apply();
      regrade();
      if(stage.classList.contains('has-live')) startLoop();
    }

    function close(){
      stopCam();
      stopLoop();
      overlay.classList.remove('show');
      document.body.classList.remove('menu-lock');
    }

    openers.forEach(function(b){ b.addEventListener('click', function(e){ e.preventDefault(); open(); }); });
    overlay.querySelectorAll('[data-wv-close]').forEach(function(b){ b.addEventListener('click', close); });
    overlay.addEventListener('click', function(e){ if(e.target === overlay) close(); });
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape' && overlay.classList.contains('show')) close(); });

    /* ---- room photo ---- */
    fileIn.addEventListener('change', function(){
      var f = fileIn.files && fileIn.files[0];
      if(!f) return;
      var rd = new FileReader();
      rd.onload = function(ev){
        stopCam();
        clearError();
        roomImg.onload = function(){ regrade(); };
        roomImg.src = ev.target.result;
        stage.classList.add('has-room');
        reset();
        setSaveState();
      };
      rd.readAsDataURL(f);
    });

    /* ---- match the room's light ----
       A studio product shot is brighter, sharper and more saturated than any
       camera feed, which is exactly what makes it read as pasted on. Sample the
       room, then grade the piece down to the same light. */
    var sampleCv = document.createElement('canvas');
    sampleCv.width = 24; sampleCv.height = 18;
    var sctx = null;
    try{ sctx = sampleCv.getContext('2d', { willReadFrequently:true }); }catch(e){ sctx = sampleCv.getContext('2d'); }
    var rafId = null, lastAt = 0;

    function grade(src){
      if(!sctx || !src) return;
      var iw = src.naturalWidth || src.videoWidth, ih = src.naturalHeight || src.videoHeight;
      if(!iw || !ih) return;
      var d;
      try{
        sctx.drawImage(src, 0, 0, 24, 18);
        d = sctx.getImageData(0, 0, 24, 18).data;
      }catch(err){ return; }

      var r=0, g=0, b=0, n=d.length/4;
      for(var i=0;i<d.length;i+=4){ r+=d[i]; g+=d[i+1]; b+=d[i+2]; }
      r/=n; g/=n; b/=n;

      var luma = (0.2126*r + 0.7152*g + 0.0722*b) / 255;
      var mx = Math.max(r, g, b) || 1;

      /* dim rooms pull the piece down; bright rooms leave it near full */
      var bright = Math.max(.52, Math.min(1.08, 0.50 + luma * .78));
      var sat = Math.max(.72, Math.min(1, 0.70 + luma * .34));
      var contrast = 0.94;
      var blurPx = Math.min(.75, Math.max(.15, (s.w / 100 * (stage.clientWidth || 800)) * .0016));

      art.style.setProperty('--wv-filter',
        'brightness(' + bright.toFixed(3) + ') saturate(' + sat.toFixed(3) + ') contrast(' + contrast + ') blur(' + blurPx.toFixed(2) + 'px)');
      art.style.setProperty('--wv-tint', 'rgb(' + Math.round(255*r/mx) + ',' + Math.round(255*g/mx) + ',' + Math.round(255*b/mx) + ')');
      art.style.setProperty('--wv-tint-a', (0.20 + (1 - luma) * .16).toFixed(3));
      art.style.setProperty('--wv-grain-a', (0.05 + (1 - luma) * .10).toFixed(3));
      art.style.setProperty('--wv-sheen-a', (0.25 + luma * .45).toFixed(3));
      art.style.setProperty('--wv-shadow-a', (0.24 + luma * .30).toFixed(3));
    }

    function ungrade(){
      art.style.setProperty('--wv-filter', 'none');
      art.style.setProperty('--wv-tint-a', '0');
      art.style.setProperty('--wv-grain-a', '0');
      art.style.setProperty('--wv-sheen-a', '0');
      art.style.setProperty('--wv-shadow-a', '.38');
    }

    function blendOn(){ return !blendIn || blendIn.checked; }

    function regrade(){
      if(!blendOn()){ ungrade(); return; }
      if(stage.classList.contains('has-live')) grade(video);
      else if(stage.classList.contains('has-room')) grade(roomImg);
    }

    /* live feed changes as the phone moves, so keep re-sampling while it runs */
    function loop(ts){
      rafId = requestAnimationFrame(loop);
      if(!stage.classList.contains('has-live') || !blendOn()) return;
      if(ts - lastAt < 250) return;
      lastAt = ts;
      grade(video);
    }
    function startLoop(){ if(rafId == null) rafId = requestAnimationFrame(loop); }
    function stopLoop(){ if(rafId != null){ cancelAnimationFrame(rafId); rafId = null; } }

    if(blendIn) blendIn.addEventListener('change', regrade);

    /* ---- trim the studio background ----
       Product shots carry a white/cream margin baked in. On a wall that reads as
       a sheet of paper taped up, so crop back to the artwork itself. */
    var trimCache = {};

    function trimEdges(src, done){
      if(trimCache[src]){ done(trimCache[src]); return; }
      var im = new Image();
      im.onload = function(){
        try{
          var W = Math.min(360, im.naturalWidth || 360);
          var H = Math.max(1, Math.round((im.naturalHeight || W) * (W / (im.naturalWidth || W))));
          var c = document.createElement('canvas');
          c.width = W; c.height = H;
          var x = c.getContext('2d', { willReadFrequently:true });
          x.drawImage(im, 0, 0, W, H);
          var data = x.getImageData(0, 0, W, H).data;

          var px = function(a, b){ var i = (b*W + a)*4; return [data[i], data[i+1], data[i+2]]; };
          var ref = px(0, 0);
          /* only trim when the corner really is a flat, light margin */
          if((ref[0]+ref[1]+ref[2])/3 < 190){ trimCache[src] = src; done(src); return; }
          var THR = 30;
          var off = function(p){ return Math.abs(p[0]-ref[0]) + Math.abs(p[1]-ref[1]) + Math.abs(p[2]-ref[2]); };
          var rowFlat = function(y){ for(var a=0;a<W;a+=2){ if(off(px(a,y)) > THR) return false; } return true; };
          var colFlat = function(a){ for(var y=0;y<H;y+=2){ if(off(px(a,y)) > THR) return false; } return true; };

          var t=0, b=H-1, l=0, r=W-1;
          while(t < b && rowFlat(t)) t++;
          while(b > t && rowFlat(b)) b--;
          while(l < r && colFlat(l)) l++;
          while(r > l && colFlat(r)) r--;

          var cw = r-l+1, chh = b-t+1;
          /* bail if it found nothing, or would eat the picture */
          if(cw < W*.3 || chh < H*.3 || (cw > W*.96 && chh > H*.96)){ trimCache[src] = src; done(src); return; }

          var nw = im.naturalWidth, nh = im.naturalHeight;
          var sx = l/W*nw, sy = t/H*nh, sw = cw/W*nw, sh = chh/H*nh;
          var o = document.createElement('canvas');
          o.width = Math.round(sw); o.height = Math.round(sh);
          o.getContext('2d').drawImage(im, sx, sy, sw, sh, 0, 0, o.width, o.height);
          var out = o.toDataURL('image/png');
          trimCache[src] = out;
          done(out);
        }catch(err){ trimCache[src] = src; done(src); }
      };
      im.onerror = function(){ done(src); };
      im.src = src;
    }

    /* ---- live camera ---- */
    var stream = null;

    function stopCam(){
      if(stream){ stream.getTracks().forEach(function(t){ t.stop(); }); stream = null; }
      if(video) video.srcObject = null;
      stage.classList.remove('has-live');
      setSaveState();
    }

    function startCam(){
      clearError();
      if(!video) return;
      if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){
        showError('This browser can\u2019t open the camera. Upload a photo of your wall instead.');
        return;
      }
      if(window.isSecureContext === false){
        showError('The camera only works over a secure (https) connection. Upload a photo of your wall instead.');
        return;
      }
      navigator.mediaDevices.getUserMedia({
        video: { facingMode: { ideal: 'environment' }, width: { ideal: 1920 } },
        audio: false
      }).then(function(st){
        stopCam();
        stream = st;
        video.srcObject = st;
        var p = video.play();
        if(p && p.catch) p.catch(function(){});
        stage.classList.remove('has-room');
        stage.classList.add('has-live');
        reset();
        setSaveState();
        startLoop();
      }).catch(function(err){
        var name = err && err.name;
        if(name === 'NotAllowedError' || name === 'SecurityError'){
          showError('Camera access is blocked. Allow it in your browser\u2019s address bar, then try again.');
        } else if(name === 'NotFoundError' || name === 'OverconstrainedError'){
          showError('No camera found on this device. Upload a photo of your wall instead.');
        } else {
          showError('The camera didn\u2019t start. Upload a photo of your wall instead.');
        }
      });
    }

    camBtns.forEach(function(b){ b.addEventListener('click', function(e){ e.preventDefault(); startCam(); }); });

    /* freeze the live frame so the piece can be placed on a still image */
    if(freezeBtn) freezeBtn.addEventListener('click', function(){
      if(!stage.classList.contains('has-live') || !video.videoWidth) return;
      var cv = document.createElement('canvas');
      cv.width = video.videoWidth; cv.height = video.videoHeight;
      cv.getContext('2d').drawImage(video, 0, 0);
      roomImg.onload = function(){ regrade(); };
      roomImg.src = cv.toDataURL('image/jpeg', .92);
      stopCam();
      stage.classList.add('has-room');
      setSaveState();
    });

    /* ---- sliders ---- */
    if(sizeIn) sizeIn.addEventListener('input', function(){ s.w = parseFloat(sizeIn.value); apply(); regrade(); });
    if(turnIn) turnIn.addEventListener('input', function(){ s.rot = parseFloat(turnIn.value); apply(); });
    if(yawIn) yawIn.addEventListener('input', function(){ s.yaw = parseFloat(yawIn.value); apply(); });
    /* ---- frame + mount ---- */
    var frameBtns = document.querySelectorAll('[data-wv-frame]');
    var matIn = document.getElementById('wvMat');
    var matEl = document.querySelector('.wv-mat');
    var frameKind = 'gold';

    function applyFrame(){
      artBox.classList.remove('f-none','f-black','f-oak','f-gold');
      artBox.classList.add('f-' + frameKind);
      artBox.classList.toggle('has-frame', frameKind !== 'none');
      if(matEl) matEl.classList.toggle('on', !!(matIn && matIn.checked));
      frameBtns.forEach(function(b){
        b.classList.toggle('is-on', b.dataset.wvFrame === frameKind);
      });
    }

    frameBtns.forEach(function(b){
      b.addEventListener('click', function(e){
        e.preventDefault();
        frameKind = b.dataset.wvFrame;
        applyFrame();
      });
    });
    if(matIn) matIn.addEventListener('change', applyFrame);
    applyFrame();
    if(resetBtn) resetBtn.addEventListener('click', reset);

    /* ---- drag / resize / rotate (pointer events = mouse + touch) ---- */
    var mode = null, orig = null;

    function centerPx(){
      var r = stage.getBoundingClientRect();
      return { x: r.left + s.x/100*r.width, y: r.top + s.y/100*r.height, r: r };
    }

    art.addEventListener('pointerdown', function(e){
      var grip = e.target.closest('.wv-grip');
      mode = grip ? grip.dataset.wvGrip : 'move';
      var c = centerPx();
      orig = {
        px: e.clientX, py: e.clientY,
        x: s.x, y: s.y, w: s.w, rot: s.rot,
        cx: c.x, cy: c.y, sw: c.r.width, sh: c.r.height,
        dist: Math.hypot(e.clientX - c.x, e.clientY - c.y),
        ang: Math.atan2(e.clientY - c.y, e.clientX - c.x) * 180 / Math.PI
      };
      art.classList.add('active');
      if(mode === 'move') art.classList.add('dragging');
      art.setPointerCapture(e.pointerId);
      e.preventDefault();
    });

    art.addEventListener('pointermove', function(e){
      if(!mode || !orig) return;
      if(mode === 'move'){
        s.x = Math.min(100, Math.max(0, orig.x + (e.clientX - orig.px) / orig.sw * 100));
        s.y = Math.min(100, Math.max(0, orig.y + (e.clientY - orig.py) / orig.sh * 100));
      } else if(mode === 'size'){
        var d = Math.hypot(e.clientX - orig.cx, e.clientY - orig.cy);
        if(orig.dist > 4) s.w = Math.min(92, Math.max(6, orig.w * (d / orig.dist)));
      } else if(mode === 'turn'){
        var a = Math.atan2(e.clientY - orig.cy, e.clientX - orig.cx) * 180 / Math.PI;
        var next = orig.rot + (a - orig.ang);
        s.rot = Math.max(-TILT, Math.min(TILT, next));
      }
      apply();
    });

    function endDrag(e){
      mode = null; orig = null;
      art.classList.remove('dragging');
      if(e && e.pointerId != null && art.hasPointerCapture && art.hasPointerCapture(e.pointerId)){
        art.releasePointerCapture(e.pointerId);
      }
    }
    art.addEventListener('pointerup', endDrag);
    art.addEventListener('pointercancel', endDrag);
    stage.addEventListener('pointerdown', function(e){ if(!e.target.closest('.wv-art')) art.classList.remove('active'); });

    /* ---- save a flat image of the preview ---- */
    function drawCover(ctx, src, w, h){
      var iw = src.naturalWidth || src.videoWidth || w;
      var ih = src.naturalHeight || src.videoHeight || h;
      var sc = Math.max(w / iw, h / ih);
      var dw = iw * sc, dh = ih * sc;
      ctx.drawImage(src, (w - dw) / 2, (h - dh) / 2, dw, dh);
    }

    if(saveBtn) saveBtn.addEventListener('click', function(){
      if(!hasView()) return;
      var source = stage.classList.contains('has-live') ? video : roomImg;
      try{
        var r = stage.getBoundingClientRect();
        var cw = 1400, ch = Math.round(cw * (r.height / r.width));
        var cv = document.createElement('canvas');
        cv.width = cw; cv.height = ch;
        var ctx = cv.getContext('2d');
        drawCover(ctx, source, cw, ch);

        var FRAMES = {
          black: ['#2a2f3a', '#0e1117'],
          oak:   ['#C08A4A', '#6E4A1E'],
          gold:  ['#E7CE9A', '#8A5F21']
        };
        var cs = getComputedStyle(art);
        var ratio = (artImg.naturalHeight && artImg.naturalWidth) ? artImg.naturalHeight / artImg.naturalWidth : 1;
        var pad = frameKind !== 'none' ? 0.028 : 0;
        var matPad = (matIn && matIn.checked) ? 0.05 : 0;
        var aw = s.w / 100 * cw, ah = aw * ratio;
        var yawScale = Math.max(.35, Math.cos(s.yaw * Math.PI / 180));

        ctx.save();
        ctx.translate(s.x / 100 * cw, s.y / 100 * ch);
        ctx.rotate(s.rot * Math.PI / 180);
        ctx.scale(yawScale, 1);

        /* cast shadow, matching the preview's offset and softness */
        var shA = parseFloat(cs.getPropertyValue('--wv-shadow-a')) || .38;
        ctx.save();
        ctx.globalAlpha = shA;
        if(ctx.filter !== undefined) ctx.filter = 'blur(' + (aw * .035).toFixed(1) + 'px)';
        ctx.fillStyle = '#05070c';
        var outer = aw*(pad+matPad);
        ctx.fillRect(-aw/2 + aw*.016 - outer, -ah/2 + ah*.032 - outer, aw + outer*2, ah + outer*2);
        ctx.restore();

        if(pad){
          var fc = FRAMES[frameKind] || FRAMES.black;
          var gw = aw/2 + aw*pad;
          var grd = ctx.createLinearGradient(-gw, -ah/2 - aw*pad, gw, ah/2 + aw*pad);
          grd.addColorStop(0, fc[0]); grd.addColorStop(1, fc[1]);
          ctx.fillStyle = grd;
          ctx.fillRect(-aw/2 - aw*(pad+matPad), -ah/2 - aw*(pad+matPad),
                       aw + aw*(pad+matPad)*2, ah + aw*(pad+matPad)*2);
        }
        if(matPad){
          ctx.fillStyle = '#F6F2E9';
          ctx.fillRect(-aw/2 - aw*matPad, -ah/2 - aw*matPad, aw + aw*matPad*2, ah + aw*matPad*2);
        }

        /* same grade the preview is showing */
        var f = (cs.getPropertyValue('--wv-filter') || '').trim();
        if(ctx.filter !== undefined && f && f !== 'none') ctx.filter = f;
        ctx.drawImage(artImg, -aw/2, -ah/2, aw, ah);
        if(ctx.filter !== undefined) ctx.filter = 'none';

        /* ambient colour cast */
        var tintA = parseFloat(cs.getPropertyValue('--wv-tint-a')) || 0;
        var tint = (cs.getPropertyValue('--wv-tint') || '').trim();
        if(tintA > 0 && tint){
          ctx.globalCompositeOperation = 'multiply';
          ctx.globalAlpha = tintA;
          ctx.fillStyle = tint;
          ctx.fillRect(-aw/2, -ah/2, aw, ah);
          ctx.globalAlpha = 1;
          ctx.globalCompositeOperation = 'source-over';
        }
        ctx.restore();

        var a = document.createElement('a');
        a.download = 'arthubly-wall-preview.png';
        a.href = cv.toDataURL('image/png');
        a.click();
      }catch(err){
        if(window.console) console.error('[arthubly.js] wall preview save failed:', err);
        alert('This image can\u2019t be saved from the browser. Take a screenshot instead.');
      }
    });

    apply();
  }

  function safe(fn){
    try{ fn(); }
    catch(err){ if(window.console && console.error) console.error('[arthubly.js] '+fn.name+' failed:', err); }
  }
  function boot(){
    safe(initReveal); safe(initHeader); safe(initMobileMenu); safe(initTop);
    safe(initNavDropdown); safe(initSortDropdown); safe(initLiveSearch); safe(initAiModal);
    safe(initWallPreview);
  }
  if(document.readyState!=='loading')boot(); else document.addEventListener('DOMContentLoaded',boot);

})();

/* =========================================================================
   PRODUCT CARD v2 — variations (size chips + finish swatches)
   ========================================================================= */
(function ($) {

    function pcVars($card) {
        try { return JSON.parse($card.attr('data-vars') || '[]'); } catch (e) { return []; }
    }

    function pcMatch(vars, color, size) {
        return vars.find(function (v) {
            var c = !color || (v.color && String(v.color).toLowerCase() === String(color).toLowerCase());
            var s = !size || (v.size && String(v.size).toLowerCase() === String(size).toLowerCase());
            return c && s;
        });
    }

    function pcMoney(n) {
        return '₹' + parseFloat(n || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    /* Ek variation stock me hai ya nahi.
       quantity/stock field aayi hi na ho (null/undefined) to hum use
       "stock pata nahi" maankar available maan lete hain — par blade ab
       hamesha 'quantity' bhejta hai, isliye normally exact check hota hai. */
    function pcInStock(v) {
        if (!v) return false;
        var qty = v.quantity;
        if (qty === undefined || qty === null || qty === '') qty = v.stock;
        if (qty === undefined || qty === null || qty === '') return true;   // unknown
        return parseInt(qty, 10) > 0;
    }

    function pcRefresh($card) {
        var vars    = pcVars($card);
        var color   = $card.find('.color-dot.active').data('color');
        var size    = $card.find('.pc-chip.active').data('size');
        var compare = parseFloat($card.attr('data-compare') || 0);

        var v = pcMatch(vars, color, size) || pcMatch(vars, color, '') || pcMatch(vars, '', size);
        var price = (v && v.price) ? parseFloat(v.price) : parseFloat($card.attr('data-base-price') || 0);

        // price
        $card.find('.pc-price').text(pcMoney(price));

        // discount
        if (compare > price) {
            $card.find('.pc-price-old').text(pcMoney(compare));
            $card.find('.pc-price-off').text(Math.round(((compare - price) / compare) * 100) + '% OFF');
            $card.find('.pc-price-right').show();
        } else {
            $card.find('.pc-price-right').hide();
        }

        // image
        if (v && v.image) $card.find('.pc-img.main').attr('src', v.image);

        // product link me colour add
        if (color) {
            $card.find('.pc-imglink, .pc-title a').each(function () {
                var u = $(this).attr('href');
                if (u && u.indexOf('product/') > -1) {
                    $(this).attr('href', u.split('?')[0] + '?color=' + encodeURIComponent(color));
                }
            });
        }

        // us colour me jo sizes available nahi, unhe disable
        // us colour me jo sizes available nahi ya out of stock hain, unhe disable karein
        // ---- chosen colour me har size ka stock check ----
        $card.find('.pc-chip').each(function () {
            var s = $(this).data('size');
            // sirf exact combination dekhein — warna dusre colour ka stock
            // is size ko galat "available" bana deta hai
            var mv = color ? pcMatch(vars, color, s) : pcMatch(vars, '', s);
            var ok = !!mv && pcInStock(mv);

            $(this).toggleClass('pc-off', !ok);
            if (!ok) $(this).removeClass('active');
        });

        // active size hat gayi? pehli available khud chun lein
        if ($card.find('.pc-chip').length && !$card.find('.pc-chip.active').length) {
            $card.find('.pc-chip').not('.pc-off').first().addClass('active');
        }

        // ---- har colour ka stock check (us colour ki koi bhi variation) ----
        $card.find('.color-dot').each(function () {
            var c = $(this).data('color');
            var anyOk = false;
            for (var i = 0; i < vars.length; i++) {
                if (vars[i].color && String(vars[i].color).toLowerCase() === String(c).toLowerCase() && pcInStock(vars[i])) {
                    anyOk = true;
                    break;
                }
            }
            $(this).toggleClass('pc-off', !anyOk);
            if (!anyOk) $(this).removeClass('active');
        });

        if ($card.find('.color-dot').length && !$card.find('.color-dot.active').length) {
            $card.find('.color-dot').not('.pc-off').first().addClass('active');
        }

        // ---- poora product available hai ya nahi ----
        var $add = $card.find('.pc-add');
        var sizesOut  = $card.find('.pc-chip').length   && !$card.find('.pc-chip').not('.pc-off').length;
        var colorsOut = $card.find('.color-dot').length && !$card.find('.color-dot').not('.pc-off').length;

        // koi variation hi nahi (single piece) — us case me base variation dekhein
        var noVarOut = false;
        if (!$card.find('.pc-chip').length && !$card.find('.color-dot').length && vars.length) {
            noVarOut = !vars.some(pcInStock);
        }

        if (sizesOut || colorsOut || noVarOut) {
            $card.addClass('is-soldout');
            $add.prop('disabled', true);
        } else {
            $card.removeClass('is-soldout');
            $add.prop('disabled', false);
        }
    }

    /* Selected combination ki exact variation — validation ke liye */
    function pcSelectedVar($card) {
        var vars  = pcVars($card);
        var color = $card.find('.color-dot.active').data('color');
        var size  = $card.find('.pc-chip.active').data('size');

        if ($card.find('.color-dot').length && $card.find('.pc-chip').length) {
            return pcMatch(vars, color, size);
        }
        if ($card.find('.color-dot').length) return pcMatch(vars, color, '');
        if ($card.find('.pc-chip').length)   return pcMatch(vars, '', size);
        return vars.length ? vars[0] : null;
    }
    window.pcSelectedVar = pcSelectedVar;
    window.pcInStock = pcInStock;

    // Finish (colour) click
    $(document).on('click', '.pcard-v2 .color-dot', function (e) {
        e.preventDefault();
        if ($(this).hasClass('pc-off')) return;      // out of stock finish — select nahi hoga
        var $card = $(this).closest('.pcard-v2');
        $card.find('.color-dot').removeClass('active');
        $(this).addClass('active');

        // agar current size is colour me nahi hai to pehla available size chun lo
        var vars  = pcVars($card),
            color = $(this).data('color'),
            $size = $card.find('.pc-chip.active');

        if ($size.length && !pcMatch(vars, color, $size.data('size'))) {
            var $ok = $card.find('.pc-chip').filter(function () {
                return !!pcMatch(vars, color, $(this).data('size'));
            }).first();
            $card.find('.pc-chip').removeClass('active');
            $ok.addClass('active');
        }
        pcRefresh($card);
    });

    // Size chip click
    $(document).on('click', '.pcard-v2 .pc-chip', function (e) {
        e.preventDefault();
        if ($(this).hasClass('pc-off')) return;
        var $card = $(this).closest('.pcard-v2');
        $card.find('.pc-chip').removeClass('active');
        $(this).addClass('active');
        pcRefresh($card);
    });

    // page load par ek baar sync
    $(function () {
        $('.pcard-v2').each(function () { pcRefresh($(this)); });
    });

})(jQuery);

/* =========================================================================
   PRODUCT CARD v2 — Add to Cart form sync
   (chip/swatch select karne par hidden color/size update ho jate hain)
   ========================================================================= */
(function ($) {

    function pcSyncCart($card) {
        var color = $card.find('.color-dot.active').data('color');
        var size  = $card.find('.pc-chip.active').data('size');
        if (typeof color !== 'undefined') $card.find('.pc-in-color').val(color);
        if (typeof size  !== 'undefined') $card.find('.pc-in-size').val(size);
    }

    $(document).on('click', '.pcard-v2 .color-dot, .pcard-v2 .pc-chip', function () {
        pcSyncCart($(this).closest('.pcard-v2'));
    });

    $(function () {
        $('.pcard-v2').each(function () { pcSyncCart($(this)); });
    });

    /* -------------------------------------------------------------
       Selection missing? — browser alert() ki jagah shaant inline
       hint + toast. Card ke andar hi group highlight hota hai,
       taaki user ko dikhe kya chunna hai.
       ------------------------------------------------------------- */
    function pcClearHint($card) {
        $card.removeClass('needs-pick');
        $card.find('.pc-group').removeClass('needs-pick');
        $card.find('.pc-hint').remove();
    }

    function pcAskFor($card, $group, message) {
        pcClearHint($card);
        $card.addClass('needs-pick');
        $group.addClass('needs-pick');

        $('<p class="pc-hint" role="status">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">' +
            '<circle cx="12" cy="12" r="9"/><path d="M12 8h.01M12 11v5"/></svg>' +
            '<span></span></p>')
            .find('span').text(message).end()
            .insertAfter($card.find('.pc-vars'));

        if ($group.length && $group[0].scrollIntoView) {
            $group[0].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }

        if (window.abToast) window.abToast(message, 'error');

        setTimeout(function () { pcClearHint($card); }, 4000);
    }

    // chip/swatch chunte hi hint hat jaye
    $(document).on('click', '.pcard-v2 .pc-chip, .pcard-v2 .color-dot', function () {
        pcClearHint($(this).closest('.pcard-v2'));
    });

    // submit se pehle: size/finish chuna hai ya nahi
    $(document).on('submit', '.pc-cartform', function (e) {
        var $card = $(this).closest('.pcard-v2');
        pcSyncCart($card);

        // sab sizes out of stock
        if ($card.find('.pc-chip').length && !$card.find('.pc-chip').not('.pc-off').length) {
            e.preventDefault();
            pcAskFor($card, $card.find('.pc-sizes'), 'Ye piece abhi stock me nahi hai.');
            return false;
        }

        if ($card.find('.pc-chip').length && !$card.find('.pc-chip.active').length) {
            e.preventDefault();
            pcAskFor($card, $card.find('.pc-sizes'), 'Pehle size chunein — phir bag me add ho jayega.');
            return false;
        }

        if ($card.find('.color-dot').length && !$card.find('.color-dot.active').length) {
            e.preventDefault();
            pcAskFor($card, $card.find('.pc-finish'), 'Pehle finish chunein — phir bag me add ho jayega.');
            return false;
        }

        // FINAL GATE — jo combination chuna hai wahi stock me hai ya nahi
        if (typeof window.pcSelectedVar === 'function') {
            var sel = window.pcSelectedVar($card);
            if (!sel || !window.pcInStock(sel)) {
                e.preventDefault();
                pcAskFor($card, $card.find('.pc-sizes, .pc-finish').first(),
                    'Ye option abhi stock me nahi hai — dusra size ya finish chunein.');
                return false;
            }
        }

        if ($card.hasClass('is-soldout')) {
            e.preventDefault();
            pcAskFor($card, $card.find('.pc-vars'), 'Ye piece abhi stock me nahi hai.');
            return false;
        }

        pcClearHint($card);
    });

})(jQuery);