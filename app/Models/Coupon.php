<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'usage_limit',
        'used_count',
        'expiry_date',
        'is_active',
    ];

    // Check if coupon is valid
    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->expiry_date && now()->gt($this->expiry_date)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    // ==========================================
    // COUPON ALERTS (Model Events)
    // ==========================================
    protected static function booted()
    {
        // EVENT 1: Jab koi Naya Coupon Create ho
        static::created(function ($coupon) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', // Generic notification icon
                    'message' => "New Promo Code '{$coupon->code}' was created.",
                    'url' => route('admin.coupons.index') 
                ]));
            }
        });

        // EVENT 2: Jab Coupon Update ho (Limit Reached ya Deactivated)
        static::updated(function ($coupon) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                
                // Scenario A: Agar order place hote waqt coupon ki usage limit PURI khatam ho jaye
                if ($coupon->wasChanged('used_count') && $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'stock', // Red Alert Icon dikhane ke liye
                        'message' => "ALERT: Promo Code '{$coupon->code}' has reached its maximum usage limit!",
                        'url' => route('admin.coupons.edit', $coupon->id)
                    ]));
                }
                
                // Scenario B: Agar kisi admin ne manually coupon band (inactive) kar diya ho
                elseif ($coupon->wasChanged('is_active') && $coupon->is_active == 0) {
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'setting', // Gear Icon
                        'message' => "Promo Code '{$coupon->code}' was manually deactivated.",
                        'url' => route('admin.coupons.index')
                    ]));
                }
            }
        });
    }
}