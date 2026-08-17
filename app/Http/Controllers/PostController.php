<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // ==========================================
    // SHOW ALL POSTS (Filtered by Role)
    // ==========================================
    public function index()
    {
        $user = auth()->user();
        $query = Post::latest();

        // 🟢 B2B SELLER LOGIC: Seller ko sirf uske apne posts dikhao
        if ($user->role === 'seller') {
            $query->where('user_id', $user->id);
        }

        $posts = $query->get();
        return view('admin.posts.index', compact('posts'));
    }

    // ==========================================
    // SHOW CREATE FORM (Filtered Category Dropdown)
    // ==========================================
    public function create()
    {
        $user = auth()->user();
        $catQuery = Category::where('status', 1); // Active categories

        // 🟢 B2B SELLER LOGIC: Seller ko dropdown me sirf uski categories dikhao
        if ($user->role === 'seller') {
            $catQuery->where('user_id', $user->id);
        }

        $categories = $catQuery->get();
        return view('admin.posts.create', compact('categories'));
    }

    // ==========================================
    // STORE NEW POST
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts',
            'slug' => 'nullable|unique:posts', 
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);

        $post = new Post();
        $post->user_id = auth()->id(); // 🟢 B2B SELLER LOGIC: Owner ID assign ki
        $post->title = $request->title;
        $post->slug = $slug;
        $post->category_id = $request->category_id;
        $post->content = $request->content;
        $post->status = $request->status ?? 1; 

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'posts/' . $slug . '/' . $imageName;
            $image->move(public_path('posts/' . $slug), $imageName);
            $post->image = $imagePath;
        }

        $post->save();

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Posts',
            'description' => "Created a new blog post: '{$post->title}'",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully');
    }

    // ==========================================
    // SHOW EDIT FORM (Security Check Included)
    // ==========================================
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK: Seller dusre ke posts edit nahi kar sakta
        if ($user->role === 'seller' && $post->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this post.');
        }

        $catQuery = Category::where('status', 1);
        if ($user->role === 'seller') {
            $catQuery->where('user_id', $user->id);
        }
        $categories = $catQuery->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    // ==========================================
    // UPDATE POST
    // ==========================================
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|unique:posts,title,' . $id,
            'slug' => 'required|unique:posts,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
        ]);

        // Form fields process karne ke liye template array taiyar kiya
        $post->fill($request->all());
        $post->slug = Str::slug($request->slug);

        // Image upload handling during update (optional, code logic clean rakhne ke liye)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'posts/' . $post->slug . '/' . $imageName;
            $image->move(public_path('posts/' . $post->slug), $imageName);
            $post->image = $imagePath;
        }

        // 🟢 CAPTURE CHANGES FOR AUDIT TRAIL
        $changes = $post->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $post->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $post->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Posts',
                'description' => json_encode(['post_title' => $post->title, 'old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully');
    }

    // ==========================================
    // DELETE POST
    // ==========================================
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG BEFORE DELETING RECORD
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Posts',
            'description' => "Deleted blog post: '{$post->title}'",
            'ip_address' => request()->ip(),
        ]);

        // Physical folder and image deletion code logic
        if ($post->image && file_exists(public_path($post->image))) {
            @unlink(public_path($post->image));
        }

        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully');
    }

    // ==========================================
    // UPDATE STATUS (AJAX/TOGGLE)
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        // 🟢 B2B SELLER SECURITY CHECK
        if ($user->role === 'seller' && $post->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $oldStatus = $post->status;
        $post->status = $request->status;
        $post->save();

        // 🟢 CREATE STATUS TOGGLE LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Status',
            'module' => 'Posts',
            'description' => json_encode([
                'post_title' => $post->title,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]),
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}