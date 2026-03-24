@extends('layouts.qa-coordinator')

@section('title', 'Manage Users - ' . auth()->user()->department->name)

@section('content')
<!-- Topbar -->
<div class="qa-topbar reveal" style="--delay: 0s;">
    <div>
        <h3>Manage Staff</h3>
        <p>{{ auth()->user()->department->name }} · {{ $staff->total() }} members</p>
    </div>
    <button class="btn btn-primary" onclick="sendReminderToAll()">
        <i class="bi bi-envelope"></i> Remind All
    </button>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="qa-stat-card reveal" style="--delay: 0.05s;">
            <div class="stat-icon users"><i class="bi bi-people-fill"></i></div>
            <h4>{{ $totalStaff }}</h4>
            <p>Total Staff</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="qa-stat-card reveal" style="--delay: 0.1s;">
            <div class="stat-icon" style="background: #48bb78;"><i class="bi bi-check-circle-fill"></i></div>
            <h4>{{ $contributorsCount }}</h4>
            <p>Active Staff</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="qa-stat-card reveal" style="--delay: 0.15s;">
            <div class="stat-icon" style="background: #f56565;"><i class="bi bi-hourglass-split"></i></div>
            <h4>{{ $pendingCount }}</h4>
            <p>Not Active Staff</p>
        </div>
    </div>
</div>

<!-- Staff List -->
<div class="qa-card reveal" style="--delay: 0.2s;">
    <div class="qa-card-header">
        <h5><i class="bi bi-people"></i> Staff Members</h5>
        <div class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm" placeholder="Search staff..." id="searchInput">
            <select class="form-select form-select-sm w-auto" id="filterSelect">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="not_active">Not Active</option>
            </select>
        </div>
    </div>
    <div class="qa-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="staffTable">
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Email</th>
                        <th>Ideas</th>
                        <th>Comments</th>
                        <th>Last Active</th>
                        <th>Status</th>
                        <th>Actions</th>
                     </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                        <tr data-status="{{ ($member->ideas_count > 0 || $member->comments_count > 0) ? 'active' : 'not_active' }}">
                             <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                         style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $member->name }}</strong>
                                        @if($member->id === auth()->id())
                                            <span class="badge bg-info ms-1">You</span>
                                        @endif
                                    </div>
                                </div>
                             </td>
                             <td>{{ $member->email }}</td>
                             <td>
                                <span class="badge bg-primary">{{ $member->ideas_count }}</span>
                             </td>
                             <td>
                                <span class="badge bg-info">{{ $member->comments_count }}</span>
                             </td>
                             <td>
                                @if($member->last_active_at)
                                    <small>{{ $member->last_active_at->diffForHumans() }}</small>
                                @else
                                    <small class="text-muted">Never</small>
                                @endif
                             </td>
                             <td>
                                @if($member->ideas_count > 0 || $member->comments_count > 0)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Not Active</span>
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
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted"></i>
                                <p class="mt-2">No staff found in your department</p>
                             </td>
                         </tr>
                    @endforelse
                </tbody>
             </table>
        </div>
    </div>
    
    @if($staff->hasPages())
        <div class="qa-card-footer border-top p-3">
            {{ $staff->links() }}
        </div>
    @endif
</div>

<!-- Include loading modal and reminder functions -->
@include('qa-coordinator.partials.reminder-modals')
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#staffTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Filter functionality - FIXED
    document.getElementById('filterSelect')?.addEventListener('change', function() {
        const filter = this.value;
        const rows = document.querySelectorAll('#staffTable tbody tr');
        
        rows.forEach(row => {
            const status = row.dataset.status;
            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'active' && status === 'active') {
                row.style.display = '';
            } else if (filter === 'not_active' && status === 'not_active') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Reminder functions
    function sendReminderToAll() {
        if (!confirm('Send reminder to all staff members?')) return;
        
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
        });
    }

    function sendReminderToUser(userId, userName) {
        if (!confirm(`Send reminder to ${userName}?`)) return;
        
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
        });
    }

    // Setup reminder buttons
    document.querySelectorAll('.remind-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            sendReminderToUser(userId, userName);
        });
    });
</script>
@endpush