@extends('layouts.app')

@section('title', 'Staff Dashboard - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-tachometer-alt"></i> My Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        @if($canSubmitIdea)
            <a href="{{ route('ideas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Submit Idea
            </a>
        @endif
    </div>

    <!-- Closure Dates Alert -->
    @if($ideaClosureDate || $finalClosureDate)
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt"></i>
            <strong>Important Dates:</strong>
            @if($ideaClosureDate)
                Idea submission {{ now()->lt($ideaClosureDate) ? 'closes' : 'closed' }} on <strong>{{ $ideaClosureDate->format('F d, Y') }}</strong>
            @endif
            @if($finalClosureDate)
                | Commenting {{ now()->lt($finalClosureDate) ? 'closes' : 'closed' }} on <strong>{{ $finalClosureDate->format('F d, Y') }}</strong>
            @endif
        </div>
    @endif

    <!-- My Stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stats-number">{{ $stats['my_ideas'] }}</div>
                <div class="stats-label">My Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number">{{ $stats['my_comments'] }}</div>
                <div class="stats-label">My Comments</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-vote-yea"></i>
                </div>
                <div class="stats-number">{{ $stats['my_votes'] }}</div>
                <div class="stats-label">My Votes</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stats-number">{{ $stats['total_views'] }}</div>
                <div class="stats-label">Total Views</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-lightbulb"></i> My Recent Ideas</span>
                    <a href="{{ route('ideas.index') }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    @forelse($myIdeas as $idea)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ Str::limit($idea->title, 50) }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i></span>
                                @endif
                            </h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $idea->created_at->diffForHumans() }}
                                </small>
                                <div>
                                    <span class="badge bg-success me-1"><i class="fas fa-thumbs-up"></i> {{ $idea->thumbs_up_count }}</span>
                                    <span class="badge bg-primary"><i class="fas fa-comment"></i> {{ $idea->comments_count }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-lightbulb fa-3x mb-3"></i>
                            <p>You haven't submitted any ideas yet.</p>
                            @if($canSubmitIdea)
                                <a href="{{ route('ideas.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Submit Your First Idea
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- My Comments -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> My Recent Comments
                </div>
                <div class="card-body">
                    @forelse($myComments as $comment)
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
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>You haven't commented on any ideas yet.</p>
                            <a href="{{ route('ideas.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Browse Ideas
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-bolt"></i> Quick Actions
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('ideas.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i> Browse Ideas
                    </a>
                </div>
                @if($canSubmitIdea)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('ideas.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus-circle"></i> Submit Idea
                        </a>
                    </div>
                @endif
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('home') }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-home"></i> Home
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="btn btn-outline-warning w-100">
                        <i class="fas fa-fire"></i> Popular Ideas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
