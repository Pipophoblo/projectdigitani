<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'thread_id'];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
