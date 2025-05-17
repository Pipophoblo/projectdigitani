<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Article;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'role', // E.g., 'Admin', 'Petani', 'Mahasiswa', etc.
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

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    /**
     * Get the threads for the user.
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Get the comments for the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes for the user.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }
    
/**
 * Get the articles for the user.
 */
public function articles()
{
    return $this->hasMany(Article::class);
}

/**
 * Check if user is dosen
 */
public function isDosen()
{
    return $this->role === 'Dosen';
}

/**
 * Check if user is peneliti
 */
public function isPeneliti()
{
    return $this->role === 'Peneliti';
}

 /**
     * Get the password reset requests for the user.
     */
    public function passwordResetRequests()
    {
        return $this->hasMany(PasswordResetRequest::class);
    }
}