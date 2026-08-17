<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Category::query();

        // B2B SELLER LOGIC
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $categories = $query->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status ?? 1
        ];

        // Image Upload Logic
        if ($request->hasFile('image')) {
            $imageName = time() . '_img.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $data['image'] = $imageName;
        }

        // Icon Upload Logic
        if ($request->hasFile('icon')) {
            $iconName = time() . '_icon.' . $request->icon->extension();
            $request->icon->move(public_path('uploads/categories/icons'), $iconName);
            $data['icon'] = $iconName;
        }

        $category = Category::create($data);

        // CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Categories',
            'description' => "Created new category: {$category->name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();

        // B2B SELLER LOGIC
        if ($user->role === 'seller' && $category->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();

        // B2B SELLER LOGIC
        if ($user->role === 'seller' && $category->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'description' => 'nullable',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ]);

        // Changes capture karne ke liye data fill karenge
        $category->fill([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        // Update Image
        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
                unlink(public_path('uploads/categories/' . $category->image)); // Purani delete
            }
            $imageName = time() . '_img.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $category->image = $imageName;
        }

        // Update Icon
        if ($request->hasFile('icon')) {
            if ($category->icon && file_exists(public_path('uploads/categories/icons/' . $category->icon))) {
                unlink(public_path('uploads/categories/icons/' . $category->icon));
            }
            $iconName = time() . '_icon.' . $request->icon->extension();
            $request->icon->move(public_path('uploads/categories/icons'), $iconName);
            $category->icon = $iconName;
        }

        // CAPTURE CHANGES FOR ACTIVITY LOG
        $changes = $category->getDirty();
        $oldData = [];
        $newData = [];

        if (! empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $category->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $category->save();

        // CREATE UPDATE ACTIVITY LOG
        if (! empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Categories',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();

        if ($user->role === 'seller' && $category->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete images from folder if they exist before deleting record
        if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            unlink(public_path('uploads/categories/' . $category->image));
        }
        if ($category->icon && file_exists(public_path('uploads/categories/icons/' . $category->icon))) {
            unlink(public_path('uploads/categories/icons/' . $category->icon));
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Categories',
            'description' => "Deleted category: {$category->name}",
            'ip_address' => request()->ip(),
        ]);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();

        if ($user->role === 'seller' && $category->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $oldStatus = $category->status;
        $category->status = $request->status;
        $category->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Status',
            'module' => 'Categories',
            'description' => json_encode([
                'category_name' => $category->name,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]),
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}