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
        background: rgba(15,23,42,0.70);
        border-radius: 1.25rem;
        border: 1px solid rgba(148,163,184,0.35);
        backdrop-filter: blur(16px);
        box-shadow: 0 14px 40px rgba(15,23,42,0.55);
    }

    .stats-grid .stats-card {
        border-radius: 1.25rem;
        padding: 1.4rem 1.1rem;
        background: rgba(15,23,42,0.80);
        border: 1px solid rgba(148,163,184,0.4);
        backdrop-filter: blur(16px);
        color: #fff;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }

    .stats-grid .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(15,23,42,0.7);
        background: rgba(15,23,42,0.92);
    }

    .stats-icon {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        background: rgba(15,23,42,0.9);
    }

    .stats-number {
        font-size: 1.6rem;
        font-weight: 700;
    }

    .stats-label {
        opacity: 0.85;
        font-size: 0.85rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
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
        padding: 1rem 0;
        border-bottom: 1px solid rgba(148,163,184,0.25);
    }

    .idea-card:last-child {
        border-bottom: none;
    }

    .idea-title a {
        color: inherit;
    }

    .badge-anonymous {
        font-size: 0.7rem;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
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
        <div class="blur-surface mt-n4 mb-4 p-3 p-md-4 fade-in-up fade-in-delay-1">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <span class="badge rounded-pill bg-light text-dark">
                        <i class="bi bi-calendar-event-fill me-1"></i>
                        Key Dates
                    </span>
                </div>
                <div>
                    @if($ideaClosureDate)
                        <div>Idea submission closes on <strong>{{ $ideaClosureDate->format('F d, Y') }}</strong></div>
                    @endif
                    @if($finalClosureDate)
                        <div>Final closure (comments) on <strong>{{ $finalClosureDate->format('F d, Y') }}</strong></div>
                    @endif
                </div>
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
                            <h5 class="idea-title d-flex align-items-center justify-content-between">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous bg-secondary-subtle text-light">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </h5>
                            <div class="idea-meta small mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">•</span>
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
                            <h5 class="idea-title d-flex align-items-center justify-content-between">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous bg-secondary-subtle text-light">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </h5>
                            <div class="idea-meta small mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">•</span>
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
                            <h5 class="idea-title d-flex align-items-center justify-content-between">
                                <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                    {{ $idea->title }}
                                </a>
                                @if($idea->is_anonymous)
                                    <span class="badge badge-anonymous bg-secondary-subtle text-light">
                                        <i class="bi bi-incognito me-1"></i> Anonymous
                                    </span>
                                @endif
                            </h5>
                            <div class="idea-meta small mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">•</span>
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
                                    <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none">
                                        {{ $comment->idea->title }}
                                    </a>
                                </h6>
                                @if($comment->is_anonymous)
                                    <span class="badge badge-anonymous bg-secondary-subtle text-light">
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
