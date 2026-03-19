@extends('layouts.app')

@section('title', 'Manage Users - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center mb-4 admin-users-header">
                <div>
                    <h2><i class="fas fa-users"></i> Manage Users</h2>
                    <p class="text-muted mb-0">View and manage system users</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-journal-text"></i> Audit Logs
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                </div>
                <div class="toast-container admin-users-toast">
                    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="toastMessage">User deleted successfully!</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
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
                                @forelse($users as $user)
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
                                        <td>
                                            @if(in_array($user->role, ['admin', 'qa_manager']))
                                                <span class="text-muted fw-semibold">University Wide</span>
                                            @else
                                                {{ $user->department?->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->terms_accepted)
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                            @else
                                                <span class="badge bg-warning"><i class="fas fa-times"></i> No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit fa-fw"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" data-confirm="Are you sure you want to delete this user?">
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

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </section>
</div>
@endsection

@include('admin.partials.sidebar-assets')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
 
        @if(session('success'))
            showToast('{{ session('success') }}');
        @endif
    });

    function showToast(message) {
        const toast = document.getElementById('successToast');
        const messageSpan = document.getElementById('toastMessage');
        
        if (toast && messageSpan) {
            messageSpan.textContent = message;
            toast.style.display = 'block';
            
            const bsToast = new bootstrap.Toast(toast, {
                animation: true,
                autohide: true,
                delay: 5000
            });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function () {
                toast.style.display = 'none';
            });
        }
    }
</script>
@endpush

@push('styles')
<style>
    .admin-users-header {
        position: relative;
    }
    .admin-users-toast {
        position: absolute;
        top: -6px;
        left: auto;
        right: 140px;
        transform: none;
        z-index: 30;
        pointer-events: none;
        display: block;
    }
    .admin-users-toast .toast {
        pointer-events: auto;
        max-width: 460px;
    }
    @media (max-width: 992px) {
        .admin-users-toast {
            position: static;
            transform: none;
            margin-top: 0.75rem;
            display: block;
        }
    }
    .alert-success {
        display: none !important;
    }
</style>
@endpush
