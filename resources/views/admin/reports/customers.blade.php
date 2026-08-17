@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <h3 class="mb-3"><i class="bi bi-people"></i> Customer Report</h3>

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
                            <a href="{{ route('admin.reports.customers') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>{{ $newCustomers }}</h3><p>New Customers (in period)</p></div>
                        <div class="icon"><i class="bi bi-person-plus"></i></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small-box bg-secondary">
                        <div class="inner"><h3>{{ $guestOrders }}</h3><p>Guest Orders (no account)</p></div>
                        <div class="icon"><i class="bi bi-person-dash"></i></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Top Customers (by Spend, Top 20)</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th class="text-right">Orders</th>
                                <th class="text-right">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers as $row)
                                @php $u = $userMap[$row->user_id] ?? null; @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $u->name ?? 'User #'.$row->user_id }}</strong></td>
                                    <td>{{ $u->email ?? '-' }}</td>
                                    <td class="text-right">{{ $row->orders }}</td>
                                    <td class="text-right">&#8377;{{ number_format($row->spent, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center p-4 text-muted">Is period me koi registered customer order nahi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
