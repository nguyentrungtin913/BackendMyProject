<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Validators\OrderValidator;
use App\Helpers\DataHelper;
use Session;
use Response;
use App\Helpers\ResponseHelper;
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

    public function index(Request $request, Response $response){
        $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];
        $orderBy = $this->order->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);

        $query = $this->order->filter($this->order::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);
        $query = $this->order->includes($query,$with);
        $data = DataHelper::getList($query, $this->orderTransformer,$perPage,'ListAllOrder');
        $orders= $this->orderTransformer->transformCollection($query->get());

        return ResponseHelper::success($response, $orders);
        // $orders = $this->order->with('user')->get();
        // return view('Order.Orders')->with(compact('orders'));
    }
    public function find(Request $request, Response $response)
    {
        $params = $request->all();
        $id = $params['orderId'];
        if (!$this->orderValidator->checkId($id)) {
            $errors = $this->orderValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        $with = $params['with'] ?? [];


        $query = $this->order->where('order_id', $id);
        $order = $this->order->includes($query,$with)->first();
        if($order){
            $order = $this->orderTransformer->transformItem($order);
            return ResponseHelper::success($response, compact('order'));    
        }
        return ResponseHelper::requestFailed($response);

    }
    public function updateStatus(Request $request, Response $response)
    {
        $params = $request->all();
        $id = $params['orderId'];
        $status = $params['status'];

        if (!$this->orderValidator->update($id,$status)) {
            $errors = $this->orderValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        if($this->order->where('order_id', $id)->update(['order_status'=> $status])){
            $order = $this->order->where('order_id', $id)->first();
            $order = $this->orderTransformer->transformItem($order);
            return ResponseHelper::success($response, compact('order'));
        }else{
            return ResponseHelper::requestFailed($response);
        }

    }

    public function delete(Request $request, Response $response)
    {
        $params = $request->all();
        $orderId = $params['orderId'];

        if (!$this->orderValidator->checkId($orderId)) {
            $errors = $this->orderValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        // $foder = str_replace('-', '/',  $order->order_date);
        // $orderDetails = $this->orderDetail->where('order_id',$orderId)->get();
        // if($orderDetails){
        //   foreach($orderDetails as $key => $orderDetail)
        //     {
        //         $path = "storage/app/public/order/".$foder."/".$orderDetail->image;
        //         if(file_exists($path))
        //         {
        //             unlink($path);
        //         }
        //         $orderDetail->delete();
        //     }  
        // }
        // $order->delete();
        
        if($this->order->where('order_id', $orderId)->update(['order_deleted' => 1])){
            $order = $this->order->where('order_id',$orderId)->first();
            $order = $this->orderTransformer->transformItem($order);
            return ResponseHelper::success($response, compact('order'));
        }else{
            return ResponseHelper::requestFailed($response);
        }
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

    public function save(Request $request, Response $response)
    {
        if (!$this->orderValidator->setRequest($request)->store()) {
            $errors = $this->orderValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $param = $request->all();
        //$userId = Session::get('user_id') ?? 0;

        $userId = $param['userId'] ?? 0;
        $folder=md5($userId);
        $name = $param['name'];
        $address = $param['address'];

        $carts = $this->cart->where('user_id',$userId)->get();
        $total = 0;
        foreach($carts as $cart){
            $total += ($cart->cart_amount * $cart->mockup->mockup_price);
        }
        $date = date("Y-m-d");
        $order = $this->order->create([
            'order_name'    => $name,
            'order_address' => $address,
            'order_total'   => $total,
            'order_status'  => 0,
            'order_date'    => $date,
            'user_id'       => $userId,
        ]);

        if($order)
        {
            foreach($carts as $cart){
                $image = $cart->cart_image;
                $cart = $this->cart->where('cart_image', $image)->first();
                $price = $cart->mockup->mockup_price;
                $total = $cart->cart_amount * $price;

                // $oldPath = "storage/app/public/cart/".$folder."/".$image;
                $oldPath = $image;

                $arr= explode("/",$oldPath);

                $arr[3]="order/".date("Y/m/d");
                $image = $arr[count($arr)-1];
                unset($arr[count($arr)-1]);

                $newPath = implode("/",$arr);
            
                
                $orderDetail = $this->orderDetail->create([
                    'order_id'         => $order->order_id,
                    'detail_image'     => $newPath."/".$image,
                    'detail_price'     => $price,
                    'detail_amount'    => $cart->cart_amount,
                ]); 

                if($orderDetail)
                {
                    if(!is_dir($newPath))
                    {
                        mkdir($newPath,0777, true);
                        //chmod($newPath, 0777);
                    }
                    if (file_exists($oldPath)){

                        if (!rename($oldPath, $newPath."/".$image)) {
                            if (copy ($oldPath, $newPath."/".$image)) {
                                unlink($oldPath);
                            }
                        }
                    }
                    $cart->update(['order_deleted' => 1]);
                }
                else {
                   return ResponseHelper::requestFailed($response);
                }
            }
        }
        else
        {
            return ResponseHelper::requestFailed($response);
        }
        return ResponseHelper::success($response, compact('order'));
        // return Redirect::to('/order')->with('success', 'xóa thành công !');
    }
    public function getByUserId()
    {
       $userId = Session::get('user_id') ?? 0;

       $orders = $this->order->where('user_id', $userId)->get();
       return view('Order.Order')->with(compact('orders'));
    }
   
}
