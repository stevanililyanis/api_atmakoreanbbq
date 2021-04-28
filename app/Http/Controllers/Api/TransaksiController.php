<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaksi;

class TransaksiController extends Controller
{
    public function index(){
        $transaksi = DB::table('transaksi')->where('is_deleted',0)->get();

        if(count($transaksi)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$transaksi
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_transaksi){
        $transaksi = DB::table('transaksi')
                    ->where('is_deleted',0)
                    ->where('id_transaksi',$id_transaksi)
                    ->get();

        
        if(!is_null($transaksi)){
            return response([
                'message'  => 'Retrieve transaksi Success',
                'data' => $transaksi
            ],200);

        }

        return response([
            'message' => 'transaksi Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'tanggal_transaksi'=> 'required|date',
            'sub_total'=>'required',
            'tax'=>'required',
            'service'=>'required',
            'grand_total'=>'required',
            'metode_pembayaran'=>'required',
            'id_karyawan'=>'required',
            'id_pesanan'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

    
        $count= DB::table('transaksi')->count() +1;
        $grand_total=$request->sub_total-($request->service+$request->tax);
        $transaksi = Transaksi::create([
            'id_transaksi'=>'TRA-'.$count,
            'tanggal_transaksi'=>$request->tanggal_transaksi,
            'sub_total'=>$request->sub_total,
            'tax'=>$request->tax,
            'service'=>$request->service,
            'grand_total'=>$grand_total,
            'metode_pembayaran'=>$request->metode_pembayaran,
            'verifikasi_edc'=>$request->verifikasi_edc,
            'id_karyawan'=>$request->id_karyawan,
            'id_pesanan'=>$request->id_pesanan,
            'id_kartu'=>$request->id_kartu,
            
        ]);
        
        
        
        return response([
            'message' => 'Add transaksi Success',
            'data' => $transaksi,
        ],200);
   

    }

    public function update(Request $request, $id_transaksi){
        $transaksi = Transaksi::find($id_transaksi);
        $storeData = $request->all();


        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

      
        $transaksi->tanggal_transaksi=$storeData['tanggal_transaksi'];
        $transaksi->sub_total=$storeData['sub_total'];
        $transaksi->tax=$storeData['tax'];
        $transaksi->service=$storeData['service'];
        $transaksi->$grand_total=$storeData['grand_total'];
        $transaksi->metode_pembayaran=$storeData['metode_pembayaran'];
        $transaksi->verifikasi_edc=$storeData['verifikasi_edc'];
        $transaksi->id_karyawan=$storeData['id_karyawan'];
        $transaksi->id_pesanan=$storeData['id_pesanan'];
        $transaksi->id_kartu=$storeData['id_kartu'];

        if($transaksi->save()){
            return response([
                'message' => 'Update transaksi Success',
                'data' => $transaksi,
            ],200);
        }
        
        return response([
            'message' => 'Update transaksi Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_transaksi){
        $transaksi = Transaksi::find($id_transaksi);

        if(is_null($transaksi)){
            return response([
                'message' => 'transaksi Not Found',
                'data'=>null
            ],404);
        }

        $transaksi->is_deleted=1;

        if($transaksi->save()){
            return response([
                'message' => 'Delete transaksi Success',
                'data' =>$transaksi,
            ],200);
        }
        return response([
            'message' => 'Delete transaksi Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_transaksi){
        $transaksi = Transaksi::find($id_transaksi);

            if(is_null($transaksi)){
                return response([
                    'message' => 'transaksi Not Found',
                    'data'=>null
                ],404);
            }

            if($transaksi->delete()){
                return response([
                    'message' => 'Delete transaksi Success',
                    'data' =>$transaksi,
                ],200);
            }
            return response([
                'message' => 'Delete transaksi Failed',
                'data' => null,
            ],400);
        
    }
}
