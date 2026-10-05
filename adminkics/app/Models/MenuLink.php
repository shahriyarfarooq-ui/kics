<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'menu_section_id',
        'label',
        'url',
        'description',
        'order_index',
    ];

    /**
     * Get the menu section that owns the menu link.
     */
    public function menuSection(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class);
    }

    /**
     * Scope a query to order by order_index.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    /**
     * Scope a query to filter by menu section.
     */
    public function scopeForSection($query, $sectionId)
    {
        return $query->where('menu_section_id', $sectionId);
    }
}
