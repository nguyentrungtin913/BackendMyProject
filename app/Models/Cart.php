<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Cart extends BaseModel
{
    use HasFactory;
    protected $table = 'cart';
    public $timestamps = false;
    protected $primaryKey = 'cart_id';
    protected $fillable = [
        'cart_id',
        'cart_image',
        'mockup_id',
        'user_id'
    ];

    const ALIAS = [
        'cart_id'       => 'id',
        'cart_image'    => 'image',
        'mockup_id'     => 'mockupId',
        'user_id'       => 'userId',
    ];

    public function mockup()
    {
        return $this->belongsTo(Mockup::class,'mockup_id','mockup_id');
    }
}
