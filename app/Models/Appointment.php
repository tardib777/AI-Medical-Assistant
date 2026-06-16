<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Appointment extends Model
{
    use HasUuids;
    protected $keyType = 'string';

    public $incrementing = false;
    protected $fillable = [
        'doctor_id',
        'appointment_time',
        'appointment_location',
        'status'
    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
     protected function casts(): array
    {
        return [
            'appointment_time' => 'datetime',
        ];
    }
    public function booking()
    {
        return $this->hasOne(Booking::class);
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
