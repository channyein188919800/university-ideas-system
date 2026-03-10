@extends('layouts.qa-manager')

@section('title', 'University Backlog - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-kanban"></i> University Backlog</h3>
        <p>Institutional QA backlog inspired by your legacy `qa_m_all_ideas.php` workflow</p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3 mb-2">
        <div class="qa-stat-card">
            <p>Pending</p>
            <h4>{{ $statusCounts['pending'] }}</h4>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="qa-stat-card">
            <p>Fixing</p>
            <h4>{{ $statusCounts['fixing'] }}</h4>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="qa-stat-card">
            <p>Resolved</p>
            <h4>{{ $statusCounts['resolved'] }}</h4>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="qa-stat-card">
            <p>Closed</p>
            <h4>{{ $statusCounts['closed'] }}</h4>
        </div>
    </div>
</div>

<div class="qa-card mb-3">
    <div class="qa-card-body">
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-sm {{ $tab === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('qa-manager.backlog.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}">Pending</a>
            <a class="btn btn-sm {{ $tab === 'fixing' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('qa-manager.backlog.index', array_merge(request()->query(), ['tab' => 'fixing', 'page' => null])) }}">Fixing</a>
            <a class="btn btn-sm {{ $tab === 'resolved' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('qa-manager.backlog.index', array_merge(request()->query(), ['tab' => 'resolved', 'page' => null])) }}">Resolved</a>
            <a class="btn btn-sm {{ $tab === 'closed' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('qa-manager.backlog.index', array_merge(request()->query(), ['tab' => 'closed', 'page' => null])) }}">Closed</a>
        </div>
    </div>
</div>

<div class="qa-card mb-3">
    <div class="qa-card-body">
        <form method="GET" action="{{ route('qa-manager.backlog.index') }}" class="row g-2 align-items-end">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-md-4">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (int) $categoryId === $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search titles, descriptions, or authors">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search me-1"></i> Apply
                </button>
            </div>
        </form>
    </div>
</div>

<div class="qa-card">
    <div class="qa-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Idea</th>
                        <th>Author</th>
                        <th>Department</th>
                        <th>Categories</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ideas as $idea)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $idea->title }}</div>
                                <small class="text-muted">#{{ $idea->id }} • {{ $idea->created_at->diffForHumans() }}</small>
                            </td>
                            <td>{{ $idea->author_name }}</td>
                            <td>{{ $idea->department?->name ?? 'N/A' }}</td>
                            <td>
                                @forelse($idea->categories as $category)
                                    <span class="badge bg-light text-dark border">{{ $category->name }}</span>
                                @empty
                                    <span class="text-muted">No category</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ str_replace('_', ' ', $idea->status) }}</span>
                                @if($idea->hidden)
                                    <span class="badge bg-dark ms-1">Hidden</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('ideas.show', $idea) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('qa-manager.ideas.toggle-hidden', $idea) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $idea->hidden ? 'btn-success' : 'btn-outline-danger' }}">
                                            <i class="bi {{ $idea->hidden ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No backlog items found for current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $ideas->links() }}
</div>
@endsection
