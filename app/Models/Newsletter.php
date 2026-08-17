<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;
use Illuminate\Support\Facades\Route; // 🟢 Isko import karein

class Newsletter extends Model
{
    use HasFactory;

    protected $table = 'newsletters';
    protected $fillable = ['email', 'status'];

    protected static function booted()
    {
        // EVENT 1: Jab Naya Subscriber Add ho
        static::created(function ($subscriber) {
            try {
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'review', 
                        'message' => "New Subscriber: {$subscriber->email} joined the newsletter.",
                        // 🟢 FIX: Check if route exists, warna '#' assign karega (Isse crash nahi hoga)
                        'url' => Route::has('admin.newsletters.index') ? route('admin.newsletters.index') : '#'
                    ]));
                }
            } catch (\Exception $e) {
                // Agar notification bhejne me koi error aaye toh user ka subscription fail nahi hona chahiye
            }
        });

        // EVENT 2: Jab Koi Unsubscribe kare
        static::updated(function ($subscriber) {
            if ($subscriber->wasChanged('status') && $subscriber->status == 0) {
                try {
                    $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                    if ($admins->count() > 0) {
                        Notification::send($admins, new AdminAlertNotification([
                            'type' => 'setting', 
                            'message' => "User Unsubscribed: {$subscriber->email} left the newsletter.",
                            'url' => Route::has('admin.newsletters.index') ? route('admin.newsletters.index') : '#'
                        ]));
                    }
                } catch (\Exception $e) {
                    // Ignore error
                }
            }
        });
    }
}