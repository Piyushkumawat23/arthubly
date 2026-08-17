<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Notifications ke liye imports
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;
use App\Models\User;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'category_id', 'content', 'status', 'image', 'likes'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // In Post model
    public function likes()
    {
        return $this->hasMany(BlogLike::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    // ==========================================
    // BLOG CONTENT ALERTS (Model Events)
    // ==========================================
    protected static function booted()
    {
        // EVENT 1: Jab koi Naya Blog Post Create ho
        static::created(function ($blog) {
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'system', // Generic UI icon
                    'message' => "New Blog Post created: '{$blog->title}'.",
                    'url' => route('admin.blogs.index') // Blog list page
                ]));
            }
        });

        // EVENT 2: Jab Blog ka Status Update ho (Draft <-> Published)
        static::updated(function ($blog) {
            
            if ($blog->wasChanged('status')) {
                $admins = User::whereIn('role', ['admin', 'subadmin'])->get();
                
                if ($admins->count() > 0) {
                    $statusName = ucfirst($blog->status); // e.g., 'Published' ya 'Draft'
                    
                    Notification::send($admins, new AdminAlertNotification([
                        'type' => 'review', // Star/Document icon ke liye
                        'message' => "Blog Post '{$blog->title}' status changed to {$statusName}.",
                        'url' => route('admin.blogs.edit', $blog->id)
                    ]));
                }
            }
        });

        // EVENT 3: Jab Blog Delete ho (Critical for SEO)
        static::deleted(function ($blog) {
            $admins = User::where('role', 'admin')->get(); // Sirf main admin ko bhejein
            
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'stock', // Red Warning Icon dikhane ke liye
                    'message' => "ALERT: Blog Post '{$blog->title}' was deleted!",
                    'url' => route('admin.blogs.index')
                ]));
            }
        });
    }
}