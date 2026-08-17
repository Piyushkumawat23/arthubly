{{-- Arthubly product card v3 — stock-aware.
     Expects $product; uses $wishlistProductIds if present. --}}
@php
    /* ------------------------------------------------------------------
       STOCK HELPER
       Aapke variations table me column ka naam alag ho sakta hai, isliye
       ye helper sabhi common naam try karta hai. Jo pehle mile wahi lega.
       Kuch bhi na mile to null = "stock pata nahi".
       ------------------------------------------------------------------ */
    $stockOf = function ($v) {
        foreach (['quantity', 'stock', 'qty', 'stock_qty', 'stock_quantity', 'available_qty'] as $f) {
            if (isset($v->$f) && $v->$f !== '' && !is_null($v->$f)) {
                return (int) $v->$f;
            }
        }
        return null;
    };

    $inStock = fn($v) => is_null($stockOf($v)) ? true : $stockOf($v) > 0;

    $variations = $product->variations;

    // pehli AVAILABLE variation — yahi card ka default hoga
    $defaultVariation = $variations->first(fn($v) => $inStock($v)) ?? $variations->first();

    // poora product hi out of stock? (tabhi jab kisi variation ka stock pata ho aur koi bhi available na ho)
    $anyKnown = $variations->contains(fn($v) => !is_null($stockOf($v)));
    $soldOut = $variations->count() && $anyKnown && !$variations->contains(fn($v) => $inStock($v));

    $thumb = asset('public/uploads/products/no-image.jpg');
    if ($defaultVariation && !empty($defaultVariation->image)) {
        $info = pathinfo($defaultVariation->image);
        $thumb = asset(
            'public/uploads/products/variations/thumbs/' . $info['filename'] . '_thumb.' . $info['extension'],
        );
    }

    $price = $defaultVariation ? $defaultVariation->price : $product->price;
    $isWished = in_array($product->id, $wishlistProductIds ?? []);
    $comparePrice = $product->compare_price ?? ($product->mrp ?? null);
    $hasOff = !is_null($comparePrice) && (float) $comparePrice > (float) $price;
    $ratingVal = $product->rating ?? ($product->reviews_avg_rating ?? null);
    $ratingCnt = $product->reviews_count ?? null;

    // variations
    $colorVars = $variations->filter(fn($v) => !empty($v->color) && !empty($v->image))->unique('color');
    $sizeVars = $variations->filter(fn($v) => !empty($v->size))->unique('size');

    // ek colour tabhi available hai jab us colour ki koi ek variation stock me ho
    $colorHasStock = fn($color) => $variations
        ->filter(fn($v) => strcasecmp((string) $v->color, (string) $color) === 0)
        ->contains(fn($v) => $inStock($v));

    // ek size tabhi available hai jab us size ki koi ek variation stock me ho
    $sizeHasStock = fn($size) => $variations
        ->filter(fn($v) => strcasecmp((string) $v->size, (string) $size) === 0)
        ->contains(fn($v) => $inStock($v));

    // form ke default hidden values — hamesha AVAILABLE combination
    $defColor = $defaultVariation->color ?? '';
    $defSize = $defaultVariation->size ?? '';
@endphp

@php
    $varData = $variations
        ->map(function ($v) use ($stockOf) {
            $img = null;
            if (!empty($v->image)) {
                $vi = pathinfo($v->image);
                $img = asset(
                    'public/uploads/products/variations/thumbs/' . $vi['filename'] . '_thumb.' . $vi['extension'],
                );
            }
            return [
                'color' => $v->color,
                'size' => $v->size,
                'price' => $v->price,
                'image' => $img,
                // null = stock pata nahi, number = actual stock
                'quantity' => $stockOf($v),
            ];
        })
        ->values();
@endphp

