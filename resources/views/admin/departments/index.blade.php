@extends('layouts.app')

@section('title', 'Manage Departments - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center mb-4 admin-users-header">
                <div class="admin-header-left">
                    <h2><i class="fas fa-building"></i> Manage Departments</h2>
                    <p class="text-muted mb-0">View and manage university departments</p>
                    <div class="toast-container admin-users-toast">
                        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span id="toastMessage">Department updated successfully!</span>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
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
                                                <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit fa-fw"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.departments.destroy', $department) }}" class="d-inline" data-confirm="Are you sure you want to delete this department?">
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
    .admin-header-left {
        position: relative;
        padding-right: 420px;
    }
    .admin-users-toast {
        position: absolute;
        top: -2px;
        left: 240px;
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
        .admin-header-left {
            padding-right: 0;
        }
        .admin-users-toast {
            position: relative;
            left: 0;
            top: 6px;
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
