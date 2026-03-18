<style>
    .admin-profile {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 0.85rem 0.9rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(7, 16, 33, 0.35);
    }

    .admin-profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(138, 181, 255, 0.25), rgba(255, 255, 255, 0.12));
        border: 2px solid rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f8fbff;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        overflow: hidden;
    }

    .admin-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .admin-profile-meta {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        min-width: 0;
    }

    .admin-profile-name {
        color: #f3f7ff;
        font-weight: 700;
        font-size: 0.98rem;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .admin-profile-role {
        color: #a9c2f0;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
</style>
<aside class="qa-sidebar" id="qaSidebar">
    <div class="qa-brand" style="justify-content:center;padding:0.5rem 0 0.8rem;">
        <img src="{{ asset('images/logo_sidebar.png') }}"
             alt="University Ideas"
             style="width:210px;max-height:110px;object-fit:contain;filter:brightness(1.1) drop-shadow(0 4px 12px rgba(0,0,0,0.4));">
    </div>

    <!-- Admin profile -->
    <div class="admin-profile">
        <div class="admin-profile-avatar">
            @if(auth()->user()->profile_image_url)
                <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }} profile photo">
            @else
                <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            @endif
        </div>
        <div class="admin-profile-meta">
            <div class="admin-profile-name">{{ auth()->user()->name }}</div>
            <div class="admin-profile-role">
                {{ \Illuminate\Support\Str::title(str_replace('_', ' ', auth()->user()->role)) }}
            </div>
        </div>
    </div>

    <!-- OVERVIEW -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Overview</p>
        
        <a href="{{ route('qa-coordinator.dashboard') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('qa-coordinator.department.ideas') }}" 
           class="qa-nav-link {{ request()->routeIs('qa-coordinator.department.ideas') ? 'active' : '' }}">
            <i class="bi bi-lightbulb"></i>
            <span>Department Ideas</span>
        </a>

        <a href="{{ route('qa-coordinator.popular.ideas') }}" class="qa-nav-link {{ request()->routeIs('qa-coordinator.popular.ideas') ? 'active' : '' }}">
            <i class="bi bi-fire"></i>
            <span>Popular Ideas</span>
        </a>
        
        <a href="{{ route('qa-coordinator.latest.ideas') }}" class="qa-nav-link {{ request()->routeIs('qa-coordinator.latest.ideas') ? 'active' : '' }}">
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