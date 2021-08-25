<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Validators\ProductTypeValidator;


use Illuminate\Support\Facades\Redirect;

class ProductTypeController extends Controller
{
    public function __construct(ProductType $productType, ProductTypeValidator $productTypeValidator)
    {
       $this->productType= $productType;
       $this->productTypeValidator= $productTypeValidator;
    }
    public function index()
    {
         $productTypes = $this->productType->getAll();
         return view('ProductType.ProductType')->with(compact('productTypes'));
    }
    public function insert()
    {
        return view('ProductType.ProductTypeAdd');
    }

    public function save(Request $request)
    {
        if (!$this->productTypeValidator->setRequest($request)->store()) {
            $errors = $this->productTypeValidator->getErrors();    
            return view('ProductType.ProductTypeAdd')->with(compact('errors'));
        }

        $param = $request->all();
        
        $name = $param['name'];
      

        $productType = new ProductType();
                $productType->type_name = $param['name'];
                
       $productType->save(); 

       return Redirect::to('/product-types')->with('success', 'Thêm sản phẩm thành công !');
    }

    public function edit($typeId)
    {
       $productType = $this->productType->find($typeId);
       return view('ProductType.EditProductType')->with(compact('productType'));
    }

    public function update($typeId, Request $request)
    {
        $param = $request->all();
        
        $name = $param['name'];

        $productType = $this->productType->find($typeId);

        $productType->type_name = $param['name'];
                
        $productType->save(); 

        return Redirect::to('/product-types')->with('success', 'Thêm sản phẩm thành công !');
    }

    public function delete($typeId)
    {
       
        $productType = $this->productType->find($typeId);
                
        $productType->delete(); 

        return Redirect::to('/product-types')->with('success', 'Thêm sản phẩm thành công !');
    }
}
