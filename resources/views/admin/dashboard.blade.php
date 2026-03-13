@extends('layouts.app')

@section('title', 'Admin Dashboard - University Ideas System')

@section('content')
    <div class="admin-shell">
        @include('admin.partials.sidebar')

        <section class="admin-main">
            @php
                $authUser = auth()->user();
                $departmentName = $authUser->department->name ?? 'No Department Assigned';
                $roleLabel = match ($authUser->role) {
                    'admin' => 'Administrator',
                    'qa_manager' => 'QA Manager',
                    'qa_coordinator' => 'QA Coordinator',
                    'staff' => 'Staff',
                    default => ucwords(str_replace('_', ' ', $authUser->role)),
                };
                $lastLoginAt = $authUser->last_login_at;
            @endphp

            <div class="admin-topbar">
                <div>
                    <h3 class="mb-1">{{ $lastLoginAt ? 'Welcome back' : 'Welcome' }}, {{ $authUser->name }}</h3>
                    <p class="admin-topbar-subtitle mb-0">{{ $departmentName }} · {{ $roleLabel }}</p>
                    <small class="text-muted d-block">
                        @if(!$lastLoginAt)
                            Welcome! This appears to be your first login.
                        @else
                            Last login: {{ $lastLoginAt->format('M d, Y h:i A') }}
                        @endif
                    </small>
                </div>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-journal-text"></i> View Audit Logs
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="admin-stat-card">
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
                    <div class="admin-stat-card">
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
                    <div class="admin-stat-card">
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
                    <div class="admin-stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p>Departments</p>
                                <h4>{{ $stats['total_departments'] }}</h4>
                            </div>
                            <div class="stat-icon participation">
                                <i class="bi bi-buildings-fill"></i>
                            </div>
                        </div>
                    </div>
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
                                            <span
                                                class="badge {{ now()->lt($ideaClosureDate) ? 'text-bg-success' : 'text-bg-danger' }}">
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
                                            <span
                                                class="badge {{ now()->lt($finalClosureDate) ? 'text-bg-success' : 'text-bg-danger' }}">
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
                                        <a href="{{ route('ideas.show', $idea) }}"
                                            class="feed-title">{{ Str::limit($idea->title, 60) }}</a>
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
                                        @if($comment->idea)
                                            <a href="{{ route('ideas.show', $comment->idea) }}"
                                                class="feed-title">{{ Str::limit($comment->idea->title, 52) }}</a>
                                        @else
                                            <span class="feed-title text-muted">Idea unavailable</span>
                                        @endif
                                        <p class="feed-content mb-1">{{ Str::limit($comment->content, 90) }}</p>
                                        <p class="feed-meta mb-0"><i class="bi bi-clock"></i>
                                            {{ $comment->created_at->diffForHumans() }}</p>
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

@include('admin.partials.sidebar-assets')

@push('styles')
    <style>
        .navbar {
            display: none !important;
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

        .admin-topbar-subtitle {
            color: #64748b;
            font-size: 1.05rem;
        }

        .welcome-name {
            color: var(--accent-color);
        }

        .login-notice-ios {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            background: rgba(15, 23, 42, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.45);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.28);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: #f8fafc;
            max-width: 560px;
            margin: 0 0 1.2rem 0;
            animation: loginNoticeIn 0.55s ease forwards;
            will-change: transform, opacity;
        }

        .login-notice-ios.hide {
            animation: loginNoticeOut 0.45s ease forwards;
        }

        .login-notice-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            flex-shrink: 0;
        }

        .login-notice-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #f8fafc;
            letter-spacing: 0.01em;
        }

        @keyframes loginNoticeIn {
            from {
                opacity: 0;
                transform: translateY(-16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes loginNoticeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-12px);
            }
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

        .stat-icon.users {
            background: linear-gradient(135deg, #3577ff, #5ea8ff);
        }

        .stat-icon.ideas {
            background: linear-gradient(135deg, #00a58b, #35d1b2);
        }

        .stat-icon.comments {
            background: linear-gradient(135deg, #e58f07, #f8bc5e);
        }

        .stat-icon.departments {
            background: linear-gradient(135deg, #7946fd, #9f75ff);
        }

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

        @keyframes admin-reveal {
            to {
                opacity: 1;
                transform: translateY(0);

                =======<style>.navbar {
                    display: none !important;
                    >>>>>>>feature/UIFix
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

                .admin-topbar-subtitle {
                    color: #64748b;
                    font-size: 1.05rem;
                }

                .admin-stat-card {
                    background: #fff;
                    border-radius: 1rem;
                    padding: 1.2rem;
                    border: 1px solid #e4ebf8;
                    box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
                    transition: transform 0.25s ease, box-shadow 0.25s ease;
                    height: 100%;
                }

                .admin-stat-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 15px 30px rgba(27, 52, 102, 0.12);
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

                .stat-icon.ideas {
                    background: linear-gradient(135deg, #00a58b, #35d1b2);
                }

                .stat-icon.comments {
                    background: linear-gradient(135deg, #e58f07, #f8bc5e);
                }

                .stat-icon.users {
                    background: linear-gradient(135deg, #3577ff, #5ea8ff);
                }

                .stat-icon.participation {
                    background: linear-gradient(135deg, #7946fd, #9f75ff);
                }

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

                @keyframes admin-reveal {
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @media (max-width: 991.98px) {
                    .admin-topbar {
                        padding: 1rem;
                        margin-top: 2.3rem;
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
                                    label: function (ctx) {
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
@endpush
