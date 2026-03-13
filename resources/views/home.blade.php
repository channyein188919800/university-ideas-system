@extends('layouts.app')

@section('title', 'Home - University Ideas System')

@section('content')
<style>
    :root {
        --primary-color: #1e3a5f;    /* Navy Blue */
        --secondary-color: #2c5282;  /* Lighter Navy */
        --accent-color: #d69e2e;     /* Gold */
        --success-color: #38a169;    /* Green */
        --danger-color: #e53e3e;     /* Red */
        --warning-color: #dd6b20;    /* Orange */
        --info-color: #3182ce;       /* Blue */
        --body-bg: #f0f4f8;
        --card-bg: #ffffff;
    }

    body {
        background-color: var(--body-bg);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: #2d3748;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: "Merriweather", serif;
        color: var(--primary-color);
    }

    /* --- Animations --- */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse-accent {
        0% { box-shadow: 0 0 0 0 rgba(214, 158, 46, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(214, 158, 46, 0); }
        100% { box-shadow: 0 0 0 0 rgba(214, 158, 46, 0); }
    }

    @keyframes shine {
        0% { left: -100%; }
        20% { left: 100%; }
        100% { left: 100%; }
    }

    .anim-element {
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards;
    }

    .anim-delay-1 { animation-delay: 0.1s; }
    .anim-delay-2 { animation-delay: 0.2s; }
    .anim-delay-3 { animation-delay: 0.3s; }
    .anim-delay-4 { animation-delay: 0.4s; }

    /* --- Hero Section --- */
    .hero-section {
        position: relative;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(rgba(30, 58, 95, 0.85), rgba(30, 58, 95, 0.95)), 
                    url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        overflow: hidden;
    }
    
    .hero-content {
        text-align: center;
        z-index: 2;
        max-width: 800px;
        padding: 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: white;
        text-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .hero-title span {
        color: var(--accent-color);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2.5rem;
        line-height: 1.7;
    }

    /* --- Buttons with Click Animation --- */
    .btn-gold {
        background: var(--accent-color);
        color: white;
        font-weight: 700;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        border: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(214, 158, 46, 0.3);
    }

    .btn-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(214, 158, 46, 0.5);
        color: white;
    }

    .btn-gold:active {
        transform: scale(0.95);
    }

    /* Shine effect on hover */
    .btn-gold::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: 0.5s;
    }

    .btn-gold:hover::before {
        animation: shine 1s infinite;
    }

    /* --- Key Dates --- */
    .dates-banner {
        background: white;
        border-left: 5px solid var(--accent-color);
        border-radius: 8px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    .date-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: var(--secondary-color);
        font-weight: 700;
        letter-spacing: 1px;
    }

    .date-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--primary-color);
    }

    /* --- Stats Section --- */
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem 1rem;
        text-align: center;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--accent-color);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(30, 58, 95, 0.1);
        border-color: rgba(214, 158, 46, 0.3);
    }

    .stat-card:hover::after {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(30, 58, 95, 0.05);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem auto;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-title {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: var(--secondary-color);
        font-weight: 600;
    }

    /* --- Content Cards --- */
    .content-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .content-card .card-header {
        background: var(--primary-color);
        color: white;
        padding: 1.25rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content-card .card-header i {
        color: var(--accent-color);
    }

    .content-card .card-body {
        padding: 0;
        flex-grow: 1;
        overflow-y: auto;
    }

    /* --- Idea List Items --- */
    .idea-item {
        padding: 1.25rem;
        border-bottom: 1px solid #f0f4f8;
        transition: background 0.2s;
    }

    .idea-item:last-child {
        border-bottom: none;
    }

    .idea-item:hover {
        background: #f8fafc;
    }

    .idea-title-link {
        color: var(--primary-color);
        font-weight: 700;
        text-decoration: none;
        font-size: 1.05rem;
        transition: color 0.2s;
    }

    .idea-title-link:hover {
        color: var(--accent-color);
    }

    .badge-anonymous {
        background-color: rgba(221, 107, 32, 0.1);
        color: var(--warning-color);
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
        padding: 0.3rem 0.6rem;
    }

    .meta-text {
        font-size: 0.85rem;
        color: #718096;
    }

    .meta-text i {
        margin-right: 4px;
        width: 16px;
        text-align: center;
    }

    .stat-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #f0f4f8;
        color: var(--secondary-color);
        transition: all 0.2s;
    }

    .stat-badge.success { color: var(--success-color); background: rgba(56, 161, 105, 0.1); }
    .stat-badge.danger { color: var(--danger-color); background: rgba(229, 62, 62, 0.1); }
    .stat-badge.info { color: var(--info-color); background: rgba(49, 130, 206, 0.1); }

    /* --- Categories --- */
    .category-pill {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        background: white;
        border: 1px solid var(--secondary-color);
        color: var(--secondary-color);
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        margin: 0.25rem;
    }

    .category-pill:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    /* Scrollbar styling */
    .card-body::-webkit-scrollbar {
        width: 6px;
    }
    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .card-body::-webkit-scrollbar-thumb {
        background: var(--secondary-color);
        border-radius: 3px;
    }

    footer {
        display: none;
    }

</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content anim-element">
        <i class="bi bi-lightbulb-fill" style="font-size: 4rem; color: var(--accent-color); margin-bottom: 1rem;"></i>
        <h1 class="hero-title">University <span>Ideas</span> System</h1>
        <p class="hero-subtitle">
            Your voice matters. Submit innovative ideas to help improve our university community and shape the future of our campus.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @auth
                @if(auth()->user()->canSubmitIdea())
                    <a href="{{ route('ideas.create') }}" class="btn btn-gold">
                        <i class="bi bi-plus-circle-fill me-2"></i> Submit Idea
                    </a>
                @else
                    <div class="alert alert-warning mb-0 px-4 py-2 rounded-pill">
                        <i class="bi bi-clock-history me-2"></i> Submission Closed
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-gold">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login to Participate
                </a>
            @endauth
        </div>
    </div>
</section>

<div class="container" style="margin-top: -30px; position: relative; z-index: 20;">
    
    <!-- Key Dates -->
    @if($ideaClosureDate || $finalClosureDate)
        <div class="dates-banner anim-element shadow-lg">
            <div class="row align-items-center">
                <div class="col-md-3 mb-3 mb-md-0">
                    <span class="badge bg-primary p-2 fs-6" style="background-color: var(--primary-color) !important;">
                        <i class="bi bi-calendar-event me-1"></i> Key Dates
                    </span>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    @if($ideaClosureDate)
                        <div class="date-label"><i class="bi bi-clock me-1"></i> Submission Deadline</div>
                        <div class="date-value">{{ $ideaClosureDate->format('F d, Y') }}</div>
                    @endif
                </div>
                <div class="col-md-5">
                    @if($finalClosureDate)
                        <div class="date-label"><i class="bi bi-calendar-x me-1"></i> Final Closure (Comments)</div>
                        <div class="date-value">{{ $finalClosureDate->format('F d, Y') }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="row g-4 my-5">
        <div class="col-md-3 col-6">
            <div class="stat-card anim-element anim-delay-1">
                <div class="stat-icon" style="color: var(--warning-color);">
                    <i class="bi bi-fire"></i>
                </div>
                <div class="stat-number">{{ $popularIdeas->count() }}</div>
                <div class="stat-title">Popular</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card anim-element anim-delay-2">
                <div class="stat-icon" style="color: var(--info-color);">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div class="stat-number">{{ $mostViewedIdeas->count() }}</div>
                <div class="stat-title">Most Viewed</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card anim-element anim-delay-3">
                <div class="stat-icon" style="color: var(--success-color);">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <div class="stat-number">{{ $latestIdeas->count() }}</div>
                <div class="stat-title">Latest</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card anim-element anim-delay-4">
                <div class="stat-icon" style="color: var(--accent-color);">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div class="stat-number">{{ $latestComments->count() }}</div>
                <div class="stat-title">Comments</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4 mb-5">
        <!-- Popular Ideas -->
        <div class="col-lg-6">
            <div class="content-card anim-element">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-fire me-2"></i>Popular Ideas</h5>
                    <a href="{{ route('ideas.index', ['sort' => 'popular']) }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    @forelse($popularIdeas as $idea)
                        <div class="idea-item">
                            <div class="d-flex justify-content-between mb-1">
                                <a href="{{ route('ideas.show', $idea) }}" class="idea-title-link">{{ $idea->title }}</a>
                                @if($idea->is_anonymous)
                                    <span class="badge-anonymous ms-2">Anon</span>
                                @endif
                            </div>
                            <div class="meta-text mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-clock"></i> {{ $idea->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex gap-2">
                                <span class="stat-badge success"><i class="bi bi-hand-thumbs-up-fill"></i> {{ $idea->thumbs_up_count }}</span>
                                <span class="stat-badge danger"><i class="bi bi-hand-thumbs-down-fill"></i> {{ $idea->thumbs_down_count }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i><br>No popular ideas yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Most Viewed -->
        <div class="col-lg-6">
            <div class="content-card anim-element anim-delay-1">
                <div class="card-header" style="background: var(--secondary-color);">
                    <h5 class="mb-0"><i class="bi bi-eye-fill me-2"></i>Most Viewed</h5>
                    <a href="{{ route('ideas.index', ['sort' => 'views']) }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    @forelse($mostViewedIdeas as $idea)
                        <div class="idea-item">
                            <div class="d-flex justify-content-between mb-1">
                                <a href="{{ route('ideas.show', $idea) }}" class="idea-title-link">{{ $idea->title }}</a>
                                @if($idea->is_anonymous)
                                    <span class="badge-anonymous ms-2">Anon</span>
                                @endif
                            </div>
                            <div class="meta-text mb-2">
                                <i class="bi bi-building"></i> {{ $idea->department->name }}
                            </div>
                            <div class="d-flex gap-2">
                                <span class="stat-badge info"><i class="bi bi-eye-fill"></i> {{ $idea->views_count }} Views</span>
                                <span class="stat-badge"><i class="bi bi-chat-text-fill"></i> {{ $idea->comments_count }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i><br>No viewed ideas yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Content Grid -->
    <div class="row g-4 mb-5">
        <!-- Latest Ideas -->
        <div class="col-lg-6">
            <div class="content-card anim-element">
                <div class="card-header" style="background: var(--secondary-color);">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Latest Ideas</h5>
                    <a href="{{ route('ideas.index') }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    @forelse($latestIdeas as $idea)
                        <div class="idea-item">
                            <div class="d-flex justify-content-between mb-1">
                                <a href="{{ route('ideas.show', $idea) }}" class="idea-title-link">{{ $idea->title }}</a>
                                @if($idea->is_anonymous)
                                    <span class="badge-anonymous ms-2">Anon</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-2 text-truncate" style="max-width: 95%;">
                                {{ Str::limit($idea->description, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="meta-text"><i class="bi bi-calendar3"></i> {{ $idea->created_at->diffForHumans() }}</span>
                                <span class="stat-badge"><i class="bi bi-graph-up"></i> Score: {{ $idea->popularity_score }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i><br>No ideas submitted yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Latest Comments -->
        <div class="col-lg-6">
            <div class="content-card anim-element anim-delay-1">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat-dots-fill me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    @forelse($latestComments as $comment)
                        <div class="idea-item">
                            <div class="d-flex gap-3">
                                <div class="pt-1">
                                    <i class="bi bi-chat-left-text-fill" style="font-size: 1.5rem; color: var(--secondary-color);"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1" style="font-size: 0.9rem; color: var(--primary-color);">
                                            On: <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none" style="color: var(--accent-color);">{{ $comment->idea->title ?? 'Deleted' }}</a>
                                        </h6>
                                        @if($comment->is_anonymous)
                                            <span class="badge-anonymous">Anon</span>
                                        @endif
                                    </div>
                                    <p class="mb-1 small text-muted">{{ Str::limit($comment->content, 80) }}</p>
                                    <span class="meta-text"><i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i><br>No comments yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="content-card mb-4 anim-element h-auto">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
            <h5 class="mb-0 text-white"><i class="bi bi-grid-fill me-2" style="color: var(--accent-color);"></i>Explore by Category</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @forelse($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('ideas.index', ['category' => $category->id]) }}" class="text-decoration-none">
                            <div class="category-card h-100">
                                <div class="category-icon-wrapper">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div class="category-info">
                                    <h6 class="category-name text-truncate" title="{{ $category->name }}">{{ $category->name }}</h6>
                                    <span class="category-count">{{ $category->ideas_count }} {{ Str::plural('Idea', $category->ideas_count) }}</span>
                                </div>
                                <div class="category-arrow">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 py-4 text-center text-muted">
                        <i class="bi bi-folder-x fs-1 mb-2 d-block"></i>
                        <p class="mb-0">No categories available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Add these new styles for the category cards */
    .category-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 12px;
        padding: 1.25rem 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        position: relative;
        overflow: hidden;
    }

    .category-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--accent-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
        transform-origin: bottom;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(30, 58, 95, 0.08);
        border-color: rgba(214, 158, 46, 0.2);
    }

    .category-card:hover::before {
        transform: scaleY(1);
    }

    .category-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(30, 58, 95, 0.06);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-icon-wrapper {
        background: var(--primary-color);
        color: #ffffff;
    }

    .category-info {
        flex-grow: 1;
        min-width: 0; /* Enables text truncation */
    }

    .category-name {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.15rem;
        font-size: 0.95rem;
        transition: color 0.3s ease;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; /* Override Merriweather for better UI readability */
    }

    .category-card:hover .category-name {
        color: var(--accent-color);
    }

    .category-count {
        display: inline-block;
        font-size: 0.75rem;
        color: #718096;
        font-weight: 500;
        background: #f0f4f8;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
    }

    .category-arrow {
        color: #cbd5e1;
        font-size: 1rem;
        transition: all 0.3s ease;
        transform: translateX(-4px);
        opacity: 0;
    }

    .category-card:hover .category-arrow {
        color: var(--accent-color);
        transform: translateX(0);
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Intersection Observer for Scroll Animations
        const observerOptions = {
            threshold: 0.15,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const animatedElements = document.querySelectorAll('.anim-element');
        animatedElements.forEach(el => {
            // Pause default animation defined in CSS until visible
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        // Card Ripple Click Effect (Vanilla JS implementation of jQuery ripple)
        document.querySelectorAll('.stat-card, .category-pill, .btn-gold').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Visual feedback only for actual links/buttons
                if(this.tagName === 'A' || this.tagName === 'BUTTON') {
                    const rect = this.getBoundingClientRect();
                    const ripple = document.createElement('span');
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 70%);
                        transform: scale(0);
                        border-radius: 50%;
                        pointer-events: none;
                        transition: transform 0.6s ease-out, opacity 0.6s ease-out;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    requestAnimationFrame(() => {
                        ripple.style.transform = 'scale(2)';
                        ripple.style.opacity = '0';
                    });

                    setTimeout(() => ripple.remove(), 600);
                }
            });
        });
    });
</script>
@endsection
