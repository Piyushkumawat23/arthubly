@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <h3 class="mb-3"><i class="bi bi-cash-stack"></i> Sales Report</h3>

            {{-- Date Filter --}}
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
                            <a href="{{ route('admin.reports.sales') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>&#8377;{{ number_format($totalSales, 2) }}</h3>
                            <p>Total Sales</p>
                        </div>
                        <div class="icon"><i class="bi bi-currency-rupee"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalOrders }}</h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="icon"><i class="bi bi-cart-check"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>&#8377;{{ number_format($avgOrder, 2) }}</h3>
                            <p>Average Order Value</p>
                        </div>
                        <div class="icon"><i class="bi bi-graph-up"></i></div>
                    </div>
                </div>
            </div>

            {{-- Day-wise table --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title">Day-wise Sales</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-right">Orders</th>
                                <th class="text-right">Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daily as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                                    <td class="text-right">{{ $row->orders }}</td>
                                    <td class="text-right">&#8377;{{ number_format($row->sales, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center p-4 text-muted">Is period me koi sales nahi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
