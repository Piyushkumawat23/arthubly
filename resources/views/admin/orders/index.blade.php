@extends('admin.layout.app')

@section('content')
<style>
    .orders-page {
        --om-bg: #f6f7f9;
        --om-card: #fff;
        --om-border: #e6e8ed;
        --om-text: #171a21;
        --om-muted: #747b88;
        --om-primary: #111827;
        background: var(--om-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .om-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .om-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .om-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--om-primary);
        color: #fff;
        font-size: 17px;
    }

    .om-title {
        margin: 0;
        color: var(--om-text);
        font-size: 24px;
        line-height: 1.2;
        font-weight: 750;
    }

    .om-subtitle {
        margin: 5px 0 0;
        color: var(--om-muted);
        font-size: 13px;
    }

    .om-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-left: 8px;
        padding: 5px 9px;
        border-radius: 999px;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 10px;
        font-weight: 700;
        vertical-align: middle;
    }

    .om-clear-filter {
        margin-left: 6px;
        border-radius: 7px !important;
        font-size: 10px !important;
        padding: 5px 9px !important;
    }

    .om-alert {
        border-radius: 9px;
        font-size: 12px;
        margin-bottom: 18px;
    }

    .om-card {
        background: var(--om-card);
        border: 1px solid var(--om-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .om-status-tabs {
        display: flex;
        gap: 7px;
        padding: 5px;
        margin-bottom: 18px;
        background: #fff;
        border: 1px solid var(--om-border);
        border-radius: 11px;
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .om-status-tabs .nav-link {
        padding: 8px 13px;
        border-radius: 7px;
        color: #626a77;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .om-status-tabs .nav-link:hover {
        background: #f5f6f8;
        color: #171a21;
    }

    .om-status-tabs .nav-link.active {
        background: var(--om-primary);
        color: #fff;
    }

    .om-toolbar {
        padding: 15px 18px;
        border-bottom: 1px solid var(--om-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .om-toolbar-title {
        color: var(--om-text);
        font-size: 13px;
        font-weight: 700;
    }

    .om-toolbar-subtitle {
        margin-top: 3px;
        color: var(--om-muted);
        font-size: 10px;
    }

    .om-bulk {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .om-bulk .form-select {
        height: 36px;
        min-width: 170px;
        border: 1px solid #dfe3e8;
        border-radius: 7px;
        box-shadow: none;
        font-size: 11px;
    }

    .om-bulk .btn {
        height: 36px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
    }

    .om-table-wrap {
        overflow-x: auto;
    }

    .om-table {
        width: 100%;
        min-width: 1100px;
        margin: 0;
        font-size: 12px;
    }

    .om-table thead th {
        padding: 12px 11px;
        background: #f8fafc;
        color: #68707d;
        border: 0;
        border-bottom: 1px solid var(--om-border);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .025em;
        white-space: nowrap;
        font-weight: 700;
    }

    .om-table tbody td {
        padding: 13px 11px;
        border: 0;
        border-bottom: 1px solid #eef0f3;
        vertical-align: middle;
        color: #374151;
    }

    .om-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .om-table tbody tr:hover {
        background: #fbfcfd;
    }

    .om-check {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .om-order-id {
        color: #171a21;
        font-weight: 750;
        font-size: 12px;
        white-space: nowrap;
    }

    .om-customer-name {
        color: #171a21;
        font-size: 12px;
        font-weight: 650;
        margin-bottom: 3px;
    }

    .om-phone {
        color: #8a909c;
        font-size: 10px;
    }

    .om-amount {
        color: #171a21;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .om-payment-method {
        color: #596170;
        font-size: 11px;
        white-space: nowrap;
    }

    .om-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 9px;
        line-height: 1;
        font-weight: 700;
        white-space: nowrap;
    }

    .om-badge-paid {
        background: #ecfdf3;
        color: #15803d;
    }

    .om-badge-failed {
        background: #fef2f2;
        color: #dc2626;
    }

    .om-badge-pending {
        background: #fffbeb;
        color: #b45309;
    }

    .om-status-select {
        min-width: 130px;
        height: 34px;
        padding: 5px 28px 5px 9px;
        border: 1px solid #dfe3e8;
        border-radius: 7px;
        box-shadow: none;
        color: #374151;
        font-size: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .om-status-select:focus {
        border-color: #9ca3af;
        box-shadow: none;
    }

    .om-status-select.border-success {
        border-width: 2px !important;
    }

    .om-date {
        color: #747b88;
        font-size: 10px;
        white-space: nowrap;
    }

    .om-view-btn {
        border-radius: 7px !important;
        padding: 7px 11px !important;
        font-size: 10px !important;
        font-weight: 600 !important;
        white-space: nowrap;
    }

    .om-empty {
        padding: 48px 20px !important;
        text-align: center;
    }

    .om-empty-icon {
        color: #c4c9d1;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .om-empty-title {
        color: #4b5563;
        font-size: 13px;
        font-weight: 700;
    }

    .om-empty-text {
        margin-top: 5px;
        color: #9ca3af;
        font-size: 11px;
    }

    .om-footer {
        padding: 14px 18px;
        border-top: 1px solid var(--om-border);
        display: flex;
        justify-content: flex-end;
    }

    .om-footer .pagination {
        margin: 0;
    }

    .om-footer .page-link {
        border-radius: 7px !important;
        margin-left: 4px;
        border: 1px solid var(--om-border);
        color: #4b5563;
        font-size: 11px;
    }

    @media (max-width: 700px) {
        .orders-page {
            padding: 14px;
        }

        .om-header {
            flex-direction: column;
        }

        .om-title {
            font-size: 20px;
        }

        .om-bulk {
            width: 100%;
        }

        .om-bulk .form-select {
            flex: 1;
            min-width: 0;
        }
    }
</style>

<div class="orders-page">

    {{-- HEADER --}}
    <div class="om-header">
        <div class="om-heading">
            <div class="om-heading-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>

            <div>
                <h1 class="om-title">
                    Order Management

                    @if(request('status'))
                        <span class="om-filter-badge">
                            <i class="fas fa-filter"></i>
                            {{ request('status') }}
                        </span>

                        <a href="{{ route('admin.orders.index') }}"
                           class="btn btn-sm btn-outline-secondary om-clear-filter">
                            <i class="fas fa-times mr-1"></i> Clear
                        </a>
                    @endif
                </h1>

                <p class="om-subtitle">
                    View, filter and quickly update your customer orders.
                </p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success om-alert">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- STATUS FILTER --}}
    <div class="om-status-tabs nav nav-pills">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}"
           href="{{ route('admin.orders.index') }}">
            All
        </a>

        @foreach (($statusList ?? ['Pending','Processing','Shipped','Delivered','Cancelled','Returns']) as $st)
            <a class="nav-link {{ request('status') === $st ? 'active' : '' }}"
               href="{{ route('admin.orders.index', ['status' => $st]) }}">
                {{ $st }}
            </a>
        @endforeach
    </div>

    {{-- ORDERS --}}
    <div class="om-card">

        {{-- TOOLBAR --}}
        <div class="om-toolbar">
            <div>
                <div class="om-toolbar-title">Orders</div>
                <div class="om-toolbar-subtitle">
                    Select multiple orders to change their status at once.
                </div>
            </div>

            <div class="om-bulk">
                <select id="order_bulk_status" class="form-select form-select-sm">
                    <option value="">Change Status</option>
                    <option value="Pending">Mark as Pending</option>
                    <option value="Processing">Mark as Processing</option>
                    <option value="Shipped">Mark as Shipped</option>
                    <option value="Delivered">Mark as Delivered</option>
                    <option value="Cancelled">Mark as Cancelled</option>
                    <option value="Returns">Mark as Returns</option>
                </select>

                <button id="btn_order_bulk" class="btn btn-dark btn-sm">
                    <i class="fas fa-sync-alt mr-1"></i>
                    Update Selected
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="om-table-wrap">
            <table class="table om-table">
                <thead>
                    <tr>
                        <th width="42">
                            <input type="checkbox" id="check_all_orders" class="om-check">
                        </th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th width="90">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox"
                                       class="order_chk om-check"
                                       value="{{ $order->id }}">
                            </td>

                            <td>
                                <div class="om-order-id">#{{ $order->id }}</div>
                            </td>

                            <td>
                                <div class="om-customer-name">
                                    {{ $order->name }}
                                </div>
                                <div class="om-phone">
                                    {{ $order->phone }}
                                </div>
                            </td>

                            <td>
                                <div class="om-amount">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </div>
                            </td>

                            <td>
                                <div class="om-payment-method">
                                    {{ $order->payment_method }}
                                </div>
                            </td>

                            <td>
                                @php
                                    $paymentClass = $order->payment_status == 'Paid'
                                        ? 'om-badge-paid'
                                        : ($order->payment_status == 'Failed'
                                            ? 'om-badge-failed'
                                            : 'om-badge-pending');
                                @endphp

                                <span class="om-badge {{ $paymentClass }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>

                            <td>
                                @can('orders.edit')
                                    <select
                                        class="form-select form-select-sm order-status-change om-status-select"
                                        data-id="{{ $order->id }}">
                                        @foreach (['Pending','Processing','Shipped','Delivered','Cancelled','Returns'] as $st)
                                            <option value="{{ $st }}"
                                                {{ $order->order_status === $st ? 'selected' : '' }}>
                                                {{ $st }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span class="om-badge" style="background:#e0f2fe;color:#0369a1;">
                                        {{ $order->order_status }}
                                    </span>
                                @endcan
                            </td>

                            <td>
                                <div class="om-date">
                                    {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '-' }}
                                </div>
                            </td>

                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-outline-dark btn-sm om-view-btn">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="om-empty">
                                <div class="om-empty-icon">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>

                                @if(request('status'))
                                    <div class="om-empty-title">
                                        No orders found
                                    </div>
                                    <div class="om-empty-text">
                                        There are no orders with "{{ request('status') }}" status.
                                    </div>
                                @else
                                    <div class="om-empty-title">
                                        No orders yet
                                    </div>
                                    <div class="om-empty-text">
                                        There are currently no orders to display.
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($orders->hasPages())
            <div class="om-footer">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {

        // CSRF for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // Select / deselect all orders
        $(document).on('change', '#check_all_orders', function() {
            $('.order_chk').prop('checked', this.checked);
        });

        // Keep "select all" checkbox in sync
        $(document).on('change', '.order_chk', function() {
            let total = $('.order_chk').length;
            let checked = $('.order_chk:checked').length;

            $('#check_all_orders').prop('checked', total > 0 && total === checked);
        });

        // Bulk status update
        $(document).on('click', '#btn_order_bulk', function() {
            let ids = [];

            $('.order_chk:checked').each(function() {
                ids.push($(this).val());
            });

            let status = $('#order_bulk_status').val();

            if (ids.length > 0 && status !== "") {
                if (confirm('Change status to ' + status + ' for ' + ids.length + ' orders?')) {
                    $.post("{{ route('admin.orders.bulkStatus') }}", {
                        ids: ids,
                        status: status
                    }, function() {
                        location.reload();
                    }).fail(function() {
                        alert('Error updating orders. Check your controller logic.');
                    });
                }
            } else {
                alert('Please select orders and a status first!');
            }
        });

        // Inline single-order status change
        $(document).on('change', '.order-status-change', function() {
            let $sel = $(this);
            let id = $sel.data('id');
            let status = $sel.val();

            let prev = $sel.data('previous-value');

            if (!prev) {
                prev = $sel.find('option:selected').val();
            }

            $.ajax({
                url: "{{ route('admin.orders.bulkStatus') }}",
                type: 'POST',
                data: {
                    ids: [id],
                    status: status
                },
                success: function() {
                    $sel
                        .removeClass('border-success border-danger')
                        .addClass('border-success');

                    setTimeout(function() {
                        $sel.removeClass('border-success');
                    }, 1200);

                    $sel.data('previous-value', status);
                },
                error: function() {
                    alert('Status update failed. Try again.');

                    if (prev) {
                        $sel.val(prev);
                    }

                    $sel
                        .removeClass('border-success')
                        .addClass('border-danger');

                    setTimeout(function() {
                        $sel.removeClass('border-danger');
                    }, 1200);
                }
            });
        });

        // Store current value before changing
        $(document).on('focus', '.order-status-change', function() {
            $(this).data('previous-value', $(this).val());
        });

    });
</script>
@endsection