@extends('layouts.app')

@section('title', 'QA Coordinator Dashboard - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-users-cog"></i> QA Coordinator Dashboard</h2>
            @if(isset($department))
                <p class="text-muted mb-0">Department: <strong>{{ $department->name }}</strong></p>
            @endif
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> {{ $error }}
        </div>
    @else
        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="stats-number">{{ $stats['total_ideas'] }}</div>
                    <div class="stats-label">Total Ideas</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stats-number">{{ $stats['total_comments'] }}</div>
                    <div class="stats-label">Total Comments</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">{{ $stats['contributors'] }}</div>
                    <div class="stats-label">Contributors</div>
                </div>
            </div>
        </div>

        <!-- Closure Dates -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt"></i> System Status
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Idea Submission</h6>
                                <p class="mb-0">
                                    @if($ideaClosureDate)
                                        @if(now()->lt($ideaClosureDate))
                                            <span class="badge bg-success">Open</span>
                                            <small class="text-muted">Closes {{ $ideaClosureDate->diffForHumans() }}</small>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                            <small class="text-muted">Closed on {{ $ideaClosureDate->format('M d, Y') }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Not Set</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Commenting</h6>
                                <p class="mb-0">
                                    @if($finalClosureDate)
                                        @if(now()->lt($finalClosureDate))
                                            <span class="badge bg-success">Open</span>
                                            <small class="text-muted">Closes {{ $finalClosureDate->diffForHumans() }}</small>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                            <small class="text-muted">Closed on {{ $finalClosureDate->format('M d, Y') }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Not Set</span>
                                    @endif
                                </p>
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
                        <i class="fas fa-lightbulb"></i> Recent Ideas in Your Department
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
                                        <i class="fas fa-user"></i> {{ $idea->author_name }}
                                        <span class="mx-1">|</span>
                                        <i class="fas fa-clock"></i> {{ $idea->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <span class="badge bg-primary">
                                    {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No ideas submitted yet in your department.</p>
                        @endforelse
                        <div class="text-center mt-3">
                            <a href="{{ route('ideas.index', ['department' => $department->id]) }}" class="btn btn-outline-primary btn-sm">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Ideas -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-fire"></i> Popular Ideas in Your Department
                    </div>
                    <div class="card-body">
                        @forelse($popularIdeas as $idea)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                            {{ Str::limit($idea->title, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-thumbs-up text-success"></i> {{ $idea->thumbs_up_count }}
                                        <span class="mx-1">|</span>
                                        <i class="fas fa-comment"></i> {{ $idea->comments_count }}
                                    </small>
                                </div>
                                <span class="badge bg-success">
                                    +{{ $idea->popularity_score }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No popular ideas yet in your department.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Encouragement Card -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-bullhorn"></i> Encourage Participation
            </div>
            <div class="card-body">
                <p>As QA Coordinator, you play a vital role in encouraging staff to participate in the idea submission process.</p>
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Send Reminders</h6>
                                <small class="text-muted">Email staff about the system</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-comments"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Engage with Ideas</h6>
                                <small class="text-muted">Comment and vote on submissions</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Share Success Stories</h6>
                                <small class="text-muted">Highlight implemented ideas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
