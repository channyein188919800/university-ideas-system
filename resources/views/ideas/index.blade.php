@extends('layouts.app')

@section('title', 'All Ideas - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-lightbulb"></i> All Ideas</h2>
            <p class="text-muted mb-0">Browse and discover innovative ideas from across the university</p>
        </div>
        @auth
            @if(auth()->user()->canSubmitIdea())
                <a href="{{ route('ideas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Submit Idea
                </a>
            @endif
        @endauth
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('ideas.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-filter"></i> Category</label>
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-sort"></i> Sort By</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('ideas.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ideas List -->
    @forelse($ideas as $idea)
        <div class="idea-card">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="idea-title">
                        <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                            {{ $idea->title }}
                        </a>
                        @if($idea->is_anonymous)
                            <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                        @endif
                    </h4>
                    <div class="idea-meta">
                        <i class="fas fa-building"></i> {{ $idea->department->name }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-user"></i> {{ $idea->author_name }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-calendar"></i> {{ $idea->created_at->format('M d, Y') }}
                    </div>
                    <p class="idea-description">{{ Str::limit($idea->description, 250) }}</p>
                    <div class="mb-2">
                        @foreach($idea->categories as $category)
                            <span class="badge-category">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-column align-items-end justify-content-between h-100">
                        <div class="idea-stats mb-3">
                            <span class="idea-stat text-success" title="Thumbs Up">
                                <i class="fas fa-thumbs-up"></i> {{ $idea->thumbs_up_count }}
                            </span>
                            <span class="idea-stat text-danger" title="Thumbs Down">
                                <i class="fas fa-thumbs-down"></i> {{ $idea->thumbs_down_count }}
                            </span>
                            <span class="idea-stat" title="Views">
                                <i class="fas fa-eye"></i> {{ $idea->views_count }}
                            </span>
                            <span class="idea-stat" title="Comments">
                                <i class="fas fa-comment"></i> {{ $idea->comments_count }}
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary mb-2">
                                Popularity: {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                            </span>
                            <br>
                            <a href="{{ route('ideas.show', $idea) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-right"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No ideas found</h4>
                <p class="text-muted">Be the first to submit an idea!</p>
                @auth
                    @if(auth()->user()->canSubmitIdea())
                        <a href="{{ route('ideas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Submit Idea
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-4">
        {{ $ideas->withQueryString()->links() }}
    </div>
</div>
@endsection
