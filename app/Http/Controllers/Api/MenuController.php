<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Menu;
use Validator;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(){
        $menu = DB::table('menu')->where('is_deleted',0)->get();
        $bahan = DB::table('bahan')->get();
        for($i=0;$i<count($menu);$i++){
            for($j=0;$j<count($bahan);$j++)
            {
                if($menu[$i]->id_bahan==$bahan[$j]->id_bahan){
                    if($menu[$i]->serving_size>$bahan[$j]->remaining_stock){
                        $menu[$i]->ketersediaan=0;
                        DB::table('menu')->where('id_menu', $menu[$i]->id_menu)
                        ->update(['ketersediaan'=>0]);
                    }else{
                        $menu[$i]->ketersediaan=1;
                        DB::table('menu')->where('id_menu', $menu[$i]->id_menu)
                        ->update(['ketersediaan'=>1]);
                    }

                    if($bahan[$j]->is_deleted==1){
                        $menu[$i]->ketersediaan=0;
                        DB::table('menu')->where('id_menu', $menu[$i]->id_menu)
                        ->update(['ketersediaan'=>0]);
                    }
                }
            }
        }
        
        if(count($menu)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$menu
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_menu){
        $menu = DB::table('menu')
                    ->where('is_deleted',0)
                    ->where('id_menu',$id_menu)
                    ->get();

        
        if(!is_null($menu)){
            return response([
                'message'  => 'Retrieve Menu Success',
                'data' => $menu
            ],200);

        }

        return response([
            'message' => 'Menu Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_menu'=> 'required',
            'deskripsi_menu'=>'required',
            'harga_menu'=>'required',
            'unit'=>'required',
            'serving_size'=> 'required',
            'id_bahan'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $bahan = DB::table('bahan')
            ->where('is_deleted',0)
            ->where('id_bahan',$request->id_bahan)
            ->first();
            if($bahan->remaining_stock>=$request->serving_size){
                $ketersediaan=1;  
            }
            else{
                $ketersediaan=0;
            }
           
        if($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $image = public_path().'/menu/';
            $file -> move($image, $file->getClientOriginalName());
            $image = '/menu/'.$file->getClientOriginalName();
        }else
            $image=null;
    
        $count= DB::table('menu')->count() +1;
        $menu = Menu::create([
            'id_menu'=>'MENU-'.$count,
            'nama_menu'=>$request->nama_menu,
            'deskripsi_menu'=>$request->deskripsi_menu,
            'harga_menu'=>$request->harga_menu,
            'unit'=>$request->unit,
            'serving_size'=>$request->serving_size,
            'id_bahan'=>$request->id_bahan,
            'ketersediaan'=>$ketersediaan,
            'gambar'=>$image
        ]);
        
        
        
        return response([
            'message' => 'Add Menu Success',
            'data' => $menu,
        ],200);
   

    }

    public function update(Request $request, $id_menu){
        $menu = Menu::find($id_menu);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_menu'=> 'max:255',
            'deskripsi_menu'=> 'max:255'
        ]);
        if($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $image = public_path().'/menu/';
            $file -> move($image, $file->getClientOriginalName());
            $image = '/menu/'.$file->getClientOriginalName();
            $menu->gambar=$image;
        }else
            $image=null;

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $menu->nama_menu = $storeData['nama_menu'];
        $menu->deskripsi_menu= $storeData['deskripsi_menu'];
        $menu->harga_menu = $storeData['harga_menu'];
        $menu->serving_size= $storeData['serving_size'];
        $menu->unit = $storeData['unit'];
        $menu->id_bahan= $storeData['id_bahan'];
        $menu->ketersediaan = $storeData['ketersediaan'];

        if($menu->save()){
            return response([
                'message' => 'Update Menu Success',
                'data' => $menu,
            ],200);
        }
        
        return response([
            'message' => 'Update Menu Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_menu){
        $menu = Menu::find($id_menu);

        if(is_null($menu)){
            return response([
                'message' => 'Menu Not Found',
                'data'=>null
            ],404);
        }

        $menu->is_deleted=1;

        if($menu->save()){
            return response([
                'message' => 'Delete Menu Success',
                'data' =>$menu,
            ],200);
        }
        return response([
            'message' => 'Delete Menu Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_menu){
        $menu = Menu::find($id_menu);

            if(is_null($menu)){
                return response([
                    'message' => 'Menu Not Found',
                    'data'=>null
                ],404);
            }

            if($menu->delete()){
                return response([
                    'message' => 'Delete Menu Success',
                    'data' =>$menu,
                ],200);
            }
            return response([
                'message' => 'Delete Menu Failed',
                'data' => null,
            ],400);
        
    }
}
