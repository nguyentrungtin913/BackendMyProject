<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\DataHelper;
use App\Helpers\ResponseHelper;
use App\Transformers\UserTransformer;
use App\Validators\UserValidator;
use Auth;
use Session;

session_start();
use Illuminate\Support\Facades\Redirect;
class UserController extends Controller
{
    public function __construct(User $user, UserTransformer $userTransformer, UserValidator $userValidator, ResponseHelper $responseHelper)
    {
        $this->user= $user;
        $this->userTransformer = $userTransformer;
        $this->userValidator = $userValidator;
        $this->responseHelper = $responseHelper;

    }
    public function login()
    {
        return view('Login');
    }
    public function authenticate(Request $request)
    {
        $params = $request->all();
        $email = $params['email'];
        $password = md5($params['password']);
        $user = $this->user->where([['email',$email],['password',$password]] )->first();
        $token = sha1(time());
        if($user){
            $result = 
                    '{
                        "id" : '.$user->id.',
                        "name" : "'.$user->name.'",
                        "status" : "success",
                        "customer" : '.$user->role.',
                        "token" : "'.$token.'"
                    }';
            $user->token = $token;
            $user->save();
            return $result;
        }
        return '{
                    "status" : "fail",
                    "message" : "Email or Password not exactly"
                }';   
    }

    public function logout(Request $request)
    {
        return '{"status": "fail"}';
    }

    public function save(Request $request)
    {
        $params = $request->all();

        if (!$this->userValidator->setRequest($request)->store()) {
            $errors = $this->userValidator->getErrors();
          
            return compact('errors');
        }

        $pass = md5($params['password']);

        $user = $this->user->create([
            'name' => $params['name'],
            'date' => $params['date'],
            'sex' => $params['sex'],
            'address' => $params['address'],
            'phone' => $params['phone'],
            'role' => $params['role'],
            'email' => $params['email'],
            'password' => $pass,
        ]);

        $user = $this->userTransformer->transformItem($user);

        return $user;
    }
    public function index(Request $request)
    {
        $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];

        $orderBy = $this->user->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);
        $query = $this->user->filter($this->user::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);

        $users = $this->user->includes($query,$with)->get();

        $data = DataHelper::getList($query, $this->userTransformer,$perPage,'ListAlluser');
        $users= $this->userTransformer->transformCollection($query->get());
        return $data;
    }
    public function find(Request $request)
    {
         $params=$request->all();
         $id = $params['id'] ;
         $user = $this->user->where("id", $id)->first();
         if($user){
            $user= $this->userTransformer->transformItem($user);
            return $user;
         }
         return '{"data" : "Not Found"}';  
    }
    public function delete(Request $request)
    {
        $params=$request->all();
        $params=$this->user->revertAlias($params);
        return $params;
        
         $id = $params['id'] ;
         $user = $this->user->where("id", $id)->first();
         if($user){
            $user->delete();
            $user= $this->userTransformer->transformItem($user);
            $data = $this->responseHelper->success($user);
            return $data;
         }
         return '{"data" : "Not Found"}';  
    }
}
