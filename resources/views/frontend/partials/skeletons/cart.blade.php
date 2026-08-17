<div class="sk-page">
    @include('frontend.partials.skeletons._head')
    <span class="sk-crumb skeleton"></span>
    <span class="sk-line h1 sk-w30 skeleton" style="margin-bottom:24px"></span>
    <div class="sk-2col cart">
        <div>
            @for($i=0;$i<3;$i++)
            <div class="sk-cart-item">
                <span class="sk-img skeleton"></span>
                <div class="sk-ci-lines"><span class="sk-line sk-w60 skeleton"></span><span class="sk-line sk-w30 skeleton"></span><span class="sk-line sm sk-w20 skeleton"></span></div>
                <span class="sk-line skeleton" style="width:74px;height:18px"></span>
            </div>
            @endfor
        </div>
        <div class="sk-panel">
            <span class="sk-line h2 sk-w50 skeleton" style="margin-bottom:20px"></span>
            @for($i=0;$i<4;$i++)<div style="display:flex;justify-content:space-between;margin-bottom:14px"><span class="sk-line skeleton" style="width:90px"></span><span class="sk-line skeleton" style="width:60px"></span></div>@endfor
            <span class="sk-btn skeleton" style="width:100%;margin-top:12px"></span>
        </div>
    </div>
</div>
