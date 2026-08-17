@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <h3 class="mb-3"><i class="bi bi-box-seam"></i> Products Report</h3>

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
                            <a href="{{ route('admin.reports.products') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Best Selling Products (Top 20 by Quantity)</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>Product</th>
                                <th class="text-right">Qty Sold</th>
                                <th class="text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSelling as $row)
                                @php $p = $productMap[$row->product_id] ?? null; @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $p->name ?? 'Product #'.$row->product_id }}</strong>
                                        @if($p) <br><small class="text-muted">{{ $p->sku }}</small> @endif
                                    </td>
                                    <td class="text-right"><span class="badge badge-info">{{ $row->total_qty }}</span></td>
                                    <td class="text-right">&#8377;{{ number_format($row->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center p-4 text-muted">Is period me koi product nahi bika.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
