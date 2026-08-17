@extends('frontend.layout.arthubly')

@section('title', 'Categories — Arthubly')

@section('content')
    <section class="page active">
        <div class="wrap">
            <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg><span class="cur">Categories</span></div>
            <div class="page-hero" style="text-align:center;max-width:640px;margin:0 auto">
                <span class="eyebrow">Shop by craft</span>
                <h1>Explore every <em class="serif-it">living tradition</em></h1>
                <p style="color:var(--ink-70);margin-top:14px">From canvas to clay — browse work made by hand across our
                    craft categories.</p>
            </div>

            <div class="cat-mosaic-page" data-reveal-group style="margin-top:20px">
                @foreach ($categories as $i => $cat)
                    <a href="{{ route('product.category', $cat->slug) }}" class="cat-tile {{ $i % 5 === 0 ? 'big' : '' }}">
                        <img src="{{ $cat->image ? asset('public/uploads/categories/' . $cat->image) : asset('public/uploads/products/no-image.jpg') }}"
                            alt="{{ $cat->name }}" onerror="this.style.display='none'">
                        <div class="ct-body">
                            <small>Category</small>
                            <h3>{{ $cat->name }}</h3>
                            <div style="color:#D9D0C1;font-size:13px">{{ $cat->products_count ?? 0 }} pieces</div>
                            <span class="shop">Shop now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M5 12h14m-6-6 6 6-6 6" />
                                </svg></span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
