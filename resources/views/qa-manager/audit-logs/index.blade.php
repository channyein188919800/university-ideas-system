@extends('layouts.qa-manager')

@section('title', 'QA Manager Audit Logs - University Ideas System')

@section('content')
    <div class="qa-header-section mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="qa-header-title">
                <i class="bi bi-journal-text"></i> Audit Logs
            </h1>
            <p class="qa-header-subtitle">Track moderation and export actions by QA Manager</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('qa-manager.audit-logs.export', request()->query()) }}" class="btn btn-primary">
                <i class="bi bi-download"></i> Export Logs CSV
            </a>
        </div>
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
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="min-width: 170px;">Timestamp</th>
                            <th style="min-width: 180px;">User</th>
                            <th style="min-width: 150px;">Action</th>
                            <th>Details</th>
                            <th style="min-width: 110px;">Status</th>
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
                                    <span
                                        class="badge {{ $log->status === 'success' ? 'bg-success' : ($log->status === 'warning' ? 'bg-warning text-dark' : 'bg-danger') }}">
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

    .qa-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e4ebf8;
        box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .qa-card-body {
        padding: 1rem 1.1rem;
    }
</style>
@endpush
