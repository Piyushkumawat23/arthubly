@extends('frontend.layout.arthubly')

@section('title', 'My Orders — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">My Orders</span></div>
        <div class="acct-layout">
            @include('frontend.partials.arthubly-account-nav', ['active' => 'orders'])
            <div class="acct-main">
                <div class="ac-head"><h1>My orders</h1><p>Track, review, and manage your purchases.</p></div>

                @if($orders->count() === 0)
                    <div class="empty-state">
                        <div class="es-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg></div>
                        <h3>No orders yet</h3>
                        <p>When you place an order, it will show up here.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Start shopping</a>
                    </div>
                @else
                    <div class="panel">
                        <div class="panel-body p0" style="overflow-x:auto">
                            <table class="data-table">
                                <thead>
                                    <tr><th>Order</th><th>Date</th><th>Items</th><th class="r">Amount</th><th>Payment</th><th>Status</th><th class="r">Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        @php
                                            $sp = 'sp-' . strtolower($order->order_status);
                                            $pp = $order->payment_status === 'Paid' ? 'sp-paid' : 'sp-pending';
                                        @endphp
                                        <tr>
                                            <td><b>#{{ $order->id }}</b></td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>{{ $order->items->count() }} item(s)</td>
                                            <td class="r"><b>₹{{ number_format($order->total_amount, 2) }}</b></td>
                                            <td><span class="status-pill {{ $pp }}">{{ $order->payment_status }}</span><br><small style="color:var(--ink-50)">{{ $order->payment_method }}</small></td>
                                            <td><span class="status-pill {{ $sp }}">{{ $order->order_status }}</span></td>
                                            <td class="r"><a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-ghost" style="height:38px;padding:0 16px">View</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:center;margin-top:20px">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
