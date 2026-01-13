<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=['medical_session_id','role','content'];
    public function Session(){
        return $this->belongsTo(MedicalSession::class);
    }
    public function user(){
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
