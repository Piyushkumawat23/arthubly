<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Brand extends Model
{
    use HasFactory;
    
    protected $table = 'brands';
    protected $fillable = ['name', 'slug', 'description', 'image', 'status'];

    // ==========================================
    // BRAND ALERTS (Model Events)
    // ==========================================
    protected static function booted()
    {
        static::created(function ($brand) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'setting', 
                    'message' => "New Brand partnered: '{$brand->name}'.",
                    'url' => route('admin.brands.index') 
                ]));
            }
        });

        static::updated(function ($brand) {
            if ($brand->wasChanged('status')) {
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                if ($admins->count() > 0) {
                    $statusName = $brand->status == 1 ? 'Activated' : 'Deactivated';
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'setting',
                        'message' => "Brand '{$brand->name}' was {$statusName}.",
                        'url' => route('admin.brands.index')
                    ]));
                }
            }
        });

        static::deleted(function ($brand) {
            $admins = User::where('role', 'admin')->get(); 
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', 
                    'message' => "CRITICAL: Brand '{$brand->name}' was deleted!",
                    'url' => route('admin.brands.index')
                ]));
            }
        });
    }
}