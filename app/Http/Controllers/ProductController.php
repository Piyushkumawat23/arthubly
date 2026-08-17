<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Size;
use App\Models\User;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use App\Services\ProductImageService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;

class ProductController extends Controller
{
    // ==========================================
    // 🟢 PRIVATE AUTOMATION METHODS
    // ==========================================

    private function isContentSpam($text) 
    {
        if (strlen(trim($text)) < 5) return true; 
        if (preg_match('/(.)\1{4,}/', $text)) return true; 
        if (preg_match('/\b(test|demo|testing|xyz|abc|asdf|qwerty)\b/i', $text)) return true;
        return false;
    }

    private function inspectImage($imagePath) 
    {
        @ini_set('memory_limit', '512M');

        list($width, $height) = @getimagesize($imagePath);
        // Handicraft ke liye 300 bahut kam hai — 800 minimum rakha hai
        if (!$width || $width < 800 || $height < 800) return 'bad_quality';

        $img = @imagecreatefromstring(file_get_contents($imagePath));
        if (!$img) return 'corrupted';

        $skinPixels = 0;
        $totalPixels = 0;

        for ($x = 0; $x < $width; $x += 10) {
            for ($y = 0; $y < $height; $y += 10) {
                $totalPixels++;
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if ($r > 95 && $g > 40 && $b > 20 && ($r - $g) > 15 && $r > $g && $r > $b && (max($r, $g, $b) - min($r, $g, $b)) > 15) {
                    $skinPixels++;
                }
            }
        }
        imagedestroy($img);

        if ($totalPixels === 0) return 'corrupted';

        $skinRatio = ($skinPixels / $totalPixels) * 100;
        if ($skinRatio > 45) {
            return 'adult_content';
        }

        return 'pass';
    }

    // ==========================================
    // LIST PRODUCTS
    // ==========================================
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variations'])->latest();

