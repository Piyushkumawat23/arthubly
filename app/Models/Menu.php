<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['menu_category_id', 'title', 'slug', 'url', 'parent_id', 'order', 'status'];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('status', 1)->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // ==========================================
    // MENU MODIFICATION ALERTS (Model Events)
    // ==========================================
    protected static function booted()
    {
        // EVENT 1: Jab koi Menu UPDATE ho
        static::updated(function ($menu) {
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'setting', // Gear icon dikhane ke liye
                    'message' => "Menu Updated: Navigation link '{$menu->title}' was modified.",
                    'url' => route('admin.menus.index') 
                ]));
            }
        });

        // EVENT 2: Jab koi Menu DELETE ho (Critical Action)
        static::deleted(function ($menu) {
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', // Red Shield/Lock icon dikhane ke liye
                    'message' => "CRITICAL: Navigation menu '{$menu->title}' was deleted!",
                    'url' => route('admin.menus.index') 
                ]));
            }
        });
    }
}