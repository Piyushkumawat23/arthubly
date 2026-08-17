<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnRequestController extends Controller
{
    /**
     * Customer's all return requests
     */
    public function index()
    {
        $returns = ReturnRequest::where('user_id', auth()->id())
            ->with('product', 'order')
            ->latest()
            ->paginate(10);

        return view('frontend.customer.returns', compact('returns'));
    }

    /**
     * Submit a new return request
     */
    public function store(Request $request, $orderId)
    {
        // 1. Security: order belongs to this customer
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // 2. Only delivered orders can be returned
        if ($order->order_status !== 'Delivered') {
            return back()->with('error', 'Only delivered orders can be returned.');
        }

        // 3. Validation
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'quantity'      => 'required|integer|min:1',
            'reason'        => 'required|string|max:255',
            'comment'       => 'nullable|string|max:1000',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 4. Order item belongs to this order (security)
        $item = OrderItem::where('id', $request->order_item_id)
            ->where('order_id', $order->id)
            ->firstOrFail();

        // 5. Return quantity cannot exceed purchased quantity
        if ($request->quantity > $item->quantity) {
            return back()->with('error', 'You cannot return more than the quantity you purchased.');
        }

        // 6. No existing pending/approved return for this item
        $existing = ReturnRequest::where('order_item_id', $item->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();
        if ($existing) {
            return back()->with('error', 'A return request for this item is already in progress.');
        }

        // 7. Image upload (if provided)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/returns'), $name);
            $imagePath = $name;
        }

        // 8. Refund amount = item price x return quantity
        //    (item->price is the ACTUAL price paid for that variation at order time)
        $refundAmount = $item->price * $request->quantity;

        // 9. Save
        ReturnRequest::create([
            'order_id'      => $order->id,
            'user_id'       => auth()->id(),
            'order_item_id' => $item->id,
            'product_id'    => $item->product_id,
            'quantity'      => $request->quantity,
            'reason'        => $request->reason,
            'comment'       => $request->comment,
            'image'         => $imagePath,
            'status'        => 'Pending',
            'refund_status' => 'Not Initiated',
            'refund_amount' => $refundAmount,
        ]);

        return redirect()->route('customer.returns')
            ->with('success', 'Your return request has been submitted. Please wait for admin approval.');
    }
}