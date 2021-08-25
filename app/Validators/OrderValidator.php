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

    public function store(){
        if (!$this->requireName() || !$this->requireAddress() || !$this->checkAmount()) {
            return false;
        } else {
            return true;
        }
    }
}

?>