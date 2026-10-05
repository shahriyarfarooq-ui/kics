<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErpProject extends Model
{
    use HasFactory;

    protected $table = 'erp_projects';

    protected $fillable = [
        'kics_id',
        'campus_id',
        'department_kics_id',
        'erp_department_id',
        'name',
        'campus',
        'project_manager',
        'project_coordinator',
        'project_sponser',
        'customer',
        'project_states',
        'project_type',
        'system_generated',
        'active',
        'image',
        'description',
        'is_visible',
        'admin_notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_visible' => 'boolean',
        'system_generated' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(ErpDepartment::class, 'erp_department_id', 'id');
    }

    // Scope for visible projects
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    // Scope for active projects
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Status colors for badges
    public function getStatusColorAttribute()
    {
        $colors = [
            'Active' => 'success',
            'In Progress' => 'warning',
            'Completed' => 'primary',
            'On Hold' => 'danger',
            'Pending' => 'secondary',
        ];
        return $colors[$this->project_states] ?? 'secondary';
    }
}