<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'job_title',
        'group_id',
        'location',
        'company',
        'job_close_date',
        'description'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }
    
    public function tags()
{
    return $this->belongsToMany(Tag::class, 'career_tag', 'career_id', 'tag_id');
}

}
