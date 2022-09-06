<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'price',
        'qty'
    ];

    public function products()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
}
