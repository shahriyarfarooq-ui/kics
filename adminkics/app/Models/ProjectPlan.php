<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlan extends Model
{
    protected $table = 'project_plan';
    public $timestamps = false;

    protected $fillable = [
        'task',
        'description',
        'assigned_to',
        'start_date',
        'expected',
        'project_id',
    ];
}
