<button class="btn admin-menu-toggle d-lg-none" type="button" id="adminMenuToggle">
    <i class="bi bi-list"></i>
</button>

<div class="admin-backdrop" id="adminBackdrop"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-brand" style="justify-content:center;padding:0.5rem 0 0.8rem;">
        <img src="{{ asset('images/logo1_no_bg.png') }}"
             alt="University Ideas"
             style="width:130px;max-height:90px;object-fit:contain;filter:brightness(1.1) drop-shadow(0 4px 12px rgba(0,0,0,0.4));">
    </div>

    <div class="admin-nav-group">
        <p class="admin-nav-title">Overview</p>
        <a href="{{ route('staff.dashboard') }}" class="admin-nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('ideas.index') }}" class="admin-nav-link {{ request()->routeIs('ideas.index') ? 'active' : '' }}">
            <i class="bi bi-lightbulb"></i>
            <span>Browse Ideas</span>
        </a>
        <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="admin-nav-link">
            <i class="bi bi-fire"></i>
            <span>Popular Ideas</span>
        </a>
        @if(auth()->user()->canSubmitIdea())
            <a href="{{ route('ideas.create') }}" class="admin-nav-link {{ request()->routeIs('ideas.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Submit Idea</span>
            </a>
        @endif
    </div>

    <div class="admin-nav-group">
        <p class="admin-nav-title">Account</p>
        <a href="{{ route('staff.account.edit') }}" class="admin-nav-link {{ request()->routeIs('staff.account.edit') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            <span>Profile Edit</span>
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
