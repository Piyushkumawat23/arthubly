@extends('admin.layout.app')

@section('content')
<style>
    .inventory-page {
        --inv-bg: #f6f7f9;
        --inv-card: #fff;
        --inv-border: #e6e8ed;
        --inv-text: #171a21;
        --inv-muted: #747b88;
        --inv-primary: #111827;
        background: var(--inv-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .inv-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .inv-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .inv-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--inv-primary);
        color: #fff;
        font-size: 17px;
    }

    .inv-title {
        margin: 0;
        color: var(--inv-text);
        font-size: 24px;
        line-height: 1.2;
        font-weight: 750;
    }

    .inv-subtitle {
        margin: 5px 0 0;
        color: var(--inv-muted);
        font-size: 13px;
    }

    .inv-card {
        background: var(--inv-card);
        border: 1px solid var(--inv-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16, 24, 40, .035);
        overflow: hidden;
    }

    .inv-filter {
        padding: 17px 18px;
        border-bottom: 1px solid var(--inv-border);
        background: #fff;
    }

    .inv-filter-form {
        display: grid;
        grid-template-columns: minmax(260px, 1fr) auto;
        gap: 10px;
        align-items: center;
    }

    .inv-search {
        position: relative;
    }

    .inv-search i {
        position: absolute;
        left: 13px;
        top: 13px;
        color: #9ca3af;
        font-size: 13px;
    }

    .inv-search .form-control {
        height: 42px;
        padding-left: 36px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: none;
        font-size: 13px;
    }

    .inv-filter-actions {
        display: flex;
        gap: 8px;
    }

    .inv-filter-actions .btn {
        height: 42px;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .inv-body {
        padding: 18px;
    }

    .inv-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 13px;
    }

    .inv-summary-title {
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .inv-summary-text {
        color: var(--inv-muted);
        font-size: 11px;
    }

    .inv-table-wrap {
        overflow-x: auto;
        border: 1px solid var(--inv-border);
        border-radius: 10px;
    }

    .inv-table {
        width: 100%;
        min-width: 900px;
        margin: 0;
        font-size: 12px;
    }

    .inv-table thead th {
        padding: 12px 11px;
        background: #f8fafc;
        color: #68707d;
        border: 0;
        border-bottom: 1px solid var(--inv-border);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .025em;
        white-space: nowrap;
        font-weight: 700;
    }

    .inv-table tbody td {
        padding: 13px 11px;
        border: 0;
        border-bottom: 1px solid #eef0f3;
        vertical-align: middle;
    }

    .inv-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .inv-table tbody tr:hover {
        background: #fbfcfd;
    }

    .inv-product {
        display: flex;
        align-items: center;
        min-width: 230px;
    }

    .inv-product-image,
    .inv-no-image {
        width: 54px;
        height: 54px;
        flex: 0 0 54px;
        border-radius: 9px;
        border: 1px solid var(--inv-border);
        margin-right: 12px;
    }

    .inv-product-image {
        object-fit: cover;
        background: #f8fafc;
    }

    .inv-no-image {
        display: grid;
        place-items: center;
        background: #f8fafc;
        color: #9ca3af;
        font-size: 9px;
    }

    .inv-product-name {
        color: #171a21;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
        margin-bottom: 4px;
    }

    .inv-product-sku {
        color: #8a909c;
        font-size: 11px;
    }

    .inv-stock {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .inv-stock-low {
        background: #fef2f2;
        color: #dc2626;
    }

    .inv-stock-good {
        background: #eff6ff;
        color: #2563eb;
    }

    .inv-variations {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        max-width: 520px;
    }

    .inv-variation {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 8px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        color: #596170;
        font-size: 10px;
        white-space: nowrap;
    }

    .inv-variation b {
        font-size: 11px;
        color: #374151;
    }

    .inv-variation .low {
        color: #dc2626;
    }

    .inv-no-variation {
        color: #9ca3af;
        font-size: 11px;
    }

    .inv-action .btn {
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .inv-pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }

    .inv-pagination .pagination {
        margin: 0;
    }

    .inv-pagination .page-link {
        border-radius: 7px !important;
        margin-left: 4px;
        border: 1px solid var(--inv-border);
        color: #4b5563;
        font-size: 12px;
    }

    @media (max-width: 650px) {
        .inventory-page {
            padding: 14px;
        }

        .inv-header {
            flex-direction: column;
        }

        .inv-title {
            font-size: 20px;
        }

        .inv-filter-form {
            grid-template-columns: 1fr;
        }

        .inv-filter-actions .btn {
            flex: 1;
        }

        .inv-summary {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="inventory-page">
    <div class="inv-header">
        <div class="inv-heading">
            <div class="inv-heading-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <h1 class="inv-title">Inventory / Stock Management</h1>
                <p class="inv-subtitle">Monitor product stock and quickly update variation inventory.</p>
            </div>
        </div>
    </div>

    <div class="inv-card">
        {{-- SEARCH --}}
        <div class="inv-filter">
            <form action="{{ route('admin.stock.index') }}" method="GET">
                <div class="inv-filter-form">
                    <div class="inv-search">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search product name or SKU..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="inv-filter-actions">
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-search mr-1"></i> Search
                        </button>

                        @if(request('search'))
                            <a href="{{ route('admin.stock.index') }}" class="btn btn-light border">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="inv-body">
            <div class="inv-summary">
                <div>
                    <div class="inv-summary-title">Current Inventory</div>
                    <div class="inv-summary-text">Review main stock and individual color/size variation stock.</div>
                </div>

                <div class="inv-summary-text">
                    {{ $products->total() }} products
                </div>
            </div>

            <div class="inv-table-wrap">
                <table class="table inv-table align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Main Stock</th>
                            <th>Variations Stock</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="inv-product">
                                        @php
                                            $varWithImage = null;
                                            foreach($product->variations as $var) {
                                                if(!empty($var->image)) {
                                                    $varWithImage = $var;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        @if($varWithImage)
                                            <img
                                                src="{{ asset('public/uploads/products/variations/' . $varWithImage->image) }}"
                                                alt="{{ $product->name }}"
                                                class="inv-product-image">
                                        @else
                                            <div class="inv-no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="inv-product-name">{{ $product->name }}</div>
                                            <div class="inv-product-sku">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="inv-stock {{ $product->stock < 5 ? 'inv-stock-low' : 'inv-stock-good' }}">
                                        {{ $product->stock }}
                                    </span>

                                    @if($product->stock < 5)
                                        <div class="text-danger mt-1" style="font-size: 10px;">
                                            <i class="fas fa-exclamation-circle"></i> Low stock
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($product->variations->count())
                                        <div class="inv-variations">
                                            @foreach($product->variations as $var)
                                                <span class="inv-variation">
                                                    <span>{{ $var->color }} / {{ $var->size }}</span>
                                                    <b class="{{ $var->stock <= 5 ? 'low' : '' }}">
                                                        {{ $var->stock }}
                                                    </b>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="inv-no-variation">No variations</span>
                                    @endif
                                </td>

                                <td class="inv-action">
                                    <a
                                        href="{{ route('admin.stock.edit', $product->id) }}"
                                        class="btn btn-outline-dark">
                                        <i class="fas fa-edit mr-1"></i> Update Stock
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open mb-2" style="font-size: 24px;"></i>
                                        <div style="font-size: 13px; font-weight: 600;">No products found</div>
                                        <div style="font-size: 11px;">Try another product name or SKU.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="inv-pagination">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection