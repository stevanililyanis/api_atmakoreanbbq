<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bahan;
use Validator;
use Illuminate\Support\Facades\DB;

class BahanController extends Controller
{
    public function index(){
        $bahan = DB::table('bahan')->where('is_deleted',0)->get();

        if(count($bahan)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$bahan
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_bahan){
        $bahan=Bahan::find($id_bahan);

        
        if(!is_null($bahan)){
            return response([
                'message'  => 'Retrieve Bahan Success',
                'data' => $bahan
            ],200);

        }

        return response([
            'message' => 'Bahan Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_bahan'=> 'required',
            'unit'=>'required',
            'harga_bahan'=>'required',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('bahan')->count() +1;
        $bahan = Bahan::create([
            'id_bahan'=>'BHN-'.$count,
            'nama_bahan'=>$request->nama_bahan,
            'unit'=>$request->unit,
            'harga_bahan'=> $request->harga_bahan,
        ]);
     
        return response([
            'message' => 'Add Bahan Success',
            'data' => $bahan,
        ],200);
   

    }

    public function update(Request $request, $id_bahan){
        $bahan = Bahan::find($id_bahan);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_bahan'=> 'max:255',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $bahan->nama_bahan = $storeData['nama_bahan'];
        $bahan->unit = $storeData['unit'];
        $bahan->harga_bahan = $storeData['harga_bahan'];

        if($bahan->save()){
            return response([
                'message' => 'Update Bahan Success',
                'data' => $bahan,
            ],200);
        }
        
        return response([
            'message' => 'Update Bahan Failed',
            'data' => null,
        ],400);
   
    }

    public function update_stock(Request $request, $id_bahan){
        $bahan = Bahan::find($id_bahan);
        $storeData = $request->all();

       
        if($request->tipe_stock=='Incoming'){
            $bahan->remaining_stock = $bahan->remaining_stock + $request->stock;
        }else{
            if($bahan->remaining_stock<$request->stock){
                return response([
                    'message' => 'Stock bahan tidak ada yang bisa di buang',
                    'data' => $bahan,
                ],400);
            }else{
                $bahan->remaining_stock = $bahan->remaining_stock - $request->stock;
            }
        }

        if($bahan->save()){
            return response([
                'message' => 'Update Bahan Success',
                'data' => $bahan,
            ],200);
        }
        
        return response([
            'message' => 'Update Bahan Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_bahan){
        $bahan = Bahan::find($id_bahan);

        if(is_null($bahan)){
            return response([
                'message' => 'Bahan Not Found',
                'data'=>null
            ],404);
        }

        $bahan->is_deleted=1;

        if($bahan->save()){
            return response([
                'message' => 'Delete Bahan Success',
                'data' =>$bahan,
            ],200);
        }
        return response([
            'message' => 'Delete Bahan Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_bahan){
        $bahan = Bahan::find($id_bahan);

            if(is_null($bahan)){
                return response([
                    'message' => 'Bahan Not Found',
                    'data'=>null
                ],404);
            }

            if($bahan->delete()){
                return response([
                    'message' => 'Delete Bahan Success',
                    'data' =>$bahan,
                ],200);
            }
            return response([
                'message' => 'Delete Bahan Failed',
                'data' => null,
            ],400);
        
    }
}
