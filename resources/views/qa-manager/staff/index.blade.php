@extends('layouts.qa-manager')

@section('title', 'Manage Users - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-people"></i> Manage Users</h3>
        <p>View and manage system users</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('qa-manager.audit-logs.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-journal-text"></i> Audit Logs
        </a>
        <a href="{{ route('qa-manager.staff.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Add User
        </a>
    </div>
</div>

<div class="qa-card mb-3">
    <div class="qa-card-body">
        <form method="GET" action="{{ route('qa-manager.staff.index') }}" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search by name or email">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search me-1"></i> Search
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Terms Accepted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-info">You</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @break
                                    @case('qa_manager')
                                        <span class="badge bg-primary">QA Manager</span>
                                        @break
                                    @case('qa_coordinator')
                                        <span class="badge bg-success">QA Coordinator</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Staff</span>
                                @endswitch
                            </td>
                            <td>{{ $user->department?->name ?? 'N/A' }}</td>
                            <td>
                                @if($user->terms_accepted)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-times"></i> No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('qa-manager.staff.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('qa-manager.staff.destroy', $user) }}" class="d-inline" data-confirm="Are you sure you want to delete this user?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $staff->links() }}
</div>
@endsection
