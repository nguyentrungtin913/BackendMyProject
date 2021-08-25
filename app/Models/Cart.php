<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends BaseModel
{
    use HasFactory;
    protected $table = 'cart';
    public $timestamps = false;
    protected $primaryKey = 'cart_id';
    public function mockup()
    {
        return $this->hasOne(Mockup::class,'mockup_id','mockup_id');
    }
}
