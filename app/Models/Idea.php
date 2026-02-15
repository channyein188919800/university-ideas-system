<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Idea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'department_id',
        'is_anonymous',
        'status',
        'views_count',
        'thumbs_up_count',
        'thumbs_down_count',
        'comments_count',
        'is_closed',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_closed' => 'boolean',
        'views_count' => 'integer',
        'thumbs_up_count' => 'integer',
        'thumbs_down_count' => 'integer',
        'comments_count' => 'integer',
    ];

    protected $with = ['user', 'department', 'categories'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'idea_category');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getAuthorNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        return $this->user?->name ?? 'Unknown';
    }

    public function getPopularityScoreAttribute()
    {
        return $this->thumbs_up_count - $this->thumbs_down_count;
    }

    public function getHasDocumentsAttribute()
    {
        return $this->documents()->count() > 0;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateVoteCounts()
    {
        $this->thumbs_up_count = $this->votes()->where('vote_type', 'up')->count();
        $this->thumbs_down_count = $this->votes()->where('vote_type', 'down')->count();
        $this->save();
    }

    public function updateCommentsCount()
    {
        $this->comments_count = $this->comments()->count();
        $this->save();
    }

    public function userHasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function getUserVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopePopular($query)
    {
        return $query->orderByRaw('(thumbs_up_count - thumbs_down_count) DESC');
    }

    public function scopeMostViewed($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    public function scopeWithoutComments($query)
    {
        return $query->where('comments_count', 0);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }
}
