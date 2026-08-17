<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist; // Using your existing model
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the full wishlist page
     *
     * ⬇️ CHANGE: 'images' bhi eager load — warna wishlist page ka
     *    gallery-image fallback kabhi chalega hi nahi.
     */
    public function index()
    {
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id');
            $products = Product::with(['variations', 'category', 'images'])
                ->whereIn('id', $wishlistIds)
                ->get();
        } else {
            $guestWishlistIds = session()->get('guest_wishlist', []);
            $products = Product::with(['variations', 'category', 'images'])
                ->whereIn('id', $guestWishlistIds)
                ->get();
        }

        // Make sure this view path matches your actual blade file location!
        // E.g., 'frontend.wishlist.index' or 'frontend.wishlist'
        return view('frontend.wishlist.index', compact('products'));
    }

    /**
     * Toggle a product in the wishlist (Handles both Auth & Guest)
     */
    public function toggle($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            // Existing DB logic
            $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $id)->first();

            if ($wishlist) {
                $wishlist->delete();
                $added = false;
            } else {
                Wishlist::create(['user_id' => $userId, 'product_id' => $id]);
                $added = true;
            }
            $count = Wishlist::where('user_id', $userId)->count();
        } else {
            // New Session logic for Guests
            $guestWishlist = session()->get('guest_wishlist', []);

            if (in_array($id, $guestWishlist)) {
                $guestWishlist = array_diff($guestWishlist, [$id]);
                $added = false;
            } else {
                $guestWishlist[] = (int) $id;
                $added = true;
            }

            session()->put('guest_wishlist', $guestWishlist);
            session()->save(); // Force session save

            $count = count($guestWishlist);
        }

        return response()->json([
            'added' => $added,
            'count' => $count
        ]);
    }

    /**
     * Remove an item from the wishlist drawer/page
     */
    public function remove(Request $request)
    {
        $id = $request->id;

        if (Auth::check()) {
            Wishlist::where('user_id', Auth::id())->where('product_id', $id)->delete();
        } else {
            $guestWishlist = session()->get('guest_wishlist', []);
            $guestWishlist = array_diff($guestWishlist, [$id]);

            session()->put('guest_wishlist', $guestWishlist);
            session()->save(); // Force session save
        }

        return response()->json(['success' => true]);
    }

    /**
     * Fetch items and render the Drawer HTML
     *
     * ⬇️ CHANGE: yahan bhi 'images' load kar diya — drawer aur page
     *    dono ek jaisi image logic use kar sakein.
     */
    public function drawer()
    {
        $formattedItems = [];

        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id');
            $products = Product::with(['variations', 'images'])->whereIn('id', $wishlistIds)->get();
        } else {
            $guestWishlistIds = session()->get('guest_wishlist', []);
            $products = Product::with(['variations', 'images'])->whereIn('id', $guestWishlistIds)->get();
        }

        // Format to match the expected structure in wish-items.blade.php
        foreach ($products as $p) {
            $formattedItems[] = [
                'key' => $p->id,
                'product' => $p
            ];
        }

        return response()->json([
            'items' => view('frontend.partials.wish-items', ['items' => $formattedItems])->render(),
            'count' => count($formattedItems)
        ]);
    }

    /**
     * Merge guest wishlist into user account
     */
    public function merge()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $guestWishlist = session()->get('guest_wishlist', []);

            foreach ($guestWishlist as $productId) {
                // firstOrCreate prevents duplicates without requiring DB schema constraints
                Wishlist::firstOrCreate([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);
            }

            // Clear the session flags
            session()->forget(['guest_wishlist', 'wishlist_merge_ask']);

            $count = Wishlist::where('user_id', $userId)->count();

            return response()->json([
                'success' => true,
                'message' => 'Items added to your account.',
                'count' => $count
            ]);
        }

        return response()->json(['success' => false], 401);
    }

    /**
     * Discard the guest wishlist entirely
     */
    public function discard()
    {
        session()->forget(['guest_wishlist', 'wishlist_merge_ask']);

        // Return the current authenticated user's normal wishlist count to reset the UI badge
        $count = Auth::check() ? Wishlist::where('user_id', Auth::id())->count() : 0;

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}