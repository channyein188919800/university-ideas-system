<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'profile_image',
        'terms_accepted',
        'terms_accepted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'terms_accepted' => 'boolean',
        'terms_accepted_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isQaManager()
    {
        return $this->role === 'qa_manager';
    }

    public function isQaCoordinator()
    {
        return $this->role === 'qa_coordinator';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function canSubmitIdea()
    {
        if (!$this->terms_accepted) {
            return false;
        }
        
        $closureDate = Setting::getValue('idea_closure_date');
        if ($closureDate) {
            return now()->lt($closureDate);
        }
        
        return true;
    }

    public function canComment()
    {
        $finalClosureDate = Setting::getValue('final_closure_date');
        if ($finalClosureDate) {
            return now()->lt($finalClosureDate);
        }
        
        return true;
    }

    public function getDisplayName()
    {
        return $this->name;
    }

    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return null;
        }

        return Storage::disk('public')->url($this->profile_image);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeWithRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
