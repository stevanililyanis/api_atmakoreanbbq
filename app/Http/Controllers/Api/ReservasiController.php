<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Reservasi;
use Validator;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    public function index(){
        $reservasi = DB::table('reservasi')->where('is_deleted',0)->get();

        if(count($reservasi)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$reservasi
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_reservasi){
        $reservasi = DB::table('reservasi')
                    ->where('is_deleted',0)
                    ->where('id_reservasi',$id_reservasi)
                    ->get();
        
        if(!is_null($reservasi)){
            return response([
                'message'  => 'Retrieve Reservasi Success',
                'data' => $reservasi
            ],200);

        }

        return response([
            'message' => 'Reservasi Not Found',
            'data' => null
        ],404);
    }

    public function showByDate ($date, $sesi){
        $reservasi = DB::table('reservasi')
                    ->where('tanggal_reservasi',$date)
                    ->where('sesi_reservasi',$sesi)
                    ->where('is_deleted',0)
                    ->get();
        
        if(!is_null($reservasi)){
            return response([
                'message'  => 'Retrieve Reservasi Success',
                'data' => $reservasi
            ],200);

        }
        return response([
            'message' => 'Reservasi Not Found',
            'data' => null
        ],404);
    }
    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'tanggal_reservasi'=> 'required|date|after_or_equal:today',
            'sesi_reservasi'=>'required',
            'no_meja'=>'required',
            'id_customer'=>'required',
           
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('reservasi')->count() +1;
        $reservasi = Reservasi::create([
            'id_reservasi'=>'RES-'.$count,
            'tanggal_reservasi'=> $request->tanggal_reservasi,
            'sesi_reservasi'=>$request->sesi_reservasi,
            'no_meja'=>$request->no_meja,
            'id_customer'=>$request->id_customer
          
        ]);
     
        return response([
            'message' => 'Add Reservasi Success',
            'data' => $reservasi,
        ],200);
   

    }

    public function update(Request $request, $id_reservasi){
        $reservasi = Reservasi::find($id_reservasi);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'tanggal_reservasi'=> 'date|after_or_equal:today',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $reservasi->tanggal_reservasi = $storeData['tanggal_reservasi'];
        $reservasi->sesi_reservasi = $storeData['sesi_reservasi'];
        $reservasi->no_meja = $storeData['no_meja'];
        $reservasi->id_customer = $storeData['id_customer'];


        if($reservasi->save()){
            return response([
                'message' => 'Update Reservasi Success',
                'data' => $reservasi,
            ],200);
        }
        
        return response([
            'message' => 'Update Reservasi Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_reservasi){
        $reservasi = Reservasi::find($id_reservasi);

        if(is_null($reservasi)){
            return response([
                'message' => 'Reservasi Not Found',
                'data'=>null
            ],404);
        }

        $reservasi->is_deleted=1;

        if($reservasi->save()){
            return response([
                'message' => 'Delete Reservasi Success',
                'data' =>$reservasi,
            ],200);
        }
        return response([
            'message' => 'Delete Reservasi Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_reservasi){
        $reservasi = Reservasi::find($id_reservasi);

            if(is_null($reservasi)){
                return response([
                    'message' => 'Reservasi Not Found',
                    'data'=>null
                ],404);
            }

            if($reservasi->delete()){
                return response([
                    'message' => 'Delete Reservasi Success',
                    'data' =>$reservasi,
                ],200);
            }
            return response([
                'message' => 'Delete Reservasi Failed',
                'data' => null,
            ],400);
        
    }
}
