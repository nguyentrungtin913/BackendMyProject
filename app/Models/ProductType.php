<?php

namespace App\Models;

class ProductType extends BaseModel
{
  
    public $timestamps = false;
    protected $table = 'product_type';
    protected $primaryKey = 'type_id';

    public function getAll()
    {
        return $this->get();
    }
    
    public function find($typeId)
    {
        return $this->where('type_id', $typeId)->first();
    }
}
