<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Validators\OrderValidator;
use App\Helpers\DataHelper;
use Session;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Transformers\MockupTransformer;
use App\Transformers\OrderTransformer;
class OrderController extends Controller
{
    public function __construct(Order $order, Cart $cart, OrderDetail $orderDetail,OrderValidator $orderValidator, MockupTransformer $mockupTransformer, OrderTransformer $orderTransformer)
    {
        $this->order = $order;
        $this->cart = $cart;
        $this->orderDetail = $orderDetail;
        $this->orderValidator = $orderValidator;
        $this->mockupTransformer = $mockupTransformer;
        $this->orderTransformer = $orderTransformer;
    }

    public function index(Request $request){
        $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];
        $orderBy = $this->order->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);

        $query = $this->order->filter($this->order::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);
        $query = $this->order->includes($query,$with);
        $data = DataHelper::getList($query, $this->orderTransformer,$perPage,'ListAllOrder');
        $orders= $this->orderTransformer->transformCollection($query->get());
        return $data;

        // $orders = $this->order->with('user')->get();
        // return view('Order.Orders')->with(compact('orders'));
    }
    public function find(Request $request)
    {
        $params = $request->all();
        $id = $params['id'];

        $with = $params['with'] ?? [];

        $query = $this->order->where('order_id', $id);
        $order = $this->order->includes($query,$with)->first();
        if($order){
            $order = $this->orderTransformer->transformItem($order);
            return $order;    
        }
        return '{"data" : "Not Found"}';

    }
    public function updateStatus(Request $request)
    {
        $params = $request->all();
        $id = $params['id'];
        $status = $params['status'];

        if (!$this->orderValidator->update($id,$status)) {
            $errors = $this->orderValidator->getErrors();
            return compact('errors');
        }
        $order = $this->order->where('order_id', $id)->first();
            $order->order_status = $status;
        $order->save();
        $order = $this->orderTransformer->transformItem($order);
        return $order;

    }

    public function delete(Request $request)
    {
        $params = $request->all();
        $orderId = $params['id'];

        if (!$this->orderValidator->checkId($orderId)) {
            $errors = $this->orderValidator->getErrors();
            return compact('errors');
        }

        $order = $this->order->where('order_id',$orderId)->first();

        $foder = str_replace('-', '/',  $order->order_date);
        $orderDetails = $this->orderDetail->where('order_id',$orderId)->get();
        if($orderDetails){
          foreach($orderDetails as $key => $orderDetail)
            {
                $path = "storage/app/public/order/".$foder."/".$orderDetail->image;
                if(file_exists($path))
                {
                    unlink($path);
                }
                $orderDetail->delete();
            }  
        }
        
        $order->delete();
        $order = $this->orderTransformer->transformItem($order);
        return $order;
        //return Redirect::to('/orders')->with('success', 'xóa thành công !');
    }
    public function update(Request $request){

        $param = $request->all();
        $id = $param['id'];
        $status = $param['status'];

        if (!$this->orderValidator->update($id, $status)) {
            $errors = $this->orderValidator->getErrors();
            return Redirect::to('/orders')->with(compact('errors'));
        }
        $order = $this->order->where('order_id', $id)->first();
            $order->order_status = $status;
        $order->save();
        return Redirect::to('/orders')->with('success', 'update thành công !');
    }
    public function manyUpdate(Request $request)
    {
        $param = $request->all();

        $k=$param['key'];
        $v=$param['value'];

        $key = explode(',',$k);
        array_shift($key);

        $values = explode(',',$v);
        array_shift($values);

        $arr = array_combine($key,$values);

        if (!$this->orderValidator->updates($arr)) {
            $errors = $this->orderValidator->getErrors();
            return Redirect::to('/orders')->with(compact('errors'));
        }

        foreach ($arr as $key => $value) {
            $order = $this->order->where('order_id', $key)->first();
                $order->order_status = $value;
            $order->save();
        }
        return Redirect::to('/orders')->with('success', 'update thành công !');
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
        //$userId = Session::get('user_id') ?? 0;

        $userId = $param['userId'] ?? 0;
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

        $order = $this->order->create([
            'order_name'    => $name,
            'order_address' => $address,
            'order_total'   => $total,
            'order_status'  => 0,
            'user_id'       => $userId,
        ]);
        if($order->save())
        {
            $newImage = $order->id.'-'.$image;

            $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->order_id;
                $orderDetail->image = $newImage;
                $orderDetail->price = $price;
                $orderDetail->amount = $amount;
            if($orderDetail->save())
            {
                if(!is_dir($newPath))
                {
                    mkdir($newPath,0777, true);
                    chmod($newPath, 0777);
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
    public function getByUserId()
    {
       $userId = Session::get('user_id') ?? 0;

       $orders = $this->order->where('user_id', $userId)->get();
       return view('Order.Order')->with(compact('orders'));
    }
   
}
