<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Mockup extends BaseModel
{
    public $timestamps = false;
    protected $table = 'mockup';
    protected $primaryKey = 'mockup_id';
    protected $fillable = [
        'mockup_name',
        'mockup_side',
        'mockup_price',
        'mockup_path',
        'mockup_ratting',
        'type_id',
    ];

    const ALIAS = [
        'mockup_id'        => 'id',
        'mockup_name'      => 'name',
        'mockup_side'      => 'side',
        'mockup_price'     => 'price',
        'mockup_path'      => 'path',
        'mockup_ratting'   => 'ratting',
        'type_id'          => 'typeId'
    ];

    public function mockupType()
    {
        return $this->belongsTo(MockupType::class,'type_id','type_id');
    }
}
