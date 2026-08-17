<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\PostLike;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\WebsiteSetting;  


class BlogsController extends Controller
{


    public function index()
    {
        $blogs = Blog::all();
        return view('admin.Blogs.index', compact('blogs'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        return view('admin.Blogs.create', compact('categories'));
    }

    // Store Blogs
    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'title' => 'required|unique:Blogs',
            'slug' => 'nullable|unique:Blogs', // Allow null, but if provided, it must be unique
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Generate slug if not provided
        $slug = $request->slug ?: Str::slug($request->title);

        // Initialize the Blogs
        $Blogs = new Blog();
        $Blogs->title = $request->title;
        $Blogs->slug = $slug;
        $Blogs->category_id = $request->category_id;
        $Blogs->content = $request->content;
        $Blogs->status = $request->status ?? 1; // Default to active if not provided

        // Handle image upload
        if ($request->hasFile('image')) {
            // Get the uploaded image
            $image = $request->file('image');
            // Generate a new name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            // Define the folder path
            $imagePath = 'Blogs/' . $slug . '/' . $imageName;
            // Move the image to the public Blogs folder
            $image->move(public_path('Blogs/' . $slug), $imageName);
            // Store the image path in the database
            $Blogs->image = $imagePath;
        }

        // Save the Blogs
        $Blogs->save();

        // Redirect with success message
        return redirect()->route('admin.blogs.index')->with('success', 'Blogs created successfully');
    }


    // Show edit form
    public function edit($id)
    {
        $blogs = Blog::findOrFail($id);
        $categories = Category::all();
        return view('admin.Blogs.edit', compact('blogs', 'categories'));
    }

    // Update Blogs
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|unique:Blogs,title,' . $id,
            'slug' => 'required|unique:Blogs,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
        ]);

        $Blog = Blog::findOrFail($id);
        $Blog->update($request->all());

        return redirect()->route('admin.blogs.index')->with('success', 'Blogs updated successfully');
    }

    // Delete Blogs
    public function destroy($id)
    {
        Blog::destroy($id);
        return redirect()->route('admin.blogs.index')->with('success', 'Blogs deleted successfully');
    }



    public function updateStatus(Request $request, $id)
    {
        $Blog = Blog::findOrFail($id);
        $Blog->status = $request->status;
        $Blog->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
}
