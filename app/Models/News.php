<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'content',
        'category',
        'image_url',
        'image_position_x', // แกนแนวนอน
        'image_position_y', // แกนแนวตั้ง
        'is_published'
    ];
}
