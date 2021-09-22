<?php


namespace App\Transformers;


use App\Models\Cart;
use League\Fractal\Manager;

class CartTransformer extends BaseTransformer
{
    protected MockupTransformer $mockupTransformer;

    public function __construct(Manager $fractal, Cart $cart, MockupTransformer $mockupTransformer)
    {
        parent::__construct($fractal, $cart);
        $this->mockupTransformer = $mockupTransformer;
    }
    public function transform($data)
    {
        $transform = parent::transform($data);
        if($mockup = $data->getRelations()['mockup'] ?? null) {
           
            $mockup = $this->mockupTransformer->transformItem($mockup);
            
            $transform += compact('mockup');
        }  
        return $transform;
    }
}