<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Meja;
class MejaController extends Controller
{
    public function index(){
        $meja = Meja::all();

        if(count($meja)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$meja
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($no_meja){
        $meja=Meja::find($no_meja);

        
        if(!is_null($meja)){
            return response([
                'message'  => 'Retrieve Meja Success',
                'data' => $meja
            ],200);

        }

        return response([
            'message' => 'Meja Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'no_meja'=> 'required',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('meja')->count() +1;
        $meja = Meja::create([
            'no_meja'=>$request->no_meja,
            'status_meja'=>0,
        ]);
     
        return response([
            'message' => 'Add Meja Success',
            'data' => $meja,
        ],200);
   
    

        
    }

    public function update(Request $request, $no_meja){
        $meja = Meja::find($no_meja);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'no_meja'=> 'required|max:255'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $meja->status_meja = $storeData['status_meja'];

        if($meja->save()){
            return response([
                'message' => 'Update Meja Success',
                'data' => $meja,
            ],200);
        }
        
        return response([
            'message' => 'Update Meja Failed',
            'data' => null,
        ],400);
   
    }

    public function destroy($no_meja){
        $meja = Meja::find($no_meja);

            if(is_null($meja)){
                return response([
                    'message' => 'Meja Not Found',
                    'data'=>null
                ],404);
            }

            if($meja->delete()){
                return response([
                    'message' => 'Delete Meja Success',
                    'data' =>$meja,
                ],200);
            }
            return response([
                'message' => 'Delete Meja Failed',
                'data' => null,
            ],400);
        
    }
}
