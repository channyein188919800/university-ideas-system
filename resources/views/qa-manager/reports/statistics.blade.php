@extends('layouts.qa-manager')

@section('title', 'Statistics Report - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-bar-chart-line"></i> Statistics Dashboard</h3>
        <p>Department contributions and export center for QA Manager</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <p>Total Ideas</p>
            <h4>{{ $totalIdeas }}</h4>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <p>Total Contributors</p>
            <h4>{{ $departmentStats->sum('contributors_count') }}</h4>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <p>Total Comments</p>
            <h4>{{ $totalComments }}</h4>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <p>Total Votes</p>
            <h4>{{ $totalVotes }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="qa-card h-100">
            <div class="qa-card-header">
                <h5><i class="bi bi-bar-chart"></i> Ideas Per Department</h5>
            </div>
            <div class="qa-card-body">
                <canvas id="ideasPerDepartmentChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="qa-card h-100">
            <div class="qa-card-header">
                <h5><i class="bi bi-pie-chart"></i> Distribution %</h5>
            </div>
            <div class="qa-card-body">
                <canvas id="distributionChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-table"></i> Department Breakdown</h5>
    </div>
    <div class="qa-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Ideas</th>
                        <th>Distribution</th>
                        <th>Contributors</th>
                        <th>Avg Ideas/Contributor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentStats as $dept)
                        <tr>
                            <td><strong>{{ $dept['name'] }}</strong></td>
                            <td>{{ $dept['ideas_count'] }}</td>
                            <td>{{ $dept['percentage'] }}%</td>
                            <td>{{ $dept['contributors_count'] }}</td>
                            <td>{{ $dept['contributors_count'] > 0 ? round($dept['ideas_count'] / $dept['contributors_count'], 2) : 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 mt-2">No department statistics available.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="qa-card" id="export-section">
    <div class="qa-card-header">
        <h5><i class="bi bi-download"></i> Data Export</h5>
    </div>
    <div class="qa-card-body">
        <p class="mb-2">
            Final Closure Date:
            <strong>{{ $finalClosureDate ? $finalClosureDate->format('F d, Y') : 'Not set' }}</strong>
        </p>
        <p class="text-muted mb-3">
            Export is available only after the final closure date.
        </p>

        @if(!$canExport)
            <div class="alert alert-warning">
                <i class="bi bi-lock-fill me-1"></i>
                Export is currently locked.
            </div>
        @endif

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('qa-manager.reports.download-csv', ['type' => 'ideas']) }}" class="btn btn-primary {{ $canExport ? '' : 'disabled' }}">
                <i class="bi bi-filetype-csv me-1"></i> Ideas CSV
            </a>
            <a href="{{ route('qa-manager.reports.download-csv', ['type' => 'comments']) }}" class="btn btn-primary {{ $canExport ? '' : 'disabled' }}">
                <i class="bi bi-filetype-csv me-1"></i> Comments CSV
            </a>
            <a href="{{ route('qa-manager.reports.download-csv', ['type' => 'votes']) }}" class="btn btn-primary {{ $canExport ? '' : 'disabled' }}">
                <i class="bi bi-filetype-csv me-1"></i> Votes CSV
            </a>
            <a href="{{ route('qa-manager.reports.download-csv', ['type' => 'users']) }}" class="btn btn-primary {{ $canExport ? '' : 'disabled' }}">
                <i class="bi bi-filetype-csv me-1"></i> Users CSV
            </a>
            <a href="{{ route('qa-manager.reports.download-documents') }}" class="btn btn-success {{ $canExport ? '' : 'disabled' }}">
                <i class="bi bi-file-earmark-zip me-1"></i> Documents ZIP
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = @json($departmentStats->pluck('name')->values());
        const ideaCounts = @json($departmentStats->pluck('ideas_count')->values());
        const percentages = @json($departmentStats->pluck('percentage')->values());
        const contributorCounts = @json($departmentStats->pluck('contributors_count')->values());

        const barCtx = document.getElementById('ideasPerDepartmentChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Ideas',
                            data: ideaCounts,
                            backgroundColor: '#3577ff',
                            borderRadius: 6
                        },
                        {
                            label: 'Contributors',
                            data: contributorCounts,
                            backgroundColor: '#00a58b',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const pieCtx = document.getElementById('distributionChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: percentages,
                        backgroundColor: [
                            '#3577ff', '#00a58b', '#e58f07', '#7946fd', '#ef4444',
                            '#06b6d4', '#84cc16', '#f97316', '#8b5cf6', '#14b8a6'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
