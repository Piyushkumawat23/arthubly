<?php

// Yahan Frontend wale controller ko import kiya gaya hai
use App\Http\Controllers\Frontend\AiSearchController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\DrawerController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderHistoryController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProductReviewController;
use App\Http\Controllers\Frontend\ReturnRequestController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

// Naya Frontend Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==========================================
// ----- FRONTEND CART & CHECKOUT ROUTES ----
// ==========================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/product-details/{id}', [HomeController::class, 'getProductDetails']);
Route::post('/update-cart-quantity', [CartController::class, 'updateQuantity'])->name('cart.update_quantity');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
// ==========================================

// ==========================================
// ----- BAG + WISHLIST DRAWERS (AJAX) ------
// ==========================================
// Drawer ka HTML fragment (dono guest ke liye bhi chalte hain)
Route::get('/bag-drawer', [DrawerController::class, 'bag'])->name('bag.drawer');
Route::get('/wish-drawer', [WishlistController::class, 'drawer'])->name('wish.drawer');

// JS in exact URIs par hit karta hai — ye purane CartController methods
// ke hi alias hain, koi naya logic nahi.
Route::post('/cart-update', [CartController::class, 'updateQuantity'])->name('cart.update.ajax');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove.ajax');

// Wishlist toggle — auth group ke BAAHAR, taaki guest ko
// login-redirect ki jagah controller ka JSON {guest:true} mile
Route::get('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove.ajax');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

// ==========================================

Route::post('/wishlist/merge', [WishlistController::class, 'merge'])->name('wishlist.merge');
Route::post('/wishlist/discard', [WishlistController::class, 'discard'])->name('wishlist.discard');
// 🟢 PRODUCT LISTING & SHOP PAGES ROUTES
// 1. All Products Listing (Pehla wala UI)
// Route::get('/products', [HomeController::class, 'product_listing'])->name('product.shop');

// 2. Category-wise Products Listing (Pehla wala UI but filtered)
Route::get('/category/{slug}', [HomeController::class, 'categoryProducts'])->name('product.category');

// 3. ONLY Categories Banner Page (Dusra wala UI jiska design change nahi karna hai)
Route::get('/categories', [HomeController::class, 'categories'])->name('product.categories_list');

// /////////////////////////////////////////////
Route::post('/subscribe-newsletter', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');

Route::get('/search', [HomeController::class, 'search'])->name('product.search');
// AI search — throttle zaroori hai, warna koi bhi aapka Gemini quota jala dega
Route::post('/ai-search', [AiSearchController::class, 'search'])
    ->middleware('throttle:20,1')
    ->name('ai.search');
// Sabhi authenticated routes ko ek group mein rakhein
Route::match(['get', 'post'], '/payment/{order}/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::middleware('auth')->group(function () {

    Route::get('/my-orders', [OrderHistoryController::class, 'index'])->name('customer.orders');
    Route::get('/my-orders/{id}', [OrderHistoryController::class, 'show'])->name('customer.orders.show');

    Route::post('/my-orders/{order}/return', [ReturnRequestController::class, 'store'])->name('customer.return.store');
    Route::get('/my-returns', [ReturnRequestController::class, 'index'])->name('customer.returns');

    Route::get('/payment/{order}/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');

    // ----- PRODUCT REVIEW (customer ka apna review) -----
    // POST hi hai, isliye GET /product/{slug} se koi clash nahi hota.
    Route::post('/product/{product}/review', [ProductReviewController::class, 'store'])
        ->name('product.review.store');

    // Wishlist page (toggle upar move ho gaya hai)

    // Checkout (Yahan user ka email/mobile zaroori hota hai)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('frontend.dashboard');
    })->name('dashboard');
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::controller(SellerController::class)->prefix('seller')->group(function () {
    Route::get('/login', 'showLoginForm')->name('seller.login');
    Route::post('/login', 'login');
});

Route::get('/public/{path}', function ($path) {
    $filePath = public_path($path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    abort(404);
})->where('path', '.*');

require __DIR__.'/auth.php';