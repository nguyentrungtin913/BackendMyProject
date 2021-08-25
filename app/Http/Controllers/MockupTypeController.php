<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MockupType;
use App\Helpers\APIRender;
use App\Transformers\MockupTypeTransformer;

use Illuminate\Support\Facades\Redirect;

class MockupTypeController extends Controller
{
    public function __construct(MockupType $mockupType, MockupTypeTransformer $mockupTypeTransformer)
    {
       $this->mockupType= $mockupType;
       $this->mockupTypeTransformer= $mockupTypeTransformer;
    }

    public function index()
    {
        $mockupTypes= $this->mockupType->get();
        $mockupTypes= $this->mockupTypeTransformer->transformCollection($mockupTypes);
        return $mockupTypes;
        //return view('MockupType.MockupType')->with(compact('mockupTypes'));
    }
    public function find($typeId)
    {
       $mockupType = $this->mockupType->where('type_id',$typeId)->first();
       $mockups = $mockupType->mockup()->get();
       return view('Mockup.Mockup')->with(compact('mockups'));
    }
   
    
}
