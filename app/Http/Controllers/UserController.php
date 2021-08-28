<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Session;

session_start();
use Illuminate\Support\Facades\Redirect;
class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user= $user;
    }
    public function login()
    {
        return view('Login');
    }
    public function authenticate(Request $request)
    {
        $param = $request->all();
        if (Auth::attempt(
            [
                 'email' => $param['email'],
                 'password' => $param['password'],
            ])) {
            $user = $this->user->where('email',$param['email'])->first();
            $request->session()->put('user_id', $user->id);
            return Redirect::to('/all');
        }else{
            dd("fail") ;
        }
    }
}
