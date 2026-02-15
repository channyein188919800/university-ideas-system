@extends('layouts.app')

@section('title', $idea->title . ' - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ideas.index') }}">Ideas</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($idea->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Idea Card -->
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h2 class="mb-3">{{ $idea->title }}</h2>
                    
                    <div class="d-flex flex-wrap align-items-center mb-3 text-muted">
                        <span class="me-3">
                            <i class="fas fa-building"></i> {{ $idea->department->name }}
                        </span>
                        <span class="me-3">
                            <i class="fas fa-user"></i> {{ $idea->author_name }}
                        </span>
                        <span class="me-3">
                            <i class="fas fa-calendar"></i> {{ $idea->created_at->format('M d, Y') }}
                        </span>
                        @if($idea->is_anonymous)
                            <span class="badge badge-anonymous">
                                <i class="fas fa-user-secret"></i> Anonymous
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        @foreach($idea->categories as $category)
                            <span class="badge-category">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <div class="idea-description" style="font-size: 1.05rem; line-height: 1.8;">
                        {!! nl2br(e($idea->description)) !!}
                    </div>
                    
                    <!-- Documents -->
                    @if($idea->documents->count() > 0)
                        <hr>
                        <h5><i class="fas fa-paperclip"></i> Supporting Documents</h5>
                        <div class="list-group">
                            @foreach($idea->documents as $document)
                                <a href="{{ Storage::url($document->file_path) }}" 
                                   target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas {{ $document->icon_class }}"></i>
                                        {{ $document->original_name }}
                                    </div>
                                    <span class="badge bg-secondary">{{ $document->file_size_formatted }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    
                    <hr>
                    
                    <!-- Voting Section -->
                    @auth
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('ideas.vote', $idea) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="vote_type" value="up">
                                    <button type="submit" class="vote-btn {{ $userVote && $userVote->vote_type === 'up' ? 'active-up' : '' }}">
                                        <i class="fas fa-thumbs-up"></i>
                                        <span>{{ $idea->thumbs_up_count }}</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('ideas.vote', $idea) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="vote_type" value="down">
                                    <button type="submit" class="vote-btn {{ $userVote && $userVote->vote_type === 'down' ? 'active-down' : '' }}">
                                        <i class="fas fa-thumbs-down"></i>
                                        <span>{{ $idea->thumbs_down_count }}</span>
                                    </button>
                                </form>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-eye"></i> {{ $idea->views_count }} views
                                <span class="mx-2">|</span>
                                <i class="fas fa-comment"></i> {{ $idea->comments_count }} comments
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> 
                            <a href="{{ route('login') }}">Login</a> to vote on this idea.
                        </div>
                    @endauth
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> Comments ({{ $idea->comments_count }})
                </div>
                <div class="card-body">
                    <!-- Comment Form -->
                    @auth
                        @if(auth()->user()->canComment())
                            <form method="POST" action="{{ route('comments.store', $idea) }}" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="content" class="form-label">Add a Comment</label>
                                    <textarea class="form-control" 
                                              id="content" 
                                              name="content" 
                                              rows="3" 
                                              required
                                              placeholder="Share your thoughts on this idea..."></textarea>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_anonymous" name="is_anonymous" value="1">
                                        <label class="form-check-label" for="is_anonymous">
                                            <i class="fas fa-user-secret"></i> Comment anonymously
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Post Comment
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-clock"></i> Commenting is now closed.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i> 
                            Please <a href="{{ route('login') }}">login</a> to leave a comment.
                        </div>
                    @endauth
                    
                    <hr>
                    
                    <!-- Comments List -->
                    @forelse($comments as $comment)
                        <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>
                                        @if($comment->is_anonymous)
                                            <i class="fas fa-user-secret"></i> Anonymous
                                        @else
                                            <i class="fas fa-user-circle"></i> {{ $comment->user->name }}
                                        @endif
                                    </strong>
                                    <small class="text-muted ms-2">
                                        <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @if($comment->is_anonymous)
                                    <span class="badge badge-anonymous">Anonymous</span>
                                @endif
                            </div>
                            <p class="mb-0">{!! nl2br(e($comment->content)) !!}</p>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>No comments yet. Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                    
                    <!-- Comments Pagination -->
                    <div class="mt-4">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Idea Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar"></i> Idea Statistics
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stats-number text-success">{{ $idea->thumbs_up_count }}</div>
                            <div class="stats-label">Thumbs Up</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stats-number text-danger">{{ $idea->thumbs_down_count }}</div>
                            <div class="stats-label">Thumbs Down</div>
                        </div>
                        <div class="col-6">
                            <div class="stats-number">{{ $idea->views_count }}</div>
                            <div class="stats-label">Views</div>
                        </div>
                        <div class="col-6">
                            <div class="stats-number">{{ $idea->comments_count }}</div>
                            <div class="stats-label">Comments</div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="stats-number" style="color: var(--accent-color);">
                            {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                        </div>
                        <div class="stats-label">Popularity Score</div>
                    </div>
                </div>
            </div>
            
            <!-- Author Info -->
            @if(!$idea->is_anonymous)
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user"></i> Submitted By
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; font-size: 1.5rem;">
                                    {{ strtoupper(substr($idea->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $idea->user->name }}</h6>
                                <small class="text-muted">{{ $idea->department->name }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cog"></i> Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('ideas.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Back to Ideas
                        </a>
                        @auth
                            @if(auth()->user()->canSubmitIdea())
                                <a href="{{ route('ideas.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Submit New Idea
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
