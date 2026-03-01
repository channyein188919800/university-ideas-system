@extends('layouts.app')

@section('title', 'Home - University Ideas System')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: "Merriweather", serif;
    }

    .hero-glass {
        position: relative;
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
        color: #fff;
        background-image:
            linear-gradient(135deg, rgba(15,23,42,0.80), rgba(15,23,42,0.5)),
            url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .hero-overlay-card {
        max-width: 720px;
        width: 100%;
        margin: 0 auto;
        padding: 2.5rem 2rem;
        border-radius: 1.5rem;
        background: rgba(15,23,42,0.55);
        border: 1px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(18px);
        box-shadow: 0 18px 45px rgba(0,0,0,0.45);
    }

    .hero-title-icon {
        font-size: 3rem;
        margin-bottom: 0.5rem;
    }

    .hero-subtitle {
        opacity: 0.9;
        max-width: 520px;
        margin: 0 auto 1.5rem auto;
    }

    .hero-cta-group {
        gap: 0.75rem;
    }

    .blur-surface {
        background: transparent;
        border-radius: 1.25rem;
        border: 2px solid #d69e2e;
        backdrop-filter: none;
        box-shadow: none;
    }

    .blur-surface .key-dates-label {
        color: #d69e2e;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .blur-surface .key-dates-label .badge {
        background: #d69e2e !important;
        color: #fff !important;
        font-size: 0.95rem;
        padding: 0.4rem 0.9rem;
    }

    .blur-surface .date-item {
        color: #1e293b;
        font-size: 1.2rem;
        font-weight: 500;
        line-height: 1.6;
    }

    .blur-surface .date-item strong {
        font-size: 1.3rem;
        font-weight: 800;
        color: #d69e2e;
    }

    .blur-surface .date-item .date-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .stats-grid .stats-card {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        border-radius: 1.25rem;
        padding: 1.4rem 1.1rem;
        background:
            radial-gradient(circle at 20% 10%, rgba(255,255,255,0.20), transparent 40%),
            radial-gradient(circle at 80% 90%, rgba(59,130,246,0.22), transparent 45%),
            linear-gradient(145deg, rgba(30,41,59,0.82), rgba(51,65,85,0.72));
        background-size: 190% 190%;
        border: 1px solid rgba(191,219,254,0.28);
        backdrop-filter: blur(22px) saturate(145%);
        -webkit-backdrop-filter: blur(22px) saturate(145%);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.22),
            inset 0 -24px 50px rgba(15,23,42,0.2),
            0 16px 38px rgba(15,23,42,0.38);
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        animation: liquid-card-flow 9s ease-in-out infinite;
    }

    .stats-grid .stats-card::before {
        content: "";
        position: absolute;
        inset: -35%;
        z-index: -1;
        background:
            conic-gradient(from 120deg,
                rgba(255,255,255,0.05),
                rgba(147,197,253,0.18),
                rgba(255,255,255,0.06),
                rgba(125,211,252,0.16),
                rgba(255,255,255,0.05));
        filter: blur(20px);
        animation: liquid-sheen 8s linear infinite;
    }

    .stats-grid .stats-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: -120%;
        width: 70%;
        height: 100%;
        background: linear-gradient(
            105deg,
            transparent,
            rgba(255,255,255,0.26),
            transparent
        );
        transform: skewX(-14deg);
        animation: liquid-sweep 4.8s ease-in-out infinite;
        pointer-events: none;
    }

    .stats-grid .stats-card:hover {
        transform: translateY(-6px) scale(1.01);
        border-color: rgba(191,219,254,0.45);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.28),
            inset 0 -30px 60px rgba(15,23,42,0.28),
            0 20px 44px rgba(15,23,42,0.48);
    }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.9rem;
        background: linear-gradient(145deg, rgba(15,23,42,0.88), rgba(15,23,42,0.65));
        border: 1px solid rgba(191,219,254,0.26);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.16),
            0 10px 22px rgba(2,6,23,0.35);
        font-size: 1.35rem;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.1;
        text-shadow: 0 1px 8px rgba(15,23,42,0.55);
    }

    .stats-label {
        opacity: 1;
        font-size: 0.95rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-weight: 600;
        color: #dbeafe;
        text-shadow: 0 1px 6px rgba(15,23,42,0.45);
    }

    @keyframes liquid-sheen {
        0% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.05); }
        100% { transform: rotate(360deg) scale(1); }
    }

    @keyframes liquid-sweep {
        0%, 22% { left: -120%; }
        40%, 100% { left: 150%; }
    }

    @keyframes liquid-card-flow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .glass-section-card {
        border-radius: 1.5rem;
        background: rgba(15,23,42,0.78);
        border: 1px solid rgba(148,163,184,0.4);
        backdrop-filter: blur(18px);
        box-shadow: 0 18px 45px rgba(15,23,42,0.65);
        overflow: hidden;
    }

    .glass-section-card .card-header {
        border-bottom: 1px solid rgba(148,163,184,0.4);
        background: linear-gradient(to right, rgba(15,23,42,0.9), rgba(30,64,175,0.7));
        color: #fff;
    }

    .glass-section-card .card-body {
        background: transparent;
    }

    .idea-card {
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
        border-radius: 1rem;
        background: rgba(255,255,255,0.96);
        border-bottom: 1px solid rgba(148,163,184,0.25);
    }

    .idea-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .idea-title {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
        color: #1e3a5f;
    }

    .idea-title a {
        color: inherit;
        flex: 1;
        min-width: 0;
        line-height: 1.3;
        word-break: break-word;
    }

    .badge-anonymous {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.2rem 0.7rem;
        background: #e2e8f0;
        border: 1px solid #cbd5e1;
        color: #334155 !important;
    }

    .badge-category {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        background: rgba(15,23,42,0.9);
        border: 1px solid rgba(148,163,184,0.5);
        color: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .badge-category:hover {
        transform: translateY(-2px);
        background: rgba(15,23,42,1);
        box-shadow: 0 10px 26px rgba(15,23,42,0.7);
    }

    .fade-in-up {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-in-delay-1 {
        transition-delay: 0.1s;
    }

    .fade-in-delay-2 {
        transition-delay: 0.2s;
    }

    .fade-in-delay-3 {
        transition-delay: 0.3s;
    }

    .fade-in-delay-4 {
        transition-delay: 0.4s;
    }

    .loading-shimmer {
        position: relative;
        overflow: hidden;
    }

    .loading-shimmer::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg,
            transparent,
            rgba(255,255,255,0.18),
            transparent);
        transform: translateX(-100%);
        animation: shimmer 1.4s infinite;
        pointer-events: none;
    }

    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .stats-grid .stats-card,
        .stats-grid .stats-card::before,
        .stats-grid .stats-card::after {
            animation: none !important;
        }
    }

    @media (max-width: 767.98px) {
        .hero-overlay-card {
            padding: 2rem 1.4rem;
        }

        .hero-title-icon {
            font-size: 2.4rem;
        }
    }
