<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Setting;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // To show the checkout form
    public function index()
{
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('home')->with('error', 'Admins are not allowed to place orders.');
    }

    $setting = Setting::first();
    $cart = session()->get('cart');

    if (!$cart) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
    }

    // Only gateways turned ON by admin
    $paymentGateways = PaymentGateway::active()->get();

    return view('frontend.checkout.index', compact('cart', 'setting', 'paymentGateways'));
}

    // To place Cash on Delivery Order
    public function placeOrder(Request $request)
{
    $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email',
        'phone'          => 'required|numeric',
        'address'        => 'required|string',
        'city'           => 'required|string',
        'state'          => 'required|string',
        'pincode'        => 'required|numeric',
        'payment_method' => 'required|string|exists:payment_gateways,slug',
    ]);

    $cart = session()->get('cart');
    if (! $cart) {
        return redirect()->route('home')->with('error', 'Cart is empty');
    }

    // Is selected gateway really active? (security)
    $gateway = PaymentGateway::where('slug', $request->payment_method)
        ->where('is_active', true)
        ->first();

    if (! $gateway) {
        return redirect()->back()->with('error', 'Selected payment method is not available.');
    }

    // Total
    $totalAmount = 0;
    foreach ($cart as $details) {
        $totalAmount += $details['price'] * $details['quantity'];
    }
    if (session()->has('coupon')) {
        $totalAmount = session('coupon')['grand_total'];
    }

    // Order save
    $order = new Order;
    $order->user_id        = Auth::id();
    $order->name           = $request->name;
    $order->email          = $request->email;
    $order->phone          = $request->phone;
    $order->address        = $request->address;
    $order->city           = $request->city;
    $order->state          = $request->state;
    $order->pincode        = $request->pincode;
    $order->total_amount   = $totalAmount;
    $order->payment_method = strtoupper($gateway->slug);
    $order->payment_status = 'Pending';
    $order->save();

    // Order items
    foreach ($cart as $cartId => $details) {
        $variationDetails = [];
        if (! empty($details['size']))  { $variationDetails[] = 'Size: '.$details['size']; }
        if (! empty($details['color'])) { $variationDetails[] = 'Color: '.$details['color']; }

        OrderItem::create([
    'order_id'       => $order->id,
    'product_id'     => $details['product_id'],
    'variation_id'   => $details['variation_id'] ?? null,   // ⬅️ ADD
    'variation_info' => implode(', ', $variationDetails),
    'quantity'       => $details['quantity'],
    'price'          => $details['price'],
]);
    }

  
    // COD → order done
// COD → order done
if ($gateway->slug === 'cod') {
    foreach ($cart as $details) {
        $this->reduceStock(
            $details['product_id'],
            $details['quantity'],
            $details['variation_id'] ?? null
        );
    }

    session()->forget('cart');
    session()->forget('coupon');
    return redirect()->route('home')
        ->with('success', 'Order placed successfully! Your order ID is #'.$order->id);
}

    // Online gateway → payment page (cart NOT cleared yet — will do on success)
    return redirect()->route('payment.initiate', ['order' => $order->id]);
}


/**
 * Reduce stock — decrease variation stock if variation_id is present, along with main product.
 */
private function reduceStock($productId, $qty, $variationId = null)
{
    // Variation stock (if variation_id is present)
    if (! empty($variationId)) {
        $variation = \App\Models\ProductVariation::find($variationId);
        if ($variation) {
            $newStock = max(0, $variation->stock - $qty);
            $variation->update(['stock' => $newStock]);
        }
    }

    // Main product stock
    Product::where('id', $productId)
        ->where('stock', '>=', $qty)
        ->decrement('stock', $qty);
}
}
