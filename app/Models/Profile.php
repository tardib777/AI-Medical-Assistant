<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
     protected $fillable=['user_id','blood_type','height_cm','weight_kg','chronic_diseases','allergies','current_medication','extra_info'];
     public function user(){
        return $this->belongsTo(User::class);
     }
     protected $casts = [
        'chronic_diseases' => 'array',
        'allergies' => 'array',
        'current_medication' => 'array',
         'extra_info' => 'array',
    ];
   
}
