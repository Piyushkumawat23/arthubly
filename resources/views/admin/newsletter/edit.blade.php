@extends('admin.layout.app')
@section('content')

<style>
.nl-page{background:#f6f7f9;min-height:100vh;padding:24px;color:#171a21}
.nl-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;gap:15px}
.nl-title-wrap{display:flex;align-items:center;gap:13px}.nl-icon{width:46px;height:46px;border-radius:12px;background:#111827;color:#fff;display:grid;place-items:center}
.nl-title{margin:0;font-size:23px;font-weight:750}.nl-sub{margin:4px 0 0;color:#7b8390;font-size:11px}
.nl-card{background:#fff;border:1px solid #e6e8ed;border-radius:14px;box-shadow:0 3px 14px rgba(16,24,40,.035);overflow:hidden}
.nl-card-head{padding:15px 18px;border-bottom:1px solid #e8eaee;display:flex;justify-content:space-between;align-items:center}
.nl-card-title{margin:0;font-size:13px;font-weight:700}.nl-card-body{padding:20px}
.nl-btn{border-radius:8px!important;font-size:11px!important;font-weight:650!important;padding:9px 13px!important}
.nl-label{display:block;font-size:10px;font-weight:700;color:#4b5563;margin-bottom:7px}
.nl-input{height:40px!important;border:1px solid #dfe3e8!important;border-radius:8px!important;box-shadow:none!important;font-size:11px!important}
.nl-input:focus{border-color:#9ca3af!important}
.nl-table{width:100%;font-size:11px;margin:0}.nl-table thead th{padding:12px;background:#f8fafc;border:0;border-bottom:1px solid #e6e8ed;color:#68707d;text-transform:uppercase;font-size:9px;letter-spacing:.03em}
.nl-table td{padding:13px 12px;border:0;border-bottom:1px solid #eef0f3;vertical-align:middle;color:#4b5563}.nl-table tr:last-child td{border-bottom:0}.nl-table tbody tr:hover{background:#fbfcfd}
.nl-email{font-weight:650;color:#252a34}.nl-status{display:inline-flex;padding:5px 9px;border-radius:999px;font-size:9px;font-weight:700}.nl-active{background:#ecfdf3;color:#15803d}.nl-muted{background:#f1f5f9;color:#64748b}
.nl-action{border-radius:7px!important;padding:6px 10px!important;font-size:10px!important;font-weight:600!important}
.nl-footer{padding:13px 17px;border-top:1px solid #e6e8ed;display:flex;justify-content:flex-end}
.nl-alert{font-size:11px;border-radius:9px}
.nl-compose{max-width:1000px}.nl-help{font-size:9px;color:#9298a2;margin-top:5px;display:block}
.nl-editor textarea{min-height:220px}
@media(max-width:767px){.nl-page{padding:14px}.nl-head{flex-direction:column;align-items:flex-start}.nl-head .nl-btn{width:100%}.nl-card-body{padding:15px}}
</style>

<div class="nl-page">
    <div class="nl-head">
        <div class="nl-title-wrap"><div class="nl-icon"><i class="fas fa-user-edit"></i></div><div><h1 class="nl-title">Edit Subscriber</h1><p class="nl-sub">Update subscriber email and subscription status.</p></div></div>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline-secondary nl-btn"><i class="fas fa-arrow-left mr-1"></i> Back to Subscribers</a>
    </div>

    @if(session('success'))<div class="alert alert-success nl-alert">{{ session('success') }}</div>@endif

    <div class="nl-compose">
        <div class="nl-card">
            <div class="nl-card-head"><h2 class="nl-card-title"><i class="fas fa-pen mr-1"></i> Subscriber Information</h2></div>
            <form action="{{ route('admin.newsletter.update', $subscriber->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="nl-card-body">
                    <div class="mb-4"><label for="email" class="nl-label">Email Address</label><input type="email" name="email" id="email" class="form-control nl-input" value="{{ $subscriber->email }}" required></div>
                    <div><label for="status" class="nl-label">Subscription Status</label><select name="status" id="status" class="form-control nl-input" required><option value="subscribed" {{ $subscriber->status=='subscribed'?'selected':'' }}>Subscribed</option><option value="unsubscribed" {{ $subscriber->status=='unsubscribed'?'selected':'' }}>Unsubscribed</option></select><small class="nl-help">Choose whether this subscriber should receive newsletters.</small></div>
                </div>
                <div class="nl-footer"><a href="{{ route('admin.newsletter.index') }}" class="btn btn-light nl-btn mr-2">Cancel</a><button type="submit" class="btn btn-dark nl-btn"><i class="fas fa-save mr-1"></i> Update Subscriber</button></div>
            </form>
        </div>
    </div>
</div>
@endsection