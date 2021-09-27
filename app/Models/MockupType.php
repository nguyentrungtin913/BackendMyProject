<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class MockupType extends BaseModel
{
    public $timestamps = false;
    protected $table = 'mockup_type';
    protected $primaryKey = 'type_id';
    const ALIAS = [
        'type_id'        => 'id',
        'type_name'      => 'name'
    ];

    protected $fillable = [
        'type_name',
        'type_delete'
    ];
    static function query()
    {
        $query = parent::query();
        $query->notDeleted();
        return $query;
    }

    function scopeNotDeleted($query)
    {
        return $query->where('type_deleted', 0);
    }

    public function mockup()
    {
        return $this->hasMany(Mockup::class, 'type_id', 'type_id');
    }
}
