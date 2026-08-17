<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Arthubly — Handmade & Handicraft Marketplace')</title>
    <meta name="description" content="@yield('meta_description', 'Arthubly — a marketplace for handmade & handicraft, connecting independent artisans with people who value the work of the hand.')">

    {{-- Favicon (keep your existing ones) --}}
    <link rel="shortcut icon" href="{{ url('public/assets/frontend/images/logo/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ url('public/assets/frontend/images/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ url('public/assets/frontend/images/logo/favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ url('public/assets/frontend/images/logo/apple-touch-icon-180.png') }}">
    <meta property="og:image" content="{{ url('public/assets/frontend/images/logo/arthubly-logo-1024.png') }}">

    {{-- Fonts + Arthubly design --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,500;1,9..144,600&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ===== ARTHUBLY CSS — sirf 3 files, order matter karta hai =====
         1. arthubly.css          theme, layout, hero, sections
         2. arthubly-product.css  product card + quick view
         3. arthubly-shop.css     bag/wishlist drawers, toast, AI search
         (home page apni arthubly-home.css khud @push('styles') se laata hai) --}}
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/arthubly.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/arthubly-product.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/arthubly-shop.css') }}">
    @stack('styles')
</head>


<body class="{{ \Illuminate\Support\Facades\Route::currentRouteName() === 'product.details' ? 'pdp-page' : '' }}">

    {{-- ===================== PAGE LOADER (Arthubly) ===================== --}}
    @php
        $__rn = \Illuminate\Support\Facades\Route::currentRouteName();
        $__sk = 'default';
        if ($__rn === 'home') {
            $__sk = 'home';
        } elseif (in_array($__rn, ['product.category', 'product.categories_list'])) {
            $__sk = 'listing';
        } elseif ($__rn === 'product.details') {
            $__sk = 'pdp';
        } elseif ($__rn === 'cart.index') {
            $__sk = 'cart';
        } elseif ($__rn === 'checkout.index') {
            $__sk = 'checkout';
        }
    @endphp
    <div id="page-loader" role="status" aria-label="Loading Arthubly">
        @includeFirst(['frontend.partials.skeletons.' . $__sk, 'frontend.partials.skeletons.default'])
    </div>
    <script>
        (function() {
            function hide() {
                var l = document.getElementById('page-loader');
                if (l && !l.classList.contains('done')) {
                    l.classList.add('done');
                    setTimeout(function() {
                        if (l) {
                            l.style.display = 'none';
                        }
                    }, 550);
                }
            }
            if (document.readyState === 'complete') {
                hide();
            } else {
                window.addEventListener('load', hide);
            }
            setTimeout(hide, 4500); /* safety: never trap the page */
        })();
    </script>


    @php
        /* Layout is self-sufficient: works even if a view-composer doesn't inject these. */
