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
}
