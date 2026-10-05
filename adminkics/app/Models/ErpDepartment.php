<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ErpDepartment extends Model
{
    use HasFactory;

    protected $table = 'erp_department';

    protected $fillable = [
        'kics_id',
        'campus_id',
        'parent_department_id',
        'name',
        'complete_name',
        'parent_department',
        'manager',
        'campus',
        'active',
        'dept_code',
        'dept_type',
        'department_type',
        'web_department_name',
        'web_detail_description',
        'vision',
        'mission',
        'appraisals_to_process',
        'logo',
        'cover_image',
        'is_visible',
        'admin_notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(ErpProject::class, 'erp_department_id', 'id');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getDisplayNameAttribute()
    {
        return $this->web_department_name ?? $this->name ?? $this->complete_name ?? 'Unnamed';
    }
}