</style>

<section class="hero-glass">
    <div class="hero-overlay-card text-center fade-in-up">
        <div class="mb-3">
            <i class="bi bi-lightbulb-fill hero-title-icon"></i>
        </div>
        <h1 class="mb-2">Share Your Ideas</h1>
        <p class="hero-subtitle">
            Help improve our university by submitting your innovative ideas and feedback.
        </p>
        <div class="d-flex flex-column flex-sm-row justify-content-center hero-cta-group mt-3">
            @auth
                @if(auth()->user()->canSubmitIdea())
                    <a href="{{ route('ideas.create') }}" class="btn btn-accent btn-lg px-4 mb-2 mb-sm-0">
                        <i class="bi bi-plus-circle-fill me-1"></i>
                        Submit an Idea
                    </a>
                @else
                    <div class="alert alert-warning d-inline-flex align-items-center mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        <span>Idea submission is currently closed.</span>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-accent btn-lg px-4">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Login to Submit Ideas
                </a>
            @endauth
        </div>
    </div>
</section>

<div class="container mb-5" id="home-content-root">
    @if($ideaClosureDate || $finalClosureDate)
        <div class="blur-surface mt-3 mb-4 p-3 p-md-4 fade-in-up fade-in-delay-1">
            <div class="key-dates-label mb-3">
                <span class="badge rounded-pill">
                    <i class="bi bi-calendar-event-fill me-1"></i>
                    Key Dates
                </span>
            </div>
            <div class="row g-3">
                @if($ideaClosureDate)
                    <div class="col-md-6">
                        <div class="date-item">
                            <span class="date-label"><i class="bi bi-clock me-1"></i>Idea Submission Closes</span>
                            <strong>{{ $ideaClosureDate->format('F d, Y') }}</strong>
                        </div>
                    </div>
                @endif
                @if($finalClosureDate)
                    <div class="col-md-6">
                        <div class="date-item">
                            <span class="date-label"><i class="bi bi-calendar-x me-1"></i>Final Closure (Comments)</span>
                            <strong>{{ $finalClosureDate->format('F d, Y') }}</strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="row stats-grid mb-5">
        <div class="col-md-3 col-sm-6 mb-3 fade-in-up fade-in-delay-1">
            <div class="stats-card text-center">
                <div class="stats-icon text-primary">
                    <i class="bi bi-fire"></i>
                </div>
                <div class="stats-number">{{ $popularIdeas->count() }}</div>
                <div class="stats-label">Popular Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 fade-in-up fade-in-delay-2">
            <div class="stats-card text-center">
                <div class="stats-icon text-success">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div class="stats-number">{{ $mostViewedIdeas->count() }}</div>
                <div class="stats-label">Most Viewed</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 fade-in-up fade-in-delay-3">
            <div class="stats-card text-center">
                <div class="stats-icon text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stats-number">{{ $latestIdeas->count() }}</div>
                <div class="stats-label">Latest Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 fade-in-up fade-in-delay-4">
            <div class="stats-card text-center">
                <div class="stats-icon text-info">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div class="stats-number">{{ $latestComments->count() }}</div>
                <div class="stats-label">Recent Comments</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6 fade-in-up">
            <div class="card glass-section-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-fire me-2"></i>
                        Most Popular Ideas
                    </div>
                    <span class="badge bg-light text-dark">Top {{ $popularIdeas->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($popularIdeas as $idea)
                        <div class="idea-card">
                            <h5 class="idea-title">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </h5>
                            <div class="idea-meta small mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">&bull;</span>
                                <i class="bi bi-calendar3"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <p class="idea-description mb-3">{{ Str::limit($idea->description, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="idea-stats d-flex flex-wrap gap-3 small">
                                    <span class="text-success">
                                        <i class="bi bi-hand-thumbs-up-fill me-1"></i> {{ $idea->thumbs_up_count }}
                                    </span>
                                    <span class="text-danger">
                                        <i class="bi bi-hand-thumbs-down-fill me-1"></i> {{ $idea->thumbs_down_count }}
                                    </span>
                                    <span>
                                        <i class="bi bi-eye me-1"></i> {{ $idea->views_count }}
                                    </span>
                                    <span>
                                        <i class="bi bi-chat-text me-1"></i> {{ $idea->comments_count }}
                                    </span>
                                </div>
                                <span class="badge bg-primary">
                                    Score: {{ $idea->popularity_score }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 mb-2"></i>
                            <p class="mb-0">No popular ideas yet.</p>
                        </div>
                    @endforelse
                    <div class="text-center mt-3">
                        <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="btn btn-outline-primary btn-sm">
                            View All Popular <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 fade-in-up fade-in-delay-2">
            <div class="card glass-section-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-eye-fill me-2"></i>
                        Most Viewed Ideas
                    </div>
                    <span class="badge bg-light text-dark">Top {{ $mostViewedIdeas->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($mostViewedIdeas as $idea)
                        <div class="idea-card">
                            <h5 class="idea-title">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </h5>
                            <div class="idea-meta small mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">&bull;</span>
                                <i class="bi bi-calendar3"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <p class="idea-description mb-3">{{ Str::limit($idea->description, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="idea-stats d-flex flex-wrap gap-3 small">
                                    <span>
                                        <i class="bi bi-eye me-1"></i> {{ $idea->views_count }} views
                                    </span>
                                    <span>
                                        <i class="bi bi-chat-text me-1"></i> {{ $idea->comments_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 mb-2"></i>
                            <p class="mb-0">No viewed ideas yet.</p>
                        </div>
                    @endforelse
                    <div class="text-center mt-3">
                        <a href="{{ route('ideas.index', ['sort' => 'views']) }}" class="btn btn-outline-primary btn-sm">
                            View All <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-6 fade-in-up">
            <div class="card glass-section-card h-100">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>
                    Latest Ideas
                </div>
                <div class="card-body">
                    @forelse($latestIdeas as $idea)
                        <div class="idea-card">
                            <h5 class="idea-title">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </h5>
                            <div class="idea-meta small mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">&bull;</span>
                                <i class="bi bi-calendar3"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <p class="idea-description mb-3">{{ Str::limit($idea->description, 150) }}</p>
                            <div class="idea-stats d-flex flex-wrap gap-3 small">
                                <span class="text-success">
                                    <i class="bi bi-hand-thumbs-up-fill me-1"></i> {{ $idea->thumbs_up_count }}
                                </span>
                                <span class="text-danger">
                                    <i class="bi bi-hand-thumbs-down-fill me-1"></i> {{ $idea->thumbs_down_count }}
                                </span>
                                <span>
                                    <i class="bi bi-chat-text me-1"></i> {{ $idea->comments_count }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 mb-2"></i>
                            <p class="mb-0">No ideas submitted yet.</p>
                        </div>
                    @endforelse
                    <div class="text-center mt-3">
                        <a href="{{ route('ideas.index') }}" class="btn btn-outline-primary btn-sm">
                            View All Ideas <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 fade-in-up fade-in-delay-2">
            <div class="card glass-section-card h-100">
                <div class="card-header">
                    <i class="bi bi-chat-dots-fill me-2"></i>
                    Latest Comments
                </div>
                <div class="card-body">
                    @forelse($latestComments as $comment)
                        <div class="idea-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">
                                    <i class="bi bi-chat-left-text-fill text-primary me-1"></i>
                                    On:
                                    @if($comment->idea)
                                        <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none">
                                            {{ $comment->idea->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">Idea unavailable</span>
                                    @endif
                                </h6>
                                @if($comment->is_anonymous)
                                    <span class="badge badge-anonymous">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </div>
                            <p class="idea-description mb-2">{{ Str::limit($comment->content, 150) }}</p>
                            <small class="text-muted">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $comment->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 mb-2"></i>
                            <p class="mb-0">No comments yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 fade-in-up">
        <div class="col-12">
            <div class="card glass-section-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-tags-fill me-2"></i>
                        Browse by Category
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($categories as $category)
                            <a href="{{ route('ideas.index', ['category' => $category->id]) }}" class="text-decoration-none">
                                <span class="badge-category">
                                    {{ $category->name }}
                                    <span class="badge bg-secondary ms-1">{{ $category->ideas_count }}</span>
                                </span>
                            </a>
                        @empty
                            <p class="text-muted mb-0">No categories available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var animated = document.querySelectorAll('.fade-in-up');
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            animated.forEach(function (el) {
                observer.observe(el);
            });
        } else {
            animated.forEach(function (el) {
                el.classList.add('visible');
            });
        }
    });
</script>
@endsection



