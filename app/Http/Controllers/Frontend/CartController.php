<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /* =====================================================================
       HELPER — cart count + total from one place
       ===================================================================== */
    private function cartSummary(): array
    {
        $cart = session()->get('cart', []);
        $cart = is_array($cart) ? $cart : [];

        $total = 0;
        $qty   = 0;
        foreach ($cart as $d) {
            $total += ($d['price'] ?? 0) * ($d['quantity'] ?? 1);
            $qty   += ($d['quantity'] ?? 1);
        }

        return [
            'count'     => count($cart),   // how many different lines
            'qty'       => $qty,           // total pieces
            'total'     => $total,
            'total_fmt' => number_format($total, 2),
        ];
    }

    // To show the cart page
    public function index()
    {
        $cart    = session()->get('cart', []);
        $setting = Setting::first();

        return view('frontend.cart.index', compact('cart', 'setting'));
    }

    /* =====================================================================
       ADD TO CART  — now gives JSON via AJAX (no page reload)
       ===================================================================== */
    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);

        if (! $product) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ]);
            }
            return redirect()->back()->with('error', 'Product not found.');
        }

        $cart = session()->get('cart', []);
        $cart = is_array($cart) ? $cart : [];

        // Variant attributes
        $selectedSize  = $request->size;
        $selectedColor = $request->color;

        // Unique Cart ID
        $cartId = $id . '-' . ($selectedSize ?? 'na') . '-' . ($selectedColor ?? 'na');

        // Defaults from main product
        $variantImage = $product->thumbnail_image;
        $isVariant    = false;
        $variationId  = null;
        $finalPrice   = (float) ($product->sale_price ?? $product->price);

        // --- DYNAMIC VARIANT PRICE & IMAGE LOGIC ---
        if ($selectedColor || $selectedSize) {

            $variation = \App\Models\ProductVariation::where('product_id', $id)
                ->when($selectedColor, function ($q) use ($selectedColor) {
                    return $q->where('color', $selectedColor);
                })
                ->when($selectedSize, function ($q) use ($selectedSize) {
                    return $q->where('size', $selectedSize);
                })
                ->first();

            if ($variation) {
                $variationId = $variation->id;

                if ($variation->price > 0) {
                    $finalPrice = (float) $variation->price;
                }

                if ($variation->image) {
                    $variantImage = $variation->image;
                    $isVariant    = true;
                }
            }
        }

        $addQty = max(1, (int) ($request->quantity ?? 1));

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $addQty;
        } else {
            $cart[$cartId] = [
                'product_id'   => $product->id,
                'variation_id' => $variationId,
                'name'         => $product->name,
                'quantity'     => $addQty,
                'price'        => $finalPrice,
                'image'        => $variantImage,
                'is_variant'   => $isVariant,
                'size'         => $selectedSize,
                'color'        => $selectedColor,
            ];
        }

        session()->put('cart', $cart);

        // ---- AJAX → JSON (drawer updates instantly from this) ----
        if ($request->ajax() || $request->wantsJson()) {
            $s = $this->cartSummary();

            return response()->json([
                'success'   => true,
                'message'   => 'Added to bag',
                'cart_id'   => $cartId,
                'count'     => $s['count'],
                'qty'       => $s['qty'],
                'total'     => $s['total'],
                'total_fmt' => $s['total_fmt'],
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /* =====================================================================
       QUANTITY UPDATE
       ===================================================================== */
    public function updateQuantity(Request $request)
    {
        $cartId = $request->cart_id;
        $qty    = max(1, (int) $request->quantity);

        $cart = session()->get('cart', []);
        $cart = is_array($cart) ? $cart : [];

        if (! $cartId || ! isset($cart[$cartId])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart.',
                ]);
            }
            return redirect()->back();
        }

        $cart[$cartId]['quantity'] = $qty;
        session()->put('cart', $cart);

        $itemTotal = $cart[$cartId]['price'] * $qty;
        $s         = $this->cartSummary();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'item_total' => number_format($itemTotal, 2),
                'cart_total' => $s['total_fmt'],   // kept the old key name as is
                'count'      => $s['count'],
                'qty'        => $s['qty'],
                'total'      => $s['total'],
                'total_fmt'  => $s['total_fmt'],
            ]);
        }

        return redirect()->back()->with('success', 'Quantity updated');
    }

    /* =====================================================================
       REMOVE
       ===================================================================== */
    public function remove(Request $request)
    {
        $cartId = $request->cart_id;

        $cart = session()->get('cart', []);
        $cart = is_array($cart) ? $cart : [];

        if ($cartId && isset($cart[$cartId])) {
            unset($cart[$cartId]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $s = $this->cartSummary();

            return response()->json([
                'success'   => true,
                'message'   => 'Item removed',
                'count'     => $s['count'],
                'qty'       => $s['qty'],
                'total'     => $s['total'],
                'total_fmt' => $s['total_fmt'],
            ]);
        }

        return redirect()->back()->with('success', 'Product removed successfully');
    }

    /* =====================================================================
       COUPON (as it was before)
       ===================================================================== */
    public function applyCoupon(Request $request)
    {
        $couponCode = $request->coupon_code;
        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', 1)
            ->where('expiry_date', '>=', Carbon::today())
            ->first();

        if (! $coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or Expired Coupon Code.']);
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Coupon usage limit reached.']);
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $discount = 0;
        if ($coupon->discount_type == 'percentage') {
            $discount = ($subtotal * $coupon->discount_amount) / 100;
        } else {
            $discount = $coupon->discount_amount;
        }

        $grandTotal = $subtotal - $discount;

        session()->put('coupon', [
            'code'        => $coupon->code,
            'discount'    => $discount,
            'grand_total' => $grandTotal,
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Coupon applied successfully!',
            'discount'    => number_format($discount, 2),
            'grand_total' => number_format($grandTotal, 2),
        ]);
    }
}