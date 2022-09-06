<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Formater;
use App\Jobs\OrderCreatedJob;
use App\Libraries\UserLibrary;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
   
    public function store(Request $request){
       
     $data = $request->only('order_date','user_id','total');
     
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
               $item['price'] = $item['price'];
               $item['qty'] = $item['qty'];
             
               return $item;
          });
          
          $order->orderItems()->createMany($items);

          // midtrans get snap
          $transationDetails = [
               'order_id' => $order->id,
               'gross_ammount' => $request->input('total')
          ];

          
          $id =  $request->input('user_id');

          $user= UserLibrary::response('get','users/'.$id,null);

          // Just Dummy sample transaction
          $itemDetails = [
           [
                  'id' =>  1,
                  'name' => $user->data->Name,
                  'price' => 50000,
                  'quantity' => 1
           ],
           
          ];

          $customerDetail = [
               'first_name' => $user->data->Name,
               'email' => $user->data->Email,
          ];
          $midtransParams = [
              'transaction_details' => $transationDetails,
              'item_details' => $itemDetails,
              'customer_detail' => $customerDetail,
          ];

         
          $midtransSnapUrl = getMidtransSnapUrl($midtransParams);
       
          $order->snap_url = $midtransSnapUrl;
          $order->save();


          dispatch(new OrderCreatedJob($midtransParams)); // sent to queue email

          $msg = 'Order was succefully created';
          DB::commit();
          return Formater::success(new OrderResource($order), $msg);
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


    public function index(Request $request){
        die('index');
    }
}
