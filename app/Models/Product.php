<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;

class Product extends BaseModel
{
    public $timestamps = false;
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    public function getAll()
    {
        return $this->get();
    }

    public function productType()
    {
        return $this->hasOne(ProductType::class,'type_id','product_type');
    }

}
