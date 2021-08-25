<?php


namespace App\Validators;
use Illuminate\Http\Request;

class ProductValidator extends BaseValidator
{
   
    public function requireName()
    {
        return $this->requireParam('name', 'Please enter name');
    }

    public function requirePrice()
    {
        return $this->requireParam('price', 'Please enter price');
    }

    public function checkPrice()
    {
        return $this->checkNumeric('price', 'Please enter price');
    }

    public function store(){
        if (!$this->requireName() || !$this->requirePrice() || !$this->checkPrice() ) {
            return false;
        } else {
            return true;
        }
    }
}

?>