@extends('admin.layout.app')

@section('content')
<style>
    .returns-page{--bg:#f6f7f9;--card:#fff;--border:#e6e8ed;--text:#171a21;--muted:#747b88;background:var(--bg);min-height:100vh;padding:24px}
    .rt-header{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}
    .rt-title-wrap{display:flex;align-items:center;gap:13px}.rt-icon{width:46px;height:46px;border-radius:12px;background:#111827;color:#fff;display:grid;place-items:center}
    .rt-title{margin:0;font-size:24px;font-weight:750;color:var(--text)}.rt-subtitle{margin:5px 0 0;color:var(--muted);font-size:12px}
    .rt-back{border-radius:8px!important;font-size:11px!important;padding:9px 13px!important}
    .rt-alert{border-radius:9px;font-size:12px}.rt-card{background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 3px 14px rgba(16,24,40,.035);overflow:hidden;margin-bottom:18px}
    .rt-card-head{padding:14px 17px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;gap:10px}
    .rt-card-title{margin:0;font-size:13px;font-weight:700;color:var(--text)}.rt-card-title i{color:#737b87;margin-right:7px}
    .rt-body{padding:17px}.rt-table{margin:0;font-size:11px}.rt-table th{width:40%;padding:10px 8px;color:#747b88;font-size:10px;border-color:#eef0f3;font-weight:700}.rt-table td{padding:10px 8px;color:#303640;border-color:#eef0f3}
    .rt-badge{display:inline-flex;padding:5px 9px;border-radius:999px;font-size:9px;font-weight:700}.rt-warning{background:#fffbeb;color:#b45309}.rt-success{background:#ecfdf3;color:#15803d}.rt-danger{background:#fef2f2;color:#dc2626}.rt-secondary{background:#f1f5f9;color:#475569}
    .rt-proof{width:100%;max-height:330px;object-fit:contain;border-radius:9px;background:#f8fafc;border:1px solid #edf0f3}
    .rt-label{font-size:10px;font-weight:700;color:#4b5563;margin-bottom:6px}.rt-form .form-control{border:1px solid #dfe3e8;border-radius:8px;box-shadow:none;font-size:11px}.rt-form .form-control:focus{box-shadow:none;border-color:#9ca3af}
    .rt-action{border-radius:8px!important;font-size:11px!important;font-weight:650!important}.rt-divider{border-top:1px solid #edf0f3;margin:17px 0}
    .rt-refunded{text-align:center;padding:8px}.rt-refunded-icon{font-size:42px;color:#16a34a}.rt-refunded h5{font-size:14px;font-weight:700;color:#171a21}
    .rt-note{font-size:11px;color:#747b88;line-height:1.6}.rt-note strong{color:#374151}
    .rt-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center}.rt-overlay-inner{text-align:center;color:#fff}.rt-overlay-text{margin-top:18px;font-size:16px;font-weight:500}.rt-overlay-sub{margin-top:6px;font-size:12px;opacity:.8}
    @media(max-width:767px){.returns-page{padding:14px}.rt-header{align-items:flex-start;flex-direction:column}.rt-title{font-size:20px}.rt-back{width:100%}}
</style>

<div class="returns-page">
    <div class="rt-header">
        <div class="rt-title-wrap">
            <div class="rt-icon"><i class="fas fa-undo-alt"></i></div>
            <div>
                <h1 class="rt-title">Return #{{ $return->id }}</h1>
                <p class="rt-subtitle">Review the return request, proof and refund details.</p>
            </div>
        </div>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary rt-back">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rt-alert" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rt-alert" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rt-alert" role="alert">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="rt-card">
                <div class="rt-card-head">
                    <h2 class="rt-card-title"><i class="fas fa-file-alt"></i> Return Details</h2>
                    @php $sc=['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger']; @endphp
                    <span class="rt-badge rt-{{ $sc[$return->status] ?? 'secondary' }}">{{ $return->status }}</span>
                </div>
                <div class="rt-body">
                    <table class="table table-sm rt-table">
                        <tr><th>Order</th><td><a href="{{ route('admin.orders.show', $return->order_id) }}">#{{ $return->order_id }}</a></td></tr>
                        <tr><th>Customer</th><td>{{ $return->user->name ?? ($return->order->name ?? '-') }}</td></tr>
                        <tr><th>Product</th><td>{{ $return->product->name ?? '-' }}</td></tr>
                        <tr><th>Quantity</th><td>{{ $return->quantity }}</td></tr>
                        <tr><th>Reason</th><td>{{ $return->reason }}</td></tr>
                        <tr><th>Comment</th><td>{{ $return->comment ?: '-' }}</td></tr>
                        <tr><th>Refund Amount</th><td><strong>&#8377;{{ number_format($return->refund_amount, 2) }}</strong></td></tr>
                        <tr><th>Refund Status</th><td>{{ $return->refund_status }}</td></tr>
                        <tr><th>Requested On</th><td>{{ $return->created_at->format('d M Y, h:i A') }}</td></tr>
                        @if($return->approved_at)<tr><th>Approved On</th><td>{{ $return->approved_at->format('d M Y, h:i A') }}</td></tr>@endif
                        @if($return->admin_note)<tr><th>Admin Note</th><td>{{ $return->admin_note }}</td></tr>@endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            @if($return->image)
                <div class="rt-card">
                    <div class="rt-card-head"><h2 class="rt-card-title"><i class="fas fa-image"></i> Proof Photo</h2></div>
                    <div class="rt-body text-center"><img src="{{ asset('uploads/returns/' . $return->image) }}" alt="Return proof" class="rt-proof"></div>
                </div>
            @endif

            @if($return->status === 'Pending')
                @can('returns.edit')
                    <div class="rt-card">
                        <div class="rt-card-head"><h2 class="rt-card-title"><i class="fas fa-gavel"></i> Review Request</h2></div>
                        <div class="rt-body rt-form">
                            <form method="POST" action="{{ route('admin.returns.approve', $return->id) }}" class="js-action-form mb-3">
                                @csrf
                                <label class="rt-label">Admin Note <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                                <textarea name="admin_note" class="form-control mb-3" rows="2" placeholder="Add a note with this approval..."></textarea>
                                <button type="submit" class="btn btn-success btn-block rt-action js-submit-btn" data-confirm="Approve this return? Stock will be restored automatically." data-loading="Approving...">
                                    <i class="fas fa-check-circle mr-1"></i> Approve & Restore Stock
                                </button>
                            </form>
                            <hr class="rt-divider">
                            <form method="POST" action="{{ route('admin.returns.reject', $return->id) }}" class="js-action-form">
                                @csrf
                                <label class="rt-label">Reject Reason <span class="text-danger">*</span></label>
                                <textarea name="admin_note" class="form-control mb-3" rows="2" placeholder="Reason for rejection (required)" required></textarea>
                                <button type="submit" class="btn btn-danger btn-block rt-action js-submit-btn" data-confirm="Reject this return request?" data-loading="Rejecting...">
                                    <i class="fas fa-times-circle mr-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan

            @elseif($return->status === 'Approved')
                <div class="rt-card">
                    <div class="rt-card-head"><h2 class="rt-card-title"><i class="fas fa-money-bill-wave"></i> Refund</h2></div>
                    <div class="rt-body">
                        @if($return->refund_status === 'Refunded')
                            <div class="rt-refunded">
                                <div class="rt-refunded-icon"><i class="fas fa-check-circle"></i></div>
                                <h5 class="mt-2 mb-3">Refunded</h5>
                            </div>
                            <table class="table table-sm rt-table">
                                <tr><th>Amount</th><td>&#8377;{{ number_format($return->refund_amount,2) }}</td></tr>
                                <tr><th>Method</th><td>{{ $return->refund_method }}</td></tr>
                                <tr><th>Reference</th><td>{{ $return->refund_reference ?: '-' }}</td></tr>
                                <tr><th>Refunded On</th><td>{{ $return->refunded_at ? $return->refunded_at->format('d M Y, h:i A') : '-' }}</td></tr>
                            </table>
                        @else
                            @can('returns.edit')
                                <p class="rt-note">Stock has been restored. Now refund the amount to the customer.<br>
                                    <small>Order payment: <strong>{{ $return->order->payment_method ?? '-' }}</strong> ({{ $return->order->payment_status ?? '-' }})</small>
                                </p>
                                <form method="POST" action="{{ route('admin.returns.refund_manual', $return->id) }}" class="js-action-form rt-form">
                                    @csrf
                                    <label class="rt-label">Refund Amount <span class="text-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend"><span class="input-group-text">&#8377;</span></div>
                                        <input type="number" step="0.01" name="refund_amount" class="form-control" value="{{ $return->refund_amount }}" required>
                                    </div>
                                    <label class="rt-label">Refund Method <span class="text-danger">*</span></label>
                                    <select name="refund_method" class="form-control mb-3" required>
                                        <option value="">-- Select method --</option>
                                        <option value="Gateway (manual)">Gateway (done from dashboard)</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Store Credit">Store Credit</option>
                                    </select>
                                    <label class="rt-label">Reference / UTR <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                                    <input type="text" name="refund_reference" class="form-control mb-3" placeholder="Transaction ID / UTR number">
                                    <label class="rt-label">Note <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                                    <textarea name="admin_note" class="form-control mb-3" rows="2"></textarea>
                                    <button type="submit" class="btn btn-info btn-block rt-action js-submit-btn" data-confirm="Mark this refund as completed? This confirms the amount has been sent." data-loading="Saving...">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Mark as Refunded (Manual)
                                    </button>
                                </form>

                                @if($return->order && $return->order->payment_status === 'Paid' && strtoupper($return->order->payment_method) !== 'COD')
                                    <hr class="rt-divider">
                                    <p class="rt-note mb-2">Or refund directly through the payment gateway (amount returns to the customer automatically):</p>
                                    <form method="POST" action="{{ route('admin.returns.refund_gateway', $return->id) }}" class="js-action-form">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-block rt-action js-submit-btn" data-confirm="Refund &#8377;{{ number_format($return->refund_amount, 2) }} via {{ strtoupper($return->order->payment_method) }}? The amount will be returned to the customer's account." data-loading="Processing refund...">
                                            <i class="fas fa-undo mr-1"></i> Auto Refund via {{ strtoupper($return->order->payment_method) }}
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        @endif
                    </div>
                </div>
            @else
                <div class="rt-card">
                    <div class="rt-body text-center">
                        <i class="fas fa-times-circle text-danger" style="font-size:42px;"></i>
                        <h5 class="mt-2 mb-2">Rejected</h5>
                        @if($return->admin_note)<p class="rt-note mb-0">{{ $return->admin_note }}</p>@endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="refundOverlay" class="rt-overlay">
    <div class="rt-overlay-inner">
        <div class="spinner-border text-light" style="width:3.5rem;height:3.5rem;" role="status"></div>
        <div id="refundOverlayText" class="rt-overlay-text">Processing...</div>
        <div class="rt-overlay-sub">Please wait, do not close this window.</div>
    </div>
</div>

<script>
(function(){
    var overlay=document.getElementById('refundOverlay'), overlayText=document.getElementById('refundOverlayText');
    document.querySelectorAll('.js-action-form').forEach(function(form){
        form.addEventListener('submit',function(e){
            var btn=form.querySelector('.js-submit-btn');
            var confirmMsg=btn ? btn.getAttribute('data-confirm') : null;
            var loadingMsg=btn ? (btn.getAttribute('data-loading')||'Processing...') : 'Processing...';
            if(confirmMsg && !window.confirm(confirmMsg)){e.preventDefault();return false;}
            if(form.dataset.submitting==='1'){e.preventDefault();return false;}
            form.dataset.submitting='1';
            if(btn){btn.disabled=true;btn.innerHTML='<span class="spinner-border spinner-border-sm mr-2" role="status"></span>'+loadingMsg;}
            overlayText.textContent=loadingMsg; overlay.style.display='flex';
        });
    });
})();
</script>
@endsection