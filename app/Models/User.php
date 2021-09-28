<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends BaseModel //Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'user_date',
        'user_sex',
        'user_address',
        'user_phone',
        'user_role',
        'user_email',
        'user_password',
        'user_token',
        'user_token_expired',
        'user_delete',
        'user_activated'

    ];
    
    static function query()
    {
        $query = parent::query();
        $query->notDeleted();
        return $query;
    }

    function scopeNotDeleted($query)
    {
        return $query->where('user_deleted', 0);
    }

    const ALIAS = [
        'user_name'             => 'name',
        'user_date'             => 'date',
        'user_sex'              => 'sex',
        'user_address'          => 'address',
        'user_phone'            => 'phone',
        'user_role'             => 'role',
        'user_email'            => 'email',
        'user_token'            => 'token',
        'user_token_expired'    => 'tokenExpired',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','user_id');
    }

}
