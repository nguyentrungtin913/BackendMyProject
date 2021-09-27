<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
class Order extends BaseModel
{
    use HasFactory;
    protected $table='order_customer';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    protected $fillable = [
        'order_name',
        'order_address',
        'order_total',
        'order_status',
        'order_date',
        'user_id',
        'order_delete'
    ];

    const ALIAS = [
        'order_id'        => 'id',
        'order_name'      => 'name',
        'order_address'   => 'address',
        'order_total'     => 'total',
        'order_status'    => 'status',
        'order_date'      => 'date',
        'user_id'         => 'userId'
    ];

    static function query()
    {
        $query = parent::query();
        $query->notDeleted();
        return $query;
    }

    function scopeNotDeleted($query)
    {
        return $query->where('order_deleted', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}
