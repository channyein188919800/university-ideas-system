@extends('layouts.qa-coordinator')

@section('title', 'QA Coordinator Dashboard - ' . (auth()->user()->department->name ?? 'Dashboard'))

@section('content')
@php
    $authUser = auth()->user();
    $departmentName = $authUser->department->name ?? 'No Department';
    $lastLoginAt = $authUser->last_login_at;
@endphp

<!-- Topbar -->
<div class="qa-topbar reveal" style="--delay: 0s;">
    <div>
        <h3>{{ $lastLoginAt ? 'Welcome back' : 'Welcome' }}, {{ $authUser->name }}</h3>
        <p>{{ $departmentName }} Department · QA Coordinator</p>
        <small class="text-muted d-block">
            @if(!$lastLoginAt)
                Welcome! This appears to be your first login.
            @else
                Last login: {{ $lastLoginAt->format('M d, Y h:i A') }}
            @endif
        </small>
    </div>
    <div class="d-flex gap-2">
        <span class="dept-badge">
            <i class="bi bi-building me-1"></i> {{ $departmentName }}
        </span>
        @if(isset($ideaClosureDate) && $ideaClosureDate)
            @if(now()->lt($ideaClosureDate))
                <span class="dept-badge">
                    <i class="bi bi-calendar me-1"></i> Ideas open until {{ $ideaClosureDate->format('M d, Y') }}
                </span>
            @endif
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="qa-stat-card reveal" style="--delay: 0.05s;">
            <div class="stat-icon ideas"><i class="bi bi-lightbulb-fill"></i></div>
            <h4>{{ $stats['total_ideas'] ?? 0 }}</h4>
            <p>Total Ideas</p>
            @if(($stats['ideas_this_month'] ?? 0) > 0)
                <small class="text-success">+{{ $stats['ideas_this_month'] }} this month</small>
            @endif
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="qa-stat-card reveal" style="--delay: 0.1s;">
            <div class="stat-icon comments"><i class="bi bi-chat-left-text-fill"></i></div>
            <h4>{{ $stats['total_comments'] ?? 0 }}</h4>
            <p>Total Comments</p>
            @if(($stats['comments_this_month'] ?? 0) > 0)
                <small>+{{ $stats['comments_this_month'] }} this month</small>
            @endif
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="qa-stat-card reveal" style="--delay: 0.15s;">
            <div class="stat-icon users"><i class="bi bi-people-fill"></i></div>
            <h4>{{ $stats['contributors'] ?? 0 }}</h4>
            <p>Contributors</p>
            <small>out of {{ $stats['total_staff'] ?? 0 }} staff</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="qa-stat-card reveal" style="--delay: 0.2s;">
            <div class="stat-icon participation"><i class="bi bi-graph-up-arrow"></i></div>
            <h4>{{ $stats['participation_rate'] ?? 0 }}%</h4>
            <p>Participation Rate</p>
            <div class="progress progress-sm mt-2">
                <div class="progress-bar progress-bar-success" style="width: {{ $stats['participation_rate'] ?? 0 }}%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-7">
        <div class="qa-card reveal" style="--delay: 0.25s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-bar-chart-fill"></i> Ideas by Category</h5>
                <select class="form-select form-select-sm w-auto" id="categoryPeriod">
                    <option value="all">All Time</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <div class="qa-card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="qa-card reveal" style="--delay: 0.3s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-pie-chart-fill"></i> Staff Participation</h5>
            </div>
            <div class="qa-card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="participationChart"></canvas>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-6">
                        <h6 class="mb-0">{{ $stats['contributors'] ?? 0 }}</h6>
                        <small class="text-muted">Contributors</small>
                    </div>
                    <div class="col-6">
                        <h6 class="mb-0">{{ $stats['non_contributors'] ?? 0 }}</h6>
                        <small class="text-muted">Not Yet Contributed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ideas Lists -->
