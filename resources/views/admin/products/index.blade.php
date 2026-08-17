@extends('admin.layout.app')

@section('content')
<style>
    .products-page {
        --p-bg: #f6f7f9;
        --p-card: #fff;
        --p-border: #e7e9ee;
        --p-text: #171a21;
        --p-muted: #727887;
        --p-primary: #111827;
        --p-green: #15803d;
        --p-red: #dc2626;
        background: var(--p-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .products-topbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .products-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .products-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--p-primary);
        color: #fff;
        font-size: 17px;
    }

    .products-title {
        margin: 0;
        color: var(--p-text);
        font-size: 24px;
        line-height: 1.2;
        font-weight: 750;
    }

    .products-subtitle {
        margin: 5px 0 0;
        color: var(--p-muted);
        font-size: 13px;
    }

    .products-top-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .products-top-actions .btn {
        border-radius: 9px;
        font-weight: 600;
        padding: 9px 15px;
    }

    .products-card {
        background: var(--p-card);
        border: 1px solid var(--p-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16, 24, 40, .035);
        overflow: hidden;
    }

    .products-filter {
        padding: 18px;
        border-bottom: 1px solid var(--p-border);
        background: #fff;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: minmax(230px, 1.7fr) repeat(3, minmax(150px, 1fr)) auto;
        gap: 10px;
        align-items: center;
    }

    .filter-field {
        position: relative;
    }

    .filter-field .form-control {
        height: 42px;
        border-color: #e0e3e8;
        border-radius: 8px;
        font-size: 13px;
        box-shadow: none;
    }

    .filter-field .form-control:focus {
        border-color: #9ca3af;
    }

    .filter-search-icon {
        position: absolute;
        left: 13px;
        top: 13px;
        color: #9ca3af;
        font-size: 13px;
        z-index: 2;
    }

    .filter-search-input {
        padding-left: 35px !important;
    }

    .filter-actions {
        display: flex;
        gap: 7px;
    }

    .filter-actions .btn {
        height: 42px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .products-content {
        padding: 18px;
    }

    .products-alert {
        border-radius: 9px;
        font-size: 13px;
        margin-bottom: 15px;
    }

    .bulk-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid var(--p-border);
        border-radius: 9px;
        margin-bottom: 13px;
    }

    .bulk-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bulk-bar select {
        height: 36px;
        min-width: 170px;
        border: 1px solid #dfe3e8;
        border-radius: 7px;
        font-size: 12px;
    }

    .bulk-bar .btn {
        height: 36px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
    }

    .products-count-note {
        color: var(--p-muted);
        font-size: 12px;
    }

    .table-wrap {
        border: 1px solid var(--p-border);
        border-radius: 10px;
        overflow-x: auto;
    }

    .products-table {
        width: 100%;
        min-width: 1080px;
        margin: 0;
        border: 0 !important;
        font-size: 12px;
    }

    .products-table thead th {
        background: #f8fafc;
        color: #656b78;
        border: 0;
        border-bottom: 1px solid var(--p-border);
        padding: 12px 10px;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .025em;
        font-size: 10px;
        font-weight: 700;
    }

    .products-table tbody td {
        border: 0;
        border-bottom: 1px solid #eef0f3;
        padding: 12px 10px;
        vertical-align: middle;
        color: #333842;
    }

    .products-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .products-table tbody tr:hover {
        background: #fbfcfd;
    }

    .product-check {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .product-thumb {
        width: 54px;
        height: 54px;
        border-radius: 9px;
        object-fit: cover;
        border: 1px solid var(--p-border);
        background: #f8fafc;
        display: block;
    }

    .product-no-image {
        width: 54px;
        height: 54px;
        border-radius: 9px;
        display: grid;
        place-items: center;
        background: #f8fafc;
        color: #9ca3af;
        border: 1px solid var(--p-border);
        font-size: 10px;
    }

    .product-info {
        min-width: 210px;
    }

    .product-name {
        color: #171a21;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
        margin-bottom: 4px;
    }

    .product-sku {
        color: #8a909c;
        font-size: 11px;
    }

    .product-price {
        color: #111827;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .stock-number {
        font-weight: 700;
    }

    .stock-low {
        color: var(--p-red);
    }

    .stock-good {
        color: #374151;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
    }

    .status-pill::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-active {
        color: var(--p-green);
        background: #ecfdf3;
    }

    .status-inactive,
    .status-rejected {
        color: var(--p-red);
        background: #fef2f2;
    }

    .status-pending {
        color: #a16207;
        background: #fefce8;
    }

    .variation-dropdown .btn {
        border-radius: 7px;
        font-size: 11px;
        padding: 6px 9px;
    }

    .variation-dropdown .dropdown-menu {
        border: 1px solid var(--p-border);
        border-radius: 9px;
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
        padding: 7px;
        min-width: 185px;
    }

    .variation-dropdown .dropdown-item {
        font-size: 11px;
        padding: 6px 8px;
        border-radius: 5px;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-buttons .btn {
        height: 32px;
        min-width: 32px;
        padding: 5px 9px;
        border-radius: 7px !important;
        font-size: 11px;
        font-weight: 600;
    }

    .pagination-wrap {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }

    .pagination-wrap .pagination {
        margin-bottom: 0;
    }

    .pagination-wrap .page-link {
        border-radius: 7px !important;
        margin-left: 4px;
        border: 1px solid var(--p-border);
        color: #4b5563;
        font-size: 12px;
    }

    @media (max-width: 1050px) {
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid .filter-search-wrap {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 650px) {
        .products-page {
            padding: 14px;
        }

        .products-topbar {
            flex-direction: column;
        }

        .products-title {
            font-size: 20px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-grid .filter-search-wrap {
            grid-column: auto;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
        }

        .bulk-bar {
            align-items: flex-start;
            flex-direction: column;
        }

        .bulk-left {
            width: 100%;
        }

        .bulk-left select {
            flex: 1;
            min-width: 0;
        }
    }
</style>

<div class="products-page">
    <div class="products-topbar">
        <div class="products-heading">
            <div class="products-heading-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <h1 class="products-title">Products</h1>
                <p class="products-subtitle">Manage your products, stock, variations and availability.</p>
            </div>
        </div>

        <div class="products-top-actions">
            @can('products.add')
                <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus mr-1"></i> Add New Product
                </a>
            @endcan
        </div>
    </div>

    <div class="products-card">
        {{-- FILTERS --}}
        <div class="products-filter">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="filter-field filter-search-wrap">
                        <i class="fas fa-search filter-search-icon"></i>
                        <input type="text"
                            name="search"
                            class="form-control filter-search-input"
                            placeholder="Search by product name or SKU..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="filter-field">
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <select name="stock_status" class="form-control">
                            <option value="">Stock Status</option>
                            <option value="instock" {{ request('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                            <option value="outofstock" {{ request('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light border">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="products-content">
            @if (session('success'))
                <div class="alert alert-success products-alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            {{-- BULK ACTION --}}
            <div class="bulk-bar">
                <div class="bulk-left">
                    <select id="bulk_status" class="form-control form-control-sm">
                        <option value="">Bulk Action</option>
                        <option value="active">Mark as Active</option>
                        <option value="inactive">Mark as Inactive</option>
                    </select>

                    <button type="button" id="apply_bulk_action" class="btn btn-dark btn-sm">
                        Apply
                    </button>
                </div>

                <div class="products-count-note">
                    Select products to apply a bulk status update.
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-wrap">
                <table class="table products-table align-middle">
                    <thead>
                        <tr>
                            <th width="42">
                                <input type="checkbox" id="select_all" class="product-check">
                            </th>
                            <th width="55">ID</th>
                            <th width="80">Image</th>
                            <th>Product Details</th>
                            <th>Price</th>
                            <th>Total Stock</th>
                            <th>Variation Stock</th>
                            <th>Status</th>
                            <th width="125">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product_chk product-check" value="{{ $product->id }}">
                                </td>

                                <td>
                                    <span class="text-muted">#{{ $product->id }}</span>
                                </td>

                                <td>
                                    @php
                                        $varWithImage = null;
                                        foreach ($product->variations as $var) {
                                            if (!empty($var->image)) {
                                                $varWithImage = $var;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if ($varWithImage)
                                        <img src="{{ asset('public/uploads/products/variations/' . $varWithImage->image) }}"
                                            alt="{{ $product->name }}"
                                            class="product-thumb">
                                    @elseif($product->hover_image)
                                        <img src="{{ asset('uploads/products/hover/' . $product->hover_image) }}"
                                            alt="Hover"
                                            class="product-thumb">
                                    @else
                                        <div class="product-no-image">No Img</div>
                                    @endif
                                </td>

                                <td>
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-sku">SKU: {{ $product->sku }}</div>
                                    </div>
                                </td>

                                <td>
                                    <span class="product-price">₹{{ $product->price }}</span>
                                </td>

                                <td>
                                    @if ($product->stock <= 5)
                                        <span class="stock-number stock-low">
                                            {{ $product->stock }}
                                            <small>(Low)</small>
                                        </span>
                                    @else
                                        <span class="stock-number stock-good">{{ $product->stock }}</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($product->variations->count() > 0)
                                        <div class="dropdown variation-dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown">
                                                View {{ $product->variations->count() }}
                                            </button>

                                            <ul class="dropdown-menu">
                                                @foreach ($product->variations as $var)
                                                    <li>
                                                        <span class="dropdown-item">
                                                            {{ $var->color }} / {{ $var->size }}
                                                            <strong class="float-right">{{ $var->stock }}</strong>
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="text-muted">No Variations</span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $statusClass = match($product->status) {
                                            'active' => 'status-active',
                                            'pending' => 'status-pending',
                                            'rejected' => 'status-rejected',
                                            default => 'status-inactive',
                                        };
                                    @endphp

                                    <span class="status-pill {{ $statusClass }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        @can('products.edit')
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-outline-dark"
                                                title="Edit Product">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        @endcan

                                        @can('products.delete')
                                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure?')"
                                                class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    title="Delete Product">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Select all products
    $(document).on('change', '#select_all', function() {
        $('.product_chk').prop('checked', this.checked);
    });

    // Keep Select All checkbox in sync
    $(document).on('change', '.product_chk', function() {
        const total = $('.product_chk').length;
        const selected = $('.product_chk:checked').length;

        $('#select_all').prop('checked', total > 0 && total === selected);
        $('#select_all').prop('indeterminate', selected > 0 && selected < total);
    });

    // Bulk action
    $(document).on('click', '#apply_bulk_action', function() {
        let ids = [];

        $('.product_chk:checked').each(function() {
            ids.push($(this).val());
        });

        let status = $('#bulk_status').val();

        if (ids.length > 0 && status !== "") {
            if (confirm('Are you sure you want to update ' + ids.length + ' products?')) {
                $.ajax({
                    url: "{{ route('admin.products.bulkUpdate') }}",
                    type: 'POST',
                    data: {
                        ids: ids,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        location.reload();
                    },
                    error: function(err) {
                        alert('Something went wrong. Check if route exists.');
                    }
                });
            }
        } else {
            alert('Please select products and an action first!');
        }
    });
});
</script>