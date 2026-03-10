@extends('layouts.qa-manager')

@section('title', 'Exception Reports - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-exclamation-triangle"></i> Exception Reports</h3>
        <p>Track outlier submissions and discussions requiring moderation review</p>
    </div>
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-funnel"></i> Filters</h5>
    </div>
    <div class="qa-card-body">
        <form method="GET" action="{{ route('qa-manager.reports.exceptions') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Idea title or comment text">
            </div>
            <div class="col-md-4">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (int) $departmentId === $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Apply
                </button>
                <a href="{{ route('qa-manager.reports.exceptions') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5>
            <i class="bi bi-chat-slash"></i> Ideas Without Comments
            <span class="badge bg-dark ms-2">{{ $ideasWithoutComments->total() }}</span>
        </h5>
    </div>
    <div class="qa-card-body p-0">
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
                            <td>{{ Str::limit($idea->title, 60) }}</td>
                            <td>{{ $idea->department?->name ?? 'N/A' }}</td>
                            <td>{{ $idea->is_anonymous ? 'Anonymous' : ($idea->user?->name ?? 'Unknown') }}</td>
                            <td>{{ $idea->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('ideas.show', $idea) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 mt-2">No records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mb-4 d-flex justify-content-center">
    {{ $ideasWithoutComments->links() }}
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5>
            <i class="bi bi-incognito"></i> Anonymous Ideas
            <span class="badge bg-dark ms-2">{{ $anonymousIdeas->total() }}</span>
        </h5>
    </div>
    <div class="qa-card-body p-0">
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
                            <td>{{ Str::limit($idea->title, 60) }}</td>
                            <td>{{ $idea->department?->name ?? 'N/A' }}</td>
                            <td>{{ $idea->created_at->diffForHumans() }}</td>
                            <td>{{ $idea->comments_count }}</td>
                            <td>
                                <a href="{{ route('ideas.show', $idea) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 mt-2">No records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mb-4 d-flex justify-content-center">
    {{ $anonymousIdeas->links() }}
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5>
            <i class="bi bi-incognito"></i> Anonymous Comments
            <span class="badge bg-dark ms-2">{{ $anonymousComments->total() }}</span>
        </h5>
    </div>
    <div class="qa-card-body p-0">
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
                            <td>{{ Str::limit($comment->idea?->title, 50) }}</td>
                            <td>{{ Str::limit($comment->content, 80) }}</td>
                            <td>{{ $comment->created_at->diffForHumans() }}</td>
                            <td>
                                @if($comment->idea)
                                    <a href="{{ route('ideas.show', $comment->idea) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 mt-2">No records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    {{ $anonymousComments->links() }}
</div>
@endsection
