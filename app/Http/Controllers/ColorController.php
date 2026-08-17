<?php
namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index() {
        $user = auth()->user();
        $query = Color::latest();

        // 🟢 B2B SELLER LOGIC: Seller ko sirf apne banaye hue colors dikhenge
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $colors = $query->paginate(10);
        return view('admin.colors.index', compact('colors'));
    }

    public function create() {
        return view('admin.colors.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required', 
            'status' => 'required'
        ]);

        $data = $request->all();
        // 🟢 B2B SELLER LOGIC: Color save karte waqt current user ki ID add karein
        $data['user_id'] = auth()->id();

        $color = Color::create($data);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Colors',
            'description' => "Created new color: {$color->name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Color added.');
    }

    public function edit($id) {
        $color = Color::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Edit form kholne se pehle check
        if ($user->role === 'seller' && $color->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, $id) {
        $color = Color::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Update karne se pehle check
        if ($user->role === 'seller' && $color->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $color->fill($request->all());
        
        // 🟢 CAPTURE CHANGES FOR ACTIVITY LOG
        $changes = $color->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $color->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $color->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Colors',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.colors.index')->with('success', 'Color updated.');
    }

    public function destroy($id) {
        $color = Color::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Delete karne se pehle check
        if ($user->role === 'seller' && $color->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Colors',
            'description' => "Deleted color: {$color->name}",
            'ip_address' => request()->ip(),
        ]);

        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted.');
    }
}