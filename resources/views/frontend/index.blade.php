@extends('frontend.layout.arthubly')

@section('title', 'Arthubly — Handmade & Handicraft Marketplace')

@section('content')

@push('styles')
    {{-- Sirf home page ke liye — baaki pages ke cards par koi asar nahi --}}
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/arthubly-home.css') }}">
@endpush

<section class="page active home-v2" id="page-home">

    {{-- ===== HERO SLIDER (static design; drop real images at public/assets/frontend/images/hero-1..3.jpg) ===== --}}
    <section class="hero-slider" id="heroSlider" aria-label="Featured">
        {{-- Slide 1 --}}
        <div class="hero-slide on">
            <div class="hero-grad" style="background:linear-gradient(120deg,#2c3a33,#20263A 62%,#191E2E)"></div>
            <img class="hero-bg" src="{{ asset('public/assets/frontend/images/banners/banner-1.png') }}" alt="" onerror="this.remove()">
            <div class="grain-lite"></div>
            <div class="hero-content">
                <span class="eyebrow">The maker's marketplace</span>
                <h1>Nothing here was<br><em>made by a machine</em></h1>
                <p>One-of-a-kind pieces from independent artisans — paintings, pottery, textiles, jewellery and more. Every piece carries a maker's name.</p>
                <a href="{{ route('product.categories_list') }}" class="btn btn-brass btn-lg">Explore categories <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
                <div class="hero-artisan">
                    <span class="ha-av"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg></span>
                    <span class="ha-t"><small>Handcrafted by</small><b>Independent artisans</b> <span>across India</span></span>
                </div>
            </div>
        </div>
        {{-- Slide 2 --}}
        <div class="hero-slide">
            <div class="hero-grad" style="background:linear-gradient(120deg,#7a3a2f,#A9772E 58%,#20263A)"></div>
            <img class="hero-bg" src="{{ asset('public/assets/frontend/images/banners/banner-2.png') }}" alt="" onerror="this.remove()">
            <div class="grain-lite"></div>
            <div class="hero-content">
                <span class="eyebrow">Featured craft</span>
                <h1>The colour of <em>indigo</em>,<br>made by hand</h1>
                <p>Sixteen steps, natural dye, and a craft that predates the loom. Meet the Ajrakh masters of Kutch.</p>
                <a href="{{ route('product.categories_list') }}" class="btn btn-brass btn-lg">Explore the craft <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
            </div>
        </div>
        {{-- Slide 3 --}}
        <div class="hero-slide">
            <div class="hero-grad" style="background:linear-gradient(120deg,#8a4a3a,#5E7355 60%,#20263A)"></div>
            <img class="hero-bg" src="{{ asset('public/assets/frontend/images/banners/banner-fullwidth.png') }}" alt="" onerror="this.remove()">
            <div class="grain-lite"></div>
            <div class="hero-content">
                <span class="eyebrow">Straight from the studio</span>
                <h1>Where clay<br><em>remembers</em> the hand</h1>
                <p>Wheel-thrown, wood-fired, glazed by eye. Discover pottery and home décor shaped by makers, not moulds.</p>
                <a href="{{ route('product.categories_list') }}" class="btn btn-brass btn-lg">Shop the collection <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
            </div>
        </div>

        <div class="hero-dots" id="heroDots"></div>
        <div class="hero-arrows">
            <button type="button" id="heroPrev" aria-label="Previous"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg></button>
            <button type="button" id="heroNext" aria-label="Next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></button>
        </div>
    </section>

    {{-- ===== STATS BAR (static design) ===== --}}
    <div class="hero-stats">
        <div class="hs"><span class="hsi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg></span><div><b>12,000+</b><small>Independent artisans</small></div></div>
        <div class="hs"><span class="hsi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg></span><div><b>480,000+</b><small>Handmade pieces</small></div></div>
        <div class="hs"><span class="hsi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 2.6 5.3 5.9.9-4.2 4.1 1 5.8L12 22l-5.3-2.8 1-5.8L3.5 9.2l5.9-.9z"/></svg></span><div><b>4.9 / 5</b><small>Avg. artisan rating</small></div></div>
        <div class="hs"><span class="hsi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><b>60+</b><small>Craft traditions</small></div></div>
    </div>

    {{-- ===== CATEGORIES (real $categories) ===== --}}
    @if(isset($categories) && $categories->count())
    <section class="ed ed-cats">
        <div class="wrap">
            <div class="ed-head ed-head--split">
                <div class="lhs"><span class="idx">01</span><span class="eyebrow">Shop by craft</span><h2 class="ed-title">Every category,<br><em>hand-selected</em></h2></div>
                <div class="rhs"><p>From canvas to clay, discover work made by hand across our living craft traditions.</p><a class="sec-link" href="{{ route('product.categories_list') }}">All categories <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a></div>
            </div>
            <div class="cat-mosaic-page" data-reveal-group>
                @foreach($categories->take(6) as $i => $cat)
                    <a href="{{ route('product.category', $cat->slug) }}" class="cat-tile">
                        <img src="{{ $cat->image ? asset('public/uploads/categories/' . $cat->image) : asset('public/uploads/products/no-image.jpg') }}" alt="{{ $cat->name }}" onerror="this.style.display='none'">
                        <div class="ct-body"><small>Category</small><h3>{{ $cat->name }}</h3><span class="shop">Shop now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span></div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== NEW ARRIVALS (real $newArrivals) ===== --}}
    @if(isset($newArrivals) && $newArrivals->count())
    <section class="ed ed-shelf hm-section" id="hm-new">
        <div class="wrap">
            <div class="ed-head ed-head--split">
                <div class="lhs">
                    <span class="idx">02</span>
                    <span class="eyebrow">Straight from the studio</span>
                    <h2 class="ed-title">New arrivals</h2>
                </div>
                <div class="rhs hm-railnav">
                    <p class="hm-railcount">{{ $newArrivals->count() }} pieces this week</p>
                    <div class="hm-arrows">
                        <button type="button" class="hm-arrow" data-rail-prev aria-label="Previous">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 5l-7 7 7 7"/></svg>
                        </button>
                        <button type="button" class="hm-arrow" data-rail-next aria-label="Next">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- horizontal rail — 4 upar 4 neeche wali grid ki jagah --}}
            <div class="hm-rail" data-rail>
                @foreach($newArrivals->take(8) as $product)
                    <div class="hm-slide" style="--i:{{ $loop->index }}">
                        @include('frontend.partials.arthubly-product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="hm-progress"><span data-rail-bar></span></div>
        </div>
    </section>
    @endif

    {{-- ===== STORY BAND (static design) ===== --}}
    <section class="pullquote">
        <div class="wrap">
            <svg class="pq-mark" viewBox="0 0 24 24" fill="currentColor"><path d="M9 7H5a3 3 0 0 0-3 3v7h7v-7H5a2 2 0 0 1 2-2h2zm10 0h-4a3 3 0 0 0-3 3v7h7v-7h-4a2 2 0 0 1 2-2h2z"/></svg>
            <blockquote>Every piece is created by an independent artisan and checked against our <em>Handmade Verified</em> standard.</blockquote>
        </div>
    </section>

    {{-- ===== ALL PRODUCTS (real $allProducts, paginated) ===== --}}
    @if(isset($allProducts) && $allProducts->count())
    <section class="ed ed-makers hm-section" id="hm-all" style="padding-top:0">
        <div class="wrap">
            <div class="ed-head ed-head--center">
                <span class="idx">03</span>
                <span class="eyebrow">The full catalogue</span>
                <h2 class="ed-title">Made by <em>hand</em></h2>
                <p class="hm-sub">{{ method_exists($allProducts, 'total') ? $allProducts->total() : $allProducts->count() }}
                    pieces, each signed by the artisan who made it.</p>
            </div>

            {{-- staggered mosaic — har card apni line par nahi girta,
                 columns halke offset par chalte hain --}}
            <div class="hm-mosaic">
                @foreach($allProducts as $product)
                    <div class="hm-cell" style="--i:{{ $loop->index }}">
                        @include('frontend.partials.arthubly-product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
            @if(method_exists($allProducts, 'links'))
                <div style="margin-top:40px">{{ $allProducts->links('pagination::bootstrap-4') }}</div>
            @endif
        </div>
    </section>
    @endif

    {{-- ===== TRUST BAND (static design) ===== --}}
    {{-- ===== 04 — MEET THE MAKERS (static design; wire to a makers model later) ===== --}}
    <section class="ed ed-makers" style="background:none">
        <div class="wrap">
            <div class="ed-head ed-head--split">
                <div class="lhs"><span class="idx">04</span><span class="eyebrow">People behind the craft</span><h2 class="ed-title">Meet the makers</h2></div>
                <div class="rhs"><p>Each piece carries a name. Follow the artisans whose hands and heritage shape our marketplace.</p><a class="sec-link" href="{{ route('product.categories_list') }}">Discover artisans <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a></div>
            </div>
            <div class="makers-grid" data-reveal-group>
                @php
                    $__makers = [
                        ['name'=>'Meera Devi','loc'=>'Jaipur, Rajasthan','craft'=>'Blue Pottery','quote'=>'Fourth-generation potter reviving Jaipur\'s cobalt glaze.','pieces'=>'64','followers'=>'12.4k','rating'=>'4.9','g'=>'linear-gradient(135deg,#c9a0a6,#A9772E)'],
                        ['name'=>'Arjun Vishwakarma','loc'=>'Saharanpur, UP','craft'=>'Woodcraft','quote'=>'Carving sheesham heirlooms the way his grandfather taught him.','pieces'=>'48','followers'=>'8.9k','rating'=>'4.8','g'=>'linear-gradient(135deg,#8a4a3a,#5a2f26)'],
                        ['name'=>'Lila Handlooms','loc'=>'Kutch, Gujarat','craft'=>'Handwoven','quote'=>'A women\'s weaving collective keeping Kutchi textile alive.','pieces'=>'92','followers'=>'18.1k','rating'=>'4.9','g'=>'linear-gradient(135deg,#b04a56,#7a2f3a)'],
                        ['name'=>'Kamala Menon','loc'=>'Kochi, Kerala','craft'=>'Metalcraft','quote'=>'Bell-metal and brass, cast by the lost-wax method.','pieces'=>'37','followers'=>'6.2k','rating'=>'4.7','g'=>'linear-gradient(135deg,#c9b9a3,#A9772E)'],
                    ];
                @endphp
                @foreach($__makers as $m)
                    <article class="maker-card">
                        <div class="mc-banner" style="background:{{ $m['g'] }}"><span class="mc-av"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg></span></div>
                        <div class="mc-body">
                            <div class="mc-name">{{ $m['name'] }} <span class="vf" title="Verified artisan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg></span></div>
                            <div class="mc-loc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-5.2-7-11a7 7 0 0 1 14 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg> {{ $m['loc'] }}</div>
                            <span class="mc-craft">{{ $m['craft'] }}</span>
                            <p class="mc-quote">&ldquo;{{ $m['quote'] }}&rdquo;</p>
                            <div class="mc-stats"><div><b>{{ $m['pieces'] }}</b><small>Pieces</small></div><div><b>{{ $m['followers'] }}</b><small>Followers</small></div><div><b>{{ $m['rating'] }}</b><small>Rating</small></div></div>
                            <div class="mc-actions"><a href="javascript:void(0)" class="btn btn-ghost">+ Follow</a><a href="{{ route('product.categories_list') }}" class="btn btn-primary">View Shop</a></div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== 05 — STORIES WORTH COLLECTING (static; links to categories) ===== --}}
    <section class="ed" style="background:none;padding-top:0">
        <div class="wrap">
            <div class="ed-head ed-head--center"><span class="idx">05</span><span class="eyebrow">Curated collections</span><h2 class="ed-title">Stories worth collecting</h2></div>
            <div class="collections-grid" data-reveal-group>
                <a href="{{ route('product.categories_list') }}" class="coll-tile feature"><span style="position:absolute;inset:0;z-index:-2;background:linear-gradient(150deg,#3a3550,#20263A)"></span><div class="cl-body"><small>Editors' edit</small><h3>Made in India</h3><p>A journey through living craft traditions, region by region.</p><span class="explore">Explore collection <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span></div></a>
                <a href="{{ route('product.categories_list') }}" class="coll-tile"><span style="position:absolute;inset:0;z-index:-2;background:linear-gradient(135deg,#8a6a4a,#5E7355)"></span><div class="cl-body"><small>For the space</small><h3>Handcrafted for Your Home</h3><span class="explore">Explore collection <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span></div></a>
                <a href="{{ route('product.categories_list') }}" class="coll-tile"><span style="position:absolute;inset:0;z-index:-2;background:linear-gradient(135deg,#20263A,#3f5a6b)"></span><div class="cl-body"><small>Adorn</small><h3>Artisan Jewelry Edit</h3><span class="explore">Explore collection <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span></div></a>
                <a href="{{ route('product.categories_list') }}" class="coll-tile"><span style="position:absolute;inset:0;z-index:-2;background:linear-gradient(135deg,#5E7355,#38412f)"></span><div class="cl-body"><small>Old &amp; new</small><h3>Traditional Crafts, Modern Living</h3><span class="explore">Explore collection <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span></div></a>
                <a href="{{ route('product.categories_list') }}" class="coll-tile"><span style="position:absolute;inset:0;z-index:-2;background:linear-gradient(135deg,#8a3f34,#4a2a3a)"></span><div class="cl-body"><small>Rare finds</small><h3>Luxury Handmade</h3><span class="explore">Explore collection <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span></div></a>
            </div>
        </div>
    </section>

    {{-- ===== TRUST BAND ===== --}}
    <div class="trust"><div class="wrap">
        <div class="t-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="m9 12 2 2 4-4"/></svg></span>
            <div><b>Maker-signed</b><small>A name behind every piece</small></div>
        </div>
        <div class="t-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z"/></svg></span>
            <div><b>Fair to artisans</b><small>Makers set the price</small></div>
        </div>
        <div class="t-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg></span>
            <div><b>Free shipping</b><small>On orders over ₹2,499</small></div>
        </div>
        <div class="t-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-3"/></svg></span>
            <div><b>7-day returns</b><small>Easy &amp; hassle-free</small></div>
        </div>
    </div></div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    // ===== Hero slider (auto-play + dots + arrows) =====
    (function(){
        var $sl = $('#heroSlider'); if(!$sl.length) return;
        var $slides = $sl.find('.hero-slide'), n = $slides.length, i = 0, timer = null;
        var $dots = $('#heroDots');
        for(var d=0; d<n; d++){ $dots.append('<button type="button" aria-label="Slide ' + (d+1) + '"></button>'); }
        var $db = $dots.find('button');
        function go(k){ i = (k + n) % n; $slides.removeClass('on').eq(i).addClass('on'); $db.removeClass('on').eq(i).addClass('on'); }
        function next(){ go(i+1); }
        function start(){ stop(); timer = setInterval(next, 5500); }
        function stop(){ if(timer){ clearInterval(timer); timer = null; } }
        $db.on('click', function(){ go($(this).index()); start(); });
        $('#heroNext').on('click', function(){ next(); start(); });
        $('#heroPrev').on('click', function(){ go(i-1); start(); });
        $sl.on('mouseenter', stop).on('mouseleave', start);
        go(0); start();
    })();

    // Color dot -> swap image + price (real behaviour)
    $(document).on('click', '.pcard .color-dot', function(){
        var $d = $(this), img = $d.data('image'), price = $d.data('price'), color = $d.attr('title');
        $d.siblings().removeClass('active'); $d.addClass('active');
        var $card = $d.closest('.pcard');
        if(img) $card.find('.img.main').attr('src', img);
        if(price !== undefined && price !== null && price !== '') $card.find('.price').text('₹' + parseFloat(price).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}));
        if(color){ $card.find('a.p-link').each(function(){ var u = $(this).attr('href'); if(u && u.indexOf('product/') > -1){ $(this).attr('href', u.split('?')[0] + '?color=' + encodeURIComponent(color)); } }); }
    });
});
</script>
<script src="{{ asset('public/assets/frontend/js/arthubly-home.js') }}"></script>
@endpush