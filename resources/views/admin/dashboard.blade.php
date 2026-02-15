@extends('layouts.app')

@section('title', 'Admin Dashboard - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-cog"></i> Admin Dashboard</h2>
            <p class="text-muted mb-0">System administration and management</p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">{{ $stats['total_users'] }}</div>
                <div class="stats-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stats-number">{{ $stats['total_ideas'] }}</div>
                <div class="stats-label">Total Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number">{{ $stats['total_comments'] }}</div>
                <div class="stats-label">Total Comments</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-number">{{ $stats['total_departments'] }}</div>
                <div class="stats-label">Departments</div>
            </div>
        </div>
    </div>

    <!-- Closure Dates -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i> System Settings
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Idea Closure Date</h6>
                            <p class="mb-0">
                                @if($ideaClosureDate)
                                    <span class="badge {{ now()->lt($ideaClosureDate) ? 'bg-success' : 'bg-danger' }}">
                                        {{ $ideaClosureDate->format('F d, Y H:i') }}
                                    </span>
                                    @if(now()->lt($ideaClosureDate))
                                        <small class="text-muted">(Open for {{ now()->diffForHumans($ideaClosureDate, false) }})</small>
                                    @else
                                        <small class="text-muted">(Closed)</small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Not Set</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Final Closure Date</h6>
                            <p class="mb-0">
                                @if($finalClosureDate)
                                    <span class="badge {{ now()->lt($finalClosureDate) ? 'bg-success' : 'bg-danger' }}">
                                        {{ $finalClosureDate->format('F d, Y H:i') }}
                                    </span>
                                    @if(now()->lt($finalClosureDate))
                                        <small class="text-muted">(Comments open for {{ now()->diffForHumans($finalClosureDate, false) }})</small>
                                    @else
                                        <small class="text-muted">(Closed)</small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Not Set</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bolt"></i> Quick Actions
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-building"></i> Manage Departments
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-cog"></i> System Settings
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('ideas.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-lightbulb"></i> View All Ideas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-lightbulb"></i> Recent Ideas
                </div>
                <div class="card-body">
                    @forelse($recentIdeas as $idea)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                        {{ Str::limit($idea->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-building"></i> {{ $idea->department->name }}
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-clock"></i> {{ $idea->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <span class="badge bg-primary">
                                {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No ideas submitted yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> Recent Comments
                </div>
                <div class="card-body">
                    @forelse($recentComments as $comment)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <h6 class="mb-1">
                                <i class="fas fa-comment text-primary"></i>
                                On: <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none">
                                    {{ Str::limit($comment->idea->title, 40) }}
                                </a>
                            </h6>
                            <p class="mb-1 small">{{ Str::limit($comment->content, 80) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
