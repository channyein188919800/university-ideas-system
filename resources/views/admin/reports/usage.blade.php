@extends('layouts.app')

@section('title', 'Usage Reports - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="qa-header-section mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="qa-header-title">
                    <i class="bi bi-bar-chart-line"></i> Usage Reports
                </h1>
                <p class="qa-header-subtitle">Last 30 days (since {{ $since->format('M d, Y') }})</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-journal-text"></i> Audit Logs
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="report-card">
                    <h5 class="report-title"><i class="bi bi-eye"></i> Most Viewed Pages</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Page</th>
                                    <th class="text-end">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPages as $page)
                                    <tr>
                                        <td>{{ Str::after($page->details, 'Page view: ') }}</td>
                                        <td class="text-end">{{ number_format($page->total) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted">No page views recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="report-card">
                    <h5 class="report-title"><i class="bi bi-people-fill"></i> Most Active Users</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeUsers as $entry)
                                    <tr>
                                        <td>
                                            {{ $entry['user']->name ?? 'Unknown User' }}
                                            <small class="text-muted d-block">{{ $entry['user']->email ?? 'No email' }}</small>
                                        </td>
                                        <td class="text-end">{{ number_format($entry['total']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted">No activity recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="report-card">
                    <h5 class="report-title"><i class="bi bi-browser-chrome"></i> Browsers In Use</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Browser</th>
                                    <th class="text-end">Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($browserCounts as $browser => $count)
                                    <tr>
                                        <td>{{ $browser }}</td>
                                        <td class="text-end">{{ number_format($count) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted">No browser data recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="report-card">
                    <h5 class="report-title"><i class="bi bi-flag-fill"></i> Open Inappropriate Post Reports</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Idea</th>
                                    <th>Reason</th>
                                    <th class="text-end">Reported</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($openReports as $report)
                                    <tr>
                                        <td>
                                            <a href="{{ route('ideas.show', $report->idea) }}">
                                                {{ Str::limit($report->idea->title ?? 'Idea unavailable', 40) }}
                                            </a>
                                            <small class="text-muted d-block">{{ $report->reporter->name ?? 'Unknown user' }}</small>
                                        </td>
                                        <td>{{ $report->reason }}</td>
                                        <td class="text-end">{{ $report->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted">No open reports.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@include('admin.partials.sidebar-assets')

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

    .report-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #e2e8f4;
        box-shadow: 0 6px 20px rgba(30, 58, 95, 0.06);
    }

    .report-title {
        font-weight: 700;
        color: #1e3a5f;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .report-title i {
        color: #d69e2e;
    }
</style>
@endpush
