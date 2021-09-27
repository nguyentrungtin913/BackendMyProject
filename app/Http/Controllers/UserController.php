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
use Response;
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
    public function authenticate(Request $request, Response $response)
    {
        $params = $request->all();
        $email = $params['email'];
        $password = md5($params['password']);
        $user = $this->user->where([['user_email',$email],['user_password',$password]] )->first();
        $token = sha1(time());
        if($user){
            $result = [
                        'id'        => $user->user_id,
                        'name'      => $user->user_name,
                        'status'    => 'success',
                        'customer'  => $user->user_role,
                        'token'     => $token
                    ];
            if($this->user->where(['user_email'=>$email])->update(['user_token'=>$token])){
                return ResponseHelper::success($response, $result);
            }else{
                return ResponseHelper::requestFailed($response);
            } 
             
        }else{
            $errors =   [
                            'status' => 404,
                            'errors' => [
                                            [
                                                'status'    => 'fail',
                                                'msg'       => 'Email or Password not exactly',
                                                'clientMsg' => 'Email or Password not exactly'
                                            ]
                                        ]
                        ]; 
            return ResponseHelper::errors($response, $errors);
        }
    }

    public function logout(Request $request)
    {
        $errors =   [
                            'status' => 404,
                            'errors' => [
                                            [
                                                'status'    => 'fail',
                                            ]
                                        ]
                        ]; 
            return ResponseHelper::errors($response, $errors);
    }

    public function save(Request $request, Response $response)
    {
        $params = $request->all();

        if (!$this->userValidator->setRequest($request)->store()) {
            $errors = $this->userValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
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

        return ResponseHelper::success($response, $user);
    }
    public function index(Request $request, Response $response)
    {
        $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];

        $orderBy = $this->user->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);
        $query = $this->user->filter($this->user::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);

        $users = $this->user->includes($query,$with)->get();

        $data = DataHelper::getList($query, $this->userTransformer,$perPage,'ListAlluser');
        return ResponseHelper::success($response, $data);

    }
    public function find(Request $request, Response $response)
    {
        $params=$request->all();
        if (!$this->userValidator->setRequest($request)->checkUserExist()) {
            $errors = $this->userValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        $id = $params['userId'] ;
        $user = $this->user->where("user_id", $id)->first();
        if($user){
            $user= $this->userTransformer->transformItem($user);
            return ResponseHelper::success($response, $user);
        }
        return ResponseHelper::requestFailed($response); 
    }
    public function delete(Request $request, Response $response)
    {
        // $params=$this->user->revertAlias($params);
        // return $params;
        $params=$request->all();
        if (!$this->userValidator->setRequest($request)->checkUserExist()) {
            $errors = $this->userValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $userId = $params['userId'] ;
        $user = $this->user->where("user_id", $userId)->first();
        if($user){
            //$user->delete();
            $this->user->where('user_id', $userId)->update(['user_deleted' => 1]);
            $data= $this->userTransformer->transformItem($user);
            return ResponseHelper::success($response, $data);
        }
        return ResponseHelper::requestFailed($response); 
    }
}