<article class="pcard pcard-v2 {{ $soldOut ? 'is-soldout' : '' }}" data-vars='@json($varData)'
    data-base-price="{{ $price }}" data-compare="{{ $comparePrice }}">

    {{-- ---------- MEDIA ---------- --}}
    <div class="pc-media">
        <a href="{{ url('product/' . $product->slug) }}" class="pc-imglink">
            <img src="{{ $thumb }}" alt="{{ $product->name }}" class="pc-img main product-image" loading="lazy"
                onerror="this.src='{{ asset('public/uploads/products/no-image.jpg') }}'">
            @if ($product->hover_image)
                <img src="{{ asset('public/uploads/products/hover/' . $product->hover_image) }}" alt=""
                    class="pc-img alt" loading="lazy">
            @endif
        </a>

        {{-- wishlist — image ke upar --}}
        <a href="{{ route('wishlist.toggle', $product->id) }}" data-id="{{ $product->id }}"
            class="pc-fav {{ $isWished ? 'on' : '' }}"
            title="{{ $isWished ? 'Remove from wishlist' : 'Add to wishlist' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z" />
            </svg>
        </a>

        <div class="pc-badges">
            @if ($soldOut)
                <span class="pc-label out">Sold out</span>
            @else
                @if ($product->is_new_arrival)
                    <span class="pc-label">New</span>
                @endif
                @if ($hasOff)
                    <span class="pc-label sale">Sale</span>
                @endif
            @endif
        </div>
    </div>

    <div class="pc-body">

        {{-- ---------- CATEGORY + AUTHENTIC ---------- --}}
        <div class="pc-cat-row">
            <div class="pc-cat">
                <a href="{{ $product->category ? route('product.category', $product->category->slug) : '#' }}">
                    {{ $product->category->name ?? 'Handmade' }}
                </a>
            </div>

            <span class="pc-authentic">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z" />
                    <path d="m9 12 2 2 4-4" />
                </svg> Authentic Handmade
            </span>
        </div>

        <h3 class="pc-title">
            <a href="{{ url('product/' . $product->slug) }}">{{ $product->name }}</a>
        </h3>

        {{-- ---------- RATING ---------- --}}
        @if (!is_null($ratingVal))
            @php $pct = (min(5, max(0, (float) $ratingVal)) / 5) * 100; @endphp
            <div class="pc-meta">
                <div class="pc-rating">
                    <span class="pc-stars"><i style="width: {{ $pct }}%"></i></span>
                    @if (!is_null($ratingCnt))
                        <span class="pc-rcount">({{ $ratingCnt }})</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- ---------- PRICE ---------- --}}
        <div class="pc-price-row">
            <span class="pc-price product-price">₹{{ number_format($price, 2) }}</span>
            @if ($hasOff)
                <span class="pc-price-right">
                    <span class="pc-price-old">₹{{ number_format($comparePrice, 2) }}</span>
                    <span class="pc-price-off">{{ round((($comparePrice - $price) / $comparePrice) * 100) }}%
                        OFF</span>
                </span>
            @endif
        </div>

        {{-- ---------- ORNAMENT DIVIDER ---------- --}}
        <div class="pc-orn">
            <span class="ln"></span>
            <svg viewBox="0 0 40 16" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M20 3c3 0 5 2 5 5s-2 5-5 5-5-2-5-5 2-5 5-5z" />
                <path d="M15 8H8M25 8h7M8 8 4 5v6zM32 8l4-3v6z" />
            </svg>
            <span class="ln"></span>
        </div>

        {{-- ---------- VARIATIONS ---------- --}}
        <div class="pc-vars">
            @if ($sizeVars->count())
                @php $firstOkSize = true; @endphp
                <div class="pc-group pc-sizes">
                    <span class="pc-glabel">Size</span>
                    <div class="pc-chips">
                        @foreach ($sizeVars as $var)
                            @php
                                $ok = $sizeHasStock($var->size);
                                $makeActive = $ok && $firstOkSize;
                                if ($makeActive) {
                                    $firstOkSize = false;
                                }
                            @endphp
                            <button type="button"
                                class="pc-chip {{ $ok ? '' : 'pc-off' }} {{ $makeActive ? 'active' : '' }}"
                                data-size="{{ $var->size }}"
                                @if (!$ok) aria-disabled="true" title="Out of stock" @endif>{{ $var->size }}</button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="pc-group pc-uniquebox">
                    <div class="pc-unique">
                        <span class="pc-unique-ic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 11v5M12 8h.01" />
                            </svg>
                        </span>
                        <span class="pc-unique-txt">
                            <b>This piece is one of a kind</b>
                            <small>Handmade — no size variations.</small>
                        </span>
                    </div>
                </div>
            @endif

            @if ($colorVars->count())
                @php $firstOkColor = true; @endphp
                <div class="pc-group pc-finish">
                    <span class="pc-glabel">Finish</span>
                    <div class="pc-swatches">
                        @foreach ($colorVars as $var)
                            @php
                                $vInfo = pathinfo($var->image);
                                $vPath = asset(
                                    'public/uploads/products/variations/thumbs/' .
                                        $vInfo['filename'] .
                                        '_thumb.' .
                                        $vInfo['extension'],
                                );
                                $okC = $colorHasStock($var->color);
                                $activeC = $okC && $firstOkColor;
                                if ($activeC) {
                                    $firstOkColor = false;
                                }
                            @endphp
                            <span class="color-dot {{ $okC ? '' : 'pc-off' }} {{ $activeC ? 'active' : '' }}"
                                title="{{ $var->color }}{{ $okC ? '' : ' — out of stock' }}"
                                data-color="{{ $var->color }}"
                                style="background-image:url('{{ $vPath }}'); background-color:{{ strtolower($var->color) }}"
                                data-image="{{ $vPath }}" data-price="{{ $var->price }}"></span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ---------- SHIPPING BAR ---------- --}}
        <div class="pc-ship">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" />
                <circle cx="7" cy="18" r="1.6" />
                <circle cx="17" cy="18" r="1.6" />
            </svg> Ships in 3–5 days
        </div>

        {{-- ---------- ADD TO CART ---------- --}}
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="pc-cartform">
            @csrf
            <input type="hidden" name="color" class="pc-in-color" value="{{ $defColor }}">
            <input type="hidden" name="size" class="pc-in-size" value="{{ $defSize }}">
            <input type="hidden" name="quantity" value="1">

            <button type="submit" class="pc-add" @if ($soldOut) disabled @endif>
                @if ($soldOut)
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M5.6 5.6l12.8 12.8" />
                    </svg> Sold out
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 8h12l-1 12H7L6 8z" />
                        <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                    </svg> Add to Cart
                @endif
            </button>
        </form>

        {{-- ---------- TRUST ROW ---------- --}}
        <div class="pc-trust">
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
</article>