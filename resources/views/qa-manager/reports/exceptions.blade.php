@extends('layouts.app')

@section('title', 'Exception Reports - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-exclamation-triangle"></i> Exception Reports</h2>
            <p class="text-muted mb-0">Identify ideas and comments that need attention</p>
        </div>
        <a href="{{ route('qa-manager.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Ideas Without Comments -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-comment-slash"></i> Ideas Without Comments
            <span class="badge bg-dark ms-2">{{ $ideasWithoutComments->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Author</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ideasWithoutComments as $idea)
                            <tr>
                                <td>{{ Str::limit($idea->title, 50) }}</td>
                                <td>{{ $idea->department->name }}</td>
                                <td>
                                    @if($idea->is_anonymous)
                                        <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                    @else
                                        {{ $idea->user->name }}
                                    @endif
                                </td>
                                <td>{{ $idea->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('ideas.show', $idea) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-muted mb-0">All ideas have comments!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Anonymous Ideas -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-user-secret"></i> Anonymous Ideas
            <span class="badge bg-dark ms-2">{{ $anonymousIdeas->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Submitted</th>
                            <th>Comments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anonymousIdeas as $idea)
                            <tr>
                                <td>{{ Str::limit($idea->title, 50) }}</td>
                                <td>{{ $idea->department->name }}</td>
                                <td>{{ $idea->created_at->diffForHumans() }}</td>
                                <td>{{ $idea->comments_count }}</td>
                                <td>
                                    <a href="{{ route('ideas.show', $idea) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No anonymous ideas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Anonymous Comments -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-user-secret"></i> Anonymous Comments
            <span class="badge bg-dark ms-2">{{ $anonymousComments->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Idea</th>
                            <th>Comment</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anonymousComments as $comment)
                            <tr>
                                <td>{{ Str::limit($comment->idea->title, 40) }}</td>
                                <td>{{ Str::limit($comment->content, 60) }}</td>
                                <td>{{ $comment->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('ideas.show', $comment->idea) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No anonymous comments.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
