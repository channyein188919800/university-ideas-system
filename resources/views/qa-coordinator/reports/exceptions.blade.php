@extends('layouts.qa-coordinator')

@section('title', 'Exception Reports - ' . auth()->user()->department->name)

@section('content')
<!-- Topbar -->
<div class="qa-topbar reveal" style="--delay: 0s;">
    <div>
        <h3>Exception Reports</h3>
        <p>{{ auth()->user()->department->name }} · Monitoring & Quality Control</p>
    </div>
    <span class="dept-badge">
        <i class="bi bi-building me-1"></i> {{ auth()->user()->department->name }}
    </span>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="qa-stat-card reveal" style="--delay: 0.05s;">
            <div class="stat-icon" style="background: #f56565;"><i class="bi bi-chat-dots"></i></div>
            <h4>{{ $ideasWithoutCommentsCount }}</h4>
            <p>Ideas Without Comments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="qa-stat-card reveal" style="--delay: 0.1s;">
            <div class="stat-icon" style="background: #e58f07;"><i class="bi bi-incognito"></i></div>
            <h4>{{ $anonymousIdeasCount }}</h4>
            <p>Anonymous Ideas</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="qa-stat-card reveal" style="--delay: 0.15s;">
            <div class="stat-icon" style="background: #9f75ff;"><i class="bi bi-chat-quote"></i></div>
            <h4>{{ $anonymousCommentsCount }}</h4>
            <p>Anonymous Comments</p>
        </div>
    </div>
</div>

<!-- Ideas Without Comments -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="qa-card reveal" style="--delay: 0.2s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-chat-dots text-warning"></i> Ideas Without Comments</h5>
                <span class="badge bg-warning">{{ $ideasWithoutComments->count() }} ideas</span>
            </div>
            <div class="qa-card-body">
                @if($ideasWithoutComments->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Idea Title</th>
                                    <th>Author</th>
                                    <th>Submitted</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ideasWithoutComments as $idea)
                                    <tr>
                                        <td>
                                            <a href="{{ route('ideas.show', $idea) }}" class="fw-semibold">
                                                {{ $idea->title }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $idea->is_anonymous ? 'Anonymous' : $idea->user->name }}
                                        </td>
                                        <td>{{ $idea->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @foreach($idea->categories as $category)
                                                <span class="badge bg-light text-dark">{{ $category->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('ideas.show', $idea) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <p class="mt-2">All ideas have comments! Great engagement!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Anonymous Contributions -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="qa-card reveal" style="--delay: 0.25s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-incognito"></i> Anonymous Ideas</h5>
                <span class="badge bg-secondary">{{ $anonymousIdeas->count() }} ideas</span>
            </div>
            <div class="qa-card-body">
                @if($anonymousIdeas->count() > 0)
                    @foreach($anonymousIdeas as $idea)
                        <div class="feed-item with-border">
                            <div>
                                <a href="{{ route('ideas.show', $idea) }}" class="feed-title">
                                    {{ Str::limit($idea->title, 50) }}
                                </a>
                                <p class="feed-meta mb-0">
                                    <i class="bi bi-calendar"></i> {{ $idea->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="badge bg-secondary">Anonymous</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">No anonymous ideas</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="qa-card reveal" style="--delay: 0.3s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-chat-quote"></i> Anonymous Comments</h5>
                <span class="badge bg-secondary">{{ $anonymousComments->count() }} comments</span>
            </div>
            <div class="qa-card-body">
                @if($anonymousComments->count() > 0)
                    @foreach($anonymousComments as $comment)
                        <div class="feed-item with-border">
                            <div>
                                <p class="mb-1">{{ Str::limit($comment->content, 60) }}</p>
                                <small class="text-muted">
                                    on <a href="{{ route('ideas.show', $comment->idea) }}">{{ Str::limit($comment->idea->title, 30) }}</a>
                                </small>
                                <p class="feed-meta mb-0 mt-1">
                                    <i class="bi bi-calendar"></i> {{ $comment->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="badge bg-secondary">Anonymous</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">No anonymous comments</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection