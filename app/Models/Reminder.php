<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reminder extends Model
{
    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'user_id', 'name', 'dose', 'time', 'frequency', 'notes',
    ];

    public function user() { return $this->belongsTo(User::class); }

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
