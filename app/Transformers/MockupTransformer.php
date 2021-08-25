<?php


namespace App\Transformers;


use App\Models\Mockup;
use League\Fractal\Manager;

class MockupTransformer extends BaseTransformer
{
    protected MockupTypeTransformer $mockupTypeTransformer;
    /**
     * CustomerCollectionTransformer constructor.
     * @param Manager $fractal
     * @param CustomerMockup $customerMockup
     */
    public function __construct(Manager $fractal, Mockup $mockup, MockupTypeTransformer $mockupTypeTransformer)
    {
        parent::__construct($fractal, $mockup);
        $this->mockupTypeTransformer = $mockupTypeTransformer;
    }
    public function transform($data)
    {
        $transform = parent::transform($data);
        if($mockupType = $data->getRelations()['mockupType'] ?? null) {
           
            $mockupType = $this->mockupTypeTransformer->transformItem($mockupType);
            
            $transform += compact('mockupType');
        }
        return $transform;
    }
}