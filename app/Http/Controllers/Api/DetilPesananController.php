<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Detil_Pesanan;
use Validator;
use Illuminate\Support\Facades\DB;

class DetilPesananController extends Controller
{
    public function index(){
        $detil_pesanan = DB::table('detil_pesanan')->where('is_deleted',0)->get();

        if(count($detil_pesanan)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$detil_pesanan
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_detil_pesanan){
        $detil_pesanan = DB::table('detil_pesanan')
                    ->where('is_deleted',0)
                    ->where('id_detil_pesanan',$id_detil_pesanan)
                    ->get();

        
        if(!is_null($detil_pesanan)){
            return response([
                'message'  => 'Retrieve Detil_Pesanan Success',
                'data' => $detil_pesanan
            ],200);

        }

        return response([
            'message' => 'Detil_Pesanan Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'jumlah_pesanan'=> 'required',
            'id_menu'=>'required',
            'id_pesanan'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('detil_pesanan')->count() +1;
        $menu=DB::table('menu')->where('id_menu',$request->id_menu)->first();
        $detil_pesanan = Detil_Pesanan::create([
            'id_detil_pesanan'=>'DPSN-'.$count,
            'jumlah_pesanan'=>$storeData->jumlah_pesanan,
            'subtotal_harga'=>$storeData->jumlah_pesanan * $menu->harga_menu,
            'id_pesanna'=>$storeData->id_pesanan,
            'id_menu'=>$storeData->id_menu
        ]);
     
        return response([
            'message' => 'Add Detil_Pesanan Success',
            'data' => $detil_pesanan,
        ],200);
   

    }
}
