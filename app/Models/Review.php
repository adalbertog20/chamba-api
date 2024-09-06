<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_chamba_id',
        'chamba_id',
        'client_id',
        'worker_id',
        'rating',
        'comment'
    ];
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function chamba()
    {
        return $this->belongsTo(Chamba::class);
    }
}
