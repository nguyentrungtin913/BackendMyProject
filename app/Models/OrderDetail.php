<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends BaseModel
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'image',
        'price',
        'amount'
    ];

    const ALIAS = [
        'detail_id'       => 'idOderDetail',
        'order_id'        => 'idOder',
        'image'           => 'imageDetail',
        'price'           => 'priceDetail',
        'amount'          => 'amountDetail'
    ];
}
