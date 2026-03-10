@extends('layouts.app')

@section('title', 'All Ideas - University Ideas System')

@section('content')
<style>
    :root {
        --brand-navy: #0f172a;
        --brand-blue: #1e3a5f;
        --brand-gold: #d69e2e;
        --paper: #f8fafc;
        --panel: #ffffff;
        --line: #e2e8f0;
        --muted: #64748b;
        --text: #0f172a;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background:
            radial-gradient(circle at 0% 0%, rgba(214, 158, 46, 0.12), transparent 35%),
            radial-gradient(circle at 100% 100%, rgba(30, 58, 95, 0.12), transparent 40%),
            var(--paper);
        min-height: 100vh;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Merriweather', serif;
        color: var(--text);
    }

    .ideas-shell {
        padding: 1.75rem 0 3rem;
    }

    .ideas-hero {
        border-radius: 1.25rem;
        padding: 1.4rem 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        border: 1px solid var(--line);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
        margin-bottom: 1rem;
    }

    .hero-kicker {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        color: var(--brand-blue);
        text-transform: uppercase;
        margin-bottom: 0.3rem;
    }

    .hero-title {
        margin: 0;
        font-size: 1.75rem;
    }

    .hero-copy {
        color: var(--muted);
        margin: 0.45rem 0 0;
        font-size: 0.95rem;
    }

    .hero-stats {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
        color: #334155;
        background: #fff;
        font-weight: 600;
    }

    .hero-chip strong {
        color: var(--brand-blue);
    }

    .btn-submit {
        border: 0;
        border-radius: 0.8rem;
        padding: 0.62rem 1.1rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--brand-navy), var(--brand-blue));
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-submit:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 14px 24px rgba(15, 23, 42, 0.25);
    }

    .filter-card {
        border-radius: 1.15rem;
        background: var(--panel);
        border: 1px solid var(--line);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .filter-card label {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 0.35rem;
    }

    .filter-card .form-select {
        border-radius: 0.72rem;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        background: #fff;
        font-size: 0.92rem;
    }

    .filter-card .form-select:focus {
        border-color: var(--brand-gold);
        box-shadow: 0 0 0 0.2rem rgba(214, 158, 46, 0.15);
    }

    .btn-reset {
        border-radius: 0.72rem;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #334155;
        text-decoration: none;
        font-weight: 600;
        padding: 0.55rem 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-reset:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .idea-item {
        border: 1px solid var(--line);
        border-radius: 1.1rem;
        background: #fff;
        box-shadow: 0 8px 26px rgba(15, 23, 42, 0.07);
        padding: 1.15rem;
        margin-bottom: 0.9rem;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .idea-item:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.1);
    }

    .idea-title-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }

    .idea-title {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .idea-title a {
        color: #0f172a;
        text-decoration: none;
    }

    .idea-title a:hover {
        color: var(--brand-blue);
    }

    .badge-anon {
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.22rem 0.65rem;
        background: rgba(15, 23, 42, 0.06);
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .idea-meta {
        display: flex;
        gap: 0.7rem;
        align-items: center;
        flex-wrap: wrap;
        font-size: 0.81rem;
        color: #64748b;
        margin-bottom: 0.6rem;
    }

    .idea-meta i {
        color: var(--brand-blue);
    }

    .idea-desc {
        color: #334155;
        line-height: 1.58;
        font-size: 0.92rem;
        margin-bottom: 0.7rem;
    }

    .tag-wrap {
        display: flex;
        gap: 0.38rem;
        flex-wrap: wrap;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.22rem 0.68rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.73rem;
        font-weight: 700;
    }

    .author-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        padding: 0.75rem;
    }

    .author-head {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .author-avatar {
        width: 2.15rem;
        height: 2.15rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f172a, #1e3a5f);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .author-name {
        margin: 0;
        font-size: 0.88rem;
        color: #0f172a;
        font-weight: 700;
        line-height: 1.25;
    }

    .author-email {
        margin: 0.08rem 0 0;
        font-size: 0.76rem;
        color: #64748b;
        word-break: break-word;
    }

    .author-role {
        display: inline-flex;
        margin-top: 0.4rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: capitalize;
        color: #0f172a;
        background: rgba(214, 158, 46, 0.14);
        border: 1px solid rgba(214, 158, 46, 0.35);
        padding: 0.2rem 0.58rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.38rem;
        margin-top: 0.75rem;
    }

    .stat-chip {
        border: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 0.7rem;
        padding: 0.34rem 0.45rem;
        font-size: 0.77rem;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 0.28rem;
    }

    .stat-chip i { color: var(--brand-blue); }

    .score-chip {
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
        border-radius: 999px;
        padding: 0.22rem 0.72rem;
        font-size: 0.74rem;
        font-weight: 800;
        background: linear-gradient(135deg, rgba(214, 158, 46, 0.2), rgba(214, 158, 46, 0.1));
        color: #7c4a03;
        border: 1px solid rgba(214, 158, 46, 0.4);
    }

    .empty-state {
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        background: #fff;
        padding: 2.5rem 1.3rem;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 0.7rem;
        display: block;
    }

    .fade-in-up {
        opacity: 0;
        transform: translateY(14px);
        transition: opacity 0.45s ease, transform 0.45s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-in-delay-1 { transition-delay: 0.06s; }
    .fade-in-delay-2 { transition-delay: 0.12s; }

    .pagination .page-link {
        border-radius: 0.6rem !important;
        color: #334155;
        border-color: #cbd5e1;
        margin: 0 0.12rem;
    }

    .pagination .page-link:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--brand-navy), var(--brand-blue));
        border-color: transparent;
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .ideas-shell {
            padding-top: 1rem;
        }

        .ideas-hero {
            padding: 1rem;
        }

        .hero-title {
            font-size: 1.35rem;
        }

        .idea-item {
            padding: 0.95rem;
        }
    }
