<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use App\Validators\ProductValidator;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\APIRender;
use App\Helpers\ConvertString;
class ProductController extends Controller
{
    public function __construct(Product $product, ProductType $productType, ProductValidator $productValidator, APIRender $apiRender, ConvertString $convertString)
    {
        $this->product = $product;
        $this->productType= $productType;
        $this->productValidator= $productValidator;
        $this->apiRender= $apiRender;
        $this->convertString= $convertString;
    }

   
    public function rotateImage($imagePath, $angle, $color) {
        $imagick = new \Imagick(realpath($imagePath));
        $imagick->rotateimage($color, $angle);
        header("Content-Type: image/jpg");
        echo $imagick->getImageBlob();
    }
    public function index()
    {
         $products = $this->product->get();
         return view('product.product')->with(compact('products'));
    }

    public function insert()
    {
        $productTypes= $this->productType->get();
        return view('product.ProductAdd')->with(compact('productTypes'));
    }

    public function save(Request $request)
    {
        $param = $request->all();

        if (!$this->productValidator->setRequest($request)->store()) {
            $errors = $this->productValidator->getErrors();
            $productTypes= $this->productType->get();
          
            return view('product.ProductAdd')->with(compact('productTypes', 'errors'));
        }

        $tenKhongDau= $this->convertString->TenKhongDau($param['name']);
        $get_image = request('image');
        
        $new_image = $tenKhongDau.'.'.$get_image->getClientOriginalExtension();

        $get_image->move('storage/app/public/upload/products',$new_image);


       //  $path1 = 'storage/app/public/upload/products/aa.png';
       //  $path2 = 'storage/app/public/upload/products/kk.jpg';
        
       // $this->apiRender->render('poster','vrectangle_1',$path1, $path2);
        
       

        $product = new Product();
                $product->product_name = $param['name'];
                $product->product_price = $param['price'];
                $product->product_image = $new_image;
                $product->product_type = $param['type'];
                $product->product_amount = $param['amount'];
                $product->product_name_kd = $tenKhongDau;
       $product->save(); 

       return Redirect::to('/products')->with('success', 'Thêm sản phẩm thành công !');
    }
    public function delete($productId)
    {
        $product = $this->product->find($productId);
        
        $destinationPath = 'storage/app/public/upload/products/'.$product->product_image;
        if (file_exists($destinationPath) || $destinationPath!='storage/app/public/upload/products/'){
            unlink($destinationPath);
        }
        $product->delete(); 

        return Redirect::to('/products')->with('success', 'Thêm sản phẩm thành công !');
    }

    public function edit($productId)
    {
        $productTypes= $this->productType->get();
        $product = $this->product->find($productId);
        return view('product.ProductEdit')->with(compact('product','productTypes'));
    }
    public function update(Request $request, $productId)
    {
        $param = $request->all();
        $tenKhongDau= $this->convertString->TenKhongDau($param['name']);
        $getImage = request('image');
        $product = $this->product->find($productId);
            $product->product_name = $param['name'];
            $product->product_price = $param['price'];
            if(!empty($getImage))
            {
                $destinationPath = 'storage/app/public/upload/products/'.$product->product_image;
                echo  $destinationPath;
                if (file_exists($destinationPath)){
                    unlink($destinationPath);
                }
                $newImage = $tenKhongDau.'.'.$getImage->getClientOriginalExtension();
                $getImage->move('storage/app/public/upload/products',$newImage);
                $product->product_image = $newImage;
            }  
            $product->product_type = $param['type'];
            $product->product_amount = $param['amount'];
            $product->product_name_kd = $tenKhongDau;
        $product->save(); 
        return Redirect::to('/products')->with('success', 'Thêm sản phẩm thành công !');
    }
}
