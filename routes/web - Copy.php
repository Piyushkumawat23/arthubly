<?php

// Yahan Frontend wale controller ko import kiya gaya hai
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\OrderHistoryController;
use App\Http\Controllers\Frontend\ReturnRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\Frontend\PaymentController;

use Illuminate\Support\Facades\Route;
use App\Models\Role;

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


// 🟢 PRODUCT LISTING & SHOP PAGES ROUTES
// 1. All Products Listing (Pehla wala UI)
// Route::get('/products', [HomeController::class, 'product_listing'])->name('product.shop');

// 2. Category-wise Products Listing (Pehla wala UI but filtered)
Route::get('/category/{slug}', [HomeController::class, 'categoryProducts'])->name('product.category');

// 3. ONLY Categories Banner Page (Dusra wala UI jiska design change nahi karna hai)
Route::get('/categories', [HomeController::class, 'categories'])->name('product.categories_list');

///////////////////////////////////////////////
Route::post('/subscribe-newsletter', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');

Route::get('/search', [HomeController::class, 'search'])->name('product.search');
// Sabhi authenticated routes ko ek group mein rakhein
Route::match(['get', 'post'], '/payment/{order}/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::middleware('auth')->group(function () {
    

     Route::get('/my-orders', [OrderHistoryController::class, 'index'])->name('customer.orders');
    Route::get('/my-orders/{id}', [OrderHistoryController::class, 'show'])->name('customer.orders.show');
    

     Route::post('/my-orders/{order}/return', [ReturnRequestController::class, 'store'])->name('customer.return.store');
    Route::get('/my-returns', [ReturnRequestController::class, 'index'])->name('customer.returns');


    Route::get('/payment/{order}/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    
    // Wishlist

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    // POST ya GET dono use kar sakte hain, redirect ke liye GET asaan hai
    Route::get('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');


    // Checkout (Yahan user ka email/mobile zaroori hota hai)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
    // Dashboard Route
   Route::get('/dashboard', function () { return view('frontend.dashboard'); })->name('dashboard');
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});


require __DIR__ . '/auth.php';