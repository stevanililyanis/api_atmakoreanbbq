<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kartu;
use Validator;
use Illuminate\Support\Facades\DB;

class KartuController extends Controller
{
    
    public function index(){
        $kartu = DB::table('kartu')->where('is_deleted',0)->get();

        if(count($kartu)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$kartu
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_kartu){
        $kartu=Kartu::find($id_kartu);

        
        if(!is_null($kartu)){
            return response([
                'message'  => 'Retrieve Kartu Success',
                'data' => $kartu
            ],200);

        }

        return response([
            'message' => 'Kartu Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_kartu'=> 'max:255',
            'jenis_kartu'=>'required',
            'nomor_kartu'=> 'required',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('kartu')->count() +1;
        $kartu = Kartu::create([
            'id_kartu'=>'KRT-'.$count,
            'nama_kartu'=>$request->nama_kartu,
            'jenis_kartu'=>$request->jenis_kartu,
            'nomor_kartu'=> $request->nomor_kartu
        ]);
     
        return response([
            'message' => 'Add Kartu Success',
            'data' => $kartu,
        ],200);
   

    }

    public function update(Request $request, $id_kartu){
        $kartu = Kartu::find($id_kartu);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_kartu'=> 'max:255',
            'jenis_kartu'=>'max:255',
            'nomor_kartu'=> 'min:12'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $kartu->nama_kartu = $storeData['nama_kartu'];
        $kartu->jenis_kartu = $storeData['jenis_kartu'];
        $kartu->nomor_kartu = $storeData['nomor_kartu'];


        if($kartu->save()){
            return response([
                'message' => 'Update Kartu Success',
                'data' => $kartu,
            ],200);
        }
        
        return response([
            'message' => 'Update Kartu Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_kartu){
        $kartu = Kartu::find($id_kartu);

        if(is_null($kartu)){
            return response([
                'message' => 'Kartu Not Found',
                'data'=>null
            ],404);
        }

        $kartu->is_deleted=1;

        if($kartu->save()){
            return response([
                'message' => 'Delete Kartu Success',
                'data' =>$kartu,
            ],200);
        }
        return response([
            'message' => 'Delete Kartu Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_kartu){
        $kartu = Kartu::find($id_kartu);

            if(is_null($kartu)){
                return response([
                    'message' => 'Kartu Not Found',
                    'data'=>null
                ],404);
            }

            if($kartu->delete()){
                return response([
                    'message' => 'Delete Kartu Success',
                    'data' =>$kartu,
                ],200);
            }
            return response([
                'message' => 'Delete Kartu Failed',
                'data' => null,
            ],400);
        
    }
}
