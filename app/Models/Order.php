<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['cashier_id', 'status'];

    // Define the correct relationship
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
    public function orderDetails()
{
    return $this->hasMany(OrderDetail::class, 'order_id');
}
public function details()
{
    return $this->hasMany(OrderDetail::class);
}

}
