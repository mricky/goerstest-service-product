<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    public function orderItems(){
        
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
