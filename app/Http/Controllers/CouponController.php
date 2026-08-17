<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Coupon::latest();

        // 🟢 B2B SELLER LOGIC: Seller ko sirf apne banaye hue coupons dikhenge
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $coupons = $query->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'discount_type' => 'required|in:percentage,flat',
            'discount_amount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date|after_or_equal:today',
        ]);

        $data = $request->all();
        // 🟢 B2B SELLER LOGIC: Coupon save karte waqt current user ki ID add karein
        $data['user_id'] = auth()->id();

        $coupon = Coupon::create($data);

        // Activity Log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Coupons',
            'description' => "Created new coupon: {$coupon->code}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon Created Successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Seller kisi aur ka coupon edit na kar sake
        if ($user->role === 'seller' && $coupon->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Update karne se pehle validation
        if ($user->role === 'seller' && $coupon->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'discount_type' => 'required|in:percentage,flat',
            'discount_amount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        // Track changes for logs
        $coupon->fill($request->all());
        $changes = $coupon->getDirty();
        
        if (!empty($changes)) {
            $oldData = [];
            $newData = [];
            foreach ($changes as $key => $value) {
                $oldData[$key] = $coupon->getOriginal($key);
                $newData[$key] = $value;
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Coupons',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        $coupon->save();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon Updated Successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Delete karne se pehle validation
        if ($user->role === 'seller' && $coupon->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Coupons',
            'description' => "Deleted coupon: {$coupon->code}",
            'ip_address' => request()->ip(),
        ]);

        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon Deleted Successfully.');
    }
}