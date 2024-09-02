<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamba extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'rating',
        'job_id',
        'worker_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
