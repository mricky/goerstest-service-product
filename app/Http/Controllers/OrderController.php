<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Formater;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Str;
class OrderController extends Controller
{
   
    public function store(Request $request){
       
     $data = $request->only('order_date','user_id','order_number','total');
     
       $validator = Validator::make($data,[
            'order_date' => 'required|date',
            'user_id'   => 'nullable',
            'status' => 'required|in:pending,success,close',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required'
       ]);

       try {
          DB::beginTransaction();
          $order = Order::create($data);
    
          $items = $request->input('items',[]);
          $items = collect($items)->transform(function($item) use ($order){
            
               $item['order_id'] = $order->id;
               $item['product_id']  = $item['product_id'];
               $item['qty'] = $item['qty'];
             
               return $item;
          });
     
          $order->orderItems()->createMany($items);

          $msg = 'Order was succefully created';
          DB::commit();
          return Formater::success($order, $msg);
       } Catch(\Exception $e) {
         
          DB::rollBack();
          return Formater::error($e->getMessage(),500);
       }

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


    public function getMidtransSnapUrl($param){
          \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
          \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_PRODUCTION');
          \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_3DS');

          $snapUrl = \Midrans\Snap::createTransaction($param)->redirect_url;

     return $snapUrl;
    }

    public function index(Request $request){
        die('index');
    }
}
