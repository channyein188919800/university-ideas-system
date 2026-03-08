<button class="btn admin-menu-toggle d-lg-none" type="button" id="adminMenuToggle">
    <i class="bi bi-list"></i>
</button>

<div class="admin-backdrop" id="adminBackdrop"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-brand">
        <span class="admin-brand-icon"><i class="bi bi-shield-lock"></i></span>
        <div>
            <h5 class="mb-0">Admin Panel</h5>
            <small>University Ideas</small>
        </div>
    </div>

    <div class="admin-nav-group">
        <p class="admin-nav-title">Overview</p>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('ideas.index') }}" class="admin-nav-link {{ request()->routeIs('ideas.*') ? 'active' : '' }}">
            <i class="bi bi-lightbulb"></i>
            <span>All Ideas</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <p class="admin-nav-title">Administration</p>
        <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Manage Users</span>
        </a>
        <a href="{{ route('admin.users.create') }}" class="admin-nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus"></i>
            <span>Add User</span>
        </a>
        <a href="{{ route('admin.departments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.departments.index') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Departments</span>
        </a>
        <a href="{{ route('admin.departments.create') }}" class="admin-nav-link {{ request()->routeIs('admin.departments.create') ? 'active' : '' }}">
            <i class="bi bi-building-add"></i>
            <span>Add Department</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i>
            <span>System Settings</span>
        </a>
        <a href="{{ route('admin.audit-logs.index') }}" class="admin-nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>System Audit Logs</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <p class="admin-nav-title">Account</p>
        <a href="{{ route('home') }}" class="admin-nav-link">
            <i class="bi bi-house-door"></i>
            <span>Main Site</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-nav-link w-100 text-start border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
