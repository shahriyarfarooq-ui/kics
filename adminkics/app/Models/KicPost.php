<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KicPost extends Model
{
    use HasFactory;

    protected $table = 'kic_post';
    protected $primaryKey = 'post_id';
    public $timestamps = false;

    protected $fillable = [
        'post_name',
        'post_seqno',
        'des_id',
    ];

    // Relationship: Post belongs to Designation
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'des_id', 'designation_id');
    }
    
    public function people()
    {
        return $this->hasMany(People::class, 'post_id', 'post_id');
    }
}
