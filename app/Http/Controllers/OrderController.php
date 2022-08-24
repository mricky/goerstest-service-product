<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Formater;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
   
    public function store(Request $request){
       $data = $request->only('order_date','qty','user_id');
     
       $validator = Validator::make($data,[
            'order_date' => 'required|date',
            'user_id'   => 'nullable',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required'
       ]);

       if($validator->fails()){
            return Formater::error(
                $validator->messages(),
                400
            );
       }

       try {
            DB::beginTransaction();


            DB::commit();
       } catch(\Exception $e) {

            DB::rollback();
            return Formater::error($e->getMessage());
       }
    }

    public function index(Request $request){
        die('index');
    }
}
