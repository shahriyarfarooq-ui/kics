<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;


class KicGroupProjectlist extends Model
{
    use HasFactory;

    protected $table = 'kic_group_projectlist';

    protected $primaryKey = 'projectlist_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'projectlist_Name',
        'projectlist_seqno',
        'projectlist_description',
        'projectlist_small_picture',
        'group_id',
        'subgroup_id',
        'sub_site_id',
        'code',
        'project_category',
        'is_completed',
        'fundedby',
        'inactive'
    ];

    protected $casts = [
        'projectlist_seqno' => 'integer',
        'group_id' => 'integer',
        'subgroup_id' => 'integer',
        'sub_site_id' => 'integer',
        'is_completed' => 'boolean',
        'inactive' => 'boolean',
    ];
    public function group()
{
    return $this->belongsTo(Group::class, 'group_id', 'group_id');
}

    public function activities()
    {
        return $this->hasMany(ProjectActivity::class, 'project_id', 'projectlist_id');
    }

    public function content()
    {
        return $this->hasMany(ProjectContent::class, 'project_id', 'projectlist_id');
    }

    public function downloads()
    {
        return $this->hasMany(ProjectDownload::class, 'project_id', 'projectlist_id');
    }

    public function phases()
    {
        return $this->hasMany(ProjectPhase::class, 'projectlist_id', 'projectlist_id');
    }

    public function plans()
    {
        return $this->hasMany(ProjectPlan::class, 'project_id', 'projectlist_id');
    }

    public function publicationLinks()
    {
        return $this->hasMany(ProjectPublication::class, 'project_id', 'projectlist_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(ProjectTeam::class, 'project_id', 'projectlist_id');
    }

}
