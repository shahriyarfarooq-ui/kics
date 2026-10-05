<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $table = 'kic_publications';
    protected $primaryKey = 'publication_id';
    public $timestamps = false;

    protected $fillable = [
        'publication_title',
        'author',
        'journal',
        'volume',
        'publication_seqno',
        'publication_abstract',
        'publication_iscompleted',
        'publication_year',
        'group_id',
        'people_id',
        'category'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }

    public function person()
    {
        return $this->belongsTo(People::class, 'people_id', 'people_id');
    }
}
