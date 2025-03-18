<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'barcode',
        'product_picture',
        'name',
        'category',
        'quantity',
        'price',
        'unit_price'
    ];


   
    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }


    
}
