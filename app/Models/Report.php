<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    
    // เพิ่ม post_id, user_id, reason, และ additional_info ใน fillable array
    protected $fillable = ['post_id', 'user_id', 'reason', 'additional_info'];
}