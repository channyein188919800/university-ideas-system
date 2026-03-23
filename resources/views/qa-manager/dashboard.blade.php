@extends('layouts.qa-manager')

@section('title', 'QA Manager Dashboard - University Ideas System')

@section('content')
<!-- @php
    $authUser = auth()->user();
    $lastLoginAt = $authUser->last_login_at;
@endphp -->

<!-- Topbar -->
<div class="qa-topbar">
    <div>
        <h3 class="mb-1">{{ $lastLoginAt ? 'Welcome back' : 'Welcome' }}, <span style="color: #0b53fb;">{{ $authUser->name }}</span></h3>        
        <p class="mb-0">University Wide · QA Manager</p>
        <small class="text-muted d-block">
            @if(!$lastLoginAt)
                Welcome! This appears to be your first login.
            @else
                Last login: {{ $lastLoginAt->format('M d, Y h:i A') }}
            @endif
        </small>
    </div>
    <a href="{{ route('qa-manager.audit-logs.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-journal-text"></i> View Audit Logs
    </a>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Total Users</p>
                    <h4>{{ $stats['total_users'] }}</h4>
                </div>
                <div class="stat-icon users">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Total Ideas</p>
                    <h4>{{ $stats['total_ideas'] }}</h4>
                </div>
                <div class="stat-icon ideas">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Total Comments</p>
                    <h4>{{ $stats['total_comments'] }}</h4>
                </div>
                <div class="stat-icon comments">
                    <i class="bi bi-chat-left-text-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="qa-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Departments</p>
                    <h4>{{ $stats['total_departments'] }}</h4>
                </div>
                <div class="stat-icon departments">
                    <i class="bi bi-buildings-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-calendar-event"></i> System Status</h5>
    </div>
    <div class="qa-card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3">Idea Submission</h6>
                <p class="mb-0">
                    @if($ideaClosureDate)
                        @if(now()->lt($ideaClosureDate))
                            <span class="badge bg-success">Open</span>
                            <small class="text-muted">Closes {{ $ideaClosureDate->diffForHumans() }}</small>
                        @else
                            <span class="badge bg-danger">Closed</span>
                            <small class="text-muted">Closed on {{ $ideaClosureDate->format('M d, Y') }}</small>
                        @endif
                    @else
                        <span class="badge bg-secondary">Not Set</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">Commenting</h6>
                <p class="mb-0">
                    @if($finalClosureDate)
                        @if(now()->lt($finalClosureDate))
                            <span class="badge bg-success">Open</span>
                            <small class="text-muted">Closes {{ $finalClosureDate->diffForHumans() }}</small>
                        @else
                            <span class="badge bg-danger">Closed</span>
                            <small class="text-muted">Closed on {{ $finalClosureDate->format('M d, Y') }}</small>
                        @endif
                    @else
                        <span class="badge bg-secondary">Not Set</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Department Statistics -->
<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-bar-chart-fill"></i> Department Statistics</h5>
    </div>
    <div class="qa-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Ideas</th>
                        <th>Percentage</th>
                        <th>Contributors</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentStats as $dept)
                        <tr>
                            <td><strong>{{ $dept->name }}</strong></td>
                            <td>{{ $dept->ideas_count }}</td>
                            <td>{{ $dept->percentage }}%</td>
                            <td>{{ $dept->contributors_count }}</td>
                            <td>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-success" 
                                         role="progressbar" 
                                         style="width: {{ $dept->percentage }}%"
                                         aria-valuenow="{{ $dept->percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 mt-2">No department data available.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
