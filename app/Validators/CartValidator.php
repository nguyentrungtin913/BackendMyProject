<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\Cart;
class CartValidator extends BaseValidator
{
   
    public function __construct(Cart $cart)
    {
        $this->cart= $cart;
    }

    public function checkExitFile($image)
    {
        if (!file_exists($image)) 
        {
            $this->setError(400, 'not exit', "file not exit", 'Error');
            return false;
        }
       return true;
    }

    public function dataExit($image,$userId)
    {
        $cart=$this->cart->where('cart_image',$image)->where('user_id',$userId)->first();
        if($cart)
        {
            $this->setError(400, 'exit', "saved file", 'Error');
            return false;
        }
        return true;

    }

    public function store($image, $userId)
    {
        if (!$this->checkExitFile($image) || !$this->dataExit($image, $userId)) {
            return false;
        } else {
            return true;
        }
    }
}

?>