<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $table = 'director_message';
    protected $fillable = ['section_message'];
}
