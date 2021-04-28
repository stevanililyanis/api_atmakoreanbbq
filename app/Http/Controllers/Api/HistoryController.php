<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\History;
use App\Bahan;
use App\Http\Controllers\Api\BahanController;
use Validator;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index(){
        $history = DB::table('history')->where('is_deleted',0)->get();

        if(count($history)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$history
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_history){
        $history = DB::table('history')
                    ->where('is_deleted',0)
                    ->where('id_history',$id_history)
                    ->get();

        
        if(!is_null($history)){
            return response([
                'message'  => 'Retrieve History Success',
                'data' => $history
            ],200);

        }

        return response([
            'message' => 'History Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'tipe_stock'=> 'required',
            'stock'=>'required',
            'harga_bahan'=>'required',
            'tanggal'=> 'required|date|after_or_equal:today',
            'id_bahan'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('history')->count() +1;

       
        $bahan = (new BahanController)->update_stock($request, $request->id_bahan);
        
        $history = History::create([
            'id_history'=>'HIS-'.$count,
            'tipe_stock'=>$request->tipe_stock,
            'stock'=>$request->stock,
            'harga_bahan'=> $request->harga_bahan,
            'tanggal'=>$request->tanggal,
            'id_bahan'=>$request->id_bahan
        ]);
     
        return response([
            'message' => 'Add History Success',
            'data' => $history,
            'bahan'=> $bahan
        ],200);
   

    }

    public function update(Request $request, $id_history){
        $history = History::find($id_history);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'tanggal'=> 'date|after_or_equal:today',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $history->tipe_stock = $storeData['tipe_stock'];
        $history->stock = $storeData['stock'];
        $history->harga_bahan = $storeData['harga_bahan'];
        $history->tanggal = $storeData['tanggal'];
        $history->id_bahan= $storeData['id_bahan'];

        if($history->save()){
            return response([
                'message' => 'Update History Success',
                'data' => $history,
            ],200);
        }
        
        return response([
            'message' => 'Update History Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_history){
        $history = History::find($id_history);

        if(is_null($history)){
            return response([
                'message' => 'History Not Found',
                'data'=>null
            ],404);
        }

        $history->is_deleted=1;

        if($history->save()){
            return response([
                'message' => 'Delete History Success',
                'data' =>$history,
            ],200);
        }
        return response([
            'message' => 'Delete History Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_history){
        $history = History::find($id_history);

            if(is_null($history)){
                return response([
                    'message' => 'History Not Found',
                    'data'=>null
                ],404);
            }

            if($history->delete()){
                return response([
                    'message' => 'Delete History Success',
                    'data' =>$history,
                ],200);
            }
            return response([
                'message' => 'Delete History Failed',
                'data' => null,
            ],400);
        
    }
}
