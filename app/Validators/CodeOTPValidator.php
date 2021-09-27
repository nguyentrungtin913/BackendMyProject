<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\CodeOTP;
class CodeOTPValidator extends BaseValidator
{
   
    public function __construct(CodeOTP $codeOTP)
    {
        $this->codeOTP = $codeOTP;
    }

    public function requireEmail()
    {
        return $this->requireParam('email', 'Please enter email');
    }

    public function store()
    {
        if (!$this->requireEmail()) {
            return false;
        } else {
            return true;
        }
    }


    public function requireOTP()
    {
        return $this->requireParam('otp', 'Please enter OTP');
    }

    public function checkData()
    {
        $mailTo = $this->request->get('email') ?? null;
        $otp = $this->request->get('otp') ?? 0;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time = time();

        $email = $this->codeOTP->where('code_otp_email', $mailTo)->first();
         if($email){
            if($email->code_otp_num == $otp){
                if($email->code_otp_end > $time){
                    return true;
                }else{
                    $this->setError(400, 'invalid', "OTP expired", 'Error');
                }
            }else{
                $this->setError(400, 'invalid', "OTP invalid", 'Error');
            }
         }else{
            $this->setError(400, 'invalid', "Email invalid", 'Error');
         }
    }

    public function find()
    {
        if (!$this->requireEmail() || !$this->requireOTP() || !$this->checkData()) {
            return false;
        } else {
            return true;
        }
    }

}

?>