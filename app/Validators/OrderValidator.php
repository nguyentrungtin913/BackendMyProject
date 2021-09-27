<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
class OrderValidator extends BaseValidator
{

    public function __construct(Order $order, Cart $cart, User $user)
    {
        $this->order = $order;
        $this->cart  = $cart;
        $this->user  = $user;
    }

    public function requireName()
    {
        return $this->requireParam('name', 'Please enter name');
    }

    public function requireAddress()
    {
        return $this->requireParam('address', 'Please enter address');
    }

    
    public function checkManyId($arr)
    {
        $key = array_keys($arr);
        foreach ($key as $k){

            if(!$this->order->where('order_id', $k)->first())
            {
                $this->setError(400, 'invalid_param', "Invalid param: order ", "Order invalid");
                return false;
            }
        }
        return true;
    }
    public function checkStatuses($arr){
        foreach ($arr as $v){
            if (!in_array($v, [0,1,2,3])) {
                $this->setError(400, 'invalid_param', "Invalid param: status", "Status invalid");
                return false;
            }
        }
        return true;
    }

    public function checkStatus($status){
        if (!in_array($status, [0,1,2,3])) {
            $this->setError(400, 'invalid_param', "Invalid param: status", "Status invalid");
            return false;
        }
        return true;
    }
    public function checkId($id){
        if(!$this->order->where('order_id', $id)->first())
        {
            $this->setError(400, 'invalid_param', "Order not exist", "Order not exist");
            return false;
        }
        return true;
    }
    public function updates($arr){
        if (!$this->checkStatuses($arr) || !$this->checkManyId($arr)) {
            return false;
        } else {
            return true;
        }
    }
    public function update($id, $status)
    {
        if (!$this->checkStatus($status) || !$this->checkId($id)) {
            return false;
        } else {
            return true;
        }
    }
    public function checkUserExist()
    {
        $userId = $this->request->get('userId') ?? 0;
        $user = $this->user->where(["user_id" => $userId])->first();
        if(!$user){
            $this->setError(400, 'User not exist', "User not exist", 'Error');
            return false;
        }else{
            return true;
        }
    }
    public function checkCartNotEmpty()
    {
        $userId = $this->request->get('userId') ?? 0;
        $cart = $this->cart->where(["user_id" => $userId])->first();
        if(!$cart){
            $this->setError(400, 'Cart empty', "Cart empty", 'Error');
            return false;
        }else{
            return true;
        }
    }
    public function store(){
        if (!$this->requireName() || !$this->requireAddress() || !$this->checkUserExist() || !$this->checkCartNotEmpty()) {
            return false;
        } else {
            return true;
        }
    }
   
}

?>
