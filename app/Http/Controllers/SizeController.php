<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index() {
        $user = auth()->user();
        $query = Size::latest();

        // 🟢 B2B SELLER LOGIC: Seller ko sirf apne banaye huye sizes dikhenge
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $sizes = $query->paginate(10);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create() {
        return view('admin.sizes.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'status' => 'required']);
        
        $data = $request->all();
        // 🟢 B2B SELLER LOGIC: Size save karte waqt current user ki ID add karein
        $data['user_id'] = auth()->id();

        $size = Size::create($data);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Sizes',
            'description' => "Created new size: {$size->name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.sizes.index')->with('success', 'Size added.');
    }

    public function edit($id) {
        $size = Size::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Seller kisi aur ka size edit na kar sake
        if ($user->role === 'seller' && $size->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, $id) {
        $size = Size::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Update karne se pehle check
        if ($user->role === 'seller' && $size->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $size->fill($request->all());
        $changes = $size->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $size->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $size->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Sizes',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated.');
    }

    public function destroy($id) {
        $size = Size::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Delete karne se pehle check
        if ($user->role === 'seller' && $size->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Sizes',
            'description' => "Deleted size: {$size->name}",
            'ip_address' => request()->ip(),
        ]);

        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted.');
    }
}