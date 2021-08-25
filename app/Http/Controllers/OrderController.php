<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Validators\OrderValidator;
use Session;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Transformers\MockupTransformer;
class OrderController extends Controller
{
    public function __construct(Order $order, Cart $cart, OrderDetail $orderDetail,OrderValidator $orderValidator, MockupTransformer $mockupTransformer)
    {
        $this->order = $order;
        $this->cart = $cart;
        $this->orderDetail = $orderDetail;
        $this->orderValidator = $orderValidator;
        $this->mockupTransformer = $mockupTransformer;
    }
    public function insert($cartId)
    {
        $cart= $this->cart->where('cart_id',$cartId)->first();
        return view('Order.OrderAdd')->with(compact('cart'));
    }

    public function save(Request $request)
    { 
        
        if (!$this->orderValidator->setRequest($request)->store()) {
            $errors = $this->orderValidator->getErrors();
            return Redirect::to('/cart')->with(compact('errors'));
        }
        $param = $request->all();
        $userId = Session::get('user_id') ?? 0;
        $folder=md5($userId);
       
        $image = $param['image'];
        $name = $param['name'];
        $address = $param['address'];
        $amount = $param['amount'];
        $cart = $this->cart->where('cart_image', $image)->first();
        $price = $cart->mockup->mockup_price;
        $total = $amount * $price;
        $oldPath = "storage/app/public/cart/".$folder."/".$image;
        $newPath = "storage/app/public/order/".date("Y/m/d")."/";
        $order = new Order();
            $order->order_name= $name;
            $order->order_address= $address;
            $order->order_total= $total;
            $order->order_status = 0;
            $order->order_date= date("Y-m-d");
            $order->user_id=$userId;
        if($order->save())
        {
            $newImage = $order->order_id.'-'.$image;

            $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->order_id;
                $orderDetail->image = $newImage;
                $orderDetail->price = $price;
                $orderDetail->amount = $amount;
            if($orderDetail->save())
            {
                if(!is_dir($newPath))
                {
                    mkdir($newPath,'0777', true);
                }
                if (file_exists($oldPath)){
                    rename($oldPath, $newPath."/".$newImage);
                }
                $cart->delete();
            }
            else {
                echo "save detail fail";
            }
        }
        else
        {
            echo "save order fail";
        }
        return Redirect::to('/order')->with('success', 'xóa thành công !');
    }
    public function index()
    {
       $userId = Session::get('user_id') ?? 0; 

       $orders = $this->order->where('user_id', $userId)->get();
       return view('Order.Order')->with(compact('orders'));
    }
    public function delete($orderId)
    {
        $order = $this->order->where('order_id',$orderId)->first();
        $foder = str_replace('-', '/',  $order->order_date);
        $orderDetails = $this->orderDetail->where('order_id',$orderId)->get();
        foreach($orderDetails as $key => $orderDetail)
        {
            $path = "storage/app/public/order/".$foder."/".$orderDetail->image;
            if(file_exists($path))
            {
                unlink($path);
            }
            $orderDetail->delete();
        }
        $order->delete();
        return Redirect::to('/order')->with('success', 'xóa thành công !');
    }
}
