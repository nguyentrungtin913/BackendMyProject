<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Validators\CartValidator;

class CartController extends Controller
{
    public function __construct(Cart $cart, CartValidator $cartValidator)
    {
        $this->cart = $cart;
        $this->cartValidator = $cartValidator;
    }

    public function index()
    {
        $carts = $this->cart->get();
        return view('Cart.Carts')->with(compact('carts'));
    }
    public function edit(){
        $cart = $this->cart->where('cart_id', $cartId)->first();
        return view('Cart.EditCart')->with(compact('cart'));
    }
    public function getByUserId()
    {
        $userId = Session::get('user_id') ?? 0;

        $carts = $this->cart->where('user_id', $userId)->get();
        return view('Cart.Cart')->with(compact('carts'));
    }

    public function save()
    {
        $userId = Session::get('user_id') ?? 0;
        $image = Session::get('image') . '.jpg';
        $mockupId = Session::get('mockupId');
        if (!$this->cartValidator->store($image, $userId)) {
            $errors = $this->cartValidator->getErrors();
            return Redirect::to('/image-render')->with(compact('errors'));
        }


        $cart = new Cart();
        $cart->cart_image = $image;
        $cart->user_id = $userId;
        $cart->mockup_id = $mockupId;
        $cart->save();

        $folder = md5($userId);
        if (!is_dir("storage/app/public/cart/" . $folder)) {
            mkdir("storage/app/public/cart/" . $folder);
        }
        copy('storage/app/public/cache/' . $image, 'storage/app/public/cart/' . $folder . '/' . $image);

        return Redirect::to('/cart')->with('success', 'Thêm  thành công !');
    }

    public function delete($cartId)
    {
        $cart = $this->cart->where('cart_id', $cartId)->first();
        $folder = md5($cart->user_id);
        $path = "storage/app/public/cart/" . $folder . "/" . $cart->cart_image;
        if (file_exists($path)) {
            unlink($path);
        }
        $cart->delete();
        return Redirect::to('/cart')->with('success', 'Delete thành công !');
    }
}
