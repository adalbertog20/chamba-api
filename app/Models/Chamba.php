<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chamba extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'rating',
        'job_id',
        'slug',
        'worker_id'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function job(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
