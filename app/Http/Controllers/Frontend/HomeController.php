<?php
/* ======================================================================
   HomeController — only 2 minor changes:
     1) getWishlistIds()  → counts guest session wishlist too
     2) getProductDetails() → category eager load (for quick view)

   The rest of the file remains exactly as it was.
   ====================================================================== */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Setting;
use App\Models\Newsletter;
use App\Models\Wishlist;

class HomeController extends Controller
{
    // ==========================================
    // HELPER METHOD (For Clean Code)
    // ==========================================
    private function getWishlistIds()
    {
        // ⬇️ CHANGE: session for guest, DB for logged in
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray();
        }

        $ids = session('guest_wishlist', []);
        return is_array($ids) ? array_map('intval', $ids) : [];
    }

    // ==========================================
    // HOMEPAGE
    // ==========================================
    public function index(Request $request)
    {
        $allProducts = Product::with(['variations', 'category'])
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        $newArrivals = Product::with(['variations', 'category'])
            ->where('status', 'active')
            ->where('is_new_arrival', 1)
            ->latest()
            ->take(8)
            ->get();

        $womenProducts = Product::whereHas('category', fn($q) => $q->where('name', 'Women'))->where('status', 'active')->take(8)->get();
        $menProducts   = Product::whereHas('category', fn($q) => $q->where('name', 'Men'))->where('status', 'active')->take(8)->get();
        $accessories   = Product::whereHas('category', fn($q) => $q->where('name', 'Accessories'))->where('status', 'active')->take(8)->get();

        $wishlistProductIds = $this->getWishlistIds();
        $categories = Category::where('status', 'active')->get();
        $colors = Color::where('status', 'active')->get();
        $sizes = Size::where('status', 'active')->get();

        return view('frontend.index', compact(
            'allProducts', 'newArrivals', 'womenProducts', 'menProducts',
            'accessories', 'wishlistProductIds', 'categories', 'colors', 'sizes'
        ));
    }

    // ==========================================
    // PRODUCT DETAILS (Single Page & Quick View)
    // ==========================================
    public function getProductDetails($id)
    {
        // ⬇️ CHANGE: added 'category' — quick view shows its category name
        $product = Product::with(['variations', 'images', 'category'])->findOrFail($id);
        return response()->json($product);
    }

    public function productDetails($slug)
    {
        $product = Product::with([
            'images', 'variations', 'category',
            'reviews' => fn($query) => $query->where('status', 1)->latest(),
            'reviews.user'
        ])->where('slug', $slug)->firstOrFail();

        $prevProduct = Product::where('id', '<', $product->id)->orderBy('id', 'desc')->first();
        $nextProduct = Product::where('id', '>', $product->id)->orderBy('id', 'asc')->first();

        $relatedProducts = Product::with(['variations', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $wishlistProductIds = $this->getWishlistIds();

        return view('frontend.product.product_details', compact(
            'product', 'relatedProducts', 'prevProduct', 'nextProduct', 'wishlistProductIds'
        ));
    }

    // ==========================================
    // SEARCH & NEWSLETTER
    // ==========================================
    public function subscribeNewsletter(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:newsletters,email']);
        Newsletter::create(['email' => $request->email, 'status' => 'subscribed']);
        return response()->json(['message' => 'Subscribed successfully!']);
    }

    public function search(Request $request)
    {
        $queryText = $request->input('q');

        if ($request->ajax() || $request->has('live_search')) {
            if (empty($queryText)) return response()->json([]);

            $products = Product::with(['variations', 'category'])
                ->where('status', 'active')
                ->where(function($q) use ($queryText) {
                    $q->where('name', 'LIKE', "%{$queryText}%")
                      ->orWhereHas('variations', function($vq) use ($queryText) {
                          $vq->where('color', 'LIKE', "%{$queryText}%")
                             ->orWhere('size', 'LIKE', "%{$queryText}%");
                      });
                })->take(5)->get();

            return response()->json($products);
        }

        if (empty($queryText)) return redirect()->back();

        $products = Product::with(['variations', 'category'])
            ->where('status', 'active')
            ->where(function($q) use ($queryText) {
                $q->where('name', 'LIKE', "%{$queryText}%")
                  ->orWhereHas('variations', function($vq) use ($queryText) {
                      $vq->where('color', 'LIKE', "%{$queryText}%")
                         ->orWhere('size', 'LIKE', "%{$queryText}%");
                  });
            })->paginate(12);

        $wishlistProductIds = $this->getWishlistIds();

        return view('frontend.partials.search_results', compact('products', 'queryText', 'wishlistProductIds'));
    }

    // ==========================================
    // LISTING & CATEGORIES
    // ==========================================
    public function product_listing(Request $request)
    {
        $query = Product::with(['variations', 'category'])->where('status', 'active');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('color')) {
            $query->where(fn($q) => $q->where('color', $request->color)
                ->orWhereHas('variations', fn($v) => $v->where('color', $request->color)));
        }
        if ($request->filled('size')) {
            $query->where(fn($q) => $q->where('size', $request->size)
                ->orWhereHas('variations', fn($v) => $v->where('size', $request->size)));
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                default => $query->latest()
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('status', 'active')->get();
        $colors = Color::where('status', 'active')->get();
        $sizes = Size::where('status', 'active')->get();

        $allProducts = Product::with(['variations', 'category'])->where('status', 'active')->latest()->take(10)->get();
        $newArrivals = Product::with(['variations', 'category'])->where('status', 'active')->where('is_new_arrival', 1)->latest()->take(8)->get();

        $wishlistProductIds = $this->getWishlistIds();

        return view('frontend.product.product_listing', compact('products', 'categories', 'colors', 'sizes', 'allProducts', 'newArrivals', 'wishlistProductIds'));
    }

    public function categoryProducts(Request $request, $slug)
    {
        $request->merge(['category' => $slug]);
        return $this->product_listing($request);
    }

    public function categories()
    {
        $categories = Category::withCount('products')->where('status', 'active')->get();
        return view('frontend.product.categories', compact('categories'));
    }
}