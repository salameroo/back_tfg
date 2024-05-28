<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Modelo Post.php
class Post extends Model
{

    use HasFactory;

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}

// Modelo Comment.php
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// Modelo Like.php
class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// Modelo Photo.php
class Photo extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

class Follow extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'followed_user_id'];
}
