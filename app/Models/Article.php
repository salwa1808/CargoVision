<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'summary', 'content', 'category', 'author_id',
        'status', 'featured', 'views', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = ['featured' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (Article $article) {
            if (! $article->slug) {
                $article->slug = Str::slug($article->title).'-'.Str::lower(Str::random(5));
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
