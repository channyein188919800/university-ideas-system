@extends('layouts.app')

@section('title', 'System Audit Logs - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h2 class="mb-1"><i class="bi bi-journal-text"></i> System Audit Logs</h2>
                    <p class="text-muted mb-0">Track administrator actions and important system events.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="btn btn-primary">
                        <i class="bi bi-download"></i> Export Logs
                    </a>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.audit-logs.index') }}">
                        <div class="row g-2">
                            <div class="col-lg-5">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    value="{{ $filters['search'] }}"
                                    placeholder="Search by administrator, action, or details..."
                                >
                            </div>
                            <div class="col-lg-3">
                                <select name="action" class="form-select">
                                    <option value="">Filter by Action Type</option>
                                    @foreach($actionTypes as $actionType)
                                        <option value="{{ $actionType }}" {{ $filters['action'] === $actionType ? 'selected' : '' }}>
                                            {{ $actionType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="success" {{ $filters['status'] === 'success' ? 'selected' : '' }}>Success</option>
                                    <option value="warning" {{ $filters['status'] === 'warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="failed" {{ $filters['status'] === 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div class="col-lg-2 d-grid">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bi bi-funnel"></i> Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="min-width: 170px;">Timestamp</th>
                                    <th style="min-width: 180px;">Administrator</th>
                                    <th style="min-width: 150px;">Action</th>
                                    <th>Details / Target</th>
                                    <th style="min-width: 110px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('M d, Y - h:i A') }}</td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $log->actor?->name ?? 'System' }}</div>
                                                <small class="text-muted">{{ $log->actor?->email ?? '-' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge action-pill">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            <div>{{ $log->details }}</div>
                                            @if($log->target_type || $log->target_id)
                                                <small class="text-muted">
                                                    {{ $log->target_type ?? 'N/A' }}{{ $log->target_id ? ' #' . $log->target_id : '' }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status === 'success')
                                                <span class="badge text-bg-success"><i class="bi bi-check-circle-fill"></i> Success</span>
                                            @elseif($log->status === 'warning')
                                                <span class="badge text-bg-warning"><i class="bi bi-exclamation-triangle-fill"></i> Warning</span>
                                            @else
                                                <span class="badge text-bg-danger"><i class="bi bi-x-circle-fill"></i> Failed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No audit logs found for current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .action-pill {
        background: #eef4ff;
        color: #1d4ed8;
        border: 1px solid #dbe7ff;
        font-size: 0.78rem;
    }
</style>
@endpush

@include('admin.partials.sidebar-assets')
