<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',   // ⬅️ YE hona chahiye, warna create() pe silently drop ho jaata hai
        'variation_info',
        'quantity',
        'price',
    ];

    // Ye item kis order ka hissa hai
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Ye item kis product se juda hai
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
