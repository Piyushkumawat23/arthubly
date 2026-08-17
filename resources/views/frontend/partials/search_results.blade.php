@extends('frontend.layout.arthubly')

@section('title', 'Search: ' . $queryText . ' — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Search</span></div>

        <div class="listing-head" style="border:none;padding:0 0 22px">
            <h1>Search results</h1>
            <div class="meta"><span class="cnt">{{ $products->total() }} result(s) for <b>“{{ $queryText }}”</b></span></div>
        </div>

        @if($products->count() > 0)
            <div class="grid cols-4" data-reveal-group>
                @foreach($products as $product)
                    @include('frontend.partials.arthubly-product-card', ['product' => $product])
                @endforeach
            </div>
            <div style="margin-top:40px">{{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
        @else
            <div class="empty-state">
                <div class="es-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg></div>
                <h3>No results for “{{ $queryText }}”</h3>
                <p>Try a different spelling, or browse by category.</p>
                <a href="{{ route('product.categories_list') }}" class="btn btn-primary btn-lg">Browse categories</a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    $(document).on('click', '.pcard .color-dot', function(){
        var $d=$(this), img=$d.data('image'), price=$d.data('price'), color=$d.attr('title');
        $d.siblings().removeClass('active'); $d.addClass('active');
        var $c=$d.closest('.pcard');
        if(img) $c.find('.img.main').attr('src', img);
        if(price!=null && price!=='') $c.find('.price').text('₹'+parseFloat(price).toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2}));
        if(color){ $c.find('a.p-link').each(function(){ var u=$(this).attr('href'); if(u&&u.indexOf('product/')>-1){ $(this).attr('href', u.split('?')[0]+'?color='+encodeURIComponent(color)); } }); }
    });
});
</script>
@endpush
