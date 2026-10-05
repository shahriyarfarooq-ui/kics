<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'order_index',
    ];

    /**
     * Get the menu links for the menu section.
     */
    public function menuLinks(): HasMany
    {
        return $this->hasMany(MenuLink::class);
    }

    /**
     * Scope a query to order by order_index.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}
