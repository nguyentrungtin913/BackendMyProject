<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MockupType;
use App\Helpers\APIRender;
use App\Transformers\MockupTypeTransformer;

use App\Helpers\DataHelper;
use Illuminate\Support\Facades\Redirect;

class MockupTypeController extends Controller
{
    public function __construct(MockupType $mockupTypeModel, MockupTypeTransformer $mockupTypeTransformer)
    {
       $this->mockupTypeModel= $mockupTypeModel;
       $this->mockupTypeTransformer= $mockupTypeTransformer;
    }

    public function index(Request $request)
    {
        $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];

        $orderBy = $this->mockupTypeModel->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);

        $query = $this->mockupTypeModel->filter($this->mockupTypeModel::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);

        
        $data = DataHelper::getList($query, $this->mockupTypeTransformer,$perPage,'ListAllTypeMockup');
        $mockups= $this->mockupTypeTransformer->transformCollection($query->get());
        return $data;


        
        // $mockupTypes= $this->mockupType->get();
        // $mockupTypes= $this->mockupTypeTransformer->transformCollection($mockupTypes);
        // return $mockupTypes;
        //return view('MockupType.MockupType')->with(compact('mockupTypes'));
    }
    public function find($typeId)
    {
       $mockupType = $this->mockupTypeModel->where('type_id',$typeId)->first();
       $mockups = $mockupType->mockup()->get();
       return view('Mockup.Mockup')->with(compact('mockups'));
    }
    public function test(Request $request)
    {
      //  $param = $request->all();
      //  dd("aaaaaaaa");
      //  $mockupType = $this->mockupTypeModel->create([
      //    'type_name' => $param['name']
      // ]);
       $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];

        $orderBy = $this->mockupTypeModel->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);

        $query = $this->mockupTypeModel->filter($this->mockupTypeModel::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);

        
        $data = DataHelper::getList($query, $this->mockupTypeTransformer,$perPage,'ListAllTypeMockup');
        $mockups= $this->mockupTypeTransformer->transformCollection($query->get());
        return $data;
    }


}
