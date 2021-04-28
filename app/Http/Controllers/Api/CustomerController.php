<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(){
        $customer = DB::table('customer')->where('is_deleted',0)->get();

        if(count($customer)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$customer
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    
    public function show ($id_customer){
        $customer = DB::table('customer')
                    ->where('is_deleted',0)
                    ->where('id_customer',$id_customer)
                    ->get();
        
        if(!is_null($customer)){
            return response([
                'message'  => 'Retrieve Customer Success',
                'data' => $customer
            ],200);

        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ],404);
    }
    public function showByName (Request $request){
        $name= $request->all();
        $customer = DB::table('customer')
                    ->where('is_deleted',0)
                    ->where('nama_customer', $name['nama_customer'])
                    ->get();

        
        if(!is_null($customer)){
            return response([
                'message'  => 'Retrieve Customer Success',
                'data' => $customer
            ],200);

        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ],404);
    }

    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_customer'=> 'required|max:255',
            'kontak_customer'=>'max:13',
            
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('customer')->count() +1;
        $customer = Customer::create([
            'id_customer'=>'CUS-'.$count,
            'nama_customer'=>$request->nama_customer,
            'kontak_customer'=>$request->kontak_customer,
            'email_customer'=> $request->email_customer
        ]);
     
        return response([
            'message' => 'Add Customer Success',
            'data' => $customer,
        ],200);
   

    }

    public function update(Request $request, $id_customer){
        $customer = Customer::find($id_customer);
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_customer'=> 'required|max:255',
            'kontak_customer'=>'max:13'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $customer->nama_customer = $storeData['nama_customer'];
        $customer->email_customer = $storeData['email_customer'];
        $customer->kontak_customer = $storeData['kontak_customer'];


        if($customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer,
            ],200);
        }
        
        return response([
            'message' => 'Update Customer Failed',
            'data' => null,
        ],400);
   
    }

    public function delete($id_customer){
        $customer = Customer::find($id_customer);

        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data'=>null
            ],404);
        }

        $customer->is_deleted=1;

        if($customer->save()){
            return response([
                'message' => 'Delete Customer Success',
                'data' =>$customer,
            ],200);
        }
        return response([
            'message' => 'Delete Customer Failed',
            'data' => null,
        ],400);
    }

    public function destroy($id_customer){
        $customer = Customer::find($id_customer);

            if(is_null($customer)){
                return response([
                    'message' => 'Customer Not Found',
                    'data'=>null
                ],404);
            }

            if($customer->delete()){
                return response([
                    'message' => 'Delete Customer Success',
                    'data' =>$customer,
                ],200);
            }
            return response([
                'message' => 'Delete Customer Failed',
                'data' => null,
            ],400);
        
    }
}
