@extends('frontend.layout.arthubly')

@section('title', 'About — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="about-hero">
            <span class="eyebrow">Our story</span>
            <h1>Where the <em class="serif-it">work of the hand</em> finds its people</h1>
            <p>Arthubly is a marketplace for handmade &amp; handicraft — connecting independent artisans with people who value craft, provenance, and the quiet marks of a maker. Every piece carries a name, a place, and a pair of hands behind it.</p>
        </div>

        <div class="ed" style="padding-top:0">
            <div class="wrap" style="max-width:900px">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px">
                    <div class="ac-stat" style="text-align:center"><b style="color:var(--brass-d)">Maker-signed</b><small>A name behind every piece</small></div>
                    <div class="ac-stat" style="text-align:center"><b style="color:var(--brass-d)">Fair to artisans</b><small>Makers set the price</small></div>
                    <div class="ac-stat" style="text-align:center"><b style="color:var(--brass-d)">One of a kind</b><small>Small-batch, never mass-produced</small></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
