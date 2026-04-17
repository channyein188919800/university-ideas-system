@extends('layouts.qa-manager')

@section('title', 'Manage Departments - University Ideas System')

@section('content')
            <div class="qa-header-section mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1 class="qa-header-title">
                        <i class="fas fa-building"></i> Manage Departments
                    </h1>
                    <p class="qa-header-subtitle">View and manage university departments</p>
                    <div class="toast-container mt-3">
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
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('qa-manager.departments.create') }}" class="btn btn-primary">
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
                                                <a href="{{ route('qa-manager.departments.edit', $department) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit fa-fw"></i>
                                                </a>
                                                <form method="POST" action="{{ route('qa-manager.departments.destroy', $department) }}" class="d-inline" data-confirm="Are you sure you want to delete this department?">
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
<div class="mt-3">
    {{ $departments->links() }}
</div>
@endsection

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
    .qa-header-section {
        background: white;
        border-radius: 20px;
        padding: 1rem 1.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
    }

    .qa-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0 0 0.5rem 0;
        display: inline-flex;
        align-items: center;
    }

    .qa-header-title i {
        color: #d69e2e;
        font-size: 1.5rem;
        margin-right: 0.75rem;
    }

    .qa-header-subtitle {
        color: #4a5568;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .qa-header-subtitle:before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 4px;
        background: #d69e2e;
        border-radius: 50%;
        margin-right: 0.75rem;
    }

    .toast-container {
        margin-top: 0.75rem;
    }

    .alert-success {
        display: none !important;
    }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }
        
        .card-header i {
            margin-right: 0.5rem;
            color: var(--accent-color);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border: none;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border: none;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border: none;
            color: white;
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border: none;
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #b7791f;
            color: white;
        }
        /* Table Styles */
        .table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
        }
        
        .table tbody tr:hover {
            background-color: var(--light-bg);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 1.75rem;
            }
            
            .stats-number {
                font-size: 1.5rem;
            }
            
            .idea-stats {
                flex-wrap: wrap;
                gap: 0.75rem;
            }
        }
</style>
@endpush