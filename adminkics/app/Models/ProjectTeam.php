<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    protected $table = 'project_team';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'member_id',
        'role',
    ];

    public function member()
    {
        return $this->belongsTo(People::class, 'member_id', 'people_id');
    }
}
