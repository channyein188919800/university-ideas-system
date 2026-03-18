<button class="btn admin-menu-toggle d-lg-none" type="button" id="adminMenuToggle">
    <i class="bi bi-list"></i>
</button>

<div class="admin-backdrop" id="adminBackdrop"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <!-- Logo -->
    <div class="admin-brand" style="justify-content:center;padding:0.5rem 0 0.8rem;">
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
        <a href="{{ route('admin.departments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.departments.index') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Departments</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i>
            <span>Closure Dates</span>
        </a>
        <a href="{{ route('admin.audit-logs.index') }}" class="admin-nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>System Audit Logs</span>
        </a>
        <a href="{{ route('admin.reports.usage') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.usage') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Usage Reports</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <p class="admin-nav-title">Account</p>
        <a href="{{ route('admin.users.edit', auth()->user()->id) }}" class="admin-nav-link {{ request()->routeIs('admin.users.edit') && optional(request()->route('user'))->id == auth()->user()->id ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            <span>My Profile</span>
        </a>
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
