<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    const WORKER = "1";
    const CLIENT = "0";

    use HasFactory, Notifiable, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'about_me',
        'password',
        'phone_number',
        'role',
        'street',
        'postal_code',
        'city'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): string
    {
        return (string)$this->role;
    }

    public function isClient(): bool
    {
        return $this->role() === self::CLIENT;
    }

    public function isWorker(): bool
    {
        return $this->role() === self::WORKER;
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_user');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'worker_id');
    }

    public function chambas()
    {
        return $this->hasMany(Chamba::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'followed_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'followed_id', 'follower_id');
    }
}
