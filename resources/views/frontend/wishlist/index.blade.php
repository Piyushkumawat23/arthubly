@extends('frontend.layout.arthubly')

@section('title', 'My Wishlist — Arthubly')

@section('content')
    <section class="page active">
        <div class="wrap">

            <div class="crumbs">
                <a href="{{ url('/') }}">Home</a>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <span class="cur">Wishlist</span>
            </div>

            {{-- ==========================================================
                 LAYOUT: logged-in → account sidebar
                         guest     → full width (no account nav)
                 ========================================================== --}}
            <div class="{{ auth()->check() ? 'acct-layout' : '' }}">

                @auth
                    @include('frontend.partials.arthubly-account-nav', ['active' => 'wishlist'])
                @endauth

                <div class="{{ auth()->check() ? 'acct-main' : '' }}">

                    <div class="ac-head">
                        <h1>{{ auth()->check() ? 'My wishlist' : 'Your wishlist' }}</h1>
                        <p>Pieces you've saved to come back to.</p>
                    </div>

                    {{-- ---------- GUEST NOTICE ---------- --}}
                    @guest
                        @if ($products->count() > 0)
                            <div class="wl-guestnote">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 16v-5M12 8h.01" />
                                </svg>
                                <div>
                                    <b>This list is only saved in this browser.</b>
                                    <span>Sign in to save it to your account — access it on any device.</span>
                                </div>
                                <a href="#" data-toggle="modal" data-target="#signin-modal"
                                    class="btn btn-primary btn-sm">
                                    Sign in
                                </a>
                            </div>
                        @endif
                    @endguest

                    @if ($products->count() > 0)
                        <div class="panel">
                            @foreach ($products as $product)
                                @php
                                    /* ==========================================================
                                       IMAGE RESOLVER
                                       thumbnail_image → variation image → gallery image → no-image
                                       ========================================================== */
                                    $noImg = asset('public/uploads/products/no-image.jpg');
                                    $thumb = null;
                                    $raw = $product->thumbnail_image;

                                    if (filled($raw)) {
                                        if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])) {
                                            $thumb = $raw;
                                        } elseif (str_contains($raw, '/')) {
                                            $thumb = asset(ltrim($raw, '/'));
                                        } else {
                                            $thumb = asset('public/uploads/products/' . $raw);
                                        }
                                    }

                                    $variationImg = optional($product->variations->first(fn($v) => filled($v->image)))
                                        ->image;

                                    $variationUrl = $variationImg
                                        ? asset('public/uploads/products/variations/' . $variationImg)
                                        : null;

                                    $galleryUrl = null;
                                    if ($product->relationLoaded('images') && $product->images->count()) {
                                        $gi = $product->images->first(fn($i) => filled($i->image));
                                        if ($gi) {
                                            $galleryUrl = asset('public/uploads/products/gallery/' . $gi->image);
                                        }
                                    }

                                    if (!$thumb) {
                                        $thumb = $variationUrl ?: ($galleryUrl ?: $noImg);
                                    }

                                    $fallbackUrl = $variationUrl ?: ($galleryUrl ?: $noImg);

                                    $inStock =
                                        ($product->stock ?? 0) > 0 || ($product->availability ?? '') === 'in_stock';
                                @endphp

                                <div class="cart-item">
                                    <div class="ci-img">
                                        <a href="{{ url('product/' . $product->slug) }}">
                                            <img src="{{ $thumb }}" alt="{{ $product->name }}"
                                                style="width:100%;height:100%;object-fit:cover"
                                                onerror="this.onerror=null; this.src='{{ $fallbackUrl }}';">
                                        </a>
                                    </div>

                                    <div class="ci-info">
                                        <h4>
                                            <a href="{{ url('product/' . $product->slug) }}"
                                                style="color:var(--ink)">{{ $product->name }}</a>
                                        </h4>
                                        <div class="ci-meta">{{ $product->category->name ?? 'Handmade' }}</div>
                                        <div class="ci-price" style="margin-top:8px">
                                            ₹{{ number_format($product->price, 2) }}
                                        </div>
                                        <div style="margin-top:6px">
                                            @if ($inStock)
                                                <span class="stock-in">● In stock</span>
                                            @else
                                                <span class="stock-out">● Out of stock</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="wish-actions">
                                        @if ($product->variations->count() > 1)
                                            <a href="{{ url('product/' . $product->slug) }}" class="btn btn-primary">Select
                                                options</a>
                                        @else
                                            <a href="{{ url('product/' . $product->slug) }}"
                                                class="btn btn-primary viewadd">View
                                                &amp; add</a>
                                        @endif

                                        <a href="{{ route('wishlist.toggle', $product->id) }}" class="ci-remove"
                                            data-id="{{ $product->id }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                style="width:14px;height:14px">
                                                <path d="M18 6 6 18M6 6l12 12" />
                                            </svg> Remove
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="es-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path
                                        d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
                                </svg>
                            </div>
                            <h3>Your wishlist is empty</h3>
                            <p>Save pieces you love and find them here.</p>
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Discover handmade</a>

                            @guest
                                <p style="margin-top:18px;font-size:13px;color:var(--ink-50)">
                                    Already have an account?
                                    <a href="#" data-toggle="modal" data-target="#signin-modal"
                                        style="color:var(--brass-d);font-weight:600">Sign in</a>
                                    to view your saved list.
                                </p>
                            @endguest
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* --- MAIN CART ITEM LAYOUT (FIXED) --- */
        .cart-item {
            display: flex;
            align-items: center;
            /* Sabhi items ko vertically center karega */
            gap: 20px;
            /* Gap between Image, Info and Buttons */
            padding: 20px;
            /* Space inside Card, adjust accordingly */
            /* If border/background is already present then leave it */
        }

        /* --- PRODUCT INFO (Middle Area) --- */
        .ci-info {
            flex: 1;
            /* This will stretch the middle area and push buttons to the right */
        }

        /* --- WISH ACTIONS (Right Area) --- */
        .wish-actions {
            display: flex;
            flex-direction: column;
            /* Will show Button and Remove one below another */
            align-items: flex-end;
            /* Right side align karega */
            gap: 12px;
            /* Spacing between Button and Remove */
            margin-top: 0;
            width: auto;
        }

        /* --- BUTTON STYLES --- */
        .btn.btn-primary.viewadd {
            background: var(--brass-d);
            color: white;
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn.btn-primary.viewadd:hover {
            background: #b8860b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3);
        }

        /* --- REMOVE STYLES --- */
        .ci-remove {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--ink-50, #777);
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .ci-remove:hover {
            color: #d32f2f;
        }

        /* Guest note styles (same as before) */
        .wl-guestnote {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 12px;
            background: #f7f1e5;
            border: 1px solid var(--line);
        }

        .wl-guestnote>svg {
            width: 22px;
            height: 22px;
            flex: 0 0 auto;
            color: var(--brass-d);
        }

        .wl-guestnote div {
            flex: 1;
            line-height: 1.45;
        }

        .wl-guestnote b {
            display: block;
            font-size: 13.5px;
            color: var(--ink);
        }

        .wl-guestnote span {
            display: block;
            font-size: 12.5px;
            color: var(--ink-50);
            margin-top: 2px;
        }

        .wl-guestnote .btn {
            flex: 0 0 auto;
        }

        /* --- MOBILE RESPONSIVE FIXES --- */
        @media (max-width: 640px) {
            .cart-item {
                flex-direction: row;
                align-items: center;
                padding: 14px 12px;
                gap: 12px;
            }

            .ci-img {
                width: 75px;
                flex: 0 0 auto;
            }

            .ci-info {
                flex: 1;
            }

            .ci-info h4 a {
                font-size: 14px;
            }

            .wish-actions {
                flex-direction: column;
                align-items: flex-end;
                justify-content: center;
                gap: 10px;
                width: auto;
                margin-top: 0;
            }

            .btn.btn-primary.viewadd {
                padding: 6px 14px;
                font-size: 12px;
            }

            .ci-remove {
                font-size: 13px;
            }

            .wl-guestnote {
                flex-wrap: wrap;
                gap: 10px;
            }

            .wl-guestnote .btn {
                width: 100%;
            }
        }
    </style>
@endpush
