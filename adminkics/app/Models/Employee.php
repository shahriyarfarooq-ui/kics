<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// app/Models/Employee.php
class Employee extends Model
{
    protected $guarded = [];

    protected $casts = [
        'joining_date' => 'date',
        'active' => 'boolean',
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}