<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index() {
        $user = auth()->user();
        $query = Brand::latest();

        // 🟢 B2B SELLER LOGIC: Seller ko sirf apne brands dikhenge
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $brands = $query->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        // 🟢 B2B SELLER LOGIC: Brand save karte waqt current user ki ID daalein
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/brands'), $filename);
            $data['image'] = $filename;
        }

        $brand = Brand::create($data);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Brands',
            'description' => "Created new brand: {$brand->name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }

    public function edit($id) {
        $brand = Brand::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Seller kisi aur ka brand edit na kar sake
        if ($user->role === 'seller' && $brand->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id) {
        $brand = Brand::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Update karne se pehle validation
        if ($user->role === 'seller' && $brand->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->description = $request->description;
        $brand->status = $request->status;

        if ($request->hasFile('image')) {
            if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
                @unlink(public_path('uploads/brands/' . $brand->image));
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/brands'), $filename);
            $brand->image = $filename;
        }
        
        // 🟢 CAPTURE CHANGES FOR ACTIVITY LOG
        $changes = $brand->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $brand->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $brand->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Brands',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }

    public function destroy($id) {
        $brand = Brand::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Delete karne se pehle validation
        if ($user->role === 'seller' && $brand->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Brands',
            'description' => "Deleted brand: {$brand->name}",
            'ip_address' => request()->ip(),
        ]);

        if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
            @unlink(public_path('uploads/brands/' . $brand->image));
        }

        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted.');
    }
}