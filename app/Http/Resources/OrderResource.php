<?php

namespace App\Http\Resources;

use App\Libraries\UserLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function toArray($request){
        
        $user= UserLibrary::response('get','users/'.$this->user_id,null); 
       
        return [
            'user_id' => $this->user_id,
            'order_date' => $this->order_date,
            'order_number' => $this->order_number,
            'name' => $user->data->Name,
            'email' => $user->data->Email,
            'total' => $this->total,
            'snap_url' => $this->snap_url 
            // etc data manipulated when needed 
        ];
    }
}
