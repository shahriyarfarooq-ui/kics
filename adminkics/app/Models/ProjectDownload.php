<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDownload extends Model
{
    protected $table = 'project_downloads';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'file',
        'project_id',
        'url',
    ];
}
