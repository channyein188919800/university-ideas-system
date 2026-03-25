@extends('layouts.app')

@section('title', 'Manage Users - University Ideas System')

@section('content')
@php($disableGlobalToast = true)
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
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
            </div>

            {{-- Inline flash message above table --}}
            @if(session('success'))
                <div class="alert alert-success alert-autohide d-flex align-items-center gap-2 mb-3 py-2 px-3" role="alert" style="border-radius:0.75rem; font-weight:600;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-autohide d-flex align-items-center gap-2 mb-3 py-2 px-3" role="alert" style="border-radius:0.75rem; font-weight:600;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.alert-autohide').forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 500);
            }, 2000);
        });
    });
</script>
@endpush


@push('styles')
<style>
    /* Hide the global layout flash alert AND its wrapper container on this page
       — we show our own inline one above the table instead */
    main > .container {
        display: none !important;
    }
</style>
@endpush
