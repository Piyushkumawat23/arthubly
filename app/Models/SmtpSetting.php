<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class SmtpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailer', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'
    ];

    // ==========================================
    // SECURITY ALERT NOTIFICATION (Model Event)
    // ==========================================
    protected static function booted()
    {
        // Jab bhi SMTP settings UPDATE ho, tab ye function chalega
        static::updated(function ($setting) {
            
            // Ye alert khas taur par Super Admins ke liye hona chahiye
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', // 'system' type de rahe hain taaki iska icon baakiyon se alag dikhe
                    'message' => 'SECURITY ALERT: System SMTP (Email) settings were modified.',
                    'url' => route('admin.smtp.index') // Ye wahi route name hai jo aapne web.php me email view ke liye banaya tha
                ]));
            }
        });
    }
}