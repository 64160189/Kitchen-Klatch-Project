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
}
