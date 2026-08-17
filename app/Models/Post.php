<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'category_id', 'content','status','image','likes'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // In Post model
public function likes()
{
    return $this->hasMany(PostLike::class);
}


//     public function likes()
// {
//     return $this->hasMany(PostLike::class, 'post_id');
// }

    
    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
    

}
