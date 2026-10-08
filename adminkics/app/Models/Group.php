<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'kic_group';
    protected $primaryKey = 'group_id';

    protected $fillable = [
        'is_center',
        'group_name',
        'group_seqno',
        'group_description',
        'group_briefdescription',
        'group_projectlist_check',
        'group_services_check',
        'group_rdproject_check',
        'group_subgroup_check',
        'img_path',
        'short_desc',
        'sub_site_id',
        'home_intro',
        'group_banner',
        'contact_us',
        'code',
    ];

    public $timestamps = false;

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->group_name;
    }

    /* ----------------------------------------------------------
       🔗 Relationships
    ---------------------------------------------------------- */

    // Projects (kic_group_projectlist)
    public function projects()
    {
        // If your project model class name differs, update this class name accordingly.
        return $this->hasMany(\App\Models\KicGroupProjectlist::class, 'group_id', 'group_id');
    }

   
    // News (star_news)
   /* public function news()
    {
        return $this->hasMany(\App\Models\StarNews::class, 'group_id', 'group_id');
    }
        */

    // Publications (Publication.php -> Publication model)
    public function publications()
    {
        return $this->hasMany(\App\Models\Publication::class, 'group_id', 'group_id');
    }

    // Many-to-many via staff_lab pivot
public function staff()
{
    // params: related, pivot_table, foreignPivotKey(on pivot for this model), relatedPivotKey(on pivot for related model), parentKey, relatedKey
    return $this->belongsToMany(
        \App\Models\People::class,
        'staff_lab',    // pivot table
        'group_id',     // this model's key in pivot
        'staff_id',     // related model's key in pivot
        'group_id',     // local primary key on groups table
        'people_id'     // related primary key on people table
    )->withPivot('role'); // include pivot column role
}

public function people()
    {
        return $this->hasMany(People::class, 'group_id', 'group_id');
    }
}
