<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectContent extends Model
{
    protected $table = 'project_content';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'tpr',
        'team',
        'publications',
        'aad',
        'usecase',
        'downloads',
    ];
}
