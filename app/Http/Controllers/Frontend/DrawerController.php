<?php
// File: app/Http/Controllers/DrawerController.php
// (namespace is root — matches your existing import in web.php)
//
// Only for BAG drawer. Wishlist drawer endpoint is in
// WishlistController@drawer.

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class DrawerController extends Controller
{
    /** Bag drawer HTML (items + footer) and count */
    public function bag()
    {
        $cart = session()->get('cart', []);
        $cart = is_array($cart) ? $cart : [];

        $total = 0;
        $qty   = 0;
        foreach ($cart as $d) {
            $total += ($d['price'] ?? 0) * ($d['quantity'] ?? 1);
            $qty   += ($d['quantity'] ?? 1);
        }

        return response()->json([
            'items'     => view('frontend.partials.bag-items')->render(),
            'foot'      => view('frontend.partials.bag-foot')->render(),
            'count'     => count($cart),
            'qty'       => $qty,
            'total'     => $total,
            'total_fmt' => number_format($total, 2),
        ]);
    }
}