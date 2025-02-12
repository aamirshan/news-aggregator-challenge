<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'description',
        'content',
        'url',
        'image_url',
        'source',
        'category',
        'published_at',
    ];
}
