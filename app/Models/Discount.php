<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'discount_type', 'discount_amount', 'apply_to_all', 'category_id', 'start_date', 'end_date', 'is_active',
    ];

    public function isValid()
    {
        if (!$this->is_active) return false;
        $today = now()->format('Y-m-d');
        if ($this->start_date && $today < $this->start_date) return false;
        if ($this->end_date && $today > $this->end_date) return false;
        return true;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // ==========================================
    // DISCOUNT CAMPAIGN ALERTS (Model Events)
    // ==========================================
    protected static function booted()
    {
        // EVENT 1: Jab koi Naya Discount Create ho
        static::created(function ($discount) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', 
                    'message' => "New Discount Campaign '{$discount->name}' has been created.",
                    'url' => route('admin.discounts.index') 
                ]));
            }
        });

        // EVENT 2: Jab Discount ka Status (Active/Inactive) Update ho
        static::updated(function ($discount) {
            
            // Sirf tab trigger karo jab 'is_active' column me koi change hua ho
            if ($discount->wasChanged('is_active')) {
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                
                if ($admins->count() > 0) {
                    
                    if ($discount->is_active == 0) {
                        // Agar discount band kiya gaya hai
                        $msg = "ALERT: Discount Campaign '{$discount->name}' was manually deactivated.";
                        $type = 'setting'; // Gear icon ke liye
                    } else {
                        // Agar wapas chalu kiya gaya hai
                        $msg = "Discount Campaign '{$discount->name}' was reactivated.";
                        $type = 'system';
                    }

                    Notification::send($admins, new AdminAlertNotification([
                        'type' => $type,
                        'message' => $msg,
                        'url' => route('admin.discounts.index')
                    ]));
                }
            }
        });
    }
}