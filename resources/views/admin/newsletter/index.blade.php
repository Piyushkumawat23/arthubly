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
        <div class="nl-title-wrap">
            <div class="nl-icon"><i class="fas fa-envelope-open-text"></i></div>
            <div><h1 class="nl-title">Newsletter Subscribers</h1><p class="nl-sub">Manage subscriber emails and send newsletters.</p></div>
        </div>
        <a href="{{ route('admin.newsletter.show') }}" class="btn btn-dark nl-btn"><i class="fas fa-paper-plane mr-1"></i> Send Newsletter</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success nl-alert alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
    @endif

    <div class="nl-card mb-4">
        <div class="nl-card-head"><h2 class="nl-card-title"><i class="fas fa-user-plus mr-1"></i> Add New Subscriber</h2></div>
        <div class="nl-card-body">
            <form action="{{ route('admin.newsletter.store') }}" method="POST" class="row align-items-end">
                @csrf
                <div class="col-md-9 mb-2 mb-md-0"><label class="nl-label" for="email">Email Address</label><input type="email" name="email" id="email" class="form-control nl-input" placeholder="Enter email address" required></div>
                <div class="col-md-3"><button type="submit" class="btn btn-dark nl-btn w-100"><i class="fas fa-plus mr-1"></i> Subscribe</button></div>
            </form>
        </div>
    </div>

    <div class="nl-card">
        <div class="nl-card-head"><h2 class="nl-card-title"><i class="fas fa-users mr-1"></i> Subscribers List</h2><span style="font-size:10px;color:#8a909c;">{{ $subscribers->count() }} shown</span></div>
        <div class="table-responsive">
            <table class="table nl-table">
                <thead><tr><th>#</th><th>Email</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td style="color:#8a909c;font-weight:700;">{{ $loop->iteration }}</td>
                        <td><span class="nl-email">{{ $subscriber->email }}</span></td>
                        <td>
                            @if(strtolower($subscriber->status)=='active' || strtolower($subscriber->status)=='subscribed')
                                <span class="nl-status nl-active">{{ ucfirst($subscriber->status) }}</span>
                            @else
                                <span class="nl-status nl-muted">{{ ucfirst($subscriber->status) }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.newsletter.edit', $subscriber->id) }}" class="btn btn-outline-primary nl-action"><i class="fas fa-edit mr-1"></i>Edit</a>
                            <a href="{{ route('admin.newsletter.delete', $subscriber->id) }}" class="btn btn-outline-danger nl-action ml-1" onclick="return confirm('Are you sure you want to delete this subscriber?')"><i class="fas fa-trash mr-1"></i>Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-5"><i class="fas fa-inbox d-block mb-2" style="font-size:26px;"></i>No Subscribers Found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="nl-footer">{{ $subscribers->links() }}</div>
    </div>
</div>
@endsection