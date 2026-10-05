<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
        use HasFactory;
    protected $table = 'designation';
    protected $primaryKey = 'designation_id';
    public $timestamps = false;

    protected $fillable = [
        'designation_name',
        'designation_seqno',
        'designation_image',
    ];
     public function people()
    {
        return $this->hasMany(People::class, 'designation_id', 'designation_id');
    }
}
