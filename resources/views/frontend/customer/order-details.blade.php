@extends('frontend.layout.arthubly')

@section('title', 'Order #' . $order->id . ' — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><a href="{{ route('customer.orders') }}">My Orders</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Order #{{ $order->id }}</span></div>

        <div class="acct-layout">
            @include('frontend.partials.arthubly-account-nav', ['active' => 'orders'])
            <div class="acct-main">
                @php $sp = 'sp-' . strtolower($order->order_status); $pp = $order->payment_status === 'Paid' ? 'sp-paid' : 'sp-pending'; @endphp
                <div class="ac-head" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                    <div><h1>Order #{{ $order->id }}</h1><p>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p></div>
                    <span class="status-pill {{ $sp }}" style="font-size:13px;padding:8px 16px">{{ $order->order_status }}</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">
                    <div>
                        <div class="panel">
                            <div class="panel-head"><h4>Items</h4></div>
                            <div class="panel-body p0" style="overflow-x:auto">
                                <table class="data-table">
                                    <thead><tr><th>Product</th><th class="c">Qty</th><th class="r">Price</th><th class="r">Subtotal</th></tr></thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td><b>{{ $item->product->name ?? 'Product' }}</b>@if(!empty($item->variation_info))<br><small style="color:var(--ink-50)">{{ $item->variation_info }}</small>@endif</td>
                                                <td class="c">{{ $item->quantity }}</td>
                                                <td class="r">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="r"><b>₹{{ number_format($item->price * $item->quantity, 2) }}</b></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot><tr><th colspan="3" class="r">Total</th><th class="r" style="color:var(--brass-d)">₹{{ number_format($order->total_amount, 2) }}</th></tr></tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-head"><h4>Payment</h4></div>
                            <div class="panel-body">
                                <p class="kv"><b>Method:</b> {{ $order->payment_method }}</p>
                                <p class="kv" style="margin-bottom:0"><b>Status:</b> <span class="status-pill {{ $pp }}">{{ $order->payment_status }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="panel">
                            <div class="panel-head"><h4>Shipping address</h4></div>
                            <div class="panel-body">
                                <p class="kv"><b>{{ $order->name }}</b></p>
                                <p class="kv">{{ $order->phone }}</p>
                                <p class="kv">{{ $order->email }}</p>
                                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0">
                                <p class="kv">{{ $order->address }}</p>
                                <p class="kv">{{ $order->city }}, {{ $order->state }}</p>
                                <p class="kv" style="margin-bottom:0">{{ $order->pincode }}</p>
                            </div>
                        </div>

                        @if($order->order_status === 'Delivered')
                            <button type="button" class="btn btn-brass btn-lg" style="width:100%;justify-content:center" data-toggle="modal" data-target="#returnModal">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg> Request a return
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RETURN REQUEST MODAL (Delivered only) --}}
    @if($order->order_status === 'Delivered')
    <div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form method="POST" action="{{ route('customer.return.store', $order->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Request a return — Order #{{ $order->id }}</h5><button type="button" class="close" data-dismiss="modal">✕</button></div>
                    <div class="modal-body">
                        <div class="field" style="margin-bottom:14px">
                            <label>Which item do you want to return? *</label>
                            <select name="order_item_id" id="returnItem" required>
                                <option value="">— Select an item —</option>
                                @foreach($order->items as $item)
                                    <option value="{{ $item->id }}" data-max="{{ $item->quantity }}" data-price="{{ $item->price }}">{{ $item->product->name ?? 'Product' }} (Qty: {{ $item->quantity }}, ₹{{ number_format($item->price, 2) }} each)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" style="margin-bottom:8px">
                            <label>How many units to return? *</label>
                            <input type="number" name="quantity" id="returnQty" min="1" value="1" required>
                            <small id="returnQtyHelp" style="color:var(--ink-50);font-size:12px">Please select an item first</small>
                        </div>
                        <div class="alert alert-success" id="refundPreview" style="display:none;background:var(--celadon-wash);border:1px solid var(--celadon);color:#41522F;padding:12px 14px;border-radius:var(--r-md);margin-bottom:14px">
                            Estimated refund: <strong id="refundPreviewAmount">₹0.00</strong> <small id="refundPreviewCalc" style="color:var(--ink-50)"></small>
                        </div>
                        <div class="field" style="margin-bottom:14px">
                            <label>Reason *</label>
                            <select name="reason" required>
                                <option value="">— Select a reason —</option>
                                <option value="Damaged Product">Damaged Product</option>
                                <option value="Wrong Item Received">Wrong Item Received</option>
                                <option value="Wrong Size">Wrong Size</option>
                                <option value="Not as Described">Not as Described</option>
                                <option value="Quality Issue">Quality Issue</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="field" style="margin-bottom:14px"><label>Comment (optional)</label><textarea name="comment" rows="3" maxlength="1000" placeholder="Describe the issue in detail…"></textarea></div>
                        <div class="field"><label>Photo (optional, max 2MB)</label><input type="file" name="image" accept="image/jpeg,image/jpg,image/png" style="height:auto;padding:10px 14px"><small style="color:var(--ink-50);font-size:12px">Upload a photo of the issue as proof</small></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-ghost" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Submit return request</button></div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sel=document.getElementById('returnItem'), qty=document.getElementById('returnQty'), help=document.getElementById('returnQtyHelp');
            var preview=document.getElementById('refundPreview'), pAmt=document.getElementById('refundPreviewAmount'), pCalc=document.getElementById('refundPreviewCalc');
            function inr(n){return '₹'+parseFloat(n).toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2});}
            function upd(){ if(!sel||sel.selectedIndex<1){preview.style.display='none';return;} var o=sel.options[sel.selectedIndex];var price=parseFloat(o.getAttribute('data-price'))||0;var q=parseInt(qty.value)||0;pAmt.textContent=inr(price*q);pCalc.textContent=' ('+inr(price)+' × '+q+')';preview.style.display='block'; }
            if(sel){ sel.addEventListener('change',function(){var o=sel.options[sel.selectedIndex];var max=o.getAttribute('data-max');if(max){qty.setAttribute('max',max);qty.value=1;help.textContent='You can return up to '+max+' unit(s)';}else{qty.removeAttribute('max');help.textContent='Please select an item first';}upd();});
                qty.addEventListener('input',function(){var max=parseInt(qty.getAttribute('max'));if(max&&parseInt(qty.value)>max)qty.value=max;if(parseInt(qty.value)<1)qty.value=1;upd();}); }
        });
    </script>
    @endif
</section>
@endsection
