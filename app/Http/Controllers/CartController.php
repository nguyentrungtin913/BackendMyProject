<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Validators\CartValidator;
use App\Transformers\CartTransformer;

class CartController extends Controller
{
    public function __construct(Cart $cart, CartValidator $cartValidator, CartTransformer $cartTransformer)
    {
        $this->cart = $cart;
        $this->cartValidator = $cartValidator;
        $this->cartTransformer = $cartTransformer;
    }

    public function index(Request $request)
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
        return $carts;
        //return view('Cart.Cart')->with(compact('carts'));
    }
    public function find(Request $request){
        $params=$request->all();
        $cartId = $params['cartId'] ?? null;
        $cart = $this->cart->where('cart_id', $cartId)->first();
        $cart = $this->cartTransformer->transformItem($cart);
        if($cart){
            return $cart;    
        }
        return '{"Data" : "Not Found"}';
        //return view('Cart.EditCart')->with(compact('cart'));
    }
    public function getByUserId()
    {
        $userId = Session::get('user_id') ?? 0;

        $carts = $this->cart->where('user_id', $userId)->get();
        return view('Cart.Cart')->with(compact('carts'));
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
        $cart= $this->cartTransformer->transformItem($cart);

        

        return $cart;

        //return Redirect::to('/cart')->with('success', 'Thêm  thành công !');
    }

    public function delete(Request $request)
    {
        $params=$request->all();
        $cartId = $params['cartId'] ?? 0;
        $cart = $this->cart->where('cart_id', $cartId)->first();
        $folder = md5($cart->user_id);
        $path = "storage/app/public/cart/" . $folder . "/" . $cart->cart_image;
        if (file_exists($path)) {
            unlink($path);
        }
        $cart->delete();
        $cart= $this->cartTransformer->transformItem($cart);
        return $cart;
        //return Redirect::to('/cart')->with('success', 'Delete thành công !');
    }
}
