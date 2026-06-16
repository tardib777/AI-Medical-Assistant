<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'user_id',  // null = broadcast to all
        'title', 'body', 'type',   // appointment | reminder | cancel | general
        'status',                  // sent | failed
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function scopeUnread($q) { return $q->whereNull('read_at'); }

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
