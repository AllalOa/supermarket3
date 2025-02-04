<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = ['cashier_id', 'total'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

