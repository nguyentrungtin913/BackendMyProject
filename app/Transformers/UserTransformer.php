<?php


namespace App\Transformers;


use App\Models\User;
use League\Fractal\Manager;

class UserTransformer extends BaseTransformer
{
    protected OrderTransformer $orderTransformer;
    
    public function __construct(Manager $fractal, User $user, OrderTransformer $orderTransformer)
    {
        parent::__construct($fractal, $user);
        $this->orderTransformer = $orderTransformer;
    }
    public function transform($data)
    {
        $transform = parent::transform($data);
        if($orders = $data->getRelations()['orders'] ?? null) {
           
            $orders = $this->orderTransformer->transformCollection($orders);
            
            $transform += compact('orders');
        }
        return $transform;
    }
}