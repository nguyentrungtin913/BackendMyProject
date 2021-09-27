<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\MockupType;
class MockupTypeValidator extends BaseValidator
{

    public function __construct(MockupType $mockupType)
    {
        $this->mockupType= $mockupType;
    }

    public function requireName()
    {
        return $this->requireParam('name', 'Please enter name');
    }

    public function requireId()
    {
        return $this->requireParam('typeId', 'Please enter id');
    }

    public function checkNameAlreadyExit()
    {
        $mockupTypeId = $this->request->get('typeId') ?? 0;
        $mockupTypeName = $this->request->get('name') ?? null;
        $mockupType = $this->mockupType->where([["type_name",$mockupTypeName],["type_id","!=",$mockupTypeId]])->first();
        if($mockupType){
            $this->setError(400, 'Type name already exist', "Type name already exist", 'Error');
            return false;
        }else{
            return true;
        }
    }
    
    public function findTypeMockup()
    {
        $mockupTypeId = $this->request->get('typeId') ?? null;
        $mockupType = $this->mockupType->where("type_id",$mockupTypeId)->first();
        if(!$mockupType){
            $this->setError(400, 'Error', "Mockup type not exist", 'Mockup type not exist');
            return false;
        }else{
            return true;
        }
    }
  
    public function store(){
        if (!$this->requireName() || !$this->checkNameAlreadyExit()) {
            return false;
        } else {
            return true;
        }
    }
    public function update(){
        if (!$this->requireName() || !$this->requireId() || !$this->checkNameAlreadyExit() || !$this->findTypeMockup()) {
            return false;
        } else {
            return true;
        }
    }
   
}

?>
