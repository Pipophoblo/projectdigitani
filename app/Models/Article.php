<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'slug', 
        'image', 
        'content', 
        'keywords', 
        'summary',
        'status',
        'published_at',
        'view_count'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeTrending($query)
    {
        return $query->where('status', 'published')
            ->orderBy('view_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->where('status', 'published')
            ->orderBy('published_at', 'desc');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}