<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPublication extends Model
{
    protected $table = 'project_publications';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'publication_id',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }
}
