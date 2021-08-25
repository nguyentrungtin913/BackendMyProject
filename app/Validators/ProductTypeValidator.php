<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\ProductType;
class ProductTypeValidator extends BaseValidator
{
   
    public function __construct(ProductType $productType)
    {
        $this->productType= $productType;
    }

    public function requireName()
    {
        return $this->requireParam('name', 'Please enter name');
    }

    public function checkExit()
    {
        $nameType= $this->request->get('name');
        $productType = $this->productType->where('type_name', $nameType)->first();
        if ($productType) {
            $this->setError(404, 'invalid_param', 'Product type already exists', 'Please re-enter type product');
            return false;
        } else {
            return true;
        }
    }
    

    public function store(){
        if (!$this->requireName() || !$this->checkExit()) {
            return false;
        } else {
            return true;
        }
    }
}

?>