<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    protected $table = 'project_activities';
    public $timestamps = false;

    protected $fillable = [
        'task',
        'description',
        'start_date',
        'expected_date',
        'project_id',
    ];
}
