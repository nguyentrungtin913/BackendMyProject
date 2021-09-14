<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Underscore\Types\Arrays;

class BaseModel extends Model
{
    use HasFactory;
    const FILTER_PARAMS = [];
    const CREATED_AT = 'CREATED_AT'; //lưu ý
    public function getFieldByAlias($alias)
    {
        $aliasFlip = array_flip(static::ALIAS);
        return $aliasFlip[$alias] ?? null;
    }

    public function create(array $attributes = [])
    {
        return static::query()->create($attributes);
    }
   
    function orderBy($sortBy = 'createdAt', $sortType = 'desc')
    {
        $sortType = strtolower($sortType);
        $sortBy = $this->getFieldByAlias($sortBy) ?? static::CREATED_AT;
        $sortType = in_array($sortType, ['desc', 'asc']) ? $sortType : 'desc';
        return compact('sortBy', 'sortType');
    }
    public function includes($query, $relations = [])
    {
        if ($relations) {
            foreach ($relations as $relation) {
                $relationPcs = explode('.', $relation);
                if (method_exists($this, $relationPcs[0])) {
                    $query = $query->with($relation);
                }
            }
        }
        return $query;
    }
    
    function filter(Builder $query, array $inputs)
    {
        $aliasFlip = array_flip(static::ALIAS);

        foreach (static::FILTER_PARAMS as $param) {
            $alias = $param[0] ?? null;
            $operation = $param[1] ?? '=';
            $type = $param[2] ?? 'string';
            if ($type == 'dateTime') {
                $aliasTmp = preg_replace('/(From|To)$/', '', $alias);
            } else if ($operation == 'IN') {
                $aliasTmp = Strings::singular($alias);
            } else {
                $aliasTmp = $alias;
            }

            $realField = $aliasFlip[$aliasTmp] ?? null;
            if ($realField !== null) {
                if (($value = Arrays::get($inputs, $alias, '')) !== '') {
                    if ($type == 'number') {
                        $value = is_numeric($value) ? $value * 1 : intval($value);
                    } else if ($type == 'dateTime') {
                        if (Str::endsWith($alias, 'From')) {
                            $value = strtotime($value) - 1;
                        }else{
                            $value = strtotime($value . ' 23:59:59');
                        }
                    }else { //String
                        if ($operation == 'LIKE') {
                            $value = "%{$value}%";
                        }
                    }

                    if ($operation == 'IN') {
                        $query->whereIn($realField, $value);
                    } else if ($operation == 'FULLTEXT') {
                        $query->whereRaw("MATCH ($realField) AGAINST ('$value')");
                    }else {
                        $query->where($realField, $operation, $value);
                    }
                }
            }
        }

        return $query;
    }
}
