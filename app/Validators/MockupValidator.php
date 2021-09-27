<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\Mockup;
use App\Models\MockupType;
class MockupValidator extends BaseValidator
{

    public function __construct(Mockup $mockup, MockupType $mockupType)
    {
        $this->mockup= $mockup;
        $this->mockupType = $mockupType;
    }

    public function requireName()
    {
        return $this->requireParam('name', 'Please enter name');
    }
    public function requireSide()
    {
        return $this->requireParam('side', 'Please enter side');
    }
    public function requirePrice()
    {
        return $this->requireParam('price', 'Please enter price');
    }
    public function requireType()
    {
        return $this->requireParam('type', 'Please enter type');
    }
    public function checkPrice()
    {
        return $this->checkNumeric('price', 'Price invalid');
    }

    public function requireDataStore()
    {
        if (!$this->requireName() || !$this->requireSide() || !$this->requirePrice() || !$this->requireType() ) {
            return false;
        } else {
            return true;
        }
    }
    public function checkNameAlreadyExit()
    {
        $mockupName = $this->request->get('name') ?? null;
        $mockup = $this->mockup->where("mockup_name",$mockupName)->first();
        if($mockup){
            $this->setError(400, 'Mockup name already exist', "Mockup name already exist", 'Error');
            return false;
        }else{
            return true;
        }
    }

    public function checkTypeExist()
    {
        $mockupTypeId = $this->request->get('type') ?? null;
        $mockupType = $this->mockupType->where("type_id",$mockupTypeId)->first();
        if(!$mockupType){
            $this->setError(400, 'Error', "Mockup type not exist", 'Mockup type not exist');
            return false;
        }else{
            return true;
        }
    }
    

    public function findMockup($mockupId)
    {
        $mockup = $this->mockup->where("mockup_id",$mockupId)->first();
        if(!$mockup){
            $this->setError(400, 'Error', "Mockup not exist", 'Mockup not exist');
            return false;
        }else{
            return true;
        }
    }
  
    public function store(){
        if (!$this->requireDataStore() || !$this->checkNameAlreadyExit() || !$this->checkTypeExist() || !$this->checkPrice()) {
            return false;
        } else {
            return true;
        }
    }
   
}

?>
