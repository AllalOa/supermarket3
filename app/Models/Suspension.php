<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Suspension extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reason', 'start_date', 'end_date', 'old_role'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'start_date' => 'datetime', // Cast start_date to a Carbon instance
    ];
}
