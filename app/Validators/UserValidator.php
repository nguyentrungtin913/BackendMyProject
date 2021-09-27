<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\User;

class UserValidator extends BaseValidator
{
    public function __construct(User $user)
    {
        $this->user= $user;
    }
   
    public function requireData()
    {
        if(!$this->requireParam('name', 'Please enter name') || !$this->requireParam('date', 'Please enter date') || !$this->requireParam('address', 'Please enter address') || !$this->requireParam('email', 'Please enter email') || !$this->requireParam('sex', 'Please enter sex') || !$this->requireParam('phone', 'Please enter phone') || !$this->requireParam('role', 'Please enter role') || !$this->requireParam('password', 'Please enter password') || !$this->requireParam('passwordConfirm', 'Please enter passwordConfirm'))
        {
            return false;
        }else{
            return true;
        }
    }

    public function checkSex()
    {
        $sex = $this->request->get('sex');
        if (!in_array($sex, [0,1])) {
            $this->setError(400, 'invalid_param', "Invalid param: Sex", "Sex invalid");
            return false;
        }
        return true;
    }
    public function checkRole()
    {
        $role = $this->request->get('role');
        if (!in_array($role, [0,1])) {
            $this->setError(400, 'invalid_param', "Invalid param: Role", "Role invalid");
            return false;
        }
        return true;
    }

    public function confirmPassword()
    {
        $pass = $this->request->get('password');
        $passConfirm = $this->request->get('passwordConfirm');
        if($pass !== $passConfirm){
           $this->setError(400, 'invalid_param', "Confirm password not exactly", "Confirm password not exactly");
            return false;
        }
        return true;
    }
    public function store(){
        if (!$this->requireData() || !$this->checkSex() || !$this->checkRole() || !$this->confirmPassword() ) {
            return false;
        } else {
            return true;
        }
    }
    public function checkUserExist()
    {
        $id = $this->request->get('userId');
        if (!$this->user->where('user_id',$id)->first()) {
            $this->setError(400, 'invalid_param', "User not exist", "User not exist");
            return false;
        }
        return true;
    }
}

?>