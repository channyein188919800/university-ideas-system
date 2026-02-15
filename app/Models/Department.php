<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'qa_coordinator_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function qaCoordinator()
    {
        return $this->belongsTo(User::class, 'qa_coordinator_id');
    }

    public function getIdeaCountAttribute()
    {
        return $this->ideas()->count();
    }

    public function getContributorCountAttribute()
    {
        return $this->users()->whereHas('ideas')->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithCoordinator($query)
    {
        return $query->with('qaCoordinator');
    }
}
