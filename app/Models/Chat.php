<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'uuid', 'worker_id', 'request_chamba_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function requestChamba()
    {
        return $this->belongsTo(RequestChamba::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->with(['user:id,name']);
    }
}
