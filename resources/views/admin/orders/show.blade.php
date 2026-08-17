@extends('admin.layout.app')

@section('content')
<style>
    .order-show-page {
        --os-bg: #f6f7f9;
        --os-card: #fff;
        --os-border: #e6e8ed;
        --os-text: #171a21;
        --os-muted: #747b88;
        --os-primary: #111827;
        background: var(--os-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .os-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .os-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .os-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--os-primary);
        color: #fff;
        font-size: 17px;
    }

    .os-title {
        margin: 0;
        color: var(--os-text);
        font-size: 24px;
        font-weight: 750;
        line-height: 1.2;
    }

    .os-subtitle {
        margin: 5px 0 0;
        color: var(--os-muted);
        font-size: 12px;
    }

    .os-back {
        border-radius: 8px !important;
        padding: 9px 13px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
    }

    .os-alert {
        border-radius: 9px;
        font-size: 12px;
        margin-bottom: 18px;
    }

    .os-card {
        background: var(--os-card);
        border: 1px solid var(--os-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .os-card-header {
        padding: 14px 17px;
        border-bottom: 1px solid var(--os-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .os-card-title {
        margin: 0;
        color: var(--os-text);
        font-size: 13px;
        font-weight: 700;
    }

    .os-card-title i {
        color: #737b87;
        margin-right: 7px;
    }

    .os-body {
        padding: 17px;
    }

    .os-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 20px;
    }

    .os-info-item {
        min-width: 0;
    }

    .os-info-label {
        margin-bottom: 4px;
        color: #8a909c;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .os-info-value {
        color: #303640;
        font-size: 12px;
        line-height: 1.5;
        word-break: break-word;
    }

    .os-info-value strong {
        color: #171a21;
        font-weight: 650;
    }

    .os-address {
        grid-column: 1 / -1;
        padding-top: 2px;
    }

    .os-address-box {
        padding: 11px 12px;
        background: #f8fafc;
        border: 1px solid #edf0f3;
        border-radius: 8px;
        color: #4b5563;
        font-size: 11px;
        line-height: 1.6;
    }

    .os-status-card .os-card-header {
        background: #fafafa;
    }

    .os-status-form .form-label {
        display: block;
        margin-bottom: 7px;
        color: #4b5563;
        font-size: 10px;
        font-weight: 700;
    }

    .os-status-form .form-control {
        height: 40px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: none;
        color: #374151;
        font-size: 11px;
    }

    .os-status-form .form-control:focus {
        border-color: #9ca3af;
        box-shadow: none;
    }

    .os-update-btn {
        height: 40px;
        border-radius: 8px !important;
        font-size: 11px !important;
        font-weight: 650 !important;
    }

    .os-current-status {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 14px;
        padding-top: 13px;
        border-top: 1px solid #edf0f3;
        color: #737b87;
        font-size: 10px;
    }

    .os-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
        font-size: 9px;
        font-weight: 700;
    }

    .os-items-wrap {
        overflow-x: auto;
    }

    .os-items-table {
        width: 100%;
        min-width: 680px;
        margin: 0;
        font-size: 11px;
    }

    .os-items-table thead th {
        padding: 11px 12px;
        background: #f8fafc;
        color: #68707d;
        border: 0;
        border-bottom: 1px solid var(--os-border);
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .035em;
        white-space: nowrap;
        font-weight: 700;
    }

    .os-items-table tbody td {
        padding: 13px 12px;
        border: 0;
        border-bottom: 1px solid #eef0f3;
        vertical-align: middle;
        color: #4b5563;
    }

    .os-items-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .os-product-name {
        color: #171a21;
        font-size: 11px;
        font-weight: 650;
        line-height: 1.45;
    }

    .os-variation {
        display: inline-flex;
        padding: 4px 7px;
        border-radius: 5px;
        background: #f5f6f8;
        color: #68707d;
        font-size: 9px;
        line-height: 1.3;
    }

    .os-price,
    .os-subtotal {
        color: #303640;
        white-space: nowrap;
    }

    .os-subtotal {
        font-weight: 700;
    }

    .os-qty {
        display: inline-flex;
        min-width: 27px;
        justify-content: center;
        padding: 4px 7px;
        border-radius: 5px;
        background: #f1f5f9;
        color: #475569;
        font-size: 9px;
        font-weight: 700;
    }

    .os-total-row th {
        padding: 15px 12px !important;
        background: #fafafa;
        border-top: 1px solid var(--os-border) !important;
        color: #4b5563;
        font-size: 11px;
    }

    .os-total-amount {
        color: #111827 !important;
        font-size: 15px !important;
        font-weight: 800 !important;
        white-space: nowrap;
    }

    @media (max-width: 767px) {
        .order-show-page {
            padding: 14px;
        }

        .os-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .os-title {
            font-size: 20px;
        }

        .os-back {
            width: 100%;
        }

        .os-info-grid {
            grid-template-columns: 1fr;
        }

        .os-address {
            grid-column: auto;
        }
    }
</style>

<div class="order-show-page">

    {{-- HEADER --}}
    <div class="os-header">
        <div class="os-heading">
            <div class="os-icon">
                <i class="fas fa-receipt"></i>
            </div>

            <div>
                <h1 class="os-title">Order Details #{{ $order->id }}</h1>
                <p class="os-subtitle">View customer information, purchased items and update order status.</p>
            </div>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary os-back">
            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success os-alert">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-4">

            {{-- CUSTOMER INFORMATION --}}
            <div class="os-card">
                <div class="os-card-header">
                    <h2 class="os-card-title">
                        <i class="fas fa-user"></i>
                        Customer Information
                    </h2>
                </div>

                <div class="os-body">
                    <div class="os-info-grid">

                        <div class="os-info-item">
                            <div class="os-info-label">Name</div>
                            <div class="os-info-value">
                                <strong>{{ $order->name }}</strong>
                            </div>
                        </div>

                        <div class="os-info-item">
                            <div class="os-info-label">Phone</div>
                            <div class="os-info-value">
                                {{ $order->phone }}
                            </div>
                        </div>

                        <div class="os-info-item" style="grid-column:1/-1;">
                            <div class="os-info-label">Email</div>
                            <div class="os-info-value">
                                {{ $order->email }}
                            </div>
                        </div>

                        <div class="os-address">
                            <div class="os-info-label">Delivery Address</div>
                            <div class="os-address-box">
                                {{ $order->address }}, {{ $order->city }},
                                {{ $order->state }} - {{ $order->pincode }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- UPDATE STATUS --}}
            <div class="os-card os-status-card">
                <div class="os-card-header">
                    <h2 class="os-card-title">
                        <i class="fas fa-sliders-h"></i>
                        Update Status
                    </h2>
                </div>

                <div class="os-body">
                    <form action="{{ route('admin.orders.update_status', $order->id) }}"
                          method="POST"
                          class="os-status-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>

                            <select name="payment_status" class="form-control">
                                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>
                                    Paid
                                </option>
                                <option value="Failed" {{ $order->payment_status == 'Failed' ? 'selected' : '' }}>
                                    Failed
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Order Status</label>

                            <select name="order_status" class="form-control">
                                <option value="Pending" {{ $order->order_status == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>
                                    Processing
                                </option>
                                <option value="Shipped" {{ $order->order_status == 'Shipped' ? 'selected' : '' }}>
                                    Shipped
                                </option>
                                <option value="Delivered" {{ $order->order_status == 'Delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>
                                <option value="Cancelled" {{ $order->order_status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 os-update-btn">
                            <i class="fas fa-save mr-1"></i>
                            Update Status
                        </button>

                        <div class="os-current-status">
                            Current Order Status:
                            <span class="os-status-badge">
                                {{ $order->order_status }}
                            </span>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-8">

            {{-- PURCHASED ITEMS --}}
            <div class="os-card">
                <div class="os-card-header">
                    <h2 class="os-card-title">
                        <i class="fas fa-shopping-bag"></i>
                        Purchased Items
                    </h2>

                    <span style="font-size:10px;color:#8a909c;">
                        {{ $order->items->count() }} item(s)
                    </span>
                </div>

                <div class="os-items-wrap">
                    <table class="table os-items-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Variation</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="os-product-name">
                                            {{ $item->product->name ?? 'Product Deleted' }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($item->variation_info)
                                            <span class="os-variation">
                                                {{ $item->variation_info }}
                                            </span>
                                        @else
                                            <span style="color:#a0a5ae;">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="os-price">
                                            ₹{{ number_format($item->price, 2) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="os-qty">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>

                                    <td class="text-right">
                                        <span class="os-subtotal">
                                            ₹{{ number_format($item->price * $item->quantity, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="os-total-row">
                                <th colspan="4" class="text-right">
                                    Total Amount
                                </th>

                                <th class="text-right os-total-amount">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection