<div class="sk-page">
    @include('frontend.partials.skeletons._head')
    <span class="sk-crumb skeleton"></span>
    <span class="sk-line h1 sk-w30 skeleton" style="margin-bottom:24px"></span>
    <div class="sk-2col cart">
        <div class="sk-panel">
            <span class="sk-line h2 sk-w40 skeleton" style="margin-bottom:22px"></span>
            <span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span>
            <span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span>
            <div style="display:flex;gap:14px">
                <div style="flex:1"><span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span></div>
                <div style="flex:1"><span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span></div>
            </div>
            <span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span>
            <div style="display:flex;gap:14px">
                <div style="flex:1"><span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span></div>
                <div style="flex:1"><span class="sk-fieldlabel skeleton"></span><span class="sk-field skeleton"></span></div>
            </div>
        </div>
        <div class="sk-panel">
            <span class="sk-line h2 sk-w60 skeleton" style="margin-bottom:20px"></span>
            @for($i=0;$i<3;$i++)<div style="display:flex;justify-content:space-between;margin-bottom:14px"><span class="sk-line skeleton" style="width:100px"></span><span class="sk-line skeleton" style="width:56px"></span></div>@endfor
            <div style="border-top:1px solid var(--line);margin:14px 0;padding-top:14px;display:flex;justify-content:space-between"><span class="sk-line skeleton" style="width:70px;height:18px"></span><span class="sk-line skeleton" style="width:80px;height:18px"></span></div>
            <span class="sk-field skeleton" style="margin-bottom:10px"></span>
            <span class="sk-field skeleton" style="margin-bottom:18px"></span>
            <span class="sk-btn skeleton" style="width:100%"></span>
        </div>
    </div>
</div>
