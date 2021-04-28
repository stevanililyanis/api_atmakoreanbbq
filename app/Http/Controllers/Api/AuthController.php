<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'email'=>'required|email:rfc,dns|unique:users',
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],400); 

        $registrationData['password'] = bcrypt($request->tanggal_lahir); 
        $user = User::create($registrationData);
  
        return response([
            'message'=>'Register Success',
            'karyawan'=>$user,
        ],200);
    }

    public function updatePass(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'password'=>'required',
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],400); 

        $registrationData['password'] = bcrypt($request->password); 
        $user=DB::table('users') 
            ->where('email', $request->user()->email)
            ->update(['password'=>$registrationData['password']]);
  
        return response([
            'message'=>'Update Password Success',
            'karyawan'=>$user,
        ],200);
    }

    public function show(Request $request){
        $user=DB::table('users')
        ->where('email', $request->user()->email)
        ->first();

        if(!$user==null){
            $user['password']=crypt($user->password);
            return response([
                'message' => 'Retrieve karyawan sucess',
                'data' => $user
            ],200);
        }

        return response([
            'message' => 'karyawan Not Found',
            'data' => null
        ],404);
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'email'=>'required|email:rfc,dns',
            'password'=>'required'
        ]);

        $status = DB::table('karyawan')->where('email',$loginData['email'])->first();

        if($status->status_karyawan==0){
            return response(['message'=>'Akun karyawan sudah tidak aktif'],401);
        }

        if($validate->fails())
            return response(['message'=>$validate->errors()],400);

        if(!Auth::attempt($loginData))
            return response(['message'=>'Invalid Credentials'],401);

        $user = Auth::user();

        $token = $user->createToken('Authenticaton Token')->accessToken;

        return response([
            $karyawan = DB::table('users')
                    ->where('email', $request['email'])
                    ->update(['remember_token'=>bcrypt($token)]),
            'message'=>'Authenticated',
            'user'=>$user,
            'token_type'=>'Bearer',
            'access_token'=>$token
        ]);
       
    }
    public function logout(Request $request){
  
        $request->user()->token()->revoke();
        
        $karyawan = DB::table('users')
                    ->where('email', $request->user()->email)
                    ->update(['remember_token'=>null]);

        return response()->json([
            'message'=>'Succesfully logged out'
        ]);
    }
}
