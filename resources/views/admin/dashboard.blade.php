@extends('layouts.app')

@section('title', 'Admin Dashboard - University Ideas System')

@section('content')
<div class="admin-shell">
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
        </div>

        <div class="admin-nav-group mt-auto">
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

    <section class="admin-main">
        <div class="admin-topbar">
            <div>
                <h3 class="mb-1">Welcome back, {{ auth()->user()->name }}</h3>
                <p class="text-muted mb-0">System administration and monitoring center</p>
            </div>
            
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <article class="admin-stat-card reveal" style="--delay: .05s;">
                    <div class="stat-icon users"><i class="bi bi-people-fill"></i></div>
                    <h4>{{ number_format($stats['total_users']) }}</h4>
                    <p>Total Users</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="admin-stat-card reveal" style="--delay: .1s;">
                    <div class="stat-icon ideas"><i class="bi bi-lightbulb-fill"></i></div>
                    <h4>{{ number_format($stats['total_ideas']) }}</h4>
                    <p>Total Ideas</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="admin-stat-card reveal" style="--delay: .15s;">
                    <div class="stat-icon comments"><i class="bi bi-chat-left-text-fill"></i></div>
                    <h4>{{ number_format($stats['total_comments']) }}</h4>
                    <p>Total Comments</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="admin-stat-card reveal" style="--delay: .2s;">
                    <div class="stat-icon departments"><i class="bi bi-buildings-fill"></i></div>
                    <h4>{{ number_format($stats['total_departments']) }}</h4>
                    <p>Departments</p>
                </article>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="admin-card reveal" style="--delay: .25s;">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-calendar-event"></i> Closure Timeline</h5>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-primary">Edit</a>
                    </div>
                    <div class="admin-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="deadline-box">
                                    <small>Idea Closure Date</small>
                                    @if($ideaClosureDate)
                                        <h6>{{ $ideaClosureDate->format('M d, Y h:i A') }}</h6>
                                        <span class="badge {{ now()->lt($ideaClosureDate) ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ now()->lt($ideaClosureDate) ? 'Open' : 'Closed' }}
                                        </span>
                                    @else
                                        <h6>Not Set</h6>
                                        <span class="badge text-bg-secondary">Pending</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="deadline-box">
                                    <small>Final Closure Date</small>
                                    @if($finalClosureDate)
                                        <h6>{{ $finalClosureDate->format('M d, Y h:i A') }}</h6>
                                        <span class="badge {{ now()->lt($finalClosureDate) ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ now()->lt($finalClosureDate) ? 'Open' : 'Closed' }}
                                        </span>
                                    @else
                                        <h6>Not Set</h6>
                                        <span class="badge text-bg-secondary">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-7">
                <div class="admin-card reveal" style="--delay: .3s;">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-bar-chart-fill"></i> Ideas per Department</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="chart-container">
                            <canvas id="deptChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="admin-card reveal" style="--delay: .35s;">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-pie-chart-fill"></i> Idea Engagement</h5>
                    </div>
                    <div class="admin-card-body d-flex align-items-center justify-content-center">
                        <div class="chart-container chart-container-doughnut">
                            <canvas id="commentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6">
                <div class="admin-card reveal" style="--delay: .35s;">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-lightbulb"></i> Recent Ideas</h5>
                    </div>
                    <div class="admin-card-body">
                        @forelse($recentIdeas as $idea)
                            <div class="feed-item {{ !$loop->last ? 'with-border' : '' }}">
                                <div class="feed-main">
                                    <a href="{{ route('ideas.show', $idea) }}" class="feed-title">{{ Str::limit($idea->title, 60) }}</a>
                                    <p class="feed-meta mb-0">
                                        <i class="bi bi-building"></i> {{ $idea->department->name }}
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-clock"></i> {{ $idea->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="badge text-bg-primary">
                                    {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No ideas submitted yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="admin-card reveal" style="--delay: .4s;">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-chat-dots"></i> Recent Comments</h5>
                    </div>
                    <div class="admin-card-body">
                        @forelse($recentComments as $comment)
                            <div class="feed-item {{ !$loop->last ? 'with-border' : '' }}">
                                <div class="feed-main">
                                    <a href="{{ route('ideas.show', $comment->idea) }}" class="feed-title">{{ Str::limit($comment->idea->title, 52) }}</a>
                                    <p class="feed-content mb-1">{{ Str::limit($comment->content, 90) }}</p>
                                    <p class="feed-meta mb-0"><i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No comments yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .admin-shell {
        display: flex;
        min-height: calc(100vh - 72px);
        background: radial-gradient(circle at 0% 0%, #e9f2ff 0%, #f4f7fc 35%, #f8fafc 100%);
        position: relative;
    }

    .admin-sidebar {
        width: 280px;
        background: linear-gradient(180deg, #0f1f3a 0%, #15294a 100%);
        color: #cdd8ee;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: sticky;
        top: 0;
        max-height: 100vh;
        box-shadow: 8px 0 30px rgba(16, 29, 59, 0.2);
        z-index: 50;
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .admin-brand h5 {
        color: #fff;
        font-weight: 700;
    }

    .admin-brand small {
        color: #8ca3cf;
    }

    .admin-brand-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4d6ff5 0%, #46b8fc 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .admin-nav-title {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.72rem;
        color: #7287b8;
        margin: 0 0 0.45rem 0.6rem;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-radius: 0.65rem;
        color: #cdd8ee;
        text-decoration: none;
        padding: 0.68rem 0.75rem;
        margin-bottom: 0.25rem;
        transition: all 0.25s ease;
    }

    .admin-nav-link i {
        font-size: 1.05rem;
    }

    .admin-nav-link:hover,
    .admin-nav-link.active {
        background: rgba(125, 160, 255, 0.18);
        color: #ffffff;
        transform: translateX(4px);
    }

    .admin-main {
        flex: 1;
        padding: 1.5rem;
        overflow: hidden;
    }

    .admin-topbar {
        background: rgba(255, 255, 255, 0.86);
        border: 1px solid #e2e8f4;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        margin-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        backdrop-filter: blur(8px);
    }

    .admin-topbar h3 {
        font-weight: 700;
        color: #1c2a45;
    }

    .admin-topbar-tools {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-search {
        background: #f1f5fb;
        border: 1px solid #dde5f3;
        padding: 0.5rem 0.8rem;
        border-radius: 0.7rem;
        color: #6b7891;
        min-width: 280px;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.9rem;
    }

    .admin-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #dce4f3;
        background: #fff;
        color: #47649b;
        display: grid;
        place-items: center;
        font-size: 1.2rem;
    }

    .admin-stat-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.2rem;
        border: 1px solid #e4ebf8;
        box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
    }

    .admin-stat-card:hover {
        transform: translateY(-4px);
        transition: transform 0.25s ease;
    }

    .admin-stat-card h4 {
        font-size: 1.65rem;
        font-weight: 700;
        margin: 0.75rem 0 0.2rem;
        color: #22314f;
    }

    .admin-stat-card p {
        margin: 0;
        color: #70809f;
        font-size: 0.9rem;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.2rem;
    }

    .stat-icon.users { background: linear-gradient(135deg, #3577ff, #5ea8ff); }
    .stat-icon.ideas { background: linear-gradient(135deg, #00a58b, #35d1b2); }
    .stat-icon.comments { background: linear-gradient(135deg, #e58f07, #f8bc5e); }
    .stat-icon.departments { background: linear-gradient(135deg, #7946fd, #9f75ff); }

    .admin-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e4ebf8;
        box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
        overflow: hidden;
    }

    .admin-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.1rem;
        border-bottom: 1px solid #edf2fb;
        background: linear-gradient(90deg, #f9fbff, #f3f7ff);
    }

    .admin-card-header h5 {
        margin: 0;
        font-size: 1rem;
        color: #253558;
        font-weight: 600;
    }

    .admin-card-body {
        padding: 1rem 1.1rem;
    }

    .deadline-box {
        border: 1px solid #e6eef9;
        border-radius: 0.85rem;
        padding: 0.9rem;
        background: #fbfdff;
    }

    .deadline-box small {
        color: #6a7b9a;
        display: block;
        margin-bottom: 0.35rem;
    }

    .deadline-box h6 {
        margin-bottom: 0.5rem;
        color: #253558;
    }

    .feed-item {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.15rem 0;
    }

    .feed-item.with-border {
        border-bottom: 1px solid #edf2fb;
        padding-bottom: 0.85rem;
        margin-bottom: 0.85rem;
    }

    .feed-main {
        min-width: 0;
    }

    .feed-title {
        color: #263a64;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.35rem;
    }

    .feed-title:hover {
        color: #1f6dff;
    }

    .feed-meta {
        font-size: 0.85rem;
        color: #6a7b9a;
    }

    .feed-content {
        font-size: 0.9rem;
        color: #4f6288;
    }

    .reveal {
        opacity: 0;
        transform: translateY(10px);
        animation: admin-reveal .5s ease forwards;
        animation-delay: var(--delay, 0s);
    }

    .admin-menu-toggle {
        position: fixed;
        top: 12px;
        left: 10px;
        z-index: 60;
        background: #0f1f3a;
        color: #fff;
        border: none;
        border-radius: 0.55rem;
    }

    .admin-backdrop {
        display: none;
    }

    .navbar {
        display: none !important;
    }

    @keyframes admin-reveal {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            max-height: 100vh;
            transform: translateX(-102%);
            transition: transform 0.25s ease;
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }

        .admin-main {
            width: 100%;
            padding: 1rem;
        }

        .admin-topbar {
            padding: 1rem;
            margin-top: 2.3rem;
        }

        .admin-backdrop.open {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(11, 17, 31, 0.55);
            z-index: 45;
        }
    }

    .chart-container {
        position: relative;
        width: 100%;
        min-height: 300px;
    }

    .chart-container-doughnut {
        max-width: 320px;
        min-height: 300px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // === Bar Chart: Ideas per Department ===
        const deptCtx = document.getElementById('deptChart');
        if (deptCtx) {
            const deptData = @json($ideasPerDepartment);
            new Chart(deptCtx, {
                type: 'bar',
                data: {
                    labels: deptData.map(d => d.name),
                    datasets: [{
                        label: 'Ideas',
                        data: deptData.map(d => d.count),
                        backgroundColor: [
                            'rgba(77, 111, 245, 0.85)',
                            'rgba(0, 165, 139, 0.85)',
                            'rgba(229, 143, 7, 0.85)',
                            'rgba(121, 70, 253, 0.85)',
                            'rgba(53, 119, 255, 0.85)',
                            'rgba(245, 101, 101, 0.85)',
                            'rgba(72, 187, 120, 0.85)',
                            'rgba(237, 100, 166, 0.85)'
                        ],
                        borderColor: [
                            '#4d6ff5', '#00a58b', '#e58f07', '#7946fd',
                            '#3577ff', '#f56565', '#48bb78', '#ed64a6'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1c2a45',
                            titleFont: { size: 13, weight: '600' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: ctx => ctx.parsed.y + ' idea' + (ctx.parsed.y !== 1 ? 's' : '')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#70809f',
                                font: { size: 12 }
                            },
                            grid: { color: 'rgba(226,232,244,0.6)' }
                        },
                        x: {
                            ticks: {
                                color: '#70809f',
                                font: { size: 12, weight: '500' }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // === Doughnut Chart: Ideas with/without comments ===
        const commentCtx = document.getElementById('commentChart');
        if (commentCtx) {
            const withComments = {{ $ideasWithComments }};
            const withoutComments = {{ $ideasWithoutComments }};
            new Chart(commentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['With Comments', 'Without Comments'],
                    datasets: [{
                        data: [withComments, withoutComments],
                        backgroundColor: [
                            'rgba(53, 119, 255, 0.85)',
                            'rgba(245, 101, 101, 0.85)'
                        ],
                        borderColor: ['#3577ff', '#f56565'],
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#4a5568',
                                font: { size: 13, weight: '500' },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'rectRounded'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1c2a45',
                            titleFont: { size: 13, weight: '600' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                                    return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('adminMenuToggle');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');

        if (!toggleButton || !sidebar || !backdrop) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        });

        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('open');
            backdrop.classList.remove('open');
        });
    });
</script>
@endpush

