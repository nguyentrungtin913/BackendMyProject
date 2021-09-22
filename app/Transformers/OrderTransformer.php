<?php


namespace App\Transformers;


use App\Models\Order;
use League\Fractal\Manager;

class OrderTransformer extends BaseTransformer
{   
    protected OrderDetailTransformer $orderDetailTransformer;
    public function __construct(Manager $fractal, Order $order, OrderDetailTransformer $orderDetailTransformer)
    {
        parent::__construct($fractal, $order);
        $this->orderDetailTransformer = $orderDetailTransformer;
    }

    public function transform($data)
    {
        $transform = parent::transform($data);
        if($orderDetails = $data->getRelations()['orderDetails'] ?? null) {
           
            $orderDetails = $this->orderDetailTransformer->transformCollection($orderDetails);
            
            $transform += compact('orderDetails');
        }
        return $transform;
    }
}