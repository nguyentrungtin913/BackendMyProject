<?php


namespace App\Validators;
use Illuminate\Http\Request;
use App\Models\User;

class AuthValidator extends BaseValidator
{
    public function __construct(User $user)
    {
        $this->user= $user;
    }

    public function auth()
    {
        $params = $this->request->all();
        $email = $params['email'];
        $password = md5($params['password']);
        $user = $this->user->where(['user_email' => $email] )->first();
        if($user){
            if($user->user_password == $password){
                if($user->user_activated != 0){
                    $token = sha1(time());
                    $tokenExpired = time() + (3600 * 24);

                    if($user->update([
                            'user_token'            => $token,
                            'user_token_expired'    =>$tokenExpired
                        ]   
                    )){
                        return true;
                    }else{
                        $this->errors[400][] = [
                        'code' => 'set_token_fail',
                        'msg' => 'Can not create token',
                        'clientMsg' => 'Can not create token'
                        ];
                        return false;
                    } 

                }else{
                    $this->errors[400][] = [
                    'code' => 'not_activated_user',
                    'msg' => 'Your account is not activated. Please activate your account before you sign!',
                    'clientMsg' => 'Your account is not activated. Please activate your account before you sign!'
                    ];
                    return false;
                }
            }else{
                $this->errors[400][] = [
                    'code' => 'user_not_existed',
                    'msg' => 'Your password is incorrect',
                    'clientMsg' => 'Your password is incorrect'
                ];
                return false;
            }
        }else{
            $this->errors[400][] = [
                    'code' => 'user_not_existed',
                    'msg' => 'Your email is incorrect',
                    'clientMsg' => 'Your email is incorrect'
                ];
            return false;
        } 
    }
   
}

?>