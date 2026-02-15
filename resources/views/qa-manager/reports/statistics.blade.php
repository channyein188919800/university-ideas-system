@extends('layouts.app')

@section('title', 'Statistics Report - University Ideas System')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-chart-bar"></i> Statistics Report</h2>
            <p class="text-muted mb-0">Department-wise idea statistics</p>
        </div>
        <a href="{{ route('qa-manager.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $totalIdeas }}</h3>
                    <p class="text-muted mb-0">Total Ideas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $departmentStats->sum('contributors_count') }}</h3>
                    <p class="text-muted mb-0">Total Contributors</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $departmentStats->count() }}</h3>
                    <p class="text-muted mb-0">Departments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Statistics Table -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-building"></i> Department Breakdown
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Ideas</th>
                            <th>Percentage</th>
                            <th>Contributors</th>
                            <th>Avg Ideas/Contributor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departmentStats as $dept)
                            <tr>
                                <td><strong>{{ $dept['name'] }}</strong></td>
                                <td>{{ $dept['ideas_count'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: {{ $dept['percentage'] }}%"
                                                 aria-valuenow="{{ $dept['percentage'] }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span>{{ $dept['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td>{{ $dept['contributors_count'] }}</td>
                                <td>
                                    {{ $dept['contributors_count'] > 0 ? round($dept['ideas_count'] / $dept['contributors_count'], 2) : 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No department data available.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr class="table-primary">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalIdeas }}</strong></td>
                            <td><strong>100%</strong></td>
                            <td><strong>{{ $departmentStats->sum('contributors_count') }}</strong></td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Download Section -->
    <div class="card mt-4">
        <div class="card-header">
            <i class="fas fa-download"></i> Data Export
        </div>
        <div class="card-body">
            <p class="text-muted">Download all data after the final closure date.</p>
            <div class="d-flex gap-2">
                <a href="{{ route('qa-manager.reports.download-csv') }}" class="btn btn-primary">
                    <i class="fas fa-file-csv"></i> Download CSV
                </a>
                <a href="{{ route('qa-manager.reports.download-documents') }}" class="btn btn-success">
                    <i class="fas fa-file-archive"></i> Download Documents (ZIP)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
