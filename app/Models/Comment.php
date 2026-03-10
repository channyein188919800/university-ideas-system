<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'idea_id',
        'user_id',
        'content',
        'is_anonymous',
        'hidden',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'hidden' => 'boolean',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAuthorNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        return $this->user?->name ?? 'Unknown';
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeVisible($query)
    {
        return $query->where('hidden', false);
    }
}
