@extends('layouts.app')

@section('title', 'Manage Departments - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-building"></i> Manage Departments</h2>
                    <p class="text-muted mb-0">View and manage university departments</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-journal-text"></i> Audit Logs
                    </a>
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Department
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>QA Coordinator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $department)
                                    <tr>
                                        <td><code>{{ $department->code }}</code></td>
                                        <td>
                                            <strong>{{ $department->name }}</strong>
                                            @if($department->description)
                                                <br><small class="text-muted">{{ Str::limit($department->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($department->qaCoordinator)
                                                {{ $department->qaCoordinator->name }}
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($department->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.departments.destroy', $department) }}" class="d-inline" data-confirm="Are you sure you want to delete this department?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No departments found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </section>
</div>
@endsection

@include('admin.partials.sidebar-assets')