<div class="row g-4">
    <!-- Recent Ideas -->
    <div class="col-xl-6">
        <div class="qa-card reveal" style="--delay: 0.35s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-lightbulb"></i> Recent Ideas</h5>
                <a href="{{ route('ideas.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="qa-card-body">
                @forelse($recentIdeas ?? [] as $idea)
                    <div class="feed-item with-border">
                        <div class="feed-main">
                            <a href="{{ route('ideas.show', $idea) }}" class="feed-title">
                                {{ Str::limit($idea->title, 60) }}
                            </a>
                            <p class="feed-meta mb-0">
                                <i class="bi bi-person"></i> 
                                {{ $idea->is_anonymous ? 'Anonymous' : ($idea->user->name ?? 'Unknown') }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-chat"></i> {{ $idea->comments_count ?? 0 }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-hand-thumbs-up"></i> {{ $idea->thumbs_up_count ?? 0 }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-clock"></i> {{ $idea->created_at?->diffForHumans() ?? 'N/A' }}
                            </p>
                        </div>
                        @php
                            $score = ($idea->thumbs_up_count ?? 0) - ($idea->thumbs_down_count ?? 0);
                        @endphp
                        <span class="badge {{ $score >= 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $score > 0 ? '+' : '' }}{{ $score }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No ideas from your department yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Popular Ideas -->
    <div class="col-xl-6">
        <div class="qa-card reveal" style="--delay: 0.4s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-fire"></i> Popular Ideas</h5>
                <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="qa-card-body">
                @forelse($popularIdeas ?? [] as $idea)
                    <div class="feed-item with-border">
                        <div class="feed-main">
                            <a href="{{ route('ideas.show', $idea) }}" class="feed-title">
                                {{ Str::limit($idea->title, 60) }}
                            </a>
                            <p class="feed-meta mb-0">
                                <i class="bi bi-person"></i> 
                                {{ $idea->is_anonymous ? 'Anonymous' : ($idea->user->name ?? 'Unknown') }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-hand-thumbs-up"></i> {{ $idea->thumbs_up_count ?? 0 }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-hand-thumbs-down"></i> {{ $idea->thumbs_down_count ?? 0 }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-eye"></i> {{ $idea->views_count ?? 0 }}
                            </p>
                        </div>
                        @php
                            $score = ($idea->thumbs_up_count ?? 0) - ($idea->thumbs_down_count ?? 0);
                        @endphp
                        <span class="badge {{ $score >= 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $score > 0 ? '+' : '' }}{{ $score }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-fire fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No popular ideas yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Staff List -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="qa-card reveal" style="--delay: 0.45s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-people"></i> Department Staff ({{ $stats['total_staff'] ?? 0 }})</h5>
                <button class="btn btn-sm btn-outline-primary" onclick="sendReminderToAll()">
                    <i class="bi bi-envelope"></i> Remind All
                </button>
            </div>
            <div class="qa-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Staff Member</th>
                                <th>Ideas</th>
                                <th>Comments</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff ?? [] as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $member->name }}</strong>
                                                @if($member->id === auth()->id())
                                                    <span class="badge bg-info ms-2">You</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $member->ideas_count ?? 0 }}</td>
                                    <td>{{ $member->comments_count ?? 0 }}</td>
                                    <td>
                                        @if(($member->ideas_count ?? 0) > 0 || ($member->comments_count ?? 0) > 0)
                                            <span class="badge bg-success">Contributor</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($member->id !== auth()->id())
                                            <button class="btn btn-sm btn-outline-primary remind-btn" 
                                                    data-user-id="{{ $member->id }}"
                                                    data-user-name="{{ $member->name }}">
                                                <i class="bi bi-envelope"></i> Remind
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-people fs-1"></i>
                                        <p class="mt-2">No staff found in your department</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exception Reports -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="qa-card reveal" style="--delay: 0.5s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-exclamation-triangle text-warning"></i> Exception Reports</h5>
            </div>
            <div class="qa-card-body">
                <div class="row">
                    <!-- Ideas without comments -->
                    <div class="col-md-6">
                        <h6 class="mb-3"><i class="bi bi-chat-dots me-2"></i>Ideas Without Comments</h6>
                        @if(($ideasWithoutComments ?? collect())->count() > 0)
                            @foreach($ideasWithoutComments as $idea)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                        {{ Str::limit($idea->title, 50) }}
                                    </a>
                                    <span class="badge bg-warning">No comments</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small">✨ All ideas have comments - great engagement!</p>
                        @endif
                    </div>
                    
                    <!-- Anonymous contributions -->
                    <div class="col-md-6">
                        <h6 class="mb-3"><i class="bi bi-incognito me-2"></i>Anonymous Contributions</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h5 class="mb-0">{{ $anonymousStats['ideas'] ?? 0 }}</h5>
                                    <small class="text-muted">Anonymous Ideas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h5 class="mb-0">{{ $anonymousStats['comments'] ?? 0 }}</h5>
                                    <small class="text-muted">Anonymous Comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                <h5 id="loadingMessage">Sending reminders...</h5>
                <p class="text-muted mb-0">Please do not close this window.</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="toastMessage">Success!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts
        initCharts();
        
        // Setup reminder buttons
        setupReminderButtons();
        
        // Category period change
        document.getElementById('categoryPeriod')?.addEventListener('change', function(e) {
            fetchChartData(e.target.value);
        });
    });

    function initCharts() {
        // Category Chart
        const catCtx = document.getElementById('categoryChart');
        if (catCtx) {
            new Chart(catCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['categoryLabels'] ?? ['Teaching', 'Facilities', 'Research', 'Student Life', 'Other']) !!},
                    datasets: [{
                        label: 'Ideas',
                        data: {!! json_encode($chartData['categoryData'] ?? [0,0,0,0,0]) !!},
                        backgroundColor: [
                            'rgba(53, 119, 255, 0.85)',
                            'rgba(0, 165, 139, 0.85)',
                            'rgba(229, 143, 7, 0.85)',
                            'rgba(121, 70, 253, 0.85)',
                            'rgba(72, 187, 120, 0.85)'
                        ],
                        borderColor: [
                            '#3577ff', '#00a58b', '#e58f07', '#7946fd', '#48bb78'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // Participation Chart
        const partCtx = document.getElementById('participationChart');
        if (partCtx) {
            new Chart(partCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Contributors', 'Non-Contributors'],
                    datasets: [{
                        data: [{{ $stats['contributors'] ?? 0 }}, {{ $stats['non_contributors'] ?? 0 }}],
                        backgroundColor: ['#48bb78', '#f56565'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }

    function fetchChartData(period) {
        fetch(`/qa-coordinator/chart-data?period=${period}`)
            .then(response => response.json())
            .then(data => {
                const chart = Chart.getChart('categoryChart');
                if (chart) {
                    chart.data.labels = data.categoryLabels;
                    chart.data.datasets[0].data = data.categoryData;
                    chart.update();
                }
            });
    }

    function setupReminderButtons() {
        const remindAllBtn = document.querySelector('button[onclick="sendReminderToAll()"]');
        if (remindAllBtn) {
            remindAllBtn.addEventListener('click', sendReminderToAll);
        }
        
        document.querySelectorAll('.remind-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                sendReminderToUser(userId, userName);
            });
        });
    }

    function showLoading(message) {
        document.getElementById('loadingMessage').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
        modal.show();
        return modal;
    }

    function showToast(message, isSuccess = true) {
        const toast = document.getElementById('successToast');
        document.getElementById('toastMessage').textContent = message;
        
        toast.classList.remove('bg-success', 'bg-danger');
        toast.classList.add(isSuccess ? 'bg-success' : 'bg-danger');
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    function sendReminderToAll() {
        confirmAction('Send participation reminder to all department staff?', function () {
        
        const modal = showLoading('Sending reminders to all staff...');
        
        fetch('{{ route("qa-coordinator.remind-all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            showToast(data.message || 'Reminders sent successfully!');
        })
        .catch(error => {
            modal.hide();
            showToast('Error sending reminders', false);
            console.error('Error:', error);
        });
        });
    }

    function sendReminderToUser(userId, userName) {
        confirmAction(`Send reminder to ${userName}?`, function () {
        
        const modal = showLoading(`Sending reminder to ${userName}...`);
        
        fetch(`/qa-coordinator/remind-staff/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            showToast(data.message || `Reminder sent to ${userName}`);
        })
        .catch(error => {
            modal.hide();
            showToast('Error sending reminder', false);
            console.error('Error:', error);
        });
        });
    }
</script>
@endpush
