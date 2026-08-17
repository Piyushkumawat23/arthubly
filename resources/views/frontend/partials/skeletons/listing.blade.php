<div class="sk-page">
    @include('frontend.partials.skeletons._head')
    <span class="sk-crumb skeleton"></span>
    <div class="sk-2col filters">
        <div class="sk-filter">
            <div class="sk-fh"><span class="sk-line skeleton" style="width:80px;height:16px"></span><span class="sk-line skeleton" style="width:52px"></span></div>
            @for($i=0;$i<8;$i++)<div class="sk-fg"><span class="sk-line skeleton" style="width:{{ [90,70,110,80,60,100,75,95][$i] }}px;height:15px"></span><span class="sk-line skeleton" style="width:14px;height:14px"></span></div>@endfor
        </div>
        <div>
            <div class="sk-toolbar"><span class="sk-line skeleton" style="width:170px;height:16px"></span><span class="sk-line skeleton" style="width:150px;height:44px;border-radius:10px"></span></div>
            <div class="sk-grid g3">
                @for($i=0;$i<6;$i++)<div class="sk-card"><span class="sk-img skeleton"></span><span class="sk-line sk-w40 skeleton"></span><span class="sk-line h2 sk-w80 skeleton"></span><span class="sk-line sk-w30 skeleton"></span></div>@endfor
            </div>
        </div>
    </div>
</div>
