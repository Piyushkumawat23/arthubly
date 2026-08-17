@extends('frontend.layout.arthubly')

@section('title', 'My Returns — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">My Returns</span></div>
        <div class="acct-layout">
            @include('frontend.partials.arthubly-account-nav', ['active' => 'returns'])
            <div class="acct-main">
                <div class="ac-head"><h1>My returns</h1><p>Your return requests and their refund status.</p></div>

                @if($returns->count() === 0)
                    <div class="empty-state">
                        <div class="es-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg></div>
                        <h3>No returns yet</h3>
                        <p>You haven't requested any returns.</p>
                        <a href="{{ route('customer.orders') }}" class="btn btn-primary btn-lg">Go to my orders</a>
                    </div>
                @else
                    <div class="panel">
                        <div class="panel-body p0" style="overflow-x:auto">
                            <table class="data-table">
                                <thead><tr><th>#</th><th>Order</th><th>Product</th><th class="c">Qty</th><th>Reason</th><th>Status</th><th class="r">Refund</th><th>Date</th></tr></thead>
                                <tbody>
                                    @foreach($returns as $r)
                                        @php
                                            $perUnit = ($r->quantity > 0 && $r->refund_amount) ? $r->refund_amount / $r->quantity : $r->refund_amount;
                                            $sp = 'sp-' . strtolower($r->status);
                                        @endphp
                                        <tr>
                                            <td>{{ $r->id }}</td>
                                            <td><a href="{{ route('customer.orders.show', $r->order_id) }}">#{{ $r->order_id }}</a></td>
                                            <td>{{ $r->product->name ?? '-' }}</td>
                                            <td class="c">{{ $r->quantity }}</td>
                                            <td>{{ $r->reason }}</td>
                                            <td><span class="status-pill {{ $sp }}">{{ $r->status }}</span><br><small style="color:var(--ink-50)">{{ $r->refund_status }}</small></td>
                                            <td class="r">
                                                @if($r->refund_amount)
                                                    <b>₹{{ number_format($r->refund_amount, 2) }}</b>
                                                    @if($r->quantity > 1)<br><small style="color:var(--ink-50)">₹{{ number_format($perUnit, 2) }} × {{ $r->quantity }}</small>@endif
                                                @else <span style="color:var(--ink-50)">-</span> @endif
                                            </td>
                                            <td>{{ $r->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:center;margin-top:20px">{{ $returns->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
