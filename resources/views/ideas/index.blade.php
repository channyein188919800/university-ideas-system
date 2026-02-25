@extends('layouts.app')

@section('title', 'All Ideas - University Ideas System')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        background: #0f172a;
        min-height: 100vh;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: "Merriweather", serif;
    }

    /* ── Hero Banner ── */
    .ideas-hero {
        position: relative;
        min-height: 38vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3.5rem 1rem 3rem;
        color: #fff;
        background-image:
            linear-gradient(135deg, rgba(15,23,42,0.82), rgba(15,23,42,0.55)),
            url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .ideas-hero-card {
        max-width: 680px;
        width: 100%;
        margin: 0 auto;
        padding: 2.2rem 2rem;
        border-radius: 1.5rem;
        background: rgba(15,23,42,0.55);
        border: 1px solid rgba(255,255,255,0.09);
        backdrop-filter: blur(18px);
        box-shadow: 0 18px 45px rgba(0,0,0,0.45);
        text-align: center;
    }

    .ideas-hero-card .hero-icon {
        font-size: 2.8rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .ideas-hero-card h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #f8fafc;
    }

    .ideas-hero-card p {
        opacity: 0.85;
        color: #cbd5e1;
        margin-bottom: 1.4rem;
        font-size: 1rem;
    }

    /* ── Page Body ── */
    .ideas-body {
        background: #0f172a;
        padding: 2rem 0 4rem;
    }

    /* ── Glass Filter Card ── */
    .filter-glass {
        border-radius: 1.25rem;
        background: rgba(30,41,59,0.72);
        border: 1px solid rgba(148,163,184,0.22);
        backdrop-filter: blur(18px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.35);
        padding: 1.4rem 1.6rem;
        margin-bottom: 2rem;
    }

    .filter-glass label {
        color: #94a3b8;
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.4rem;
    }

    .filter-glass .form-select {
        background: rgba(15,23,42,0.75);
        border: 1px solid rgba(148,163,184,0.28);
        color: #f1f5f9;
        border-radius: 0.75rem;
        padding: 0.55rem 0.9rem;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .filter-glass .form-select:focus {
        border-color: rgba(59,130,246,0.55);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        outline: none;
    }

    .filter-glass .form-select option {
        background: #1e293b;
        color: #f1f5f9;
    }

    .btn-reset {
        border-radius: 0.75rem;
        background: rgba(148,163,184,0.12);
        border: 1px solid rgba(148,163,184,0.25);
        color: #94a3b8;
        font-weight: 600;
        padding: 0.55rem 1rem;
        transition: all 0.25s ease;
        width: 100%;
    }

    .btn-reset:hover {
        background: rgba(148,163,184,0.22);
        color: #f1f5f9;
        border-color: rgba(148,163,184,0.45);
    }

    /* ── Idea Card ── */
    .idea-item {
        position: relative;
        isolation: isolate;
        border-radius: 1.25rem;
        background:
            radial-gradient(circle at 15% 10%, rgba(255,255,255,0.06), transparent 40%),
            linear-gradient(145deg, rgba(30,41,59,0.88), rgba(15,23,42,0.78));
        border: 1px solid rgba(148,163,184,0.18);
        backdrop-filter: blur(18px);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.08),
            0 10px 30px rgba(0,0,0,0.35);
        padding: 1.6rem 1.8rem;
        margin-bottom: 1.2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        overflow: hidden;
        cursor: pointer;
    }

    /* Golden glow overlay on hover */
    .idea-item::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 50% 60%, rgba(214,158,46,0.18) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
        z-index: 0;
    }

    .idea-item:hover::before {
        opacity: 1;
    }

    /* Shimmer sweep on hover */
    .idea-item::after {
        content: "";
        position: absolute;
        top: 0;
        left: -120%;
        width: 65%;
        height: 100%;
        background: linear-gradient(105deg, transparent, rgba(214,158,46,0.12), transparent);
        transform: skewX(-14deg);
        transition: left 0.6s ease;
        pointer-events: none;
        z-index: 0;
    }

    .idea-item:hover {
        transform: translateY(-5px);
        border-color: rgba(214,158,46,0.45);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.12),
            0 20px 45px rgba(0,0,0,0.5),
            0 0 30px rgba(214,158,46,0.12);
    }

    .idea-item:hover::after {
        left: 150%;
    }

    /* Click / active press effect */
    .idea-item:active {
        transform: translateY(-2px) scale(0.99);
        transition: transform 0.1s ease;
    }

    .idea-item-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #f8fafc;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .idea-item-title a {
        color: inherit;
        text-decoration: none;
        flex: 1;
        min-width: 0;
        word-break: break-word;
        transition: color 0.2s ease;
    }

    .idea-item-title a:hover {
        color: #93c5fd;
    }

    .idea-item-meta {
        font-size: 0.83rem;
        color: #d69e2e;
        margin-bottom: 0.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        align-items: center;
    }

    .idea-item-meta i {
        color: #d69e2e;
    }

    .idea-item-meta .sep {
        color: rgba(214,158,46,0.4);
    }

    .idea-item-description {
        color: #94a3b8;
        font-size: 0.92rem;
        line-height: 1.65;
        margin-bottom: 1rem;
    }

    /* ── Category badges ── */
    .badge-cat {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.3rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 600;
        background: rgba(15,23,42,0.85);
        border: 1px solid rgba(148,163,184,0.35);
        color: #cbd5e1;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .badge-cat:hover {
        transform: translateY(-2px);
        background: rgba(30,64,175,0.55);
        border-color: rgba(99,179,237,0.45);
        box-shadow: 0 6px 18px rgba(0,0,0,0.45);
        color: #e2e8f0;
    }

    /* ── Anonymous badge ── */
    .badge-anon {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.2rem 0.65rem;
        background: rgba(51,65,85,0.75);
        border: 1px solid rgba(100,116,139,0.45);
        color: #94a3b8;
    }

    /* ── Stats strip ── */
    .stats-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 0.9rem;
        align-items: center;
        font-size: 0.83rem;
        color: #64748b;
    }

    .stats-strip .stat-item {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-weight: 600;
    }

    .stat-up   { color: #4ade80; }
    .stat-down { color: #f87171; }
    .stat-view { color: #60a5fa; }
    .stat-comm { color: #fbbf24; }

    /* ── Score badge ── */
    .score-badge {
        display: inline-flex;
        align-items: center;
        font-size: 0.78rem;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.25rem 0.75rem;
        background: linear-gradient(135deg, rgba(30,64,175,0.55), rgba(99,102,241,0.45));
        border: 1px solid rgba(99,179,237,0.35);
        color: #93c5fd;
    }

    /* ── View Details button ── */
    .btn-view {
        border-radius: 0.75rem;
        background: linear-gradient(135deg, rgba(30,64,175,0.65), rgba(99,102,241,0.55));
        border: 1px solid rgba(99,179,237,0.3);
        color: #e2e8f0;
        font-size: 0.83rem;
        font-weight: 600;
        padding: 0.45rem 1rem;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, rgba(30,64,175,0.85), rgba(99,102,241,0.75));
        border-color: rgba(99,179,237,0.55);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(30,64,175,0.45);
    }

    /* ── Submit Idea button in hero ── */
    .btn-submit-hero {
        border-radius: 999px;
        background: linear-gradient(135deg, #1d4ed8, #6366f1);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 0.65rem 1.8rem;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(30,64,175,0.45);
    }

    .btn-submit-hero:hover {
        background: linear-gradient(135deg, #1e40af, #4f46e5);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(30,64,175,0.6);
        color: #fff;
    }

    /* ── Empty state ── */
    .empty-state {
        border-radius: 1.25rem;
        background: rgba(30,41,59,0.65);
        border: 1px solid rgba(148,163,184,0.15);
        backdrop-filter: blur(18px);
        padding: 3.5rem 2rem;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        display: block;
        color: #334155;
    }

    /* ── Fade-in animation ── */
    .fade-in-up {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.55s ease, transform 0.55s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-in-delay-1 { transition-delay: 0.08s; }
    .fade-in-delay-2 { transition-delay: 0.16s; }
    .fade-in-delay-3 { transition-delay: 0.24s; }

    /* ── Pagination styling ── */
    .pagination .page-link {
        background: rgba(30,41,59,0.75);
        border: 1px solid rgba(148,163,184,0.22);
        color: #94a3b8;
        border-radius: 0.65rem !important;
        margin: 0 2px;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background: rgba(30,64,175,0.45);
        border-color: rgba(99,179,237,0.4);
        color: #e2e8f0;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #1d4ed8, #6366f1);
        border-color: transparent;
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .ideas-hero-card { padding: 1.7rem 1.2rem; }
        .ideas-hero-card h1 { font-size: 1.5rem; }
        .idea-item { padding: 1.2rem 1.2rem; }
    }
</style>



{{-- ── Page Body ── --}}
<div class="ideas-body">
    <div class="container">

        {{-- ── Filters ── --}}
        <div class="filter-glass fade-in-up fade-in-delay-1">
            <form method="GET" action="{{ route('ideas.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label><i class="bi bi-funnel-fill me-1"></i> Category</label>
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label><i class="bi bi-sort-down me-1"></i> Sort By</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest"  {{ request('sort') == 'latest'  ? 'selected' : '' }}>Latest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="views"   {{ request('sort') == 'views'   ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="d-block" style="visibility:hidden;">Reset</label>
                    <a href="{{ route('ideas.index') }}" class="btn-reset d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>

        {{-- ── Ideas List ── --}}
        @forelse($ideas as $idea)
            <div class="idea-item fade-in-up" data-href="{{ route('ideas.show', $idea) }}">
                <div class="row align-items-start g-3">
                    {{-- Left: content --}}
                    <div class="col-md-8">
                        <h5 class="idea-item-title">
                            <a href="{{ route('ideas.show', $idea) }}">{{ $idea->title }}</a>
                            @if($idea->is_anonymous)
                                <span class="badge-anon">
                                    <i class="bi bi-incognito me-1"></i> Anonymous
                                </span>
                            @endif
                        </h5>

                        <div class="idea-item-meta">
                            <span><i class="bi bi-building me-1"></i>{{ $idea->department->name }}</span>
                            <span class="sep">&bull;</span>
                            <span><i class="bi bi-person me-1"></i>{{ $idea->author_name }}</span>
                            <span class="sep">&bull;</span>
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $idea->created_at->format('M d, Y') }}</span>
                        </div>

                        <p class="idea-item-description">{{ Str::limit($idea->description, 250) }}</p>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($idea->categories as $category)
                                <span class="badge-cat">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: stats + actions --}}
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-end justify-content-between h-100 gap-3">
                            <div class="stats-strip justify-content-end">
                                <span class="stat-item stat-up">
                                    <i class="bi bi-hand-thumbs-up-fill"></i> {{ $idea->thumbs_up_count }}
                                </span>
                                <span class="stat-item stat-down">
                                    <i class="bi bi-hand-thumbs-down-fill"></i> {{ $idea->thumbs_down_count }}
                                </span>
                                <span class="stat-item stat-view">
                                    <i class="bi bi-eye-fill"></i> {{ $idea->views_count }}
                                </span>
                                <span class="stat-item stat-comm">
                                    <i class="bi bi-chat-text-fill"></i> {{ $idea->comments_count }}
                                </span>
                            </div>

                            <div class="text-end">
                                <span class="score-badge mb-2 d-inline-block">
                                    <i class="bi bi-star-fill me-1" style="color:#fbbf24;"></i>
                                    Score: {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state fade-in-up">
                <i class="bi bi-inbox"></i>
                <h5 style="color:#475569;font-family:'Merriweather',serif;">No ideas found</h5>
                <p style="color:#334155;margin-bottom:1.2rem;">Be the first to submit an idea to the community!</p>
                @auth
                    @if(auth()->user()->canSubmitIdea())
                        <a href="{{ route('ideas.create') }}" class="btn btn-submit-hero">
                            <i class="bi bi-plus-circle-fill me-1"></i> Submit Idea
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse

        {{-- ── Pagination ── --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $ideas->withQueryString()->links() }}
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fade-in observer
        var els = document.querySelectorAll('.fade-in-up');
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });
            els.forEach(function (el) { observer.observe(el); });
        } else {
            els.forEach(function (el) { el.classList.add('visible'); });
        }

        // Clickable idea cards
        document.querySelectorAll('.idea-item[data-href]').forEach(function (card) {
            card.addEventListener('click', function (e) {
                // Don't navigate if user clicked a link/button inside the card
                if (e.target.closest('a, button, select')) return;
                window.location.href = card.dataset.href;
            });
        });
    });
</script>
@endsection
