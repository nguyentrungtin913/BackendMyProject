<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Random;
use Response;
use App\Helpers\DataHelper;
use App\Helpers\ResponseHelper;
use App\Validators\CodeOTPValidator;
use App\Transformers\CodeOTPTransformer;
use Mail;
use App\Mail\SendMail;
use App\Models\CodeOTP;
use App\Models\User;
class MailController extends Controller
{
    public function __construct(CodeOTP $codeOTP, CodeOTPValidator $codeOTPValidator, CodeOTPTransformer $codeOTPTransformer, User $user)
    {
        $this->codeOTP = $codeOTP;
        $this->codeOTPValidator = $codeOTPValidator;
        $this->codeOTPTransformer = $codeOTPTransformer;
        $this->user = $user;
    }

    public function sendMail(Request $request, Response $response)
    {
        $params=$request->all();
        $title = 'Mã xác thực tài khoản là '; 

        if (!$this->codeOTPValidator->setRequest($request)->sendMail()) {
            $errors = $this->codeOTPValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $otp = rand(1000,9999);
        $mailTo = $params['email'];
        $name = $params['name'];
        $timeEnd = time() + (60 * 5);
        $sendmail = Mail::to($mailTo)->send(new SendMail($title, $otp, $name)); //send mail

        if (empty($sendmail)) 
            { 
                $email = $this->codeOTP->where('code_otp_email', $mailTo)->first();
                if($email){
                    $codeOTP = $email->update([
                        'code_otp_num'      => $otp,
                        'code_otp_expired'  => $timeEnd
                    ]);
                    if($codeOTP){
                        $email = $this->codeOTPTransformer->transformItem($email);
                        return ResponseHelper::success($response, $email);
                    }else{
                         return ResponseHelper::requestFailed($response); 
                    }
                }else{
                    $codeOTP = $this->codeOTP->create([
                        'code_otp_email'    => $mailTo,
                        'code_otp_num'      => $otp,
                        'code_otp_expired'  => $timeEnd
                    ]);
                    if($codeOTP){
                        $codeOTP = $this->codeOTPTransformer->transformItem($codeOTP);
                        return ResponseHelper::success($response, $codeOTP);
                    }else{
                        return ResponseHelper::requestFailed($response); 
                    }
                }        
            }else{
                return response()->json(['message' => 'Mail Sent fail'], 400); 
            } 
    }

    public function activateAccount(Request $request, Response $response)
    {
        $params=$request->all();
        if (!$this->codeOTPValidator->setRequest($request)->activate()) {
            $errors = $this->codeOTPValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $mailTo = $request->get('email') ?? null;

        if($this->user->where('user_email', $mailTo)->update(['user_activated' => 1])){
            return response()->json(['message' => 'Activated successfully'], 200);     
        }
        return ResponseHelper::requestFailed($response);
    }
       
}
