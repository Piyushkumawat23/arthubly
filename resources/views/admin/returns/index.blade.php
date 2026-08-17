@extends('admin.layout.app')

@section('content')
<style>
    .returns-index{--bg:#f6f7f9;--border:#e6e8ed;--text:#171a21;--muted:#747b88;background:var(--bg);min-height:100vh;padding:24px}
    .ri-header{display:flex;align-items:center;gap:13px;margin-bottom:20px}.ri-icon{width:46px;height:46px;border-radius:12px;background:#111827;color:#fff;display:grid;place-items:center}.ri-title{margin:0;color:var(--text);font-size:24px;font-weight:750}.ri-sub{margin:5px 0 0;color:var(--muted);font-size:12px}
    .ri-filter-badge{display:inline-flex;padding:5px 9px;border-radius:999px;background:#e0f2fe;color:#0369a1;font-size:9px;font-weight:700;vertical-align:middle;margin-left:7px}
    .ri-alert{border-radius:9px;font-size:12px}.ri-summary{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}.ri-stat{display:flex;align-items:center;justify-content:space-between;padding:17px;background:#fff;border:1px solid var(--border);border-radius:14px;color:inherit;text-decoration:none!important;box-shadow:0 3px 14px rgba(16,24,40,.035);transition:.15s}.ri-stat:hover{transform:translateY(-1px);box-shadow:0 5px 18px rgba(16,24,40,.06)}
    .ri-stat-label{font-size:10px;color:var(--muted);font-weight:650;margin-bottom:5px}.ri-stat-num{font-size:23px;line-height:1;font-weight:800;color:var(--text)}.ri-stat-icon{width:38px;height:38px;border-radius:10px;display:grid;place-items:center;font-size:14px}.ri-warning{background:#fffbeb;color:#b45309}.ri-info{background:#eff6ff;color:#2563eb}.ri-success{background:#ecfdf3;color:#15803d}.ri-danger{background:#fef2f2;color:#dc2626}
    .ri-tabs{display:flex;gap:7px;padding:5px;background:#fff;border:1px solid var(--border);border-radius:11px;overflow-x:auto;margin-bottom:18px}.ri-tabs .nav-link{padding:8px 13px;border-radius:7px;color:#626a77;font-size:10px;font-weight:600;white-space:nowrap}.ri-tabs .nav-link.active{background:#111827;color:#fff}.ri-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:0 3px 14px rgba(16,24,40,.035)}
    .ri-table-wrap{overflow-x:auto}.ri-table{width:100%;min-width:1080px;margin:0;font-size:11px}.ri-table thead th{padding:12px 11px;background:#f8fafc;color:#68707d;border:0;border-bottom:1px solid var(--border);font-size:9px;text-transform:uppercase;letter-spacing:.03em;white-space:nowrap}.ri-table tbody td{padding:13px 11px;border:0;border-bottom:1px solid #eef0f3;vertical-align:middle;color:#4b5563}.ri-table tbody tr:last-child td{border-bottom:0}.ri-table tbody tr:hover{background:#fbfcfd}
    .ri-id,.ri-order{font-weight:750;color:#171a21}.ri-customer,.ri-product{color:#303640;font-weight:600}.ri-muted{font-size:9px;color:#8a909c}.ri-qty{display:inline-flex;min-width:25px;justify-content:center;padding:4px 7px;border-radius:5px;background:#f1f5f9;color:#475569;font-size:9px;font-weight:700}.ri-badge{display:inline-flex;padding:5px 8px;border-radius:999px;font-size:9px;font-weight:700}.ri-amount{font-weight:750;color:#171a21;white-space:nowrap}.ri-date{font-size:10px;color:#747b88;white-space:nowrap}.ri-view{border-radius:7px!important;padding:7px 11px!important;font-size:10px!important;font-weight:600!important}.ri-empty{padding:48px!important;text-align:center;color:#9ca3af}.ri-empty i{font-size:28px;margin-bottom:9px;color:#c4c9d1}.ri-pagination{padding:14px 18px;border-top:1px solid var(--border);display:flex;justify-content:flex-end}
    @media(max-width:900px){.ri-summary{grid-template-columns:repeat(2,1fr)}}@media(max-width:600px){.returns-index{padding:14px}.ri-title{font-size:20px}.ri-summary{grid-template-columns:1fr}.ri-tabs{margin-bottom:14px}}
</style>

<div class="returns-index">
    <div class="ri-header">
        <div class="ri-icon"><i class="fas fa-undo-alt"></i></div>
        <div>
            <h1 class="ri-title">
                Returns & Refunds
                @if(request('status'))
                    <span class="ri-filter-badge">{{ request('status') }}</span>
                @elseif(request('refund') === 'pending')
                    <span class="ri-filter-badge" style="background:#fffbeb;color:#b45309;">Refund Pending</span>
                @endif
            </h1>
            <p class="ri-sub">Manage return requests, approvals and customer refunds.</p>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success ri-alert">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger ri-alert">{{ session('error') }}</div>@endif

    <div class="ri-summary">
        <a href="{{ route('admin.returns.index',['status'=>'Pending']) }}" class="ri-stat">
            <div><div class="ri-stat-label">Return Requests (Pending)</div><div class="ri-stat-num">{{ $counts['pending'] }}</div></div><div class="ri-stat-icon ri-warning"><i class="fas fa-hourglass-half"></i></div>
        </a>
        <a href="{{ route('admin.returns.index',['refund'=>'pending']) }}" class="ri-stat">
            <div><div class="ri-stat-label">Refund Requests</div><div class="ri-stat-num">{{ $counts['refund_pending'] }}</div></div><div class="ri-stat-icon ri-info"><i class="fas fa-money-bill-wave"></i></div>
        </a>
        <a href="{{ route('admin.returns.index',['status'=>'Approved']) }}" class="ri-stat">
            <div><div class="ri-stat-label">Approved</div><div class="ri-stat-num">{{ $counts['approved'] }}</div></div><div class="ri-stat-icon ri-success"><i class="fas fa-check-circle"></i></div>
        </a>
        <a href="{{ route('admin.returns.index',['status'=>'Rejected']) }}" class="ri-stat">
            <div><div class="ri-stat-label">Rejected</div><div class="ri-stat-num">{{ $counts['rejected'] }}</div></div><div class="ri-stat-icon ri-danger"><i class="fas fa-times-circle"></i></div>
        </a>
    </div>

    <div class="ri-tabs nav nav-pills">
        <a class="nav-link {{ !request('status') && !request('refund') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}">All</a>
        <a class="nav-link {{ request('status') === 'Pending' ? 'active' : '' }}" href="{{ route('admin.returns.index',['status'=>'Pending']) }}">Return Requests</a>
        <a class="nav-link {{ request('refund') === 'pending' ? 'active' : '' }}" href="{{ route('admin.returns.index',['refund'=>'pending']) }}">Refund Requests</a>
        <a class="nav-link {{ request('status') === 'Approved' ? 'active' : '' }}" href="{{ route('admin.returns.index',['status'=>'Approved']) }}">Approved</a>
        <a class="nav-link {{ request('status') === 'Rejected' ? 'active' : '' }}" href="{{ route('admin.returns.index',['status'=>'Rejected']) }}">Rejected</a>
    </div>

    <div class="ri-card">
        <div class="ri-table-wrap">
            <table class="table ri-table">
                <thead><tr><th>#</th><th>Order</th><th>Customer</th><th>Product</th><th class="text-center">Qty</th><th>Reason</th><th>Status</th><th>Refund</th><th class="text-right">Amount</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                @forelse($returns as $r)
                    <tr>
                        <td><span class="ri-id">#{{ $r->id }}</span></td>
                        <td><span class="ri-order">#{{ $r->order_id }}</span></td>
                        <td><div class="ri-customer">{{ $r->user->name ?? ($r->order->name ?? '-') }}</div></td>
                        <td><div class="ri-product">{{ $r->product->name ?? '-' }}</div></td>
                        <td class="text-center"><span class="ri-qty">{{ $r->quantity }}</span></td>
                        <td>{{ $r->reason }}</td>
                        <td>
                            @php $sc=['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger']; @endphp
                            <span class="ri-badge ri-{{ $sc[$r->status] ?? 'secondary' }}">{{ $r->status }}</span>
                        </td>
                        <td><span class="ri-muted">{{ $r->refund_status }}</span></td>
                        <td class="text-right"><span class="ri-amount">&#8377;{{ number_format($r->refund_amount,2) }}</span></td>
                        <td><span class="ri-date">{{ $r->created_at->format('d M Y') }}</span></td>
                        <td><a href="{{ route('admin.returns.show',$r->id) }}" class="btn btn-outline-dark btn-sm ri-view"><i class="fas fa-eye mr-1"></i> View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="ri-empty"><i class="fas fa-undo-alt d-block"></i>Koi return request nahi.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="ri-pagination">{{ $returns->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection