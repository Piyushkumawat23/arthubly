<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;
use App\Models\Product; // Product model ko import kiya

class Category extends Model
{
    use HasFactory;

  protected $fillable = ['name', 'slug', 'description', 'image', 'icon', 'status'];

    // 👇 YE NAYA RELATION ADD KIYA GAYA HAI 👇
    // Ek Category ke paas bahut saare products hote hain
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    // 👆 ================================== 👆

    // ==========================================
    // CATEGORY ALERTS (Model Events)
    // ==========================================
    protected static function booted()
    {
        // EVENT 1: Nayi Category banna
        static::created(function ($category) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'setting', 
                    'message' => "New Product Category added: '{$category->name}'.",
                    'url' => route('admin.categories.index') 
                ]));
            }
        });

        // EVENT 2: Status Update hona (Active <-> Inactive)
        static::updated(function ($category) {
            if ($category->wasChanged('status')) {
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                if ($admins->count() > 0) {
                    $statusName = $category->status == 1 ? 'Activated' : 'Deactivated';
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'setting',
                        'message' => "Category '{$category->name}' was {$statusName}.",
                        'url' => route('admin.categories.index')
                    ]));
                }
            }
        });

        // EVENT 3: Category Delete hona (Critical)
        static::deleted(function ($category) {
            $admins = User::where('role', 'admin')->get(); // Sirf Admin ko bhejein
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', // Red Lock icon
                    'message' => "CRITICAL: Category '{$category->name}' was deleted!",
                    'url' => route('admin.categories.index')
                ]));
            }
        });
    }
}