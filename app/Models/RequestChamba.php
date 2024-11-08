<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestChamba extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'worker_id',
        'chamba_id',
        'message',
        'status',
        'start_date',
        'end_date'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function chamba(): BelongsTo
    {
        return $this->belongsTo(Chamba::class);
    }

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }
}
