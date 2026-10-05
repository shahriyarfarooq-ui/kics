<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KicGroup extends Model
{
    protected $table = 'kic_group';
    protected $primaryKey = 'group_id';
    public $timestamps = false;

    protected $fillable = ['group_name', 'group_description'];
}
