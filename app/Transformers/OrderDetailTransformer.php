<?php


namespace App\Transformers;


use App\Models\OrderDetail;
use League\Fractal\Manager;

class OrderDetailTransformer extends BaseTransformer
{   
    public function __construct(Manager $fractal, OrderDetail $orderDetail)
    {
        parent::__construct($fractal, $orderDetail);
    }

    public function transform($data)
    {
        $transform = parent::transform($data);
        
        return $transform;
    }
}