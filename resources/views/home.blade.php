@extends('layouts.app')

@section('title', 'Home - University Ideas System')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container text-center">
        <h1><i class="fas fa-lightbulb"></i> Share Your Ideas</h1>
        <p>Help improve our university by submitting your innovative ideas and feedback.</p>
        @auth
            @if(auth()->user()->canSubmitIdea())
                <a href="{{ route('ideas.create') }}" class="btn btn-accent btn-lg">
                    <i class="fas fa-plus-circle"></i> Submit an Idea
                </a>
            @else
                <div class="alert alert-warning d-inline-block">
                    <i class="fas fa-clock"></i> Idea submission is currently closed.
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-accent btn-lg">
                <i class="fas fa-sign-in-alt"></i> Login to Submit Ideas
            </a>
        @endauth
    </div>
</section>

<div class="container mb-5">
    <!-- Closure Dates Alert -->
    @if($ideaClosureDate || $finalClosureDate)
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt"></i>
            <strong>Important Dates:</strong>
            @if($ideaClosureDate)
                Idea submission closes on <strong>{{ $ideaClosureDate->format('F d, Y') }}</strong>
            @endif
            @if($finalClosureDate)
                | Final closure (comments) on <strong>{{ $finalClosureDate->format('F d, Y') }}</strong>
            @endif
        </div>
    @endif

    <!-- Statistics Row -->
    <div class="row mb-5">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stats-number">{{ $popularIdeas->count() }}</div>
                <div class="stats-label">Popular Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stats-number">{{ $mostViewedIdeas->count() }}</div>
                <div class="stats-label">Most Viewed</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number">{{ $latestIdeas->count() }}</div>
                <div class="stats-label">Latest Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number">{{ $latestComments->count() }}</div>
                <div class="stats-label">Recent Comments</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Popular Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-fire"></i> Most Popular Ideas
                </div>
                <div class="card-body">
                    @forelse($popularIdeas as $idea)
                        <div class="idea-card mb-3">
                            <h5 class="idea-title">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                @endif
                            </h5>
                            <div class="idea-meta">
                                <i class="fas fa-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <p class="idea-description">{{ Str::limit($idea->description, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="idea-stats">
                                    <span class="idea-stat text-success">
                                        <i class="fas fa-thumbs-up"></i> {{ $idea->thumbs_up_count }}
                                    </span>
                                    <span class="idea-stat text-danger">
                                        <i class="fas fa-thumbs-down"></i> {{ $idea->thumbs_down_count }}
                                    </span>
                                    <span class="idea-stat">
                                        <i class="fas fa-eye"></i> {{ $idea->views_count }}
                                    </span>
                                    <span class="idea-stat">
                                        <i class="fas fa-comment"></i> {{ $idea->comments_count }}
                                    </span>
                                </div>
                                <span class="badge bg-primary">
                                    Score: {{ $idea->popularity_score }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No popular ideas yet.</p>
                        </div>
                    @endforelse
                    <div class="text-center mt-3">
                        <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="btn btn-outline-primary btn-sm">
                            View All Popular <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Viewed Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-eye"></i> Most Viewed Ideas
                </div>
                <div class="card-body">
                    @forelse($mostViewedIdeas as $idea)
                        <div class="idea-card mb-3">
                            <h5 class="idea-title">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                @endif
                            </h5>
                            <div class="idea-meta">
                                <i class="fas fa-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <p class="idea-description">{{ Str::limit($idea->description, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="idea-stats">
                                    <span class="idea-stat">
                                        <i class="fas fa-eye"></i> {{ $idea->views_count }} views
                                    </span>
                                    <span class="idea-stat">
                                        <i class="fas fa-comment"></i> {{ $idea->comments_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No viewed ideas yet.</p>
                        </div>
                    @endforelse
                    <div class="text-center mt-3">
                        <a href="{{ route('ideas.index', ['sort' => 'views']) }}" class="btn btn-outline-primary btn-sm">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock"></i> Latest Ideas
                </div>
                <div class="card-body">
                    @forelse($latestIdeas as $idea)
                        <div class="idea-card mb-3">
                            <h5 class="idea-title">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                @endif
                            </h5>
                            <div class="idea-meta">
                                <i class="fas fa-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <p class="idea-description">{{ Str::limit($idea->description, 150) }}</p>
                            <div class="idea-stats">
                                <span class="idea-stat text-success">
                                    <i class="fas fa-thumbs-up"></i> {{ $idea->thumbs_up_count }}
                                </span>
                                <span class="idea-stat text-danger">
                                    <i class="fas fa-thumbs-down"></i> {{ $idea->thumbs_down_count }}
                                </span>
                                <span class="idea-stat">
                                    <i class="fas fa-comment"></i> {{ $idea->comments_count }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No ideas submitted yet.</p>
                        </div>
                    @endforelse
                    <div class="text-center mt-3">
                        <a href="{{ route('ideas.index') }}" class="btn btn-outline-primary btn-sm">
                            View All Ideas <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Comments -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> Latest Comments
                </div>
                <div class="card-body">
                    @forelse($latestComments as $comment)
                        <div class="idea-card mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment text-primary"></i>
                                    On: <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none">{{ $comment->idea->title }}</a>
                                </h6>
                                @if($comment->is_anonymous)
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                @endif
                            </div>
                            <p class="idea-description mb-2">{{ Str::limit($comment->content, 150) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No comments yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tags"></i> Browse by Category
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($categories as $category)
                            <a href="{{ route('ideas.index', ['category' => $category->id]) }}" class="text-decoration-none">
                                <span class="badge-category">
                                    {{ $category->name }}
                                    <span class="badge bg-secondary ms-1">{{ $category->ideas_count }}</span>
                                </span>
                            </a>
                        @empty
                            <p class="text-muted mb-0">No categories available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