</style>

<div class="ideas-shell">
    <div class="container">
        <div class="ideas-hero fade-in-up">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <p class="hero-kicker">Community Hub</p>
                    <h1 class="hero-title">Explore Approved Ideas</h1>
                    <p class="hero-copy">
                        Browse submissions across departments with a clearer contributor panel and light reading layout.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="hero-stats mb-2">
                        <span class="hero-chip"><i class="bi bi-lightbulb"></i> Ideas: <strong>{{ $ideas->total() }}</strong></span>
                        <span class="hero-chip"><i class="bi bi-funnel"></i> Filtered: <strong>{{ $ideas->count() }}</strong></span>
                    </div>
                    @auth
                        @if(auth()->user()->canSubmitIdea())
                            <div class="text-lg-end">
                                <a href="{{ route('ideas.create') }}" class="btn-submit">
                                    <i class="bi bi-plus-circle-fill"></i> Submit Idea
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="filter-card fade-in-up fade-in-delay-1">
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
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="d-block" style="visibility:hidden;">Reset</label>
                    <a href="{{ route('ideas.index') }}" class="btn-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>

        @forelse($ideas as $idea)
            @php
                $authorName = $idea->is_anonymous ? 'Anonymous Contributor' : ($idea->user?->name ?? 'Unknown User');
                $authorEmail = $idea->is_anonymous ? 'Identity hidden by submitter' : ($idea->user?->email ?? 'Email unavailable');
                $roleLabel = $idea->is_anonymous ? 'Anonymous' : str_replace('_', ' ', ($idea->user?->role ?? 'staff'));
                $initials = collect(explode(' ', trim($authorName)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                $initials = $initials ?: 'UI';
            @endphp

            <article class="idea-item fade-in-up" data-href="{{ route('ideas.show', $idea) }}">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="idea-title-row">
                            <h5 class="idea-title mb-0">
                                <a href="{{ route('ideas.show', $idea) }}">{{ $idea->title }}</a>
                            </h5>
                            @if($idea->hidden)
                                <span class="badge text-bg-dark"><i class="bi bi-eye-slash me-1"></i>Hidden</span>
                            @endif
                            @if($idea->is_anonymous)
                                <span class="badge-anon"><i class="bi bi-incognito me-1"></i> Anonymous</span>
                            @endif
                        </div>

                        <div class="idea-meta">
                            <span><i class="bi bi-building me-1"></i>{{ $idea->department->name }}</span>
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $idea->created_at->format('M d, Y') }}</span>
                            <span><i class="bi bi-clock-history me-1"></i>{{ $idea->created_at->diffForHumans() }}</span>
                        </div>

                        <p class="idea-desc">{{ Str::limit($idea->description, 240) }}</p>

                        <div class="tag-wrap">
                            @foreach($idea->categories as $category)
                                <span class="tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="author-panel h-100">
                            <div class="author-head">
                                <span class="author-avatar">{{ $initials }}</span>
                                <div>
                                    <p class="author-name">{{ $authorName }}</p>
                                    <p class="author-email">{{ $authorEmail }}</p>
                                </div>
                            </div>
                            <span class="author-role">{{ $roleLabel }}</span>

                            <div class="stats-grid">
                                <span class="stat-chip"><i class="bi bi-hand-thumbs-up-fill"></i> {{ $idea->thumbs_up_count }}</span>
                                <span class="stat-chip"><i class="bi bi-hand-thumbs-down-fill"></i> {{ $idea->thumbs_down_count }}</span>
                                <span class="stat-chip"><i class="bi bi-eye-fill"></i> {{ $idea->views_count }}</span>
                                <span class="stat-chip"><i class="bi bi-chat-text-fill"></i> {{ $idea->comments_count }}</span>
                            </div>

                            <span class="score-chip">
                                <i class="bi bi-star-fill"></i>
                                Score {{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}
                            </span>

                            @auth
                                @if(auth()->user()->isQaManager())
                                    <form method="POST" action="{{ route('qa-manager.ideas.toggle-hidden', $idea) }}" class="mt-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $idea->hidden ? 'btn-success' : 'btn-outline-danger' }}">
                                            <i class="bi {{ $idea->hidden ? 'bi-eye' : 'bi-eye-slash' }} me-1"></i>
                                            {{ $idea->hidden ? 'Unhide' : 'Hide' }}
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-state fade-in-up">
                <i class="bi bi-inbox"></i>
                <h5 class="mb-2">No ideas found</h5>
                <p class="mb-3">Try changing filters or be the first to submit a new idea.</p>
                @auth
                    @if(auth()->user()->canSubmitIdea())
                        <a href="{{ route('ideas.create') }}" class="btn-submit">
                            <i class="bi bi-plus-circle-fill"></i> Submit Idea
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse

        <div class="mt-4 d-flex justify-content-center">
            {{ $ideas->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.fade-in-up');
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            els.forEach(function (el) {
                observer.observe(el);
            });
        } else {
            els.forEach(function (el) {
                el.classList.add('visible');
            });
        }

        document.querySelectorAll('.idea-item[data-href]').forEach(function (card) {
            card.addEventListener('click', function (e) {
                if (e.target.closest('a, button, select')) {
                    return;
                }
                window.location.href = card.dataset.href;
            });
        });
    });
</script>
@endsection
