<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'bio',
        'image',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(PostModel::class)->latest();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follower_user', 'follower_id', 'user_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follower_user', 'user_id', 'follower_id')->withTimestamps();

    }

    public function follows(User $user)
    {
        return $this->followings()->where('user_id', $user->id)->exists();
    }

    public function getImageURL()
    {
        // ตรวจสอบว่ามีภาพอยู่
        if (!empty($this->image)) {
            return url('storage/' . $this->image); // สร้าง URL สำหรับภาพที่เก็บไว้
        }
        // ถ้าไม่มีภาพ ให้ใช้ URL สำหรับ emoji
        return "https://api.dicebear.com/6.x/fun-emoji/svg?seed=" . urlencode($this->name);
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }
    
    public function sharedPosts()
    {
        return $this->hasManyThrough(PostModel::class, Feed::class, 'user_id', 'id', 'id', 'post_id');
    }
    use Notifiable;

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}