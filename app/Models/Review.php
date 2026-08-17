<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Ye 3 lines notifications ke liye add ki hain
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $guarded = []; 

    // User Relation
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Polymorphic Relation
    public function reviewable() {
        return $this->morphTo();
    }

    // ==========================================
    // AUTO-NOTIFICATION LOGIC (Model Event)
    // ==========================================
    protected static function booted()
    {
        // Jab bhi naya review CREATE ho, tab ye function chalega
        static::created(function ($review) {
            
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                
                // Polymorphic relation se product ka naam nikalein
                $productName = $review->reviewable->name ?? 'a product';
                
                // Spam check
                if ($review->is_spam) {
                    $msg = "SPAM ALERT: A suspicious review was flagged on '{$productName}'.";
                    $type = 'stock'; // Red alert icon dikhane ke liye
                } else {
                    $msg = "New {$review->rating} Star Review received for '{$productName}'.";
                    $type = 'review';
                }

                Notification::send($admins, new AdminAlertNotification([
                    'type' => $type,
                    'message' => $msg,
                    'url' => route('admin.reviews.index') // Ya edit route
                ]));
            }
        });
    }
}