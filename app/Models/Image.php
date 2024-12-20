<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'image',
        'alt',
        'path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chamba()
    {
        return $this->belongsTo(Chamba::class);
    }
}
