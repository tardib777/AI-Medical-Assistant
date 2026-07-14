<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DoctorProfile extends Model
{
    protected $table = 'doctors_profiles';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['user_id', 'specialization'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function appointments(){
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
