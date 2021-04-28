<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jabatan;
use Validator;
use Illuminate\Support\Facades\DB;
class JabatanController extends Controller
{
    public function index(){
        $jabatan = Jabatan::all();

        if(count($jabatan)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$jabatan
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_jabatan){
        $jabatan=Jabatan::find($id_jabatan);

        
        if(!is_null($jabatan)){
            return response([
                'message'  => 'Retrieve Jabatan Success',
                'data' => $jabatan
            ],200);

        }

        return response([
            'message' => 'Jabatan Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_jabatan'=> 'required|max:255'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('jabatan')->count() +1;
        $jabatan = Jabatan::create([
            'id_jabatan'=>'JAB-'.$count,
            'nama_jabatan'=>$request->nama_jabatan,
        ]);
     
        return response([
            'message' => 'Add Jabatan Success',
            'data' => $jabatan,
        ],200);
   
    

        
    }

    public function update(Request $request, $id_jabatan){
        $jabatan = Jabatan::find($id_jabatan);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_jabatan'=> 'required|max:255'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $jabatan->nama_jabatan = $storeData['nama_jabatan'];

        if($jabatan->save()){
            return response([
                'message' => 'Update Jabatan Success',
                'data' => $jabatan,
            ],200);
        }
        
        return response([
            'message' => 'Update Jabatan Failed',
            'data' => null,
        ],400);
   
    }

    public function destroy($id_jabatan){
        $jabatan = Jabatan::find($id_jabatan);

            if(is_null($jabatan)){
                return response([
                    'message' => 'Jabatan Not Found',
                    'data'=>null
                ],404);
            }

            if($jabatan->delete()){
                return response([
                    'message' => 'Delete Jabatan Success',
                    'data' =>$jabatan,
                ],200);
            }
            return response([
                'message' => 'Delete Jabatan Failed',
                'data' => null,
            ],400);
        
    }
}
