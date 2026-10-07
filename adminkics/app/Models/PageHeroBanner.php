<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeroBanner extends Model
{
    use HasFactory;

    protected $table = 'page_hero_banners';

    protected $fillable = [
        'page_key',
        'image_path',
    ];
}
