<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'ingrediant',
        'htc',
        'youtube_link',
        'user_id',
        'time_to_cook', 
        'level_of_cook',
    ];

    // ฟังก์ชันความสัมพันธ์กับผู้ใช้
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //comment
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id')->orderBy('created_at', 'desc');
    }
    //share
    public function shareToFeed($userId)
    {
        // ตรวจสอบว่าโพสต์อยู่ในฟีดของผู้ใช้แล้วหรือไม่
        if (Feed::where('user_id', $userId)->where('post_id', $this->id)->exists()) {
            return false; // โพสต์อยู่ในฟีดของผู้ใช้แล้ว
        }
        Feed::create([
            'user_id' => $userId,
            'post_id' => $this->id,
        ]);
        return true;
    }
}
