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

    protected $casts = [
        'ingrediant' => 'array',
    ];

    // ฟังก์ชันความสัมพันธ์กับผู้ใช้
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
