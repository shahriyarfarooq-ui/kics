<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'thumbnail',
        'description',
    ];

    // News belongs to a category
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    // News has many tags (many-to-many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tag', 'news_id', 'tag_id');
    }
}
