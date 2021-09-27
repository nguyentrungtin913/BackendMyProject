<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Validators\CartValidator;
use App\Transformers\CartTransformer;
use App\Helpers\ResponseHelper;
use Response;

class CartController extends Controller
{
    public function __construct(Cart $cart, CartValidator $cartValidator, CartTransformer $cartTransformer)
    {
        $this->cart = $cart;
        $this->cartValidator = $cartValidator;
        $this->cartTransformer = $cartTransformer;
    }

    public function index(Request $request, Response $response)
    {
        $params=$request->all();
        $userId = $params['userId'] ?? null;
        $with = $params['with'] ?? [];

        $orderBy = $this->cart->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);
        $query = $this->cart->filter($this->cart::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);
        if($userId)
            {$query = $query->where('user_id', $userId);}

        $query = $this->cart->includes($query, $with);

        $carts = $this->cartTransformer->transformCollection($query->get());
        
        return ResponseHelper::success($response, $carts);

        //return view('Cart.Cart')->with(compact('carts'));
    }
    public function find(Request $request, Response $response){
        $params=$request->all();
        if (!$this->cartValidator->setRequest($request)->checkCartExist()) {
            $errors = $this->cartValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $cartId = $params['cartId'] ?? 0;
        $cart = $this->cart->where('cart_id', $cartId)->first();
        
        if($cart){
            $cart = $this->cartTransformer->transformItem($cart);
            return ResponseHelper::success($response, $cart);    
        }
        return ResponseHelper::requestFailed($response); 
        //return view('Cart.EditCart')->with(compact('cart'));
    }
    public function getByUserId()
    {
        $userId = Session::get('user_id') ?? 0;

        $carts = $this->cart->where('user_id', $userId)->get();
        return view('Cart.Cart')->with(compact('carts'));
    }
    public function update(Request $request, Response $response)
    {
        $params=$request->all();
        if (!$this->cartValidator->setRequest($request)->update()) {
            $errors = $this->cartValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $cartId = $params['cartId'] ?? 0;
        $cartAmount = $params['cartAmount'] ?? 0;
        $cart = $this->cart->where('cart_id', $cartId)->first();
        if($cart->update(['cart_amount' => $cartAmount])){
            $cart = $this->cartTransformer->transformItem($cart);
            return ResponseHelper::success($response, $cart);    
        }
        return ResponseHelper::requestFailed($response);
    }

    public function save(Request $request)
    {
        $params=$request->all();
        // $userId = Session::get('user_id') ?? 0;
        // $image = Session::get('image') . '.jpg';
        // $mockupId = Session::get('mockupId');
        $userId = $params['userId'] ?? 0;
        $image = $params['image'];
        $mockupId = $params['mockupId'];
        
        if (!$this->cartValidator->store($image, $userId)) {
            $errors = $this->cartValidator->getErrors();
            return Redirect::to('/image-render')->with(compact('errors'));
        }


        $folder = md5($userId);
        if (!is_dir("storage/app/public/cart/" . $folder)) {
            mkdir("storage/app/public/cart/" . $folder);
        }
        $arr = explode('/', $image);
        $nameImage = $arr[count($arr)-1];
        $newImage= 'storage/app/public/cart/' . $folder . '/' . $nameImage;
        copy($image, $newImage);
        $cart = $this->cart->create([
            'cart_image'    => $newImage,
            'user_id'       => $userId,
            'mockup_id'     => $mockupId,
        ]);
        if($cart){
            $cart= $this->cartTransformer->transformItem($cart);
            return ResponseHelper::success($response, $cart);    
        }
        return ResponseHelper::requestFailed($response); 
        

        //return Redirect::to('/cart')->with('success', 'Thêm  thành công !');
    }

    public function delete(Request $request)
    {
        $params=$request->all();
        if (!$this->cartValidator->setRequest($request)->checkCartExist()) {
            $errors = $this->cartValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        $cartId = $params['cartId'] ?? 0;
        $cart = $this->cart->where('cart_id', $cartId)->first();
        $folder = md5($cart->user_id);
        $path = "storage/app/public/cart/" . $folder . "/" . $cart->cart_image;
        if (file_exists($path)) {
            unlink($path);
        }
        //$cart->delete();
        
        if($this->cart->where('cart_id', $cartId)->update(['cart_deleted' => 1])){
            $cart= $this->cartTransformer->transformItem($cart);
            return ResponseHelper::success($response, $cart);    
        }
        return ResponseHelper::requestFailed($response); 
        
        //return Redirect::to('/cart')->with('success', 'Delete thành công !');
    }
}
