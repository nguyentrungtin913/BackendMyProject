<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CodeOTP;
use App\Validators\CodeOTPValidator;
class UserValidator extends BaseValidator
{
    public function __construct(User $user, CodeOTPValidator $codeOTPValidator)
    {
        $this->user = $user;
        $this->codeOTPValidator = $codeOTPValidator;
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
        if (!$this->requireData() || !$this->checkEmailExist() || !$this->checkSex() || !$this->checkRole() || !$this->confirmPassword() ) {
            return false;
        } else {
            return true;
        }
    }
    public function checkEmailExist()   //return false if exist
    {
        $email = $this->request->get('email');
        if ($this->user->where('user_email',$email)->first()) {
            $this->setError(400, 'invalid_param', "Email exist", "Email already exists");
            return false;
        }
        return true;
    }

    public function checkEmailNotExist()   //return false if exist
    {
        $email = $this->request->get('email');
        if (!$this->user->where('user_email',$email)->first()) {
            $this->setError(400, 'invalid_param', "Email not exist", "Email not exists");
            return false;
        }
        return true;
    }



    public function checkUserExist()    //return false if not exist
    {
        $id = $this->request->get('userId');
        if (!$this->user->where('user_id',$id)->first()) {
            $this->setError(400, 'invalid_param', "User not exist", "User not exist");
            return false;
        }
        return true;
    }

    public function requireDataResetPassword()
    {
        if(!$this->requireParam('email', 'Please enter email') || !$this->requireParam('otp', 'Please enter otp') || !$this->requireParam('password', 'Please enter password') || !$this->requireParam('passwordConfirm', 'Please enter passwordConfirm'))
        {
            return false;
        }else{
            return true;
        }
    }

    public function resetPassword()
    {
        //require data, check user exist by email
        if (!$this->requireDataResetPassword() || !$this->checkEmailNotExist() || !$this->confirmPassword() ) {
            return false;
        }
        return $this->checkOTP();
    }
}

?>