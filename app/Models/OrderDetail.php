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
        'order_id',
        'detail_image',
        'detail_price',
        'detail_amount',
        'detail_delete'
    ];

    static function query()
    {
        $query = parent::query();
        $query->notDeleted();
        return $query;
    }

    function scopeNotDeleted($query)
    {
        return $query->where('detail_deleted', 0);
    }

    const ALIAS = [
        'detail_id'              => 'idOderDetail',
        'order_id'               => 'idOder',
        'detail_image'           => 'imageDetail',
        'detail_price'           => 'priceDetail',
        'detail_amount'          => 'amountDetail'
    ];
}
