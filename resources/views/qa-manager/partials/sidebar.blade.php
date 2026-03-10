<aside class="qa-sidebar" id="qaSidebar">
    <div class="qa-brand">
        <span class="qa-brand-icon"><i class="bi bi-shield-check"></i></span>
        <div>
            <h5 class="mb-0">QA Manager</h5>
            <small>System Administration</small>
        </div>
    </div>

    <!-- MAIN NAVIGATION -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Main</p>

        <!-- Dashboard -->
        <a href="{{ route('qa-manager.dashboard') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <!-- MANAGEMENT -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Management</p>

        <!-- Categories -->
        <a href="{{ route('qa-manager.categories.index') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            <span>Categories</span>
        </a>

        <a href="{{ route('qa-manager.departments.index') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.departments.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Departments</span>
        </a>

        <a href="{{ route('qa-manager.staff.index') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.staff.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Manage Users</span>
        </a>

        <a href="{{ route('qa-manager.backlog.index') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.backlog.*') ? 'active' : '' }}">
            <i class="bi bi-kanban"></i>
            <span>University Backlog</span>
        </a>
    </div>

    <!-- ANALYTICS & REPORTS -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Analytics</p>

        <!-- Statistics -->
        <a href="{{ route('qa-manager.reports.statistics') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.reports.statistics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i>
            <span>Statistics</span>
        </a>

        <a href="{{ route('qa-manager.reports.statistics') }}#export-section" class="qa-nav-link">
            <i class="bi bi-download"></i>
            <span>Data Export</span>
        </a>

        <!-- Exception Reports -->
        <a href="{{ route('qa-manager.reports.exceptions') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.reports.exceptions') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Exception Reports</span>
        </a>

        <a href="{{ route('qa-manager.audit-logs.index') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.audit-logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>Audit Logs</span>
        </a>
    </div>

    <div class="qa-nav-group">
        <p class="qa-nav-title">System</p>
        <a href="{{ route('qa-manager.settings.index') }}"
            class="qa-nav-link {{ request()->routeIs('qa-manager.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Academic Settings</span>
        </a>
    </div>

    <!-- IDEAS (Quick Links) -->
    <div class="qa-nav-group">
        <p class="qa-nav-title">Quick Views</p>

        <a href="{{ route('ideas.index') }}" class="qa-nav-link">
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