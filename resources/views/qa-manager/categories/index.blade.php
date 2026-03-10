@extends('layouts.qa-manager')

@section('title', 'Manage Categories - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-tags"></i> Manage Categories</h3>
        <p>Create, update, and remove idea categories for submission</p>
    </div>
    <a href="{{ route('qa-manager.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Category
    </a>
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-list-ul"></i> Category List</h5>
    </div>
    <div class="qa-card-body p-0">
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
                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i> Yes</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-x-lg"></i> No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('qa-manager.categories.edit', $category) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($category->canBeDeleted())
                                        <form method="POST" action="{{ route('qa-manager.categories.destroy', $category) }}" class="d-inline" data-confirm="Are you sure you want to delete this category?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 mt-2">No categories found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-info-circle"></i> About Categories</h5>
    </div>
    <div class="qa-card-body">
        <ul class="mb-0">
            <li>Categories help organize ideas by theme.</li>
            <li>Staff can select multiple categories during idea submission.</li>
            <li>Categories linked to ideas cannot be deleted.</li>
        </ul>
    </div>
</div>
@endsection
