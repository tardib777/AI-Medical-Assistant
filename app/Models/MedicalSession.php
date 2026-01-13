<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MedicalSession extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=['user_id','status'];
    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function User(){
        return $this->belongsTo(User::class);
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
