<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\CodeOTP;
use App\Models\User;
use Validator;
class CodeOTPValidator extends BaseValidator
{
   
    public function __construct(CodeOTP $codeOTP, User $user)
    {
        $this->codeOTP = $codeOTP;
        $this->user = $user;
    }

    public function requireEmail()
    {
        return $this->requireParam('email', 'Please enter email');
    }

    public function sendMail()
    {
        if (!$this->requireEmail() || !$this->checkEmailExist()) {
            return false;
        } else {
            return true;
        }
    }


    public function requireOTP()
    {
        return $this->requireParam('otp', 'Please enter OTP');
    }

    public function validEmail()
    {
        try {
            $validator = Validator::make($this->request->all(), [
            'email'=>'required|email'
            ]);

        } catch (NestedValidationException $exception) {
            foreach ($exception->getMessages() as $message) {
                $this->setError(400, 'invalid_param', 'Invalid param: `email`. ' . $message, $message);
            }
            return false;
        }
    }

    public function checkEmailExist()
    {
        $email = $this->request->get('email');
        $user = $this->user->where('user_email', $email)->first();
        if($user){
            return true;
        }else{
            $this->setError(400, 'invalid_param', 'Email not created account', 'Please create an account before verifying');
            return false;
        }

    }

    public function checkData()
    {
        return $this->checkOTP();
    }



    public function activate()
    {
        if (!$this->requireEmail() || !$this->checkEmailExist() || !$this->requireOTP() || !$this->checkData()) {
            return false;
        } else {
            return true;
        }
    }

}

?>