<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'worker_id', 'request_chamba_id'];

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
