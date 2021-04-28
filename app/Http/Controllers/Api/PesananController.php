<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pesanan;
use Validator;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(){
        $pesanan = DB::table('pesanan')->where('is_deleted',0)->get();

        if(count($pesanan)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$pesanan
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_pesanan){
        $pesanan=Pesanan::find($id_pesanan);

        
        if(!is_null($pesanan)){
            return response([
                'message'  => 'Retrieve Pesanan Success',
                'data' => $pesanan
            ],200);

        }

        return response([
            'message' => 'Pesanan Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
           // 'tanggal_pesanan'=> 'required|date',
            'id_reservasi'=>'required',
            'id_karyawan'=>'required'
        ]);
        
        $reservasi= DB::table('reservasi')
        ->where('id_reservasi', $request->id_reservasi)->get();

        if($validate->fails())
            return response(['reservasi'=>$reservasi[0]->tanggal_reservasi,'message'=> $validate->errors()],400);

        $count= DB::table('pesanan')->count() +1;
        $pesanan = Pesanan::create([
            'id_pesanan'=>'PSN-'.$count,
            'tanggal_pesanan'=> $reservasi[0]->tanggal_reservasi,
            'id_reservasi'=>$request->id_reservasi,
            'id_karyawan'=>$request->id_karyawan
        ]);
     
        return response([
            'message' => 'Add Pesanan Success',
            'data' => $pesanan,
            'reservasi'=>$reservasi
        ],200);
   

    }

    public function update(Request $request, $id_pesanan){
        $pesanan = Pesanan::find($id_pesanan);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'tanggal_pesanan'=> 'date|after_or_equal:today',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);
        
        if($storeData['id_karyawan']!=$pesanan->id_reservasi)
        {
            $reservasi=DB::table('reservasi')
                            ->where('id_reservasi',$storeData['id_reservasi'])->first();
            
            $pesanan->id_reservasi= $storeData['id_reservasi'];
            $pesanan->tanggal_pesanan= $reservasi->tanggal_reservasi;
        }
        
        $total_menu=DB::table('detil_pesanan')
                        ->select('id_menu')
                        ->where('id_pesanan',$pesanan->id_pesanan)
                        ->distinct()->count();
        $total_item=DB::table('detil_pesanan')
                        ->sum('jumlah_pesanan')
                        ->where('id_pesanan',$pesanan->id_pesanan);
        $total_harga=DB::table('detil_pesanan')
                        ->sum('subtotal_harga')
                        ->where('id_pesanan',$pesanan->id_pesanan);
        $pesanan->id_karyawan= $storeData['id_karyawan'];
        $pesanan->total_menu=$total_menu;
        $pesanan->total_item=$total_item;
        $pesanan->total_harga=$total_harga;
        
        

        if($pesanan->save()){
            return response([
                'message' => 'Update Pesanan Success',
                'data' => $pesanan,
            ],200);
        }
        
        return response([
            'message' => 'Update Pesanan Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_pesanan){
        $pesanan = Pesanan::find($id_pesanan);

        if(is_null($pesanan)){
            return response([
                'message' => 'Pesanan Not Found',
                'data'=>null
            ],404);
        }

        $pesanan->is_deleted=1;

        if($pesanan->save()){
            return response([
                'message' => 'Delete Pesanan Success',
                'data' =>$pesanan,
            ],200);
        }
        return response([
            'message' => 'Delete Pesanan Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_pesanan){
        $pesanan = Pesanan::find($id_pesanan);

            if(is_null($pesanan)){
                return response([
                    'message' => 'Pesanan Not Found',
                    'data'=>null
                ],404);
            }

            if($pesanan->delete()){
                return response([
                    'message' => 'Delete Pesanan Success',
                    'data' =>$pesanan,
                ],200);
            }
            return response([
                'message' => 'Delete Pesanan Failed',
                'data' => null,
            ],400);
        
    }
}
