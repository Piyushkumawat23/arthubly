<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Product;          // ⬅️ ADDED THIS
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function initiate($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_status === 'Paid') {
            return redirect()->route('home')->with('success', 'This order is already paid.');
        }

        $config = PaymentGateway::where('slug', strtolower($order->payment_method))
            ->active()->first();

        if (! $config) {
            return redirect()->route('checkout.index')
                ->with('error', 'This payment method is not available right now.');
        }

        try {
            return app(PaymentManager::class)->driver($config->slug)->initiate($order, $config);
        } catch (\Throwable $e) {
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }
    }

    public function callback(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Double-processing guard (prevent stock reduction multiple times on refresh / dual POST)
        if ($order->payment_status === 'Paid') {
            return redirect()->route('home')
                ->with('success', 'Payment already completed. Order #' . $order->id);
        }

        $config = PaymentGateway::where('slug', strtolower($order->payment_method))
            ->active()->firstOrFail();

        $success = app(PaymentManager::class)->driver($config->slug)
            ->handleCallback($request, $order, $config);

       if ($success) {
    $order->payment_status = 'Paid';
    $order->save();

    foreach (OrderItem::where('order_id', $order->id)->get() as $item) {
        $this->reduceStock($item->product_id, $item->quantity, $item->variation_id);
    }

    session()->forget('cart');
    session()->forget('coupon');

    return redirect()->route('home')
        ->with('success', 'Payment successful! Order #' . $order->id);
}
        $order->payment_status = 'Failed';
        $order->save();

        return redirect()->route('checkout.index')
            ->with('error', 'Payment failed or was cancelled. Please try again.');
    }


   private function reduceStock($productId, $qty, $variationId = null)
{
    if (! empty($variationId)) {
        $variation = \App\Models\ProductVariation::find($variationId);
        if ($variation) {
            $newStock = max(0, $variation->stock - $qty);
            $variation->update(['stock' => $newStock]);
        }
    }

    Product::where('id', $productId)
        ->where('stock', '>=', $qty)
        ->decrement('stock', $qty);
}
}