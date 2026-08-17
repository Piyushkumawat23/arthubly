@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <h3 class="mb-3"><i class="bi bi-graph-up-arrow"></i> Revenue Report</h3>

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
                            <a href="{{ route('admin.reports.revenue') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>&#8377;{{ number_format($totalRevenue, 2) }}</h3><p>Total Revenue (in period)</p></div>
                        <div class="icon"><i class="bi bi-currency-rupee"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>&#8377;{{ number_format($paidRevenue, 2) }}</h3><p>Paid (Online confirmed)</p></div>
                        <div class="icon"><i class="bi bi-check2-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner"><h3>&#8377;{{ number_format($pendingRevenue, 2) }}</h3><p>Pending Payment</p></div>
                        <div class="icon"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Payment method wise --}}
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Revenue by Payment Method</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>Method</th><th class="text-right">Orders</th><th class="text-right">Revenue</th></tr></thead>
                                <tbody>
                                    @forelse($byMethod as $r)
                                        <tr>
                                            <td>{{ $r->payment_method }}</td>
                                            <td class="text-right">{{ $r->orders }}</td>
                                            <td class="text-right">&#8377;{{ number_format($r->revenue, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted p-3">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Monthly trend --}}
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Monthly Revenue Trend (last 12 months)</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>Month</th><th class="text-right">Orders</th><th class="text-right">Revenue</th></tr></thead>
                                <tbody>
                                    @forelse($monthly as $m)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($m->month.'-01')->format('M Y') }}</td>
                                            <td class="text-right">{{ $m->orders }}</td>
                                            <td class="text-right">&#8377;{{ number_format($m->revenue, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted p-3">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
