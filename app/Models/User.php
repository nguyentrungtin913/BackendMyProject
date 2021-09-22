<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends BaseModel //Authenticatable
{
    use HasFactory, Notifiable;
    const NUMBER_FIELDS = ['id', 'id'];
    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'date',
        'sex',
        'address',
        'phone',
        'role',
        'email',
        'password'

    ];

    const ALIAS = [
        'name'      => 'name',
        'date'      => 'date',
        'sex'       => 'sex',
        'address'   => 'address',
        'phone'     => 'phone',
        'role'      => 'role',
        'email'     => 'email'
    ];
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','id');
    }

}
