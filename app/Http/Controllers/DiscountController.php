<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Category;
use App\Models\Product; 
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Discount::with(['category'])->withCount('products')->latest();

        // 🟢 B2B SELLER LOGIC: Seller ko sirf apne discounts dikhayen
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $discounts = $query->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = Category::where('status', 'active')->get();
        return view('admin.discounts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,flat',
            'discount_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id' 
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id(); // 🟢 B2B SELLER LOGIC
        $data['apply_to_all'] = $request->input('apply_to_all') == 1 ? 1 : 0;

        if ($data['apply_to_all']) {
            $data['category_id'] = null;
        }

        $discount = Discount::create($data);

        // 🟢 B2B SELLER LOGIC: Products sync karte waqt security check
        if (!$data['apply_to_all'] && empty($data['category_id']) && $request->has('products')) {
            $productIds = $request->products;
            if (auth()->user()->role === 'seller') {
                $validIds = Product::whereIn('id', $productIds)
                                   ->where('user_id', auth()->id())
                                   ->pluck('id')->toArray();
                $discount->products()->sync($validIds);
            } else {
                $discount->products()->sync($productIds);
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Discounts',
            'description' => "Created new discount campaign: {$discount->name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount Created Successfully.');
    }

    public function edit($id)
    {
        $discount = Discount::with('products')->findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $discount->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $categories = Category::all();
        $selected_products = $discount->products->pluck('id')->toArray(); 
        
        return view('admin.discounts.edit', compact('discount', 'categories', 'selected_products'));
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $discount->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,flat',
            'discount_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'products' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['apply_to_all'] = $request->input('apply_to_all') == 1 ? 1 : 0;

        if ($data['apply_to_all']) {
            $data['category_id'] = null;
        }

        $discount->fill($data);
        $changes = $discount->getDirty();
        $discount->save();

        // 🟢 B2B SELLER LOGIC: Sync products securely
        if (!$data['apply_to_all'] && empty($data['category_id']) && $request->has('products')) {
            if ($user->role === 'seller') {
                $validIds = Product::whereIn('id', $request->products)
                                   ->where('user_id', $user->id)
                                   ->pluck('id')->toArray();
                $discount->products()->sync($validIds);
            } else {
                $discount->products()->sync($request->products);
            }
        } else {
            $discount->products()->sync([]);
        }

        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Discounts',
                'description' => "Updated discount campaign: {$discount->name}",
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.discounts.index')->with('success', 'Discount Updated Successfully.');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $discount->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $discount->products()->detach(); 
        $discount->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Discounts',
            'description' => "Deleted discount: {$discount->name}",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.discounts.index')->with('success', 'Discount Deleted Successfully.');
    }

    public function searchProducts(Request $request)
    {
        $search = $request->input('q');
        $user = auth()->user();

        $query = Product::with('category');
        
        // 🟢 B2B SELLER LOGIC: Search mein sirf apne products dikhao
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $products = $query->limit(50)->get();
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'category' => $product->category ? $product->category->name : 'N/A'
            ];
        }

        return response()->json($formattedProducts);
    }
}