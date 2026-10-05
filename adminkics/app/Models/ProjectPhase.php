<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    protected $table = 'project_phases';
    public $timestamps = false;

    protected $fillable = [
        'phase',
        'start_date',
        'end_date',
        'extended_date',
        'is_complete',
        'projectlist_id',
    ];
}
