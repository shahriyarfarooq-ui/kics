<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KicRdProject extends Model
{
    protected $table = 'kic_rdproject';
    protected $primaryKey = 'rdproject_id';
    public $timestamps = false;

    protected $fillable = [
        'rdproject_name',
        'rdproject_seqno',
        'rdproject_description',
        'rdproject_small_picture',
        'group_id',
        'subgroup_id',
        'sub_site_id',
    ];
}
