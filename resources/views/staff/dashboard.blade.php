@php($hideNavFooter = true)
@extends('layouts.app')

@section('title', 'Staff Dashboard - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('staff.partials.sidebar')

    <section class="admin-main">
        <div class="staff-topbar">
            <div>
                <h3 class="mb-1"><i class="bi bi-speedometer2"></i> My Dashboard</h3>
                <p class="text-muted mb-0">Welcome back, <span class="welcome-name">{{ auth()->user()->name }}</span>!</p>
            </div>
            @if($canSubmitIdea)
                <a href="{{ route('ideas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Submit Idea
                </a>
            @endif
        </div>

        @if(session('login_notice'))
            <div class="login-notice-ios" id="loginNotice">
                <div class="login-notice-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="login-notice-text">
                    <span>{{ session('login_notice') }}</span>
                </div>
            </div>
        @endif

        @if($ideaClosureDate || $finalClosureDate)
            <div class="alert alert-info mb-4">
                <i class="bi bi-calendar-event"></i>
                <strong>Important Dates:</strong>
                @if($ideaClosureDate)
                    Idea submission {{ now()->lt($ideaClosureDate) ? 'closes' : 'closed' }} on <strong>{{ $ideaClosureDate->format('F d, Y') }}</strong>
                @endif
                @if($finalClosureDate)
                    | Commenting {{ now()->lt($finalClosureDate) ? 'closes' : 'closed' }} on <strong>{{ $finalClosureDate->format('F d, Y') }}</strong>
                @endif
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div class="stats-number">{{ $stats['my_ideas'] }}</div>
                    <div class="stats-label">My Ideas</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                    <div class="stats-number">{{ $stats['my_comments'] }}</div>
                    <div class="stats-label">My Comments</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon warning">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                    <div class="stats-number">{{ $stats['my_votes'] }}</div>
                    <div class="stats-label">My Votes</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div class="stats-number">{{ $stats['total_views'] }}</div>
                    <div class="stats-label">Total Views</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-lightbulb"></i> My Recent Ideas</span>
                        <a href="{{ route('ideas.index') }}" class="btn btn-sm btn-outline-light">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($myIdeas as $idea)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6 class="mb-1">
                                    <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                        {{ Str::limit($idea->title, 50) }}
                                    </a>
                                    @if($idea->is_anonymous)
                                        <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i></span>
                                    @endif
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $idea->created_at->diffForHumans() }}
                                    </small>
                                    <div>
                                        <span class="badge bg-success me-1"><i class="bi bi-hand-thumbs-up"></i> {{ $idea->thumbs_up_count }}</span>
                                        <span class="badge bg-primary"><i class="bi bi-chat"></i> {{ $idea->comments_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-lightbulb fs-1 mb-3 d-inline-block"></i>
                                <p>You haven't submitted any ideas yet.</p>
                                @if($canSubmitIdea)
                                    <a href="{{ route('ideas.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> Submit Your First Idea
                                    </a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-chat-left-text"></i> My Recent Comments
                    </div>
                    <div class="card-body">
                        @forelse($myComments as $comment)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6 class="mb-1">
                                    <i class="bi bi-chat text-primary"></i>
                                    On: <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none">
                                        {{ Str::limit($comment->idea->title, 40) }}
                                    </a>
                                </h6>
                                <p class="mb-1 small">{{ Str::limit($comment->content, 80) }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-chat-left-text fs-1 mb-3 d-inline-block"></i>
                                <p>You haven't commented on any ideas yet.</p>
                                <a href="{{ route('ideas.index') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i> Browse Ideas
                                </a>
                            </div>
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
        min-height: 100vh;
        background: radial-gradient(circle at 0% 0%, #e9f2ff 0%, #f4f7fc 35%, #f8fafc 100%);
        position: relative;
    }

    .admin-sidebar {
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background: linear-gradient(180deg, #0f1f3a 0%, #15294a 100%);
        color: #cdd8ee;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        box-shadow: 0 16px 40px rgba(12, 25, 52, 0.28);
        z-index: 1100;
        transition: transform 0.25s ease;
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .admin-brand h5 {
        color: #f4f7ff;
        font-weight: 700;
    }

    .admin-brand small {
        color: #98aed6;
    }

    .admin-brand-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.13);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #8ab5ff;
    }

    .admin-nav-title {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #8fa6cf;
        margin-bottom: 0.55rem;
        padding: 0 0.7rem;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.72rem 0.78rem;
        border-radius: 10px;
        color: #d5e1f5;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 0.3rem;
    }

    .admin-nav-link i {
        width: 18px;
        text-align: center;
    }

    .admin-nav-link:hover,
    .admin-nav-link.active {
        background: rgba(122, 164, 255, 0.18);
        color: #fff;
    }

    .admin-main {
        flex: 1;
        padding: 1.5rem;
        overflow-x: hidden;
        margin-left: 280px;
    }

    .staff-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.2rem;
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
        max-width: 520px;
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
        font-size: 0.92rem;
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

    .admin-menu-toggle {
        position: fixed;
        top: 84px;
        left: 14px;
        z-index: 1200;
        background: #0f1f3a;
        color: #fff;
        border: 0;
        border-radius: 10px;
        width: 42px;
        height: 42px;
        box-shadow: 0 10px 26px rgba(12, 25, 52, 0.3);
    }

    .admin-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(6, 12, 26, 0.45);
        z-index: 1090;
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            transform: translateX(-105%);
            height: 100vh;
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }

        .admin-main {
            width: 100%;
            padding-top: 2.2rem;
            margin-left: 0;
        }

        .staff-topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .admin-backdrop.open {
            display: block;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('adminMenuToggle');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');

        if (!toggleButton || !sidebar || !backdrop) {
            return;
        }

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        });

        backdrop.addEventListener('click', () => {
            sidebar.classList.remove('open');
            backdrop.classList.remove('open');
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loginNotice = document.getElementById('loginNotice');
        if (!loginNotice) {
            return;
        }

        setTimeout(() => {
            loginNotice.classList.add('hide');
            setTimeout(() => loginNotice.remove(), 400);
        }, 4000);
    });
</script>
@endpush
