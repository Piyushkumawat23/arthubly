<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Notifications ke liye zaroori imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class BlogLike extends Model {
    
    protected $fillable = ['blog_id', 'user_id'];

    public function blog() {
        return $this->belongsTo(Blog::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // NEW LIKE ALERT (Model Event)
    // ==========================================
    protected static function booted()
    {
        // EVENT: Jab bhi koi Naya Like Create ho
        static::created(function ($like) {
            
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                
                $blogTitle = $like->blog->title ?? 'a blog post';
                $userName = $like->user->name ?? 'Someone';
                
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'review', // Like/Star icon ke liye hum 'review' type use kar rahe hain
                    'message' => "Engagement: {$userName} liked your post '{$blogTitle}'.",
                    'url' => route('admin.blogs.edit', $like->blog_id) 
                ]));
            }
        });
    }
}