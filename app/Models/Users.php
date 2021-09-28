<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Underscore\Types\Arrays;
use Illuminate\Support\Arr;
use App\Helpers\S3Helper;
use Illuminate\Notifications\Notifiable;

class Users extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'userss';
    public $timestamps = false;
    protected $hidden = [
            'name', 'google_id', 'email', 'password', 'avatar',
        ];
}
