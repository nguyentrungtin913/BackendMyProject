<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\DataHelper;
use App\Helpers\ResponseHelper;
use App\Transformers\UserTransformer;
use App\Validators\UserValidator;
use App\Validators\AuthValidator;
use Auth;
use Session;
use Response;
session_start();

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\MailController;
class UserController extends Controller
{

    public function __construct(User $user, UserTransformer $userTransformer, UserValidator $userValidator, AuthValidator $authValidator, ResponseHelper $responseHelper, MailController $mailController)
    {
        $this->user= $user;
        $this->userTransformer = $userTransformer;
        $this->userValidator = $userValidator;
        $this->authValidator = $authValidator;
        $this->responseHelper = $responseHelper;
        $this->mailController = $mailController;
    }
    // public function login()
    // {
    //     return view('Login');
    // }
    public function authenticate(Request $request, Response $response)
    {
        if (!$this->authValidator->setRequest($request)->auth()) {
            $errors = $this->authValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }

        $params = $request->all();
        $email = $params['email'];
        $user = $this->user->where(['user_email' => $email])->first();
        if($user){
            $user = $this->userTransformer->transformItem($user);
            return ResponseHelper::success($response, $user);
        }
        return ResponseHelper::requestFailed($response);
    }

    public function logout(Request $request)
    {
        dd(response()->json($request->headers));
        $errors = [
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
            'user_name'      => $params['name'],
            'user_date'      => $params['date'],
            'user_sex'       => $params['sex'],
            'user_address'   => $params['address'],
            'user_phone'     => $params['phone'],
            'user_role'      => $params['role'],
            'user_email'     => $params['email'],
            'user_password'  => $pass,
        ]);
        if($user){
            $this->mailController->sendMail($request, $response);
            $user = $this->userTransformer->transformItem($user);
            return ResponseHelper::success($response, $user);
        }
        return ResponseHelper::requestFailed($response); 
        
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

    public function resetPassword(Request $request, Response $response)
    {
        $params=$request->all();
        if (!$this->userValidator->setRequest($request)->resetPassword()) {
            $errors = $this->userValidator->getErrors();
            return ResponseHelper::errors($response, $errors);
        }
        $pass = md5($params['password']);
        $email = $params['email'];
        if($this->user->where('user_email', $email)->update(['user_password'=> $pass])){
             return response()->json(['message' => 'Password reset successfully'], 200);   
        }
        return ResponseHelper::requestFailed($response); 
    }
}
