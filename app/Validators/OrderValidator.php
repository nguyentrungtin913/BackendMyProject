<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderValidator extends BaseValidator
{

    public function __construct(Order $order)
    {
        $this->order= $order;
    }

    public function requireName()
    {
        return $this->requireParam('name', 'Please enter name');
    }

    public function requireAddress()
    {
        return $this->requireParam('address', 'Please enter address');
    }

    public function checkAmount()
    {
        return $this->checkNumeric('amount', 'Please enter amount');
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
            $this->setError(400, 'invalid_param', "Invalid param: order ", "Order invalid");
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
    public function store(){
        if (!$this->requireName() || !$this->requireAddress() || !$this->checkAmount()) {
            return false;
        } else {
            return true;
        }
    }
}

?>
