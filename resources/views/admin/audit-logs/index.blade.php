@extends('layouts.app')

@section('title', 'System Audit Logs - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="qa-topbar">
            <div>
                <h3><i class="bi bi-journal-text"></i> Audit Logs</h3>
                <p>Track administrator actions and important system events.</p>
            </div>
            <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="btn btn-primary">
                <i class="bi bi-download me-1"></i> Export Logs CSV
            </a>
        </div>

        <div class="qa-card mb-3">
            <div class="qa-card-body">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-2">
                    <div class="col-lg-5">
                        <input type="text" name="search" class="form-control" value="{{ $filters['search'] }}"
                               placeholder="Search by administrator, action, or details">
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
                        <button class="btn btn-outline-primary" type="submit">
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
                                    <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $log->actor?->name ?? 'System' }}</div>
                                        <small class="text-muted">{{ $log->actor?->email ?? '-' }}</small>
                                    </td>
                                    <td><code>{{ $log->action }}</code></td>
                                    <td>
                                        <div>{{ $log->details }}</div>
                                        @if($log->target_type || $log->target_id)
                                            <small class="text-muted">
                                                {{ $log->target_type ?? 'N/A' }}{{ $log->target_id ? ' #' . $log->target_id : '' }}
                                            </small>
                                        @endif
                                    </td>
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
    </section>
</div>
@endsection

@push('styles')
<style>
    .qa-topbar {
        background: rgba(255, 255, 255, 0.86);
        border: 1px solid #e2e8f4;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        margin-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .qa-topbar h3 {
        font-weight: 700;
        color: #1c2a45;
        margin-bottom: 0.25rem;
        font-size: 1.5rem;
    }

    .qa-topbar p {
        color: #6b7891;
        margin-bottom: 0;
        font-size: 0.9rem;
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

    @media (max-width: 767.98px) {
        .qa-topbar {
            flex-direction: column;
            align-items: flex-start;
                        </div>
        }
    }
</style>
@endpush

@include('admin.partials.sidebar-assets')
