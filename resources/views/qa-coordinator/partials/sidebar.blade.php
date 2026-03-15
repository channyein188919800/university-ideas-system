<aside class="qa-sidebar" id="qaSidebar">
    <div class="qa-brand" style="justify-content:center;padding:0.5rem 0 0.8rem;">
        <img src="{{ asset('images/logo1_no_bg.png') }}"
             alt="University Ideas"
             style="width:210px;max-height:110px;object-fit:contain;filter:brightness(1.1) drop-shadow(0 4px 12px rgba(0,0,0,0.4));">
    </div>

    <!-- OVERVIEW -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Overview</p>
        
        <a href="{{ route('qa-coordinator.dashboard') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('ideas.index', ['department' => auth()->user()->department_id]) }}" 
           class="qa-nav-link">
            <i class="bi bi-lightbulb"></i>
            <span>All Ideas</span>
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

    <!-- ADMINISTRATION -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Administration</p>
        
        <a href="{{ route('qa-coordinator.statistics') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.statistics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i>
            <span>Department Statistics</span>
        </a>
        
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
        
        <!-- Staff List -->
        <a href="{{ route('qa-coordinator.staff.index') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.staff.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Manage Users</span>
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

    <!-- ACCOUNT -->
    <div class="qa-nav-group mt-auto">
        <p class="qa-nav-title">Account</p>

        <a href="{{ route('qa-coordinator.profile.edit') }}"
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            <span>My Profile</span>
        </a>

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
