<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    // Many-to-Many relation with Career
    public function careers()
    {
        return $this->belongsToMany(Career::class, 'career_tag', 'tag_id', 'career_id');
    }
    
    
    // Tag belongs to many news
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tag', 'tag_id', 'news_id');
    }
}