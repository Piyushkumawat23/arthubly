<div class="sk-page">
    @include('frontend.partials.skeletons._head')
    <div class="sk-pills"><span class="sk-pill skeleton"></span><span class="sk-pill skeleton"></span><span class="sk-pill skeleton"></span><span class="sk-pill skeleton"></span><span class="sk-pill skeleton"></span></div>
    <div class="sk-hero">
        <div class="sk-copy"><span class="sk-line sk-w30 skeleton"></span><span class="sk-line h1 sk-w80 skeleton"></span><span class="sk-line h1 sk-w60 skeleton"></span><span class="sk-line sk-w70 skeleton"></span><span class="sk-btn skeleton"></span></div>
        <div class="sk-vis skeleton"></div>
    </div>
    <div class="sk-grid">
        @for($i=0;$i<5;$i++)<div class="sk-card"><span class="sk-img skeleton"></span><span class="sk-line sk-w40 skeleton"></span><span class="sk-line h2 sk-w80 skeleton"></span><span class="sk-line sk-w30 skeleton"></span></div>@endfor
    </div>
</div>
