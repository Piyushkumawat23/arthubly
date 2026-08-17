@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <h3 class="mb-3"><i class="bi bi-cart-check"></i> Orders Report</h3>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="form-row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label>From</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from', $from->toDateString()) }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>To</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to', $to->toDateString()) }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-primary">Apply Filter</button>
                            <a href="{{ route('admin.reports.orders') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-primary">
                        <div class="inner"><h3>{{ $totalOrders }}</h3><p>Total Orders</p></div>
                        <div class="icon"><i class="bi bi-cart"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Payment Status --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">By Payment Status</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm mb-0">
                                <thead><tr><th>Status</th><th class="text-right">Count</th><th class="text-right">Amount</th></tr></thead>
                                <tbody>
                                    @forelse($byPaymentStatus as $r)
                                        <tr>
                                            <td>{{ $r->payment_status }}</td>
                                            <td class="text-right">{{ $r->count }}</td>
                                            <td class="text-right">&#8377;{{ number_format($r->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Order Status --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">By Order Status</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm mb-0">
                                <thead><tr><th>Status</th><th class="text-right">Count</th></tr></thead>
                                <tbody>
                                    @forelse($byOrderStatus as $r)
                                        <tr><td>{{ $r->order_status }}</td><td class="text-right">{{ $r->count }}</td></tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">By Payment Method</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm mb-0">
                                <thead><tr><th>Method</th><th class="text-right">Count</th><th class="text-right">Amount</th></tr></thead>
                                <tbody>
                                    @forelse($byMethod as $r)
                                        <tr>
                                            <td>{{ $r->payment_method }}</td>
                                            <td class="text-right">{{ $r->count }}</td>
                                            <td class="text-right">&#8377;{{ number_format($r->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent orders --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title">Recent Orders (latest 50)</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>#</th><th>Customer</th><th>Method</th>
                                <th>Payment</th><th>Order Status</th>
                                <th class="text-right">Amount</th><th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $o)
                                <tr>
                                    <td>{{ $o->id }}</td>
                                    <td>{{ $o->name }}</td>
                                    <td>{{ $o->payment_method }}</td>
                                    <td><span class="badge {{ $o->payment_status === 'Paid' ? 'badge-success' : ($o->payment_status === 'Failed' ? 'badge-danger' : 'badge-warning') }}">{{ $o->payment_status }}</span></td>
                                    <td>{{ $o->order_status }}</td>
                                    <td class="text-right">&#8377;{{ number_format($o->total_amount, 2) }}</td>
                                    <td>{{ $o->created_at ? $o->created_at->format('d M Y') : '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center p-4 text-muted">Koi order nahi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
