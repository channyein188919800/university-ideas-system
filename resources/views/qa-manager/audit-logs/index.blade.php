@extends('layouts.qa-manager')

@section('title', 'QA Manager Audit Logs - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-journal-text"></i> Audit Logs</h3>
        <p>Track moderation and export actions by QA Manager</p>
    </div>
    <a href="{{ route('qa-manager.audit-logs.export', request()->query()) }}" class="btn btn-primary">
        <i class="bi bi-download me-1"></i> Export Logs CSV
    </a>
</div>

<div class="qa-card mb-3">
    <div class="qa-card-body">
        <form method="GET" action="{{ route('qa-manager.audit-logs.index') }}" class="row g-2">
            <div class="col-lg-5">
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] }}"
                       placeholder="Search by user, action, or details">
            </div>
            <div class="col-lg-3">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
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
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i> Filter
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
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at?->format('M d, Y H:i') }}</td>
                            <td>{{ $log->actor?->name ?? 'System' }}</td>
                            <td><code>{{ $log->action }}</code></td>
                            <td>{{ $log->details }}</td>
                            <td>
                                <span class="badge {{ $log->status === 'success' ? 'bg-success' : ($log->status === 'warning' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No logs found.</td>
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
@endsection
