<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;

use Validator;
use Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','login', 'register']]);
    }
    
    public function index(){
        $response_status=200;
        $status=true;
        $response['message'] = 'Welcome User';
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function register(Request $request){
        $status=false;
        $response_status=401;
        $response=array();

        $rules=[ 
            'firstname' => ['required','alpha'],
            'lastname' => ['required','alpha'],
            'mobile' => ['required','numeric','regex:/^([0-9\s\-\+\(\)]*)$/','min:10','unique:users'],
            'email' => ['required','email','unique:users'],
            'password' => ['required','min:6'],
            'age' => ['required','numeric'],
            'gender' => ['required','in:m,f,o'],
            'city' => ['required','alpha']
        ];

        $validation = Validator::make($request->all(),$rules);
        if ($validation->fails()) {
            $response['error'] = $validation->errors();
        }else{
            $save_data=[
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'gender' => $request->gender,
                'city' => $request->city
            ];
            $userData=User::create($save_data);
            if($userData){
                $response_status=200;
                $status=true;
                $response['message'] = 'Register successfully';
                $response['data'] = $userData;
            }else{
                $response['message'] = 'Registration failed';
            }
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function login(Request $request){
        $status=false;
        $response_status=401;
        $response=array();

        $rules=[ 
            'email' => ['required','email'],
            'password' => ['required','min:6']
        ];

        $validation = Validator::make($request->all(),$rules);
        if ($validation->fails()) {
            $response['error'] = $validation->errors();
        }else{
            // $user_data=User::where('email',$request->email)->first();
            // if(!$user_data || !Hash::check($request->password,$user_data->password)){
            //     $response['message'] = 'Invalid login credentials';
            // }else{
            //     $response_status=200;
            //     $status=true;
            //     $response['message'] = 'Login successfully';
            //     $response['data'] = $user_data;
            // }
            if (!$token = auth()->attempt($validation->validated())) {
                $response['message'] = 'Invalid login credentials';
            }else{
                $response_status=200;
                $status=true;
                $response['message'] = 'Login successfully';
                $response['data'] = [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,
                    'user' => auth()->user()
                ];
            }
        }
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function userProfile()
    {
        $response_status=200;
        $status=true;
        $response['user'] = auth()->user();
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function newAccessToken()
    {
        $response_status=200;
        $status=true;
        $response['message'] = 'New token generated successfully';
        $response['data'] = [
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }

    public function logout()
    {
        auth()->logout();
        $response_status=200;
        $status=true;
        $response['message'] = 'Logout successfully.';
        return response()->json(array('status'=>$status,'response' => $response),$response_status);
    }
    
}