        if (auth()->user()->role === 'seller') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'instock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status == 'outofstock') {
                $query->where('stock', '<=', 0);
            }
        }

        $products = $query->paginate(10);
        $categories = Category::where('status', 'active')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    // Show Create Form
    public function create()
    {
        $user = auth()->user();

        $catQuery = Category::where('status', 'active');
        $colQuery = Color::where('status', 'active');
        $sizeQuery = Size::where('status', 'active');
        $brandQuery = Brand::where('status', 'active');
        $allProductsQuery = Product::where('status', 'active');

        if ($user->role === 'seller') {
            $catQuery->where('user_id', $user->id);
            $colQuery->where('user_id', $user->id);
            $sizeQuery->where('user_id', $user->id);
            $brandQuery->where('user_id', $user->id);
            $allProductsQuery->where('user_id', $user->id);
        }

        $categories = $catQuery->get();
        $colors = $colQuery->get();
        $sizes = $sizeQuery->get();
        $brands = $brandQuery->get();
        $allProducts = $allProductsQuery->get();

        return view('admin.products.create', compact('categories', 'colors', 'sizes', 'brands', 'allProducts'));
    }

    // ==========================================
    // STORE: NAYA PRODUCT BANANE KA LOGIC
    // ==========================================
    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver);
        $imgSvc  = new ProductImageService;

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'status' => 'required|in:active,inactive,pending,rejected',
            'hover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $data = $request->except(['hover_image', 'gallery_images', 'gallery_colors', 'variation_image']);
        $data['user_id'] = auth()->id();

        if (auth()->user()->role === 'seller') {
            $autoStatus = 'pending'; 
            
            if ($this->isContentSpam($request->name)) {
                $autoStatus = 'rejected';
            }

            if ($autoStatus !== 'rejected' && $request->hasFile('hover_image')) {
                $inspection = $this->inspectImage($request->file('hover_image')->getRealPath());
                if ($inspection === 'adult_content' || $inspection === 'bad_quality') {
                    $autoStatus = 'rejected';
                }
            }
            $data['status'] = $autoStatus;
            $data['is_trending'] = 0;
            $data['is_new_arrival'] = 0;

        } else {
            $data['status'] = $request->status;
            $data['is_trending'] = $request->has('is_trending') ? 1 : 0;
            $data['is_new_arrival'] = $request->has('is_new_arrival') ? 1 : 0;
        }

        $data['slug'] = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $data['sku'] = $request->sku ? $request->sku : 'SKU-'.strtoupper(substr($request->name, 0, 3)).'-'.time();
        $data['shipping_cost'] = $request->shipping_cost ?? 0.00;

        // 1. HOVER IMAGE
        if ($request->hasFile('hover_image')) {
            $hoverPath = public_path('uploads/products/hover/');
            File::ensureDirectoryExists($hoverPath);

            $imageFile = $request->file('hover_image');
            $productSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
            $filename = $productSlug.'-hover-'.time().'.'.$imageFile->getClientOriginalExtension();

            // ORIGINAL save + saare responsive variants (webp+jpg)
            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $originalName = $imgSvc->process($imageFile, $baseName);

            // legacy copy (purana frontend na toote) — ab 600px, 276 nahi
            $imgSvc->writeLegacyCopies($imgSvc->originalPath($originalName), [
                ['dir' => $hoverPath, 'name' => $originalName, 'w' => 600],
            ]);

            $data['hover_image'] = $originalName;
        }

        $product = Product::create($data);

        // 🟢 CREATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Products',
            'description' => "Created new product: {$product->name} (SKU: {$product->sku})",
            'ip_address' => request()->ip(),
        ]);

        if ($request->has('related_products')) {
            if (auth()->user()->role === 'seller') {
                $validIds = Product::whereIn('id', $request->related_products)
                                   ->where('user_id', auth()->id())
                                   ->pluck('id')->toArray();
                $product->relatedProducts()->attach($validIds);
            } else {
                $product->relatedProducts()->attach($request->related_products);
            }
        }
        
        // --- 2. GALLERY IMAGES ---
        if ($request->has('gallery_images')) {
            $galleryPath = public_path('uploads/products/gallery/');
            $gallerySidePath = public_path('uploads/products/gallery/side/');

            \Illuminate\Support\Facades\File::ensureDirectoryExists($galleryPath);
            \Illuminate\Support\Facades\File::ensureDirectoryExists($gallerySidePath);

            foreach ($request->gallery_images as $index => $files) {
                $assignedColor = $request->gallery_colors[$index] ?? null;
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $productSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
                        $colorName = $assignedColor ? '-'.Str::slug($assignedColor) : '';

                        $baseName = $productSlug.$colorName.'-gallery-'.time().'-'.uniqid();
                        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                        $mainName = $baseName.'_main.'.$ext;
                        $sideName = $baseName.'_side.'.$ext;

                        // FIX: variants ka base = $mainName ka base, taaki DB ka
                        // 'image' column aur variants ka naam match karein
                        $mainBase = pathinfo($mainName, PATHINFO_FILENAME);

                        $originalName = $imgSvc->process($file, $mainBase);
                        $srcPath = $imgSvc->originalPath($originalName);

                        // legacy copies — har ek ORIGINAL se banti hai (chaining nahi)
                        $imgSvc->writeLegacyCopies($srcPath, [
                            ['dir' => $galleryPath,     'name' => $originalName, 'w' => 1200],
                            ['dir' => $gallerySidePath, 'name' => $sideName,     'w' => 200],
                        ]);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $originalName,   // base name — variants isi se milte hain
                            'side_image' => $sideName,
                            'product_color' => $assignedColor,
                            'sort_order' => 0,
                            'is_primary' => 0,
                        ]);
                    }
                }
            }
        }

        // 3. VARIATIONS
        if ($request->has('variation_size') || $request->has('variation_color')) {
            $varPath = public_path('uploads/products/variations/');
            $varThumbPath = public_path('uploads/products/variations/thumbs/');
            $varSidePath = public_path('uploads/products/variations/side/');

            File::ensureDirectoryExists($varPath);
            File::ensureDirectoryExists($varThumbPath);
            File::ensureDirectoryExists($varSidePath);

            $colors = $request->variation_color;
            $sizes = $request->variation_size;
            $skus = $request->variation_sku;
            $prices = $request->variation_price;
            $stocks = $request->variation_stock;
            $images = $request->file('variation_image');

            for ($i = 0; $i < count($sizes); $i++) {
                if (! empty($sizes[$i]) || ! empty($colors[$i])) {
                    $varSku = ! empty($skus[$i]) ? $skus[$i] : $product->sku.'-'.strtoupper($colors[$i]).'-'.strtoupper($sizes[$i]);

                    $varImageName = null;
                    if (isset($images[$i]) && $images[$i]->isValid()) {
                        $file = $images[$i];
                        $productSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

                        $colorStr = ! empty($colors[$i]) ? '-'.Str::slug($colors[$i]) : '';
                        $sizeStr = ! empty($sizes[$i]) ? '-'.Str::slug($sizes[$i]) : '';

                        $baseName = $productSlug.$colorStr.$sizeStr.'-'.time().'-'.uniqid();
                        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');

                        $varImageName = $baseName.'.'.$ext;
                        $thumbName = $baseName.'_thumb.'.$ext;
                        $sideName = $baseName.'_side.'.$ext;

                        // ORIGINAL + responsive variants
                        $varImageName = $imgSvc->process($file, $baseName);
                        $srcPath = $imgSvc->originalPath($varImageName);

                        // legacy copies — har ek ORIGINAL se (quality cascade fix)
                        $imgSvc->writeLegacyCopies($srcPath, [
                            ['dir' => $varPath,      'name' => $varImageName, 'w' => 1200],
                            ['dir' => $varThumbPath, 'name' => $thumbName,    'w' => 600],
                            ['dir' => $varSidePath,  'name' => $sideName,     'w' => 200],
                        ]);
                    }

                    ProductVariation::create([
                        'product_id' => $product->id,
                        'color' => $colors[$i],
                        'size' => $sizes[$i],
                        'sku' => $varSku,
                        'price' => $prices[$i] ?? $product->price,
                        'stock' => $stocks[$i] ?? 0,
                        'image' => $varImageName,
                    ]);
                }
            }
        }

        if (isset($data['status']) && $data['status'] === 'rejected') {
            return redirect()->route('admin.products.index')->with('error', 'Product auto-rejected due to quality or content policy.');
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully and pending admin approval.');
    }

    // Show Edit Form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();
        
        if ($user->role === 'seller' && $product->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $catQuery = Category::where('status', 'active');
        $colQuery = Color::where('status', 'active');
        $sizeQuery = Size::where('status', 'active');
        $brandQuery = Brand::where('status', 'active');
        $allProductsQuery = Product::where('id', '!=', $id)->where('status', 'active');

        if ($user->role === 'seller') {
            $catQuery->where('user_id', $user->id);
            $colQuery->where('user_id', $user->id);
            $sizeQuery->where('user_id', $user->id);
            $brandQuery->where('user_id', $user->id);
            $allProductsQuery->where('user_id', $user->id);
        }

        $categories = $catQuery->get();
        $colors = $colQuery->get();
        $sizes = $sizeQuery->get();
        $brands = $brandQuery->get();
        $allProducts = $allProductsQuery->get();

        return view('admin.products.edit', compact('product', 'categories', 'colors', 'sizes', 'brands', 'allProducts'));
    }

    // ==========================================
    // UPDATE: PRODUCT UPDATE KARNE KA LOGIC
    // ==========================================
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        if (auth()->user()->role === 'seller' && $product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $manager = new ImageManager(new Driver);
        $imgSvc  = new ProductImageService;

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'status' => 'required|in:active,inactive,pending,rejected', 
            'hover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $data = $request->except(['hover_image', 'gallery_images', 'gallery_colors', 'variation_image', 'delete_gallery_images', 'existing_gallery_color']);
        
        if (auth()->user()->role === 'seller') {
            $autoStatus = 'pending'; 
            
            if ($this->isContentSpam($request->name)) {
                $autoStatus = 'rejected';
            }

            if ($autoStatus !== 'rejected' && $request->hasFile('hover_image')) {
                $inspection = $this->inspectImage($request->file('hover_image')->getRealPath());
                if ($inspection === 'adult_content' || $inspection === 'bad_quality') {
                    $autoStatus = 'rejected';
                }
            }
            $product->status = $autoStatus;
            $product->is_trending = 0;
            $product->is_new_arrival = 0;

        } else {
            $product->status = $request->status;
            $product->is_trending = $request->has('is_trending') ? 1 : 0;
            $product->is_new_arrival = $request->has('is_new_arrival') ? 1 : 0;
        }

        $product->fill($data);
        $product->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        // --- 1. HOVER IMAGE LOGIC ---
        if ($request->hasFile('hover_image')) {
            $hoverPath = public_path('uploads/products/hover/');
            File::ensureDirectoryExists($hoverPath);

            if ($product->hover_image && file_exists($hoverPath.$product->hover_image)) {
                @unlink($hoverPath.$product->hover_image);
            }

            $imageFile = $request->file('hover_image');
            $productSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
            $filename = $productSlug.'-hover-'.time().'.'.$imageFile->getClientOriginalExtension();

            $imgSvc->deleteAll($product->hover_image);

            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $originalName = $imgSvc->process($imageFile, $baseName);

            $imgSvc->writeLegacyCopies($imgSvc->originalPath($originalName), [
                ['dir' => $hoverPath, 'name' => $originalName, 'w' => 600],
            ]);

            $product->hover_image = $originalName;
        }

        // 🟢 CAPTURE CHANGES FOR ACTIVITY LOG
        $changes = $product->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $product->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $product->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Products',
                'description' => json_encode(['old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        if ($request->has('related_products')) {
            if (auth()->user()->role === 'seller') {
                $validIds = Product::whereIn('id', $request->related_products)
                                   ->where('user_id', auth()->id())
                                   ->pluck('id')->toArray();
                $product->relatedProducts()->sync($validIds);
            } else {
                $product->relatedProducts()->sync($request->related_products);
            }
        } else {
            $product->relatedProducts()->detach();
        }

        // --- EXISTING GALLERY DELETE LOGIC ---
        if ($request->has('delete_gallery_images')) {
            foreach ($request->delete_gallery_images as $imageId) {
                $galleryImg = ProductImage::find($imageId);
                if ($galleryImg) {
                    $mainPath = public_path('uploads/products/gallery/'.$galleryImg->image);
                    $sidePath = public_path('uploads/products/gallery/side/'.$galleryImg->side_image);

                    if (file_exists($mainPath)) @unlink($mainPath);
                    if (file_exists($sidePath)) @unlink($sidePath);

                    $galleryImg->delete();
                }
            }
        }

        // --- EXISTING GALLERY COLOR UPDATE ---
        if ($request->has('existing_gallery_color')) {
            foreach ($request->existing_gallery_color as $imgId => $colorName) {
                ProductImage::where('id', $imgId)->update([
                    'product_color' => $colorName,
                ]);
            }
        }

        // --- 2. NEW GALLERY IMAGES LOGIC ---
        if ($request->has('gallery_images')) {
            $galleryPath = public_path('uploads/products/gallery/');
            $gallerySidePath = public_path('uploads/products/gallery/side/');

            \Illuminate\Support\Facades\File::ensureDirectoryExists($galleryPath);
            \Illuminate\Support\Facades\File::ensureDirectoryExists($gallerySidePath);

            foreach ($request->gallery_images as $index => $files) {
                $assignedColor = $request->gallery_colors[$index] ?? null;
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $productSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
                        $colorName = $assignedColor ? '-'.Str::slug($assignedColor) : '';

                        $baseName = $productSlug.$colorName.'-gallery-'.time().'-'.uniqid();
                        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                        $mainName = $baseName.'_main.'.$ext;
                        $sideName = $baseName.'_side.'.$ext;

                        // FIX: variants ka base = $mainName ka base, taaki DB ka
                        // 'image' column aur variants ka naam match karein
                        $mainBase = pathinfo($mainName, PATHINFO_FILENAME);

                        $originalName = $imgSvc->process($file, $mainBase);
                        $srcPath = $imgSvc->originalPath($originalName);

                        // legacy copies — har ek ORIGINAL se banti hai (chaining nahi)
                        $imgSvc->writeLegacyCopies($srcPath, [
                            ['dir' => $galleryPath,     'name' => $originalName, 'w' => 1200],
                            ['dir' => $gallerySidePath, 'name' => $sideName,     'w' => 200],
                        ]);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $originalName,   // base name — variants isi se milte hain
                            'side_image' => $sideName,
                            'product_color' => $assignedColor,
                        ]);
                    }
                }
            }
        }

        // --- 3. VARIATIONS LOGIC ---
        $oldVariations = ProductVariation::where('product_id', $product->id)->get();
        ProductVariation::where('product_id', $product->id)->delete();

        if ($request->has('variation_size') || $request->has('variation_color')) {
            $varPath = public_path('uploads/products/variations/');
            $varThumbPath = public_path('uploads/products/variations/thumbs/');
            $varSidePath = public_path('uploads/products/variations/side/');

            File::ensureDirectoryExists($varPath);
            File::ensureDirectoryExists($varThumbPath);
            File::ensureDirectoryExists($varSidePath);

            $images = $request->file('variation_image');
            $colors = $request->variation_color;   // FIX: pehle ye undefined tha
            $sizes  = $request->variation_size;

            for ($i = 0; $i < count($request->variation_color); $i++) {
                $varImageName = $request->old_variation_image[$i] ?? null;

                if (isset($images[$i]) && $images[$i]->isValid()) {
                    $file = $images[$i];
                    $productSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

                    $colorStr = ! empty($colors[$i]) ? '-'.Str::slug($colors[$i]) : '';
                    $sizeStr = ! empty($sizes[$i]) ? '-'.Str::slug($sizes[$i]) : '';

                    $baseName = $productSlug.$colorStr.$sizeStr.'-'.time().'-'.uniqid();
                    $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');

                    $varImageName = $baseName.'.'.$ext;
                    $thumbName = $baseName.'_thumb.'.$ext;
                    $sideName = $baseName.'_side.'.$ext;

                    // ORIGINAL + responsive variants
                    $varImageName = $imgSvc->process($file, $baseName);
                    $srcPath = $imgSvc->originalPath($varImageName);

                    $imgSvc->writeLegacyCopies($srcPath, [
                        ['dir' => $varPath,      'name' => $varImageName, 'w' => 1200],
                        ['dir' => $varThumbPath, 'name' => $thumbName,    'w' => 600],
                        ['dir' => $varSidePath,  'name' => $sideName,     'w' => 200],
                    ]);
                }

                ProductVariation::create([
                    'product_id' => $product->id,
                    'color' => $request->variation_color[$i],
                    'size' => $request->variation_size[$i],
                    'sku' => $request->variation_sku[$i],
                    'price' => $request->variation_price[$i] ?? $product->price,
                    'stock' => $request->variation_stock[$i] ?? 0,
                    'image' => $varImageName,
                ]);
            }
        }

        if ($product->status === 'rejected') {
            return redirect()->back()->with('error', 'Update rejected due to quality or content policy.');
        }

        return redirect()->back()->with('success', 'Product updated successfully with full images!');
    }

    // ==========================================
    // DESTROY: PRODUCT DELETE KARNE KA LOGIC
    // ==========================================
    public function destroy($id)
    {
        $product = Product::with(['images', 'variations'])->findOrFail($id);
        $imgSvc  = new ProductImageService;

        if (auth()->user()->role === 'seller' && $product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Products',
            'description' => "Deleted product: {$product->name} (SKU: {$product->sku})",
            'ip_address' => request()->ip(),
        ]);

        if ($product->hover_image) {
            $hoverPath = public_path('uploads/products/hover/'.$product->hover_image);
            if (file_exists($hoverPath)) {
                @unlink($hoverPath);
            }
            $imgSvc->deleteAll($product->hover_image);   // originals + r/* variants
        }

        foreach ($product->images as $img) {
            $mainPath = public_path('uploads/products/gallery/'.$img->image);
            $sidePath = public_path('uploads/products/gallery/side/'.$img->side_image);

            if (file_exists($mainPath)) @unlink($mainPath);
            if (file_exists($sidePath)) @unlink($sidePath);

            $imgSvc->deleteAll($img->image);
        }

        foreach ($product->variations as $var) {
            if ($var->image) {
                $info = pathinfo($var->image);
                $thumbName = $info['filename'].'_thumb.'.$info['extension'];
                $sideName = $info['filename'].'_side.'.$info['extension'];

                $mainVar = public_path('uploads/products/variations/'.$var->image);
                $thumbVar = public_path('uploads/products/variations/thumbs/'.$thumbName);
                $sideVar = public_path('uploads/products/variations/side/'.$sideName);

                if (file_exists($mainVar)) @unlink($mainVar);
                if (file_exists($thumbVar)) @unlink($thumbVar);
                if (file_exists($sideVar)) @unlink($sideVar);

                $imgSvc->deleteAll($var->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product and all associated images deleted successfully.');
    }

    public function stockIndex(Request $request)
    {
        $query = Product::with('variations')->latest();

        if (auth()->user()->role === 'seller') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')->orWhere('sku', 'like', '%'.$request->search.'%');
        }
        $products = $query->paginate(15);

        return view('admin.products.stock_index', compact('products'));
    }

    public function stockEdit($id)
    {
        $product = Product::with('variations')->findOrFail($id);

        if (auth()->user()->role === 'seller' && $product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.products.stock_edit', compact('product'));
    }

    public function stockUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (auth()->user()->role === 'seller' && $product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $product->update(['stock' => $request->main_stock]);

        // 🟢 CREATE STOCK UPDATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update',
            'module' => 'Stock',
            'description' => "Updated stock for product: {$product->name} to {$request->main_stock}",
            'ip_address' => request()->ip(),
        ]);

        if ($request->has('variation_stock')) {
            foreach ($request->variation_stock as $varId => $qty) {
                ProductVariation::where('id', $varId)->update(['stock' => $qty]);
            }
        }

        if ($request->main_stock <= 5) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'stock',
                    'message' => "Low Stock Alert: Only {$request->main_stock} left for {$product->name}!",
                    'url' => route('admin.stock.edit', $product->id)
                ]));
            }
        }
        return redirect()->route('admin.stock.index')->with('success', 'Stock updated successfully!');
    }

    public function bulkUpdate(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status;

        if (!empty($ids) && in_array($status, ['active', 'inactive', 'pending', 'rejected'])) { 
            
            // 🟢 CREATE BULK UPDATE ACTIVITY LOG
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Bulk Update',
                'module' => 'Products',
                'description' => "Bulk updated status to '{$status}' for product IDs: " . implode(', ', $ids),
                'ip_address' => request()->ip(),
            ]);

            if (auth()->user()->role === 'seller') {
                Product::whereIn('id', $ids)->where('user_id', auth()->id())->update(['status' => $status]);
            } else {
                Product::whereIn('id', $ids)->update(['status' => $status]);
            }
            return response()->json(['success' => true, 'message' => 'Products updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Selection empty or invalid status.'], 400);
    }
}