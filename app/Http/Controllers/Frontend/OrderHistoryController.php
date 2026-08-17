<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderHistoryController extends Controller
{
    /**
     * Customer's complete orders list
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('frontend.customer.orders', compact('orders'));
    }

    /**
     * Single order details
     */
    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())   // security: only own order
            ->with('items.product')
            ->findOrFail($id);

        return view('frontend.customer.order-details', compact('order'));
    }
}
