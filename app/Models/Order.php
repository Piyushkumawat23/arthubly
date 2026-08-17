<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    // Jin columns me hum data insert karenge, unhe yahan define karna zaroori hai
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status'
    ];

    // Ek order ke andar bahut saare items ho sakte hain
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ==========================================
    // AUTO-NOTIFICATION LOGIC (Model Events)
    // ==========================================
    protected static function booted()
    {
        // EVENT 1: Jab NAYA Order Create Ho
        static::created(function ($order) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'order', // Ye aapke UI me green cart icon (bi-cart-check-fill) dikhayega
                    'message' => "New Order Received! ID: #ORD-{$order->id} for ₹{$order->total_amount}.",
                    'url' => route('admin.orders.show', $order->id) // Order ki detail page ka link
                ]));
            }
        });

        // EVENT 2: Jab Order Status Update Ho (Khas taur par Cancel hone par)
        static::updated(function ($order) {
            
            // Agar order_status change hua hai aur wo 'cancelled' ho gaya hai
            if ($order->wasChanged('order_status') && $order->order_status == 'cancelled') {
                
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                
                if ($admins->count() > 0) {
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'stock', // Red triangle icon (bi-exclamation-triangle-fill) ke liye 'stock' use kar rahe hain
                        'message' => "ALERT: Order #ORD-{$order->id} (₹{$order->total_amount}) was CANCELLED.",
                        'url' => route('admin.orders.show', $order->id)
                    ]));
                }
            }
        });
    }
}