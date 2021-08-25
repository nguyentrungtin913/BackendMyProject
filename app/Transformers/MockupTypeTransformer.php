<?php


namespace App\Transformers;


use App\Models\MockupType;
use League\Fractal\Manager;

class MockupTypeTransformer extends BaseTransformer
{
   
    public function __construct(Manager $fractal, MockupType $mockupType)
    {
        parent::__construct($fractal, $mockupType);
    }
    public function transform($data)
    {
        $transform = parent::transform($data);
             
        return $transform;
    }
}