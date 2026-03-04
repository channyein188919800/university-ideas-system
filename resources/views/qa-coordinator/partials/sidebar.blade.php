<aside class="qa-sidebar" id="qaSidebar">
    <div class="qa-brand">
        <span class="qa-brand-icon"><i class="bi bi-building"></i></span>
        <div>
            <h5 class="mb-0">QA Coordinator</h5>
            <small>{{ auth()->user()->department->name ?? 'Department' }}</small>
        </div>
    </div>

    <!-- MAIN NAVIGATION -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Main</p>
        
        <!-- Dashboard -->
        <a href="{{ route('qa-coordinator.dashboard') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <!-- STATISTICS & REPORTS -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Analytics</p>
        
        <!-- Statistics -->
        <a href="{{ route('qa-coordinator.statistics') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.statistics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i>
            <span>Department Statistics</span>
        </a>
        
        <!-- Exception Reports -->
        <a href="{{ route('qa-coordinator.reports.exceptions') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.reports.exceptions') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Exception Reports</span>
            @php
                $exceptionsCount = \App\Models\Idea::where('department_id', auth()->user()->department_id)
                    ->where('status', 'approved')
                    ->doesntHave('comments')
                    ->count();
            @endphp
            @if($exceptionsCount > 0)
                <span class="badge bg-warning ms-auto">{{ $exceptionsCount }}</span>
            @endif
        </a>
    </div>

    <!-- MANAGEMENT -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Management</p>
        
        <!-- Staff List -->
        <a href="{{ route('qa-coordinator.staff.index') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.staff.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Department Staff</span>
            @php
                $pendingStaff = \App\Models\User::where('department_id', auth()->user()->department_id)
                    ->where('role', 'staff')
                    ->whereDoesntHave('ideas')
                    ->whereDoesntHave('comments')
                    ->count();
            @endphp
            @if($pendingStaff > 0)
                <span class="badge bg-primary ms-auto">{{ $pendingStaff }}</span>
            @endif
        </a>
        
        <!-- Notifications -->
        <a href="{{ route('qa-coordinator.notifications') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.notifications') ? 'active' : '' }}">
            <i class="bi bi-bell"></i>
            <span>Notifications</span>
            @php
                $unreadCount = auth()->user()->unreadNotifications->count();
            @endphp
            @if($unreadCount > 0)
                <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>

    <!-- IDEAS (Quick Links) -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Quick Views</p>
        
        <a href="{{ route('ideas.index', ['department' => auth()->user()->department_id]) }}" 
           class="qa-nav-link">
            <i class="bi bi-lightbulb"></i>
            <span>All Department Ideas</span>
        </a>
        
        <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="qa-nav-link">
            <i class="bi bi-fire"></i>
            <span>Popular Ideas</span>
        </a>
        
        <a href="{{ route('ideas.index', ['sort' => 'latest']) }}" class="qa-nav-link">
            <i class="bi bi-clock-history"></i>
            <span>Latest Ideas</span>
        </a>
    </div>

    <!-- ACCOUNT -->
    <div class="qa-nav-group mt-auto">
        <p class="qa-nav-title">Account</p>
        
        <a href="{{ route('home') }}" class="qa-nav-link">
            <i class="bi bi-house-door"></i>
            <span>Main Site</span>
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
            @csrf
            <button type="submit" class="qa-nav-link w-100">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>