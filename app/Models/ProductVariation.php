<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'color', 'size', 'sku', 'price', 'stock', 'image' // NAYA: Variation image ke liye
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::updated(function ($variation) {
            
            if ($variation->wasChanged('stock') && $variation->getOriginal('stock') > 5 && $variation->stock <= 5) {
                
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                $productName = $variation->product->name ?? 'Product';
                $varDetails = $variation->color . '-' . $variation->size;

                if ($admins->count() > 0) {
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'stock',
                        'message' => "Low Variation Stock: {$productName} ({$varDetails}) has only {$variation->stock} left!",
                        'url' => route('admin.stock.edit', $variation->product_id)
                    ]));
                }
            }
        });
    }
}