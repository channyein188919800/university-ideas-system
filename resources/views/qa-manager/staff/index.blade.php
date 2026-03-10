@extends('layouts.qa-manager')

@section('title', 'Manage Staff Accounts - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-people"></i> Staff Accounts</h3>
        <p>Manage staff and QA coordinator accounts</p>
    </div>
    <a href="{{ route('qa-manager.staff.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Add Account
    </a>
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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ str_replace('_', ' ', $user->role) }}</td>
                            <td>{{ $user->department?->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('qa-manager.staff.edit', $user) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('qa-manager.staff.destroy', $user) }}" onsubmit="return confirm('Delete this account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No accounts found.</td>
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
