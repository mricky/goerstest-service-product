<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'order_number',
        'user_id',
        'order_date',
        'metadata',
        'qty',
        'snap_url',
        'status',
        'total'


    ];

  
    protected $cast = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'metadata' => 'array',
    ];

    protected static function boot(){
        parent::boot();
        self::saving(function ($m){
            $m->order_number = $m->generateOrderNumber();
        });
    }

    public function generateOrderNumber(){
        $prefix = "GOERS-A";

        $number =  Str::random(5);
       
      
        $newOrderNumber = $prefix.$number;
        return $newOrderNumber;
    }
    public function orderItems(){
        
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
