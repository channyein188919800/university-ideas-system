@extends('layouts.qa-manager')

@section('title', 'Manage Users - University Ideas System')

@section('content')
<style>
    .action-dropdown {
        position: relative;
        display: inline-block;
    }

    .action-dropdown-toggle {
        background: transparent;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-dropdown-toggle:hover {
        background: #f8fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    .action-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        min-width: 180px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        padding: 6px;
        z-index: 100;
        display: none;
    }

    .action-dropdown-menu.show {
        display: block;
    }

    .action-dropdown-item {
        width: 100%;
        border: none;
        background: transparent;
        color: #334155;
        text-decoration: none;
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        text-align: left;
    }

    .action-dropdown-item:hover {
        background: #f8fafc;
        color: #1f2937;
    }

    .action-dropdown-item.text-danger {
        color: #dc2626;
    }

    .action-dropdown-item.text-danger:hover {
        background: #fef2f2;
        color: #b91c1c;
    }

    .action-dropdown-item.text-warning {
        color: #b45309;
    }

    .action-dropdown-item.text-warning:hover {
        background: #fffbeb;
        color: #92400e;
    }

    .action-dropdown-item.text-success {
        color: #15803d;
    }

    .action-dropdown-item.text-success:hover {
        background: #f0fdf4;
        color: #166534;
    }
</style>

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
                        <th>User</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th class="text-center">Ideas</th>
                        <th class="text-center">Comments</th>
                        <th class="text-center">Terms Accepted</th>
                        <th class="text-center">Actions</th>
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
                                <br>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
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
                                <span class="badge {{ $user->status === 'disabled' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $user->ideas_count }}</td>
                            <td class="text-center">{{ $user->comments_count }}</td>
                            <td class="text-center">
                                @if($user->terms_accepted)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-times"></i> No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-dropdown">
                                    <button type="button" class="action-dropdown-toggle" onclick="toggleDropdown({{ $user->id }})">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown-menu" id="dropdown-{{ $user->id }}">
                                        <a href="{{ route('qa-manager.staff.edit', $user) }}" class="action-dropdown-item">
                                            <i class="bi bi-pencil-square"></i> Edit User
                                        </a>

                                        <form method="POST" action="{{ route('qa-manager.users.toggle-status', $user) }}" class="m-0"
                                              data-confirm="{{ $user->status === 'disabled' ? 'Restore this user and restore their hidden ideas/comments?' : 'Disable this user and hide all their ideas/comments?' }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-dropdown-item {{ $user->status === 'disabled' ? 'text-success' : 'text-warning' }}">
                                                <i class="bi {{ $user->status === 'disabled' ? 'bi-person-check' : 'bi-person-x' }}"></i>
                                                {{ $user->status === 'disabled' ? 'Restore User' : 'Disable User' }}
                                            </button>
                                        </form>

                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('qa-manager.staff.destroy', $user) }}" class="m-0" data-confirm="Are you sure you want to delete this user?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-dropdown-item text-danger">
                                                    <i class="bi bi-trash"></i> Delete User
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
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

@push('scripts')
<script>
    function toggleDropdown(id) {
        document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
            if (menu.id !== 'dropdown-' + id) {
                menu.classList.remove('show');
            }
        });

        const dropdown = document.getElementById('dropdown-' + id);
        if (dropdown) dropdown.classList.toggle('show');
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-dropdown')) {
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>
@endpush
