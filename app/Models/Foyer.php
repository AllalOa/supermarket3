<?php

namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


class Foyer extends Model
{
        use HasFactory;
    
        protected $fillable = [
            'name',
            'chief_id',
            'stat',
            'description',
        ];
    
        /**
         * Get the chief (user) that is in charge of this foyer.
         */
        public function chief()
        {
            return $this->belongsTo(User::class, 'chief_id');
        }
    
        /**
         * Get the workers (users) that belong to this foyer.
         */
        public function workers()
        {
            return $this->hasMany(User::class, 'foyer_id')->where('id', '!=', $this->chief_id);
        }
   



public function users()
{
    return $this->hasMany(User::class);
}

}
