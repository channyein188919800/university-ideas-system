@extends('layouts.qa-manager')

@section('title', 'Manage Departments - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-building"></i> Departments</h3>
        <p>Manage departments and assign QA coordinators</p>
    </div>
    <a href="{{ route('qa-manager.departments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Department
    </a>
</div>

<div class="qa-card">
    <div class="qa-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Coordinator</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr>
                            <td>{{ $department->name }}</td>
                            <td><code>{{ $department->code }}</code></td>
                            <td>{{ $department->qaCoordinator?->name ?? 'Unassigned' }}</td>
                            <td>
                                <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('qa-manager.departments.edit', $department) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                    <form method="POST" action="{{ route('qa-manager.departments.destroy', $department) }}" data-confirm="Delete this department?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash fa-fw"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $departments->links() }}
</div>
@endsection
