<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KicSubgroup extends Model
{
    protected $table = 'kic_subgroup';
    protected $primaryKey = 'subgroup_id';
    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'subgroup_name',
        'subgroup_seqno',
        'subgroup_description',
        'subgroup_briefdescription',
        'subgroup_projectlist_check',
        'subgroup_services_check',
        'subgroup_rdproject_check',
    ];
}
