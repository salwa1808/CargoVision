<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'thumbnail',
        'category',
        'author_id',
        'status',
        'featured',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
