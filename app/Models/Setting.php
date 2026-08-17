<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_name',
        'contact_email',
        'contact_phone',
        'address',
        'logo',
        'favicon',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    // ==========================================
    // SYSTEM SETTINGS ALERT (Model Event)
    // ==========================================
    protected static function booted()
    {
        // Jab bhi Website Settings UPDATE ho, tab ye trigger hoga
        static::updated(function ($setting) {
            
            // Ye alert khas taur par main Admins ko hi jaana chahiye
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() > 0) {
                
                // Kaunsa data change hua hai, wo batane ke liye (optional but professional)
                $changes = array_keys($setting->getChanges());
                // 'updated_at' ko ignore karein
                $changes = array_diff($changes, ['updated_at']);
                
                $changedFields = implode(', ', str_replace('_', ' ', $changes));

                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'setting', 
                    'message' => "System Settings Updated: {$changedFields} were modified.",
                    'url' => route('admin.settings.index') // Settings page ka route
                ]));
            }
        });
    }
}