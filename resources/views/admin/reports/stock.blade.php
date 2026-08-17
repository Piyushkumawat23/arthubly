@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <h3 class="mb-3"><i class="bi bi-clipboard-data"></i> Stock Report</h3>

            {{-- Summary Cards --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>{{ $inStock }}</h3><p>In Stock (&gt;{{ $lowStockThreshold }})</p></div>
                        <div class="icon"><i class="bi bi-check-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner"><h3>{{ $lowStock }}</h3><p>Low Stock (&le;{{ $lowStockThreshold }})</p></div>
                        <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3>{{ $outOfStock }}</h3><p>Out of Stock</p></div>
                        <div class="icon"><i class="bi bi-x-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>&#8377;{{ number_format($inventoryValue, 2) }}</h3><p>Inventory Value</p></div>
                        <div class="icon"><i class="bi bi-cash-stack"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Low main-stock products --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Low / Out of Stock Products</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead><tr><th>Product</th><th>SKU</th><th class="text-right">Stock</th></tr></thead>
                                <tbody>
                                    @forelse($lowProducts as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td><small class="text-muted">{{ $p->sku }}</small></td>
                                            <td class="text-right">
                                                <span class="badge {{ $p->stock <= 0 ? 'badge-danger' : 'badge-warning' }}">{{ $p->stock }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted p-3">Sab products me stock theek hai.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Low variation-stock --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Low / Out of Stock Variations</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead><tr><th>Product</th><th>Variation</th><th class="text-right">Stock</th></tr></thead>
                                <tbody>
                                    @forelse($lowVariations as $v)
                                        @php $vp = $variationProductMap[$v->product_id] ?? null; @endphp
                                        <tr>
                                            <td>{{ $vp->name ?? 'Product #'.$v->product_id }}</td>
                                            <td><small>{{ $v->color }} {{ $v->size ? '/ '.$v->size : '' }}</small></td>
                                            <td class="text-right">
                                                <span class="badge {{ $v->stock <= 0 ? 'badge-danger' : 'badge-warning' }}">{{ $v->stock }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted p-3">Sab variations me stock theek hai.</td></tr>
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
