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
    public function mockup()
    {
        return $this->belongsTo(Mockup::class,'mockup_id','mockup_id');
    }
}
