<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'email', 'password', 'role', 'avatar'];
    protected $hidden = ['password', 'remember_token'];

    public function people()
    {
        return $this->hasMany(People::class, 'group_id', 'group_id');
    }
}
