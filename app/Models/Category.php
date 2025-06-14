<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'description'];

    /**
     * Get the threads for the category.
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}