$__cart = session('cart', []);
$__cartCount = is_array($__cart) ? count($__cart) : 0;
$__cartTotal = 0;
if (is_array($__cart)) {
    foreach ($__cart as $__d) {
        $__cartTotal += ($__d['price'] ?? 0) * ($__d['quantity'] ?? 1);
    }
}
$__wish = 0;
if (auth()->check()) {
    try {
        $__wish = \App\Models\Wishlist::where('user_id', auth()->id())->count();
    } catch (\Throwable $e) {
        $__wish = 0;
    }
} else {
    $__guestWish = session('guest_wishlist', []);
            $__wish = is_array($__guestWish) ? count($__guestWish) : 0;
        }
        $__menus = $frontMenus ?? collect();
    @endphp

    {{-- ===================== TOP STRIP ===================== --}}
    <div class="top-strip">
        <div class="wrap">
            <div class="marquee">
                <span id="marqueeText">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="width:14px;height:14px;vertical-align:-2px">
                        <path d="M12 2l7 3v6c0 5-3 8-7 9-4-1-7-4-7-9V5z" />
                    </svg>
                    Free shipping over ₹2,499 &nbsp;·&nbsp; 7-day easy returns &nbsp;·&nbsp; Every artisan verified
                    &nbsp;·&nbsp; Handmade, quality-checked
                </span>
            </div>
            <div class="strip-links">
                @auth
                    <a href="{{ route('customer.orders') }}">Track Order</a>
                @endauth
                <a href="{{ route('product.categories_list') }}">Categories</a>
                <a href="#">₹ INR</a>
            </div>
        </div>
    </div>

    {{-- ===================== HEADER ===================== --}}
    <header class="site-header" id="siteHeader">
        <div class="wrap header-main">
            <button class="logo-menu-btn" type="button" data-open-menu aria-label="Open menu" title="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
            <a class="logo" href="{{ url('/') }}" aria-label="Arthubly home">
                <span class="mark" id="logoMark">
                    <img src="{{ asset('public/assets/frontend/images/logo/arthubly-logo-512.png') }}" alt="Arthubly"
                        width="42" height="42">
                </span>
                <span>
                    <span class="name">Art<b>hubly</b></span>
                    <span class="tag">Handmade Marketplace</span>
                </span>
            </a>

            {{-- Live AJAX search (kept exactly — same route + classes your JS uses) --}}
            {{-- Live AJAX search + Ask AI --}}
            <div class="search">
                <form action="{{ route('product.search') }}" method="get"
                    style="position:relative;width:100%;display:flex;align-items:center;gap:10px" autocomplete="off">
                    <div class="search-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <input type="search" class="form-control live-search-input" name="q" id="q"
                            placeholder="Search handmade crafts, artisans, collections…" autocomplete="off"
                            style="flex:1;min-width:0;border:none;background:none;outline:none;height:100%;font-size:15px;color:var(--ink)">
                    </div>
                    <button type="button" class="search-ai" id="openAiSearch">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            style="width:16px;height:16px">
                            <path
                                d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1M7.7 16.3l-2.1 2.1" />
                        </svg>
                        Ask AI
                    </button>

                    <div class="suggest" id="suggestBox">
                        @isset($categories)
                            <h5>Popular categories</h5>
                            <div class="chips">
                                @foreach ($categories->take(6) as $cat)
                                    <button type="button" class="suggest-chip"
                                        data-cat="{{ $cat->slug }}">{{ $cat->name }}</button>
                                @endforeach
                            </div>
                        @endisset
                        <h5>Products</h5>
                        <div id="suggestProducts"></div>
                        <div class="row" id="suggestAiRow"
                            style="cursor:pointer;border-top:1px solid var(--line);margin-top:4px;padding-top:12px">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                style="color:var(--brass-d)">
                                <path
                                    d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1M7.7 16.3l-2.1 2.1" />
                            </svg>
                            <span class="m"><b style="color:var(--brass-d)">Ask Arthubly AI to find it for
                                    you</b></span>
                        </div>
                    </div>
                </form>
            </div>

            <div class="header-actions">
                <button class="iconbtn" type="button" data-open-menu aria-label="Menu" title="Menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <a class="iconbtn" href="{{ route('wishlist.index') }}" data-open-wish aria-label="Wishlist"
                    title="Wishlist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
                    </svg>
                    <span class="dot wish-count"
                        style="{{ $__wish > 0 ? '' : 'display:none' }}">{{ $__wish }}</span>
                </a>

                {{-- Cart — ab bag DRAWER kholta hai (bootstrap dropdown hata diya) --}}
                <a href="{{ route('cart.index') }}" class="iconbtn" data-open-bag aria-label="Cart" title="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 8h12l-1 12H7L6 8z" />
                        <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                    </svg>
                    <span class="dot cart-count"
                        style="{{ $__cartCount > 0 ? '' : 'display:none' }}">{{ $__cartCount }}</span>
                </a>

                @auth
                    <a class="acct-btn" href="{{ route('dashboard') }}">
                        <span class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                        <span
                            class="who"><small>Account</small><strong>{{ \Illuminate\Support\Str::words(auth()->user()->name ?? 'My account', 1, '') }}</strong></span>
                    </a>
                @else
                    <button class="acct-btn" type="button" data-toggle="modal" data-target="#signin-modal">
                        <span class="avatar">A</span>
                        <span class="who"><small>Account</small><strong>Sign in</strong></span>
                    </button>
                @endauth
            </div>
        </div>

        <nav class="catnav">
            <div class="wrap" style="overflow:visible">
                <ul class="a-nav">
                    {{-- <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li> --}}
                    @foreach ($navCategories as $category)
                        <li class="{{ request()->is('category/' . $category->slug) ? 'active' : '' }}">
                            <a href="{{ route('product.category', $category->slug) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </header>

    {{-- ===================== FLASH MESSAGES ===================== --}}
    {{-- ===================== FLASH MESSAGES (TOAST DESIGN) ===================== --}}
    <div class="toast-wrap" id="toastWrap">
        @if (session('success'))
            <div class="toast show" role="alert">
                <div class="t-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </div>
                <div class="t-body">
                    <b>{{ session('success') }}</b>
                </div>
                <button type="button" class="close"
                    style="background:none;border:none;color:#fff;font-size:22px;line-height:1;cursor:pointer;opacity:0.7"
                    onclick="this.closest('.toast').remove()">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="toast show" role="alert">
                <div class="t-check" style="background: var(--madder);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </div>
                <div class="t-body">
                    <b>{{ session('error') }}</b>
                </div>
                <button type="button" class="close"
                    style="background:none;border:none;color:#fff;font-size:22px;line-height:1;cursor:pointer;opacity:0.7"
                    onclick="this.closest('.toast').remove()">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="toast show" role="alert">
                <div class="t-check" style="background: var(--madder);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </div>
                <div class="t-body">
                    <ul style="margin:0;padding-left:16px;font-size:13.5px;color:#F4EFE4">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="close"
                    style="background:none;border:none;color:#fff;font-size:22px;line-height:1;cursor:pointer;opacity:0.7"
                    onclick="this.closest('.toast').remove()">&times;</button>
            </div>
        @endif
    </div>
    {{-- ===================== PAGE CONTENT ===================== --}}
    <main id="app">
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="footer">
        <div class="newsletter">
            <div class="wrap">
                <div>
                    <h2>Join the <em>maker's circle</em></h2>
                    <p>Studio stories, new collections, and first access to limited editions — once a week.</p>
                </div>
                <form class="nl-form" action="{{ route('newsletter.subscribe') }}" method="POST"
                    id="footer-newsletter">
                    @csrf
                    <input type="email" name="email" placeholder="your@email.com" required>
                    <button class="btn btn-brass btn-lg" type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="wrap">
            <div class="foot-grid">
                <div class="foot-brand">
                    <a class="logo" href="{{ url('/') }}"><span class="mark"><svg viewBox="0 0 44 44"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="44" height="44" rx="12" fill="#20263A" />
                                <path
                                    d="M12 34c1.9-6.6 5-13.9 9.7-22.4 .8-1.4 2.9-1 3 .6 0 .6-.3 1.2-.7 1.9C19.6 21.7 16.9 28 15.2 34.4c-.5 1.8-3.7 1.4-3.2-.4z"
                                    fill="#E5C888" />
                                <path
                                    d="M21.2 12.6c4 6.6 7.2 13.4 9.5 21 .5 1.8-2.4 2.6-3 .9-2.2-6.2-5-11.9-8.4-17.6-1-1.7 1-3 1.9-1.3z"
                                    fill="#C9973A" />
                                <path
                                    d="M17 27c3.8-1.1 7.4-1.1 11.2 0 1.5.4 1.1 2.7-.5 2.5-3.4-.4-6.8-.4-10.2 0-1.6.2-2-2.1-.5-2.5z"
                                    fill="#B14237" />
                                <circle cx="32.5" cy="12.6" r="1.9" fill="#5E7355" />
                            </svg></span><span class="name">Art<b>hubly</b></span></a>
                    <p>A marketplace for handmade &amp; handicraft — connecting independent artisans with people who
                        value the work of the hand.</p>
                    <div class="socials">
                        <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                            </svg></a>
                        <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14 9h3V6h-3c-2 0-3 1.3-3 3v2H9v3h2v6h3v-6h2.5l.5-3H14V9.5c0-.3.2-.5.5-.5z" />
                            </svg></a>
                        <a href="#" aria-label="Pinterest"><svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <path
                                    d="M12 3a9 9 0 0 0-3.3 17.4c-.1-.7-.2-1.9 0-2.7l1.1-4.6s-.3-.6-.3-1.4c0-1.3.8-2.3 1.7-2.3.8 0 1.2.6 1.2 1.3 0 .8-.5 2-.8 3.1-.2.9.5 1.6 1.4 1.6 1.6 0 2.9-1.7 2.9-4.2 0-2.2-1.6-3.7-3.8-3.7a4 4 0 0 0-4.2 4c0 .8.3 1.6.7 2.1l-.3 1.1c-1.1-.5-1.8-2.2-1.8-3.5 0-2.8 2-5.4 5.9-5.4 3.1 0 5.5 2.2 5.5 5.2 0 3.1-1.9 5.6-4.7 5.6-.9 0-1.8-.5-2.1-1l-.6 2.2A9 9 0 1 0 12 3z" />
                            </svg></a>
                    </div>
                </div>
                <div class="foot-col">
                    <h4>Shop</h4>
                    <a href="{{ route('product.categories_list') }}">All Categories</a>
                    <a href="{{ url('/') }}">New Arrivals</a>
                    <a href="{{ route('wishlist.index') }}">My Wishlist</a>
                    <a href="{{ route('cart.index') }}">View Cart</a>
                </div>
                <div class="foot-col">
                    <h4>Account</h4>
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('customer.orders') }}">My Orders</a>
                        <a href="{{ route('customer.returns') }}">My Returns</a>
                        <a href="{{ route('profile.edit') }}">Profile</a>
                    @else
                        <a href="#" data-toggle="modal" data-target="#signin-modal">Sign In</a>
                        <a href="{{ route('cart.index') }}">View Cart</a>
                        <a href="{{ route('seller.login') }}" class="seller-link">Seller Login</a>
                    @endauth
                </div>
                <div class="foot-col">
                    <h4>Support</h4>
                    <a href="#">Help Centre</a>
                    <a href="#">Shipping &amp; Returns</a>
                    <a href="#">Authenticity</a>
                    <a href="#">Contact Us</a>
                </div>

            </div>
            <div class="foot-bottom">
                <span>© {{ date('Y') }} Arthubly Marketplace · Made with care for makers everywhere</span>
                <div class="pays">
                    <span>UPI</span><span>Visa</span><span>Mastercard</span><span>Razorpay</span><span>COD</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- ===================== MOBILE BOTTOM NAV ===================== --}}
    <nav class="mobile-nav">
        <div class="mn-grid">
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'on' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 11l8-7 8 7" />
                    <path d="M6 10v9h12v-9" />
                </svg>Home</a>
            <a href="{{ route('product.categories_list') }}"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <rect x="4" y="4" width="7" height="7" rx="1" />
                    <rect x="13" y="4" width="7" height="7" rx="1" />
                    <rect x="4" y="13" width="7" height="7" rx="1" />
                    <rect x="13" y="13" width="7" height="7" rx="1" />
                </svg>Shop</a>
            <a href="#" data-open-menu><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>Menu</a>
            <a href="{{ route('cart.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <path d="M6 8h12l-1 12H7L6 8z" />
                    <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                </svg>
                @if ($__cartCount > 0)
                    <span class="dot">{{ $__cartCount }}</span>
                @endif Bag
            </a>
            <a href="{{ route('wishlist.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
                </svg>Wishlist</a>
        </div>
    </nav>

    {{-- ===================== MOBILE DRAWER (dynamic menu) ===================== --}}
    <aside class="mobile-drawer" id="mobileDrawer">
        <div class="md-head">
            <span class="logo"><span class="name" style="font-size:20px">Art<b
                        style="color:var(--brass)">hubly</b></span></span>
            <button class="iconbtn" data-close-menu aria-label="Close"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg></button>
        </div>
        <div class="md-body">
            <ul class="mm">
                <li>
                    <div class="mm-row"><a href="{{ url('/') }}">Home</a></div>
                </li>
                @foreach ($__menus as $menu)
                    <li class="{{ $menu->children->isNotEmpty() ? 'mm-has' : '' }}">
                        <div class="mm-row">
                            <a
                                href="{{ $menu->url ? url($menu->url) : 'javascript:void(0)' }}">{{ $menu->title }}</a>
                            @if ($menu->children->isNotEmpty())
                                <button class="mm-toggle" type="button" aria-label="Expand"><svg
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        style="width:18px;height:18px">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg></button>
                            @endif
                        </div>
                        @if ($menu->children->isNotEmpty())
                            <ul>
                                @foreach ($menu->children as $child)
                                    <li><a
                                            href="{{ $child->url ? url($child->url) : 'javascript:void(0)' }}">{{ $child->title }}</a>
                                    </li>
                                    @if ($child->children->isNotEmpty())
                                        @foreach ($child->children as $sub)
                                            <li><a href="{{ $sub->url ? url($sub->url) : 'javascript:void(0)' }}"
                                                    style="padding-left:32px;font-size:13px">{{ $sub->title }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li>
                    <div class="mm-row"><a href="{{ route('product.categories_list') }}">All Categories</a></div>
                </li>
                @auth
                    <li>
                        <div class="mm-row"><a href="{{ route('customer.orders') }}">My Orders</a></div>
                    </li>
                    <li>
                        <div class="mm-row"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    </li>
                @else
                    <li>
                        <div class="mm-row"><a href="#" data-toggle="modal" data-target="#signin-modal"
                                data-close-menu>Sign In</a></div>
                    </li>
                @endauth
            </ul>
        </div>
    </aside>

    <button id="backTop" title="Back to top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" style="width:18px;height:18px">
            <path d="M12 19V5m-7 7 7-7 7 7" />
        </svg></button>

    {{-- ===================== QUICK VIEW DRAWER (redesigned card layout) ===================== --}}
    {{-- arthubly.blade.php me purane <div class="qv-drawer" id="quickViewModal"> ... </div> block ko
     (line ~543 se ~587 tak) POORA hata kar ye paste karein. IDs same hain, JS chalta rahega. --}}
    <div class="qv-drawer" id="quickViewModal" aria-hidden="true">
        <div class="qv-backdrop" data-qv-close></div>

        <aside class="qv-panel" role="dialog" aria-modal="true" aria-labelledby="qv-title">

            {{-- ---------- TOP BAR: Handmade pill + wishlist heart ---------- --}}
            <div class="qv-topbar">
                <span class="qv-pill">Handmade</span>
                <div class="qv-topbar-actions">
                    <a href="javascript:void(0)" class="qv-fav" id="qv-fav" title="Add to wishlist"
                        aria-label="Add to wishlist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
                        </svg>
                    </a>
                    <button type="button" class="qv-close" data-qv-close aria-label="Close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="qv-scroll">

                {{-- ---------- MEDIA ---------- --}}
                <div class="qv-media">
                    <img id="qv-image" src="" alt="Product image" class="qv-main">
                    <button type="button" class="qv-zoom" id="qv-zoom" aria-label="Zoom image">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M11 8v6M8 11h6M20 20l-3.5-3.5" />
                        </svg>
                    </button>
                </div>

                {{-- side thumbnails (JS isi me bharta hai) --}}
                <div id="qv-gallery" class="qv-thumbs"></div>

                <div class="qv-body">

                    {{-- ---------- CATEGORY + TITLE ---------- --}}
                    <div class="qv-cat" id="qv-cat"></div>
                    <h3 id="qv-title"></h3>

                    {{-- ---------- RATING + TRUST ---------- --}}
                    <div class="qv-meta">
                        <div class="qv-rating" id="qv-rating">
                            <span class="qv-stars"><i style="width:100%"></i></span>
                            <span class="qv-rcount" id="qv-rcount"></span>
                        </div>
                        <span class="qv-authentic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg> Authentic Handmade
                        </span>
                    </div>

                    {{-- ---------- PRICE ---------- --}}
                    <div class="qv-price-row">
                        <span class="product-price" id="qv-price"></span>
                        <span class="qv-price-right">
                            <span class="qv-price-old" id="qv-price-old"></span>
                            <span class="qv-price-off" id="qv-price-off"></span>
                        </span>
                    </div>

                    {{-- ---------- ORNAMENT DIVIDER ---------- --}}
                    <div class="qv-orn">
                        <span class="ln"></span>
                        <svg viewBox="0 0 40 16" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M20 3c3 0 5 2 5 5s-2 5-5 5-5-2-5-5 2-5 5-5z" />
                            <path d="M15 8H8M25 8h7M8 8 4 5v6zM32 8l4-3v6z" />
                        </svg>
                        <span class="ln"></span>
                    </div>

                    {{-- description (chhota, optional) --}}
                    <p id="qv-description" class="qv-desc"></p>

                    {{-- ---------- VARIATIONS ---------- --}}
                    <form id="qv-form" action="" method="POST">
                        @csrf
                        {{-- hidden inputs: chips inhi ki value set karte hain --}}
                        <input type="hidden" name="color" id="qv-color" value="">
                        <input type="hidden" name="size" id="qv-size" value="">
                        <input type="hidden" name="quantity" id="qv-qty" value="1">

                        <div class="qv-varhead" id="qv-varhead">Variations</div>

                        <div class="qv-group" id="qv-size-group">
                            <span class="qv-glabel">Size</span>
                            <div class="qv-chips" id="qv-size-wrap"></div>
                        </div>

                        <div class="qv-group" id="qv-color-group">
                            <span class="qv-glabel">Finish</span>
                            <div class="qv-swatches" id="qv-color-wrap"></div>
                        </div>

                        <div class="qv-group qv-qtygroup">
                            <span class="qv-glabel">Quantity</span>
                            <div class="qv-stepper">
                                <button type="button" data-qty="-1" aria-label="Decrease">−</button>
                                <span id="qv-qty-label">1</span>
                                <button type="button" data-qty="1" aria-label="Increase">+</button>
                            </div>
                        </div>
                    </form>

                    {{-- ---------- SHIPPING BAR ---------- --}}
                    <div class="qv-ship">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" />
                            <circle cx="7" cy="18" r="1.6" />
                            <circle cx="17" cy="18" r="1.6" />
                        </svg> Ships in 3–5 days
                    </div>
                </div>
            </div>

            {{-- ---------- FOOTER: Add to cart + trust row ---------- --}}
            <div class="qv-foot">
                <button type="submit" form="qv-form" class="qv-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 8h12l-1 12H7L6 8z" />
                        <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                    </svg> Add to Cart
                </button>

                <div class="qv-trust">
                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path
                                d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" />
                        </svg> Handmade with Love</span>
                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="12" r="9" />
                            <path d="m9 12 2 2 4-4" />
                        </svg> Easy Returns</span>
                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="4" y="10" width="16" height="10" rx="2" />
                            <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                        </svg> Secure Payment</span>
                </div>
            </div>

        </aside>
    </div>

    @include('frontend.partials.drawers')
    @include('frontend.partials.wishlist-merge')
    {{-- ===================== SIGN IN / REGISTER MODAL (kept — route login) ===================== --}}
    <div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="auth-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">✕</button>
                        <div class="auth-modal-head">
                            <span class="mark"><svg viewBox="0 0 44 44" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="44" height="44" rx="12" fill="#20263A" />
                                    <path
                                        d="M12 34c1.9-6.6 5-13.9 9.7-22.4 .8-1.4 2.9-1 3 .6 0 .6-.3 1.2-.7 1.9C19.6 21.7 16.9 28 15.2 34.4c-.5 1.8-3.7 1.4-3.2-.4z"
                                        fill="#E5C888" />
                                    <path
                                        d="M21.2 12.6c4 6.6 7.2 13.4 9.5 21 .5 1.8-2.4 2.6-3 .9-2.2-6.2-5-11.9-8.4-17.6-1-1.7 1-3 1.9-1.3z"
                                        fill="#C9973A" />
                                    <path
                                        d="M17 27c3.8-1.1 7.4-1.1 11.2 0 1.5.4 1.1 2.7-.5 2.5-3.4-.4-6.8-.4-10.2 0-1.6.2-2-2.1-.5-2.5z"
                                        fill="#B14237" />
                                    <circle cx="32.5" cy="12.6" r="1.9" fill="#5E7355" />
                                </svg></span>
                            <h3>Welcome to Arthubly</h3>
                            <p>Sign in or create an account to continue.</p>
                        </div>
                        <ul class="nav nav-pills seg-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="signin-tab" data-toggle="tab"
                                    href="#signin" role="tab" aria-selected="true">Sign In</a></li>
                            <li class="nav-item"><a class="nav-link" id="register-tab" data-toggle="tab"
                                    href="#register" role="tab" aria-selected="false">Register</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="signin" role="tabpanel">
                                <form action="{{ route('login') }}" method="POST" class="auth-form">
                                    @csrf
                                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                    <div class="field"><label for="signin-email">Email address</label><input
                                            type="email" class="form-control" id="signin-email" name="email"
                                            required placeholder="you@example.com"></div>
                                    <div class="field"><label for="signin-password">Password</label>
                                        <div class="pw"><input type="password" class="form-control"
                                                id="signin-password" name="password" required
                                                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"><button
                                                type="button" class="toggle-pw" aria-label="Show password"><svg
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg></button></div>
                                    </div>
                                    <div class="auth-row">
                                        <label class="auth-check"><input type="checkbox" name="remember"> Remember
                                            me</label>
                                        @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="auth-link">Forgot
                                                password?</a>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg auth-submit">Sign in</button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form
                                    action="{{ \Illuminate\Support\Facades\Route::has('register') ? route('register') : url('/register') }}"
                                    method="POST" class="auth-form">
                                    @csrf
                                    <div class="field"><label for="register-name">Full name</label><input
                                            type="text" class="form-control" id="register-name" name="name"
                                            required placeholder="Your name"></div>
                                    <div class="field"><label for="register-email">Email address</label><input
                                            type="email" class="form-control" id="register-email" name="email"
                                            required placeholder="you@example.com"></div>
                                    <div class="field"><label for="register-password">Password</label>
                                        <div class="pw"><input type="password" class="form-control"
                                                id="register-password" name="password" required
                                                placeholder="Create a password"><button type="button"
                                                class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg></button></div>
                                    </div>
                                    <div class="field"><label for="register-password-confirm">Confirm
                                            password</label>
                                        <div class="pw"><input type="password" class="form-control"
                                                id="register-password-confirm" name="password_confirmation" required
                                                placeholder="Repeat password"><button type="button"
                                                class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg></button></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg auth-submit"
                                        style="margin-top:4px">Create account</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="overlay" id="aiOverlay">
        <div class="ai-modal">
            <button class="modal-close" data-close type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
            <div class="ai-modal-head">
                <span class="ai-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1M7.7 16.3l-2.1 2.1" />
                    </svg>
                    Arthubly AI Search
                </span>
                <h2>Describe what you're <em>looking for</em></h2>
            </div>
            <div class="ai-modal-body">
                <form action="{{ route('product.search') }}" method="get">
                    <div class="ai-input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <input type="text" name="q" id="aiModalInput"
                            placeholder="Show handmade blue pottery under ₹5,000…">
                        <button class="go-ai" type="submit">Search</button>
                    </div>
                </form>
                <div class="ai-prompts" id="aiModalPrompts">
                    <span>Try:</span>
                    <button type="button">Blue pottery under ₹5,000</button>
                    <button type="button">Wall art for a living room</button>
                    <button type="button">Handmade gifts under ₹1,000</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            var t = e.target.closest('#signin-modal .toggle-pw');
            if (!t) return;
            var inp = t.parentElement.querySelector('input');
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
        });
    </script>

    {{-- ===================== NEWSLETTER POPUP (kept — magnific + your JS) ===================== --}}
    <div class="container newsletter-popup-container mfp-hide" id="newsletter-popup-form">
        <div class="modal-content" style="max-width:520px;margin:auto;padding:30px;text-align:center">
            <span class="name" style="font-family:var(--font-d);font-size:24px;color:var(--ink)">Art<b
                    style="color:var(--brass)">hubly</b></span>
            <h2 style="font-family:var(--font-d);margin:14px 0 6px">Get <span style="color:var(--brass)">25%</span>
                off</h2>
            <p style="color:var(--ink-70,#4a5064)">Subscribe to the Arthubly newsletter for updates from your favourite
                makers.</p>
            <form action="javascript:void(0)" id="newsletter-form" style="margin-top:12px">
                @csrf
                <div class="ai-input" style="max-width:none">
                    <input type="email" name="email" placeholder="Your email address" required
                        style="flex:1;min-width:0;border:none;background:none;outline:none;height:46px;padding-left:14px">
                    <button class="btn btn-brass" type="submit">Go</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== SCRIPTS (yours, unchanged) + Arthubly ===================== --}}
    <script src="{{ asset('public/assets/frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/bootstrap-input-spinner.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/jquery.elevateZoom.min.js') }}"></script>
    {{-- Arthubly interactions (reveal, count-up, grain, mobile menu, back-to-top) --}}
    {{-- ===== ARTHUBLY JS — sirf 2 files =====
         1. arthubly.js        theme interactions + product card logic (stock validation)
         2. arthubly-shop.js   drawers + toast + quick view + AI search
         arthubly.js pehle hi rehna chahiye — uski validation preventDefault
         karti hai jise arthubly-shop.js ka ajax handler respect karta hai.
         (home page apni arthubly-home.js khud @push('scripts') se laata hai) --}}
    <script>
        window.ARTHUBLY_BASE = "{{ url('/') }}";
        window.AI_SEARCH_URL = "{{ route('ai.search') }}";
    </script>
    <script src="{{ asset('public/assets/frontend/js/arthubly.js') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/arthubly-shop.js') }}"></script>


    <script>
        $(document).ready(function() {
            // Quick view + drawers ab external JS me hain (3-quickview.js, 4-drawers.js)
            let searchTimeout;

            // ---- Flash auto-hide ----
            setTimeout(function() {
                $(".toast-wrap .toast.show").removeClass('show');
                setTimeout(function() {
                    $(".toast-wrap .toast").remove();
                }, 400);
            }, 5000);

            // ---- Live AJAX search ----
            $('.live-search-input').on('keyup', function(e) {
                if (e.keyCode === 13) return true;
                clearTimeout(searchTimeout);
                let query = $(this).val(),
                    $form = $(this).closest('form'),
                    $box = $form.find('.live-search-results');
                if (query.length < 2) {
                    $box.hide();
                    return;
                }
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('product.search') }}",
                        type: "GET",
                        data: {
                            q: query,
                            live_search: 1
                        },
                        success: function(response) {
                            $box.empty();
                            if (response.length > 0) {
                                let html = '<div class="ls-list">';
                                $.each(response, function(i, product) {
                                    let imgPath =
                                        "{{ asset('public/uploads/products/no-image.jpg') }}",
                                        displayPrice = product.price || 0;
                                    if (product.variations && product.variations
                                        .length > 0) {
                                        let vi = product.variations.find(v => v
                                            .image);
                                        if (vi) imgPath =
                                            "{{ asset('public/uploads/products/variations') }}/" +
                                            vi.image;
                                        if (product.variations[0].price)
                                            displayPrice = product.variations[0]
                                            .price;
                                    }
                                    let productUrl = "{{ url('product') }}/" +
                                        product.slug;
                                    let cat = (product.category && product
                                            .category.name) ? product.category
                                        .name : 'Handmade';
                                    html += `<a class="ls-item" href="${productUrl}">
                                    <span class="ls-thumb"><img src="${imgPath}" onerror="this.src='{{ asset('public/uploads/products/no-image.jpg') }}'"></span>
                                    <span class="ls-meta"><span class="ls-cat">${cat}</span><span class="ls-name">${product.name}</span></span>
                                    <span class="ls-price">₹${displayPrice}</span></a>`;
                                });
                                html +=
                                    '</div><a class="ls-all" href="{{ route('product.search') }}?q=' +
                                    encodeURIComponent(query) +
                                    '">View all results <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a>';
                                $box.html(html).show();
                            } else {
                                $box.html(
                                    '<div class="ls-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" style="width:26px;height:26px"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><span>No products found for &ldquo;' +
                                    query + '&rdquo;.</span></div>').show();
                            }
                        }
                    });
                }, 300);
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('form').length) $('.live-search-results').hide();
            });
        });
    </script>
    {{-- ===================== CATNAV: DRAG-TO-SCROLL (desktop mouse) ===================== --}}
    <script>
        (function() {
            var SEL = '.catnav .a-nav, .catnav .wrap';
            var DRAG_THRESHOLD = 5; // px — isse kam move = normal click

            function isScrollable(el) {
                return el.scrollWidth - el.clientWidth > 2;
            }

            function markOverflow(el) {
                el.classList.toggle('can-drag', isScrollable(el));
            }

            function setup(el) {
                if (el.__catnavDrag) return;
                el.__catnavDrag = true;

                var down = false,
                    moved = false,
                    startX = 0,
                    startScroll = 0,
                    pid = null;

                markOverflow(el);

                el.addEventListener('pointerdown', function(e) {
                    // sirf mouse/pen — touch ka native scroll waise hi chalta hai
                    if (e.pointerType === 'touch') return;
                    if (e.button !== 0) return;
                    if (!isScrollable(el)) return;
                    // khule hue dropdown ke andar se drag start na ho
                    if (e.target.closest('.a-nav ul')) return;

                    down = true;
                    moved = false;
                    startX = e.clientX;
                    startScroll = el.scrollLeft;
                    pid = e.pointerId;
                    try {
                        el.setPointerCapture(pid);
                    } catch (err) {}
                });

                el.addEventListener('pointermove', function(e) {
                    if (!down || e.pointerId !== pid) return;
                    var dx = e.clientX - startX;

                    if (!moved && Math.abs(dx) > DRAG_THRESHOLD) {
                        moved = true;
                        el.classList.add('is-dragging');
                    }
                    if (moved) {
                        el.scrollLeft = startScroll - dx;
                        e.preventDefault();
                    }
                });

                function end(e) {
                    if (!down || (e && e.pointerId !== pid)) return;
                    down = false;
                    try {
                        el.releasePointerCapture(pid);
                    } catch (err) {}
                    pid = null;
                    el.classList.remove('is-dragging');

                    if (moved) {
                        // drag ke baad link open na ho — agla click nigal lo
                        el.addEventListener('click', function swallow(ev) {
                            ev.preventDefault();
                            ev.stopPropagation();
                        }, {
                            capture: true,
                            once: true
                        });
                        setTimeout(function() {
                            moved = false;
                        }, 0);
                    }
                }

                el.addEventListener('pointerup', end);
                el.addEventListener('pointercancel', end);

                // drag ke dauran text/image select na ho
                el.addEventListener('dragstart', function(e) {
                    if (el.classList.contains('is-dragging')) e.preventDefault();
                });

                // shift + wheel se horizontal scroll
                el.addEventListener('wheel', function(e) {
                    if (!e.shiftKey || !isScrollable(el)) return;
                    if (e.deltaX !== 0) return;
                    el.scrollLeft += e.deltaY;
                    e.preventDefault();
                }, {
                    passive: false
                });

                window.addEventListener('resize', function() {
                    markOverflow(el);
                });
            }

            function init() {
                document.querySelectorAll(SEL).forEach(setup);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>

    @stack('scripts')
    {{-- ===================== SMART SCROLL HEADER ===================== --}}
    <script>
        (function() {
            var lastY = window.pageYOffset || document.documentElement.scrollTop || 0;
            var body = document.body,
                hdr = document.getElementById('siteHeader'),
                nav = document.querySelector('.mobile-nav');

            var ticking = false,
                DEAD = 15;
            var isLocked = false;
            var lockTimer = null;

            function apply() {
                var y = window.pageYOffset || document.documentElement.scrollTop || 0;

                // शैडो
                if (hdr) hdr.classList.toggle('scrolled', y > 6);

                // ==========================================
                // यह सबसे जरूरी हिस्सा है: लॉक के दौरान पुरानी पोजीशन अपडेट करना
                if (isLocked) {
                    lastY = y; // लॉक खुलने के बाद झटके से बचने के लिए इसे अपडेट रखना
                    ticking = false;
                    return;
                }
                // ==========================================

                // टॉप पर वापस आने पर सब कुछ दिखाएं
                if (y < 200) {
                    if (body.classList.contains('hdr-min')) {
                        body.classList.remove('hdr-min');
                        if (nav) nav.classList.remove('nav-hidden');
                        setLock();
                    }
                    lastY = y;
                    ticking = false;
                    return;
                }

                var delta = y - lastY;
                if (Math.abs(delta) < DEAD) {
                    ticking = false;
                    return;
                }

                if (delta > 0 && !body.classList.contains('hdr-min')) {
                    // नीचे स्क्रॉल (Hide)
                    body.classList.add('hdr-min');
                    if (nav) nav.classList.add('nav-hidden');
                    setLock();
                } else if (delta < 0 && body.classList.contains('hdr-min')) {
                    // ऊपर स्क्रॉल (Show)
                    body.classList.remove('hdr-min');
                    if (nav) nav.classList.remove('nav-hidden');
                    setLock();
                }

                lastY = y;
                ticking = false;
            }

            // लॉक लगाने का फंक्शन
            function setLock() {
                isLocked = true;
                clearTimeout(lockTimer);
                lockTimer = setTimeout(function() {
                    isLocked = false;
                    // लॉक खुलने पर ताज़ा पोजीशन लें
                    lastY = window.pageYOffset || document.documentElement.scrollTop || 0;
                }, 450); // 450ms का स्मूथ लॉक
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(apply);
                    ticking = true;
                }
            }, {
                passive: true
            });
            window.addEventListener('resize', function() {
                if (!ticking) {
                    window.requestAnimationFrame(apply);
                    ticking = true;
                }
            }, {
                passive: true
            });

            apply();
        })();
    </script>
</body>

</html>
