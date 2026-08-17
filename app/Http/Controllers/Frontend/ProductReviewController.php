<?php
/* ======================================================================
   Arthubly — FRONTEND REVIEW CONTROLLER
   File: app/Http/Controllers/Frontend/ProductReviewController.php

   Admin ka ReviewController (App\Http\Controllers\ReviewController) waise
   ka waisa rehta hai — ye sirf product page se aane wale customer reviews
   ke liye hai. Dono ek hi `reviews` table use karte hain, isliye admin
   panel me ye reviews turant dikhne lagenge (approve / spam / delete sab
   wahin se chalega).

   ROUTE (routes/web.php me add karein):
       use App\Http\Controllers\Frontend\ProductReviewController;

       Route::post('product/{product}/review', [ProductReviewController::class, 'store'])
            ->middleware('auth')
            ->name('product.review.store');
   ====================================================================== */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductReviewController extends Controller
{
    /**
     * Naya review auto-publish ho ya admin approval ka intezaar kare?
     *   true  → spam na ho to seedha live (page par turant dikhega)
     *   false → status = 0, admin panel se approve karna padega
     */
    private const AUTO_PUBLISH = true;

    /** Admin controller wali hi spam list — dono jagah same behaviour */
    private const SPAM_WORDS = ['buy cheap', 'click here', 'viagra', 'casino', 'http://', 'https://'];

    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Pehle rating chunein.',
        ]);

        // ---- ek user, ek product, ek review ----
        $already = Review::where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($already) {
            return back()
                ->withErrors(['rating' => 'Aap is product par pehle hi review de chuke hain.'])
                ->withInput();
        }

        // ---- spam check ----
        $isSpam = 0;
        foreach (self::SPAM_WORDS as $word) {
            if (stripos((string) $request->comment, $word) !== false) {
                $isSpam = 1;
                break;
            }
        }

        $isVerified = $this->hasPurchased($product->id) ? 1 : 0;

        Review::create([
            'user_id'         => auth()->id(),
            'reviewable_type' => Product::class,
            'reviewable_id'   => $product->id,
            'rating'          => $data['rating'],
            'comment'         => $data['comment'] ?? null,
            'status'          => $isSpam ? 0 : (self::AUTO_PUBLISH ? 1 : 0),
            'is_verified'     => $isVerified,
            'is_spam'         => $isSpam,
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Create',
            'module'      => 'Reviews',
            'description' => "Customer review posted for Product ID: {$product->id} "
                . "(Spam: " . ($isSpam ? 'Yes' : 'No') . ", Verified: " . ($isVerified ? 'Yes' : 'No') . ")",
            'ip_address'  => $request->ip(),
        ]);

        $msg = $isSpam
            ? 'Aapka review mil gaya hai — review ke baad publish hoga.'
            : (self::AUTO_PUBLISH
                ? 'Shukriya! Aapka review live ho gaya hai.'
                : 'Shukriya! Aapka review approval ke baad dikhega.');

        return back()->with('review_ok', $msg)->withFragment('product-review-tab');
    }

    /**
     * "Verified buyer" badge — user ne ye product kabhi khareeda hai ya nahi.
     * Har project ka orders schema alag hota hai, isliye table/column pehle
     * check karte hain. Na mile to chup-chaap false (badge nahi lagega,
     * baaki review normal save hota hai).
     */
    private function hasPurchased(int $productId): bool
    {
        if (!auth()->check()) {
            return false;
        }

        try {
            if (!Schema::hasTable('order_items') || !Schema::hasTable('orders')) {
                return false;
            }

            $itemProductCol = Schema::hasColumn('order_items', 'product_id') ? 'product_id' : null;
            if (!$itemProductCol) {
                return false;
            }

            $q = DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('order_items.' . $itemProductCol, $productId)
                ->where('orders.user_id', auth()->id());

            // agar status column hai to cancelled orders hata dein
            if (Schema::hasColumn('orders', 'status')) {
                $q->whereNotIn('orders.status', ['cancelled', 'canceled', 'failed']);
            }

            return $q->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
