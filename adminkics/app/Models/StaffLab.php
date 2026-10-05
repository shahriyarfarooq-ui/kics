<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLab extends Model
{
    protected $table = 'staff_lab';
    public $timestamps = false; // change if you have timestamps
    protected $fillable = ['staff_id','group_id','role'];
}
