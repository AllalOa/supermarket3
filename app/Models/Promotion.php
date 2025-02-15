<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions'; // Table name

    protected $fillable = [
        'user_id',
        'previous_role',
        'promoted_at',
        'expired_at',
    ];

    public $timestamps = false; // We are manually handling timestamps


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

