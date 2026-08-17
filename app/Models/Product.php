<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'category_id', 'brand', 'parent_id',
        'price', 'sale_price', 'stock', 'min_order_qty', 'max_order_qty',
        'thumbnail_image', 'gallery_images', 'video_url',
        'color', 'size', 'weight', 'warranty',
        'meta_title', 'meta_description', 'meta_keywords',
        'tax_rate', 'shipping_cost',
        'is_trending', 'is_new_arrival', 'status',
    ];

    // JSON aur Boolean ko sahi format me convert karne ke liye
    protected $casts = [
        'gallery_images' => 'array', // JSON ko array banayega
        'is_trending' => 'boolean',
        'is_new_arrival' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class);
    }

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function relatedProducts()
    {
        // Table ka naam 'related_products' hai
        return $this->belongsToMany(
            Product::class,
            'related_products',
            'product_id',
            'related_product_id'
        );
    }

    public function reviews() {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    protected static function booted()
    {
        static::updated(function ($product) {
            
            // MAGIC LOGIC: Ye check karega ki kya stock update hua hai? 
            // Aur kya purana stock 5 se zyada tha aur naya stock 5 ya usse kam ho gaya hai?
            if ($product->wasChanged('stock') && $product->getOriginal('stock') > 5 && $product->stock <= 5) {
                
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                
                if ($admins->count() > 0) {
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'stock',
                        'message' => "Low Stock Alert: Only {$product->stock} left for {$product->name}!",
                        'url' => route('admin.stock.edit', $product->id)
                    ]));
                }
            }
        });
    }
}
