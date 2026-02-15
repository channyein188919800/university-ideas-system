@extends('layouts.app')

@section('title', 'Manage Categories - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-tags"></i> Manage Categories</h2>
            <p class="text-muted mb-0">Create and manage idea categories</p>
        </div>
        <a href="{{ route('qa-manager.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Ideas Count</th>
                            <th>Status</th>
                            <th>Can Delete</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ Str::limit($category->description, 50) }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->ideas_count }}</span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->canBeDeleted())
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-times"></i> No</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('qa-manager.categories.edit', $category) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($category->canBeDeleted())
                                            <form method="POST" action="{{ route('qa-manager.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No categories found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
    
    <!-- Info Card -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> About Categories
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="fas fa-check text-success"></i>
                    Categories help organize ideas by topic or theme.
                </li>
                <li class="mb-2">
                    <i class="fas fa-check text-success"></i>
                    Staff can select multiple categories when submitting ideas.
                </li>
                <li class="mb-0">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Categories that have been used cannot be deleted.
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
