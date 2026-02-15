<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'user_id',
        'vote_type',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isUpVote()
    {
        return $this->vote_type === 'up';
    }

    public function isDownVote()
    {
        return $this->vote_type === 'down';
    }

    public function scopeUpVotes($query)
    {
        return $query->where('vote_type', 'up');
    }

    public function scopeDownVotes($query)
    {
        return $query->where('vote_type', 'down');
    }
}
