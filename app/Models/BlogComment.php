<?php

namespace App\Models;

use App\Notifications\AdminAlertNotification;
// Notifications ke liye zaroori imports
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class BlogComment extends Model
{
    protected $fillable = ['blog_id', 'user_id', 'comment'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // NEW COMMENT ALERT (Model Event)
    // ==========================================
    protected static function booted()
    {
        // EVENT: Jab bhi koi Naya Comment Create ho
        static::created(function ($comment) {

            // Sabhi Admins aur Sub-admins ko alert bhejein
            $admins = User::whereIn('role', ['admin', 'subadmin'])->get();

            if ($admins->count() > 0) {

                // Relationships se Blog ka title aur User ka naam nikal rahe hain
                // Taki notification message clear aur descriptive ho
                $blogTitle = $comment->blog->title ?? 'a blog post';
                $userName = $comment->user->name ?? 'A user';

                Notification::send($admins, new AdminAlertNotification([
                    'type' => 'review', // 'review' type use kar rahe hain (user feedback/comment icon ke liye)
                    'message' => "New Comment: {$userName} commented on '{$blogTitle}'.",

                    // Niche aap us route ka naam de sakte hain jahan comments manage hote hain.
                    // Filhal main isey blog edit page par redirect kar raha hu.
                    'url' => route('admin.blogs.edit', $comment->blog_id),
                ]));
            }
        });
    }
}
