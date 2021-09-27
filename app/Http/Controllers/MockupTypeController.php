<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MockupType;
use App\Helpers\APIRender;
use App\Transformers\MockupTypeTransformer;
use App\Validators\MockupTypeValidator;
use App\Helpers\DataHelper;
use Response;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Redirect;

class MockupTypeController extends Controller
{
    public function __construct(MockupType $mockupTypeModel, MockupTypeTransformer $mockupTypeTransformer, MockupTypeValidator $mockupTypeValidator)
    {
        $this->mockupTypeModel = $mockupTypeModel;
        $this->mockupTypeTransformer = $mockupTypeTransformer;
        $this->mockupTypeValidator = $mockupTypeValidator;
    }

    public function index(Request $request, Response $response)
    {
        $params = $request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];

        $orderBy = $this->mockupTypeModel->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);

        $query = $this->mockupTypeModel->filter($this->mockupTypeModel::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);


        $data = DataHelper::getList($query, $this->mockupTypeTransformer, $perPage, 'ListAllTypeMockup');
        //$mockups = $this->mockupTypeTransformer->transformCollection($query->get());
        return ResponseHelper::success($response, $data);



        // $mockupTypes= $this->mockupType->get();
        // $mockupTypes= $this->mockupTypeTransformer->transformCollection($mockupTypes);
        // return $mockupTypes;
        //return view('MockupType.MockupType')->with(compact('mockupTypes'));
    }
    public function find(Request $request, Response $response)
    {
        $param = $request->all();
        $typeId = $param['typeId'] ?? null;

        if (!$this->mockupTypeValidator->findTypeMockup($typeId)) {
            $errors = $this->mockupTypeValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $mockupType = $this->mockupTypeModel->where('type_id', $typeId)->first();
        $mockupType = $this->mockupTypeTransformer->transformItem($mockupType);
        return ResponseHelper::success($response, compact('mockupType'));

    }
    public function save(Request $request, Response $response)
    {
        $param = $request->all();

        if (!$this->mockupTypeValidator->setRequest($request)->store()) {
            $errors = $this->mockupTypeValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        $mockupType = $this->mockupTypeModel->create([
            'type_name' => $param['name']
        ]);

        $mockupType = $this->mockupTypeTransformer->transformItem($mockupType);

        return ResponseHelper::success($response, compact('mockupType'));
    }

    public function update(Request $request, Response $response)
    {
        $param = $request->all();
        if (!$this->mockupTypeValidator->setRequest($request)->update()) {
            $errors = $this->mockupTypeValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        $mockupType = $this->mockupTypeModel->where('type_id', $param['id'])->first();
        if($mockupType->update(['type_name' => $param['name']])) {
            $mockupType = $this->mockupTypeTransformer->transformItem($mockupType);
            return ResponseHelper::success($response, compact('mockupType'));
        }else
        {
            return ResponseHelper::requestFailed($response);
        }
    }

    public function delete(Request $request, Response $response)
    {
        $param = $request->all();
        $typeId = $param['typeId'] ?? null;

        if (!$this->mockupTypeValidator->setRequest($request)->findTypeMockup($typeId)) {
            $errors = $this->mockupTypeValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        if($this->mockupTypeModel->where('type_id', $typeId)->update(['type_deleted' => 1])) {
            $mockupType = $this->mockupTypeModel->where('type_id', $typeId)->first();
            $mockupType = $this->mockupTypeTransformer->transformItem($mockupType);
            return ResponseHelper::success($response, compact('mockupType'), 'Success Delete type mockup success');
        }else
        {
            return ResponseHelper::requestFailed($response);
        }
    }


}
