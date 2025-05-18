<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token',
        'status',
        'processed_by',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user associated with this password reset request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed this request.
     */
    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if the request is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}