@extends('layouts.qa-coordinator')

@section('title', 'Department Statistics - ' . auth()->user()->department->name)

@section('content')
<!-- Topbar -->
<div class="qa-topbar reveal" style="--delay: 0s;">
    <div>
        <h3>Department Statistics</h3>
        <p>{{ auth()->user()->department->name }} · Analytics & Insights</p>
    </div>
    <div class="d-flex gap-2">
        <span class="dept-badge">
            <i class="bi bi-building me-1"></i> {{ auth()->user()->department->name }}
        </span>
        <select class="form-select form-select-sm w-auto" id="yearSelect">
            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
            <option value="{{ date('Y')-1 }}">{{ date('Y')-1 }}</option>
        </select>
    </div>
</div>

<!-- Stats Overview Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="qa-stat-card reveal" style="--delay: 0.05s;">
            <div class="stat-icon ideas"><i class="bi bi-lightbulb-fill"></i></div>
            <h4>{{ $totalIdeas }}</h4>
            <p>Total Ideas</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="qa-stat-card reveal" style="--delay: 0.1s;">
            <div class="stat-icon comments"><i class="bi bi-chat-left-text-fill"></i></div>
            <h4>{{ $totalComments }}</h4>
            <p>Total Comments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="qa-stat-card reveal" style="--delay: 0.15s;">
            <div class="stat-icon users"><i class="bi bi-people-fill"></i></div>
            <h4>{{ $totalStaff }}</h4>
            <p>Total Staff</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="qa-stat-card reveal" style="--delay: 0.2s;">
            <div class="stat-icon participation"><i class="bi bi-graph-up"></i></div>
            <h4>{{ $participationRate }}%</h4>
            <p>Participation Rate</p>
        </div>
    </div>
</div>

<!-- Department Comparison Chart -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="qa-card reveal" style="--delay: 0.25s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-bar-chart"></i> Ideas by Department (Comparison)</h5>
            </div>
            <div class="qa-card-body">
                <div class="chart-container" style="height: 400px;">
                    <canvas id="deptComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="qa-card reveal" style="--delay: 0.3s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-table"></i> Department Statistics Breakdown</h5>
                <div>
                    <span class="badge bg-primary me-2">Your Department</span>
                </div>
            </div>
            <div class="qa-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Ideas</th>
                                <th>% of Total</th>
                                <th>Comments</th>
                                <th>Contributors</th>
                                <th>Staff Count</th>
                                <th>Participation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allDepartments as $dept)
                                <tr class="{{ $dept->id === auth()->user()->department_id ? 'table-primary' : '' }}">
                                    <td>
                                        <strong>{{ $dept->name }}</strong>
                                        @if($dept->id === auth()->user()->department_id)
                                            <span class="badge bg-primary ms-2">Your Dept</span>
                                        @endif
                                    </td>
                                    <td>{{ $dept->ideas_count }}</td>
                                    <td>{{ $dept->ideas_percentage }}%</td>
                                    <td>{{ $dept->comments_count }}</td>
                                    <td>{{ $dept->contributors_count }}</td>
                                    <td>{{ $dept->staff_count }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-sm flex-grow-1 me-2" style="width: 100px;">
                                                <div class="progress-bar bg-success" style="width: {{ $dept->participation_rate }}%"></div>
                                            </div>
                                            <small>{{ $dept->participation_rate }}%</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="qa-card reveal" style="--delay: 0.35s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-pie-chart"></i> Ideas by Category (Your Department)</h5>
            </div>
            <div class="qa-card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="qa-card reveal" style="--delay: 0.4s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-graph-up"></i> Monthly Trends</h5>
            </div>
            <div class="qa-card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="monthlyTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department Comparison Chart
        const deptCtx = document.getElementById('deptComparisonChart');
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($deptComparison['labels']) !!},
                datasets: [{
                    label: 'Ideas',
                    data: {!! json_encode($deptComparison['data']) !!},
                    backgroundColor: {!! json_encode($deptComparison['colors']) !!},
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Category Pie Chart
        const catCtx = document.getElementById('categoryPieChart');
        new Chart(catCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($categoryStats['labels']) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats['data']) !!},
                    backgroundColor: [
                        '#3577ff', '#00a58b', '#e58f07', '#7946fd', 
                        '#48bb78', '#f56565', '#9f75ff', '#ed64a6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Monthly Trends Chart
        const monthCtx = document.getElementById('monthlyTrendsChart');
        new Chart(monthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyStats['labels']) !!},
                datasets: [
                    {
                        label: 'Ideas',
                        data: {!! json_encode($monthlyStats['ideas']) !!},
                        borderColor: '#3577ff',
                        backgroundColor: 'rgba(53, 119, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Comments',
                        data: {!! json_encode($monthlyStats['comments']) !!},
                        borderColor: '#48bb78',
                        backgroundColor: 'rgba(72, 187, 120, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush