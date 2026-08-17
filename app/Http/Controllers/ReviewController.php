<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product; 
use App\Models\User;    
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;

class ReviewController extends Controller
{
    public function index(Request $request) {
        $user = auth()->user();
        
        $query = Review::with(['reviewable', 'user']);

        // 🟢 B2B SELLER LOGIC: Seller ko sirf wahi reviews dikhao jinke products unke hain
        if ($user->role === 'seller') {
            $query->whereHasMorph('reviewable', [Product::class], function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // --- FILTERS ---
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }
        if ($request->filled('is_spam')) {
            $query->where('is_spam', $request->is_spam);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }

        $reviews = $query->latest()->paginate(15); 
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create() {
        $user = auth()->user();
        
        $productsQuery = Product::where('status', 1);
        if ($user->role === 'seller') {
            $productsQuery->where('user_id', $user->id);
        }
        $products = $productsQuery->get(); 

        $users = User::all(); 
        return view('admin.reviews.create', compact('products', 'users'));
    }

    public function store(Request $request) {
        $request->validate([
            'product_id' => 'required',
            'user_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'status' => 'required|boolean',
            'is_verified' => 'required|boolean',
        ]);

        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller') {
            $productOwner = Product::where('id', $request->product_id)
                                   ->where('user_id', $user->id)
                                   ->exists();
            if (!$productOwner) {
                abort(403, 'Unauthorized. Aap dusre ke products par review add nahi kar sakte.');
            }
        }

        // Auto Spam Detection (Basic Keyword match)
        $spamKeywords = ['buy cheap', 'click here', 'viagra', 'casino', 'http://', 'https://'];
        $isSpam = 0;
        foreach ($spamKeywords as $word) {
            if (stripos($request->comment, $word) !== false) {
                $isSpam = 1; 
                break;
            }
        }

        $review = Review::create([
            'user_id' => $request->user_id,
            'reviewable_type' => 'App\Models\Product', 
            'reviewable_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => $isSpam ? 0 : $request->status, 
            'is_verified' => $request->is_verified,
            'is_spam' => $isSpam,
        ]);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Reviews',
            'description' => "Manually created a review for Product ID: {$request->product_id} (Spam: " . ($isSpam ? 'Yes' : 'No') . ")",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review added successfully!');
    }

    public function edit($id) {
        $review = Review::with('reviewable')->findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller') {
            if ($review->reviewable_type === 'App\Models\Product' && $review->reviewable->user_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
        }

        $productsQuery = Product::query();
        if ($user->role === 'seller') {
            $productsQuery->where('user_id', $user->id);
        }
        $products = $productsQuery->get();

        $users = User::all();
        return view('admin.reviews.edit', compact('review', 'products', 'users'));
    }

    public function update(Request $request, $id) {
        $review = Review::with('reviewable')->findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller') {
            if ($review->reviewable_type === 'App\Models\Product' && $review->reviewable->user_id !== $user->id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'status' => 'required|boolean',
            'is_verified' => 'required|boolean',
            'is_spam' => 'required|boolean',
        ]);

        // Changes capture karne ke liye fill ka use kiya
        $review->fill([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => $request->status,
            'is_verified' => $request->is_verified,
            'is_spam' => $request->is_spam,
        ]);

        // 🟢 CAPTURE CHANGES FOR AUDIT TRAIL
        $changes = $review->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $review->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $review->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Reviews',
                'description' => json_encode(['review_id' => $review->id, 'old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully!');
    }

    public function toggleStatus($id) {
        $review = Review::with('reviewable')->findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $review->reviewable_type === 'App\Models\Product' && $review->reviewable->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $oldStatus = $review->status;
        $review->status = !$review->status;
        $review->save();

        // 🟢 CREATE STATUS TOGGLE LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Toggle Status',
            'module' => 'Reviews',
            'description' => "Toggled visibility status of Review ID: {$review->id} from " . ($oldStatus ? 'Active' : 'Inactive') . " to " . ($review->status ? 'Active' : 'Inactive'),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Status updated!');
    }

    public function toggleSpam($id) {
        $review = Review::with('reviewable')->findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $review->reviewable_type === 'App\Models\Product' && $review->reviewable->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $oldSpamStatus = $review->is_spam;
        $review->is_spam = !$review->is_spam;
        if($review->is_spam) { $review->status = 0; }
        $review->save();

        // 🟢 CREATE SPAM TOGGLE LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Toggle Spam',
            'module' => 'Reviews',
            'description' => "Changed spam status of Review ID: {$review->id} from " . ($oldSpamStatus ? 'Spam' : 'Not Spam') . " to " . ($review->is_spam ? 'Spam (Auto-Hidden)' : 'Not Spam'),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Spam status updated!');
    }

    public function destroy($id) {
        $review = Review::with('reviewable')->findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $review->reviewable_type === 'App\Models\Product' && $review->reviewable->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Reviews',
            'description' => "Deleted review record ID: {$review->id} posted by User ID: {$review->user_id}",
            'ip_address' => request()->ip(),
        ]);

        $review->delete();
        return back()->with('success', 'Review deleted successfully!');
    }
}