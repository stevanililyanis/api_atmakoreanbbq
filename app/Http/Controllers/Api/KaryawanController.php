<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Karyawan;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\AuthController;

class KaryawanController extends Controller
{
    public function index(){
        $karyawan = Karyawan::all();

        if(count($karyawan)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$karyawan
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    public function show ($id_karyawan){
        $karyawan=DB::table('karyawan')->where('id_karyawan',$id_karyawan)->first();

        if(!is_null($karyawan)){
            return response([
                'message'  => 'Retrieve karyawan Success',
                'data' => $karyawan
            ],200);

        }

        return response([
            'message' => 'karyawan Not Found',
            'data' => null
        ],404);
    }

    public function findByEmail($email){
        $karyawan=DB::table('karyawan')
                    ->where('email',$email)->first();

        
        if(!is_null($karyawan)){
            return response([
                'message'  => 'Retrieve karyawan Success',
                'data' => $karyawan
            ],200);

        }

        return response([
            'message' => 'karyawan Not Found',
            'data' => null
        ],404);
    }
    
    public function store(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'nama_karyawan'=>'required|max:60',
            'tanggal_lahir'=>'required|date',
            'alamat_karyawan'=>'required|max:200',
            'kontak_karyawan'=>'required|max:13',
            'tanggal_masuk'=>'required|date|after_or_equal:today',
            'email'=>'required|email:rfc,dns|unique:karyawan',
            'id_jabatan'=>'required',
        ]);
        
        if($validate->fails())
            return response(['message'=>$validate->errors()],400); 

        $count= DB::table('karyawan')->count() +1;
        $registrationData['id_karyawan']= 'KAR-'.$count;
        $user = Karyawan::create($registrationData);
        $registerAccount= (new AuthController)->register($request);
        return response([
            'message'=>'Register Success',
            'karyawan'=>$user,
            'account'=>$registerAccount
        ],200);
    }


    public function update(Request $request, $id_karyawan){
        $karyawan = Karyawan::find($id_karyawan);
        if(is_null($karyawan)){
            return response([
                'message'=>'Karyawan Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'nama_karyawan'=> 'max:255',
            'tanggal_lahir'=>'date',
            'alamat_karyawan'=>'max:255',
            'kontak_karyawan'=>'max:13',
            'tanggal_masuk'=>'date',
            'email'=> 'email:rfc,dns',
            'id_jabatan'=>'max:10',
            
     
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);
     
        
        $karyawan->nama_karyawan = $updateData['nama_karyawan'];
        $karyawan->alamat_karyawan = $updateData['alamat_karyawan'];
        $karyawan->kontak_karyawan = $updateData['kontak_karyawan'];
        $karyawan->email = $updateData['email'];
        $karyawan->tanggal_lahir = $updateData['tanggal_lahir'];
        $karyawan->id_jabatan = $updateData['id_jabatan'];
        $karyawan->tanggal_masuk = $updateData['tanggal_masuk'];
        $karyawan->status_karyawan = $updateData['status_karyawan'];
        if($karyawan->save()){
            return response([
                'message' => 'Update Karyawan Success',
                'data'=> $karyawan,
            ],200);
        }

        return response([
            'messsage'=>'Update Karyawan Failed',
            'data'=>null,
        ],400);
    }

    public function updatePP(Request $request, $id_karyawan){
        $karyawan = Karyawan::find($id_karyawan);

        if(is_null($karyawan)){
            return response([
                'message'=>'Karyawan Not Found',
                'data'=>null
            ],404);
        }

        if($request->hasFile('profile_pict')) {
            $file = $request->file('profile_pict');
            $image = public_path().'/profile/';
            $file -> move($image, $file->getClientOriginalName());
            $image = '/profile/'.$file->getClientOriginalName();
            $karyawan->profile_pict=$image;
        }

            if($karyawan->save()){
                return response([
                    'message' => 'Update Profile Pict Karyawan Success',
                    'data'=> $karyawan,
                ],200);
            }
    
            return response([
                'messsage'=>'Update Profile Pict Karyawan Failed',
                'data'=>null,
            ],400);
    }

    public function updateStatus(Request $request, $id_karyawan){
        $karyawan = Karyawan::find($id_karyawan);
        if(is_null($karyawan)){
            return response([
                'message'=>'Karyawan Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'status_karyawan'=>'max:1'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);
     
        
        $karyawan->status_karyawan = $updateData['status_karyawan'];

        if($karyawan->save()){
            return response([
                'message' => 'Update Status Karyawan Success',
                'data'=> $karyawan,
            ],200);
        }

        return response([
            'messsage'=>'Update Status Karyawan Failed',
            'data'=>null,
        ],400);
    }

    

}
