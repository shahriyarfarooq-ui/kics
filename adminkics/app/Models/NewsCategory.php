<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    // Table name (optional if Laravel naming convention matches)
    protected $table = 'news_categories';

    // Primary key (optional if 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

      // Category has many news
    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }

    // Timestamps (optional, Laravel handles created_at/updated_at automatically)
    public $timestamps = true;


}


//chnage are from here ---------------------------------