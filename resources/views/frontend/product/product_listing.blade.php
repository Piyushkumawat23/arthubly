@extends('frontend.layout.arthubly')

@section('title', ($activeCategory->name ?? 'Shop') . ' — Arthubly')

@section('content')
    @php
        $currentCategorySlug = request()->route('slug') ?? request('category');
        $activeCategory = collect($categories)->firstWhere('slug', $currentCategorySlug);
        $hasFilters = request()->hasAny(['size', 'color', 'min_price', 'max_price']);
    @endphp
    <section class="page active" id="page-shop">
        <div class="wrap">
            <div class="crumbs">
                <a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <a href="{{ route('product.categories_list') }}">Categories</a><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <span class="cur">{{ $activeCategory->name ?? 'Products' }}</span>
            </div>

            <div class="list-layout">
                {{-- ===== FILTERS ===== --}}
                <aside class="filters" id="shopFilters">
                    <div class="filter-card">
                        <div class="fc-head">
                            <b>Filters</b>
                            <button type="button" class="filters-close"
                                onclick="document.getElementById('shopFilters').classList.remove('open');document.getElementById('filtersOverlay').classList.remove('show');document.body.style.overflow='';"
                                style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--ink-50)">✕</button>
                        </div>

                        <form action="{{ url()->current() }}" method="GET" id="filterForm">
                            @if (request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif

                            <details class="fgroup" open>
                                <summary>Categories <svg class="chev" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg></summary>
                                <div class="fg-body">
                                    @foreach ($categories as $cat)
                                        <label class="fopt">
                                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                                {{ $currentCategorySlug === $cat->slug ? 'checked' : '' }}
                                                onchange="window.location.href='{{ route('product.category', $cat->slug) }}';">
                                            {{ $cat->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </details>

                            @if ($sizes->count())
                                <details class="fgroup" open>
                                    <summary>Size <svg class="chev" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg></summary>
                                    <div class="fg-body">
                                        @foreach ($sizes as $size)
                                            <label class="fopt"><input type="radio" name="size"
                                                    value="{{ $size->name }}"
                                                    {{ request('size') === $size->name ? 'checked' : '' }}
                                                    onchange="this.form.submit();"> {{ $size->name }}</label>
                                        @endforeach
                                    </div>
                                </details>
                            @endif

                            @if ($colors->count())
                                <details class="fgroup" open>
                                    <summary>Color <svg class="chev" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg></summary>
                                    <div class="fg-body">
                                        <div class="swatches">
                                            @foreach ($colors as $color)
                                                <label class="swatch {{ request('color') === $color->name ? 'sel' : '' }}"
                                                    style="background:{{ strtolower($color->name) }}"
                                                    title="{{ $color->name }}">
                                                    <input type="radio" name="color" value="{{ $color->name }}"
                                                        {{ request('color') === $color->name ? 'checked' : '' }}
                                                        onchange="this.form.submit();" style="display:none">
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </details>
                            @endif

                            <details class="fgroup" open>
                                <summary>Price range <svg class="chev" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg></summary>
                                <div class="fg-body">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                                        <input type="number" name="min_price" placeholder="Min ₹"
                                            value="{{ request('min_price') }}"
                                            style="width:100%;height:42px;border:1.5px solid var(--line);border-radius:var(--r-md);padding:0 12px;background:var(--surface)">
                                        <span style="color:var(--ink-50)">–</span>
                                        <input type="number" name="max_price" placeholder="Max ₹"
                                            value="{{ request('max_price') }}"
                                            style="width:100%;height:42px;border:1.5px solid var(--line);border-radius:var(--r-md);padding:0 12px;background:var(--surface)">
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                        style="width:100%;justify-content:center;height:42px">Apply</button>
                                </div>
                            </details>

                            @if ($hasFilters)
                                <div style="padding:16px 18px"><a href="{{ url()->current() }}" class="btn btn-ghost"
                                        style="width:100%;justify-content:center;height:42px">Clear all filters</a></div>
                            @endif
                        </form>
                    </div>
                </aside>
                <div class="filters-overlay" id="filtersOverlay"
                    onclick="document.getElementById('shopFilters').classList.remove('open');this.classList.remove('show');document.body.style.overflow='';">
                </div>

                {{-- ===== PRODUCTS ===== --}}
                <div>
                    <div class="listing-head" style="border:none;padding:0 0 18px">
                        <h1>{{ $activeCategory->name ?? 'Shop' }}</h1>
                        <div class="meta"><span class="cnt">Showing
                                <b>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</b> of
                                <b>{{ $products->total() }}</b> pieces</span></div>
                    </div>

                    <div class="toolbar">
                        <button type="button" class="filter-mobile-btn"
                            onclick="document.getElementById('shopFilters').classList.add('open');document.getElementById('filtersOverlay').classList.add('show');document.body.style.overflow='hidden';">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M7 12h10M10 18h4" />
                            </svg> Filters
                        </button>
                        @php
                            $sortLabels = [
                                'newest' => 'Newest arrivals',
                                'price_low' => 'Price: Low to High',
                                'price_high' => 'Price: High to Low',
                            ];
                            $activeSort = request('sort', 'newest');
                        @endphp
                        <div class="sort-wrap" style="margin-left:auto">
                            <form action="{{ url()->current() }}" method="GET" id="sortForm">
                                @foreach (request()->except(['sort', 'page', 'category']) as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach
                                <input type="hidden" name="sort" id="sortValue" value="{{ $activeSort }}">
                            </form>
                            <div class="sel-box sort-select-custom" id="sortBox" tabindex="0" role="button"
                                aria-haspopup="listbox" aria-expanded="false">
                                <span>Sort: <b
                                        id="sortLabel">{{ $sortLabels[$activeSort] ?? 'Newest arrivals' }}</b></span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <ul class="sel-list" role="listbox">
                                    @foreach ($sortLabels as $val => $label)
                                        <li role="option" data-value="{{ $val }}"
                                            class="{{ $activeSort == $val ? 'active' : '' }}">{{ $label }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($products->count() > 0)
                        <div class="grid cols-3" data-reveal-group>
                            @foreach ($products as $product)
                                @include('frontend.partials.arthubly-product-card', ['product' => $product])
                            @endforeach
                        </div>

                        <div style="margin-top:40px">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    @else
                        <div class="empty-state">
                            <div class="es-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.6">
                                    <circle cx="11" cy="11" r="7" />
                                    <path d="m20 20-3.5-3.5" />
                                </svg></div>
                            <h3>No products found</h3>
                            <p>Try adjusting your filters or search terms.</p>
                            <a href="{{ url()->current() }}" class="btn btn-primary btn-lg">Clear filters</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- New arrivals --}}
            @if (isset($newArrivals) && $newArrivals->count() > 0)
                <section class="ed ed-shelf">
                    <div class="ed-head ed-head--center"><span class="eyebrow">Straight from the studio</span>
                        <h2 class="ed-title">New arrivals</h2>
                    </div>
                    <div class="grid cols-3" data-reveal-group>
                        @foreach ($newArrivals->take(4) as $product)
                            @include('frontend.partials.arthubly-product-card', ['product' => $product])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
@endsection

{{-- Product card variation logic is now in arthubly.js (pcard-v2 block) --}}