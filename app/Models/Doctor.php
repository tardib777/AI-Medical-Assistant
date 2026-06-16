<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Doctor extends Model
{
    use HasRoles, HasUuids;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'specialization',
        'qualification',
        'experience_years',
        'license_number',
        'bio',
        'profile_picture'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function appointments(){
        return $this->hasMany(Appointment::class);
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
