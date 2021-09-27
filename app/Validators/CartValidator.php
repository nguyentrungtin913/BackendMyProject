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
            $this->setError(400, 'not exist', "file not exist", 'Error');
            return false;
        }
       return true;
    }
    public function requireId()
    {
        return $this->requireParam('cartId', 'Please enter id');
    }

    public function requireAmount()
    {
        return $this->requireParam('cartAmount', 'Please enter amount');
    }

    public function checkAmount()
    {
        return $this->checkNumeric('cartAmount', 'Please enter number');
    }

    public function checkCartExist()
    {
        $cartId = $this->request->get('cartId') ?? 0;
        $cart=$this->cart->where('cart_id',$cartId)->first();
        if(!$cart)
        {
            $this->setError(400, 'invalid', "Card not exist", 'Card not exist');
            return false;
        }
        return true;
    }

    public function update()
    {
        if(!$this->requireId() || !$this->checkCartExist() || !$this->requireAmount() || !$this->checkAmount()) {
            return false;
        } else {
            return true;
        }
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