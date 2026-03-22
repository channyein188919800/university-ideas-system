@extends('layouts.app')

@section('title', 'All Ideas - University Ideas System')

@section('content')
<style>
    :root {
        --primary-color: #1e3a5f;
        --secondary-color: #2c5282;
        --accent-color: #d69e2e;
        --body-bg: #f0f4f8;
        --card-bg: #ffffff;
        --text-muted: #718096;
    }

    body {
        background-color: var(--body-bg);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: #2d3748;
        margin: 0;
        padding: 0;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: "Merriweather", serif;
        color: var(--primary-color);
    }

    .fade-in-up {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .ideas-hero {
        background: linear-gradient(rgba(30, 58, 95, 0.9), rgba(30, 58, 95, 0.95)), 
                    url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg');
        background-size: cover;
        background-position: center;
        border-radius: 1.25rem;
        padding: 3rem 1.5rem 5.5rem 1.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    @media (min-width: 768px) {
        .ideas-hero { padding: 4rem 2.5rem 6.5rem 2.5rem; }
    }

    .hero-title span { color: var(--accent-color); }
    .hero-kicker { color: var(--accent-color); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; display: block; font-size: 0.8rem; }

    .filter-card {
        background: white;
        border-radius: 1.15rem;
        border: none;
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        padding: 1.5rem;
        margin-top: -3.5rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 10;
        border-top: 5px solid var(--accent-color);
    }

    .filter-card label {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--primary-color);
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
    }

    .form-select {
        border-radius: 0.6rem;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        font-size: 0.9rem;
        width: 100%; 
        max-width: 100%;
        background-color: #fff;
        cursor: pointer;
    }

    .btn-reset {
        background: #f1f5f9;
        color: var(--secondary-color);
        font-weight: 700;
        border-radius: 0.6rem;
        padding: 0.75rem;
        text-align: center;
        display: block;
        text-decoration: none;
        border: none;
        width: 100%;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: var(--primary-color);
    }

    .idea-item {
        background: white;
        border-radius: 1.25rem;
        border: 1px solid rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        cursor: pointer;
    }

    .idea-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(30, 58, 95, 0.08);
    }

    .idea-content-area { padding: 1.5rem; }
    
    .idea-title a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 800;
        font-size: 1.2rem;
    }

    .idea-title a:hover {
        color: var(--accent-color);
    }

    .idea-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .tag {
        background: rgba(30, 58, 95, 0.04);
        color: var(--secondary-color);
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid rgba(30, 58, 95, 0.08);
        text-decoration: none;
    }

    .badge-anon {
        background: rgba(214, 158, 46, 0.1);
        color: var(--accent-color);
        padding: 0.25rem 0.6rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .author-panel {
        background: #fcfdfe;
        border-top: 1px solid #f1f5f9;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (min-width: 992px) {
        .author-panel {
            border-top: none;
            border-left: 1px solid #f1f5f9;
            padding: 2rem;
        }
    }

    .author-avatar {
        width: 44px;
        height: 44px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        border: 2px solid var(--accent-color);
    }

    .author-name { color: var(--primary-color); font-weight: 700; margin: 0; font-size: 0.95rem; }
    .author-role { 
        font-size: 0.65rem; 
        text-transform: uppercase; 
        background: var(--accent-color); 
        color: white; 
        padding: 0.15rem 0.5rem; 
        border-radius: 4px; 
        font-weight: 700;
        display: inline-block;
    }

    .stat-chip {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
    }

    .score-chip {
        display: block;
        text-align: center;
        background: var(--primary-color);
        color: white;
        padding: 0.6rem;
        border-radius: 8px;
        font-weight: 700;
        margin-top: 1rem;
        font-size: 0.85rem;
    }

    .btn-gold {
        background: var(--accent-color);
        color: white;
        font-weight: 700;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-gold:hover {
        background: #b8891d;
        color: white;
        transform: translateY(-2px);
    }

    @media (min-width: 768px) {
        .btn-gold { width: auto; }
    }
</style>

<div class="ideas-shell py-4">
    <div class="container">
        <div class="ideas-hero fade-in-up">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="hero-kicker">Knowledge Base</span>
                    <h1 class="hero-title text-white mb-2">
                        @if(request('my_ideas'))
                            My <span>Submitted</span> Ideas
                        @else
                            Explore <span>University</span> Ideas
                        @endif
                    </h1>
                    <p class="opacity-75 mb-0 d-none d-md-block">
                        @if(request('my_ideas'))
                            View and track all the ideas you have contributed to the platform.
                        @else
                            Discover community contributions from across all departments.
                        @endif
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="d-flex flex-column align-items-lg-end gap-3">
                        <div>
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-lightbulb-fill text-warning me-1"></i> Ideas: {{ $ideas->total() }}
                            </span>
                        </div>
                        @auth
                            @if(auth()->user()->canSubmitIdea())
                                <a href="{{ route('ideas.create') }}" class="btn-gold shadow-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Submit Idea
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER CARD - FIXED VERSION -->
        <div class="filter-card fade-in-up">
            <form method="GET" action="{{ route('ideas.index') }}" class="row g-3" id="filterForm">
                <div class="col-12 col-md-4">
                    <label><i class="bi bi-tag-fill me-2 text-warning"></i> Category</label>
                    <select name="category" class="form-select" id="categorySelect">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label><i class="bi bi-sort-up me-2 text-warning"></i> Sort By</label>
                    <select name="sort" class="form-select" id="sortSelect">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Most Recent</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Trending Now</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="d-none d-md-block" style="visibility: hidden;">Reset</label>
                    <a href="{{ route('ideas.index') }}" class="btn-reset w-100" id="resetFiltersBtn">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- IDEAS LIST -->
        <div class="idea-list-container">
            @forelse($ideas as $idea)
                @php
                    $authorName = $idea->is_anonymous ? 'Anonymous Contributor' : ($idea->user?->name ?? 'Unknown User');
                    $roleLabel = $idea->is_anonymous ? 'Member' : str_replace('_', ' ', ($idea->user?->role ?? 'staff'));
                    $initials = collect(explode(' ', trim($authorName)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                    $initials = $initials ?: 'AN';
                @endphp

                @php
                    $detailUrl = route('ideas.show', $idea);
                    if (request('my_ideas') && auth()->user()?->isStaff() && $idea->status !== 'approved') {
                        $detailUrl = route('staff.ideas.show', $idea);
                    }
                @endphp
                <article class="idea-item fade-in-up" data-href="{{ $detailUrl }}">
                    <div class="row g-0">
                        <div class="col-lg-8">
                            <div class="idea-content-area">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h5 class="idea-title mb-0">
                                        <a href="{{ $detailUrl }}">{{ $idea->title }}</a>
                                    </h5>
                                    @if($idea->is_anonymous)
                                        <span class="badge-anon"><i class="bi bi-incognito"></i> Anonymous</span>
                                    @endif
                                </div>

                                <div class="idea-meta">
                                    <span><i class="bi bi-building"></i> {{ $idea->department?->name ?? 'Unassigned' }}</span>
                                    <span><i class="bi bi-calendar3"></i> {{ $idea->created_at->format('M d, Y') }}</span>
                                    @if(request('my_ideas'))
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger'
                                            ];
                                            $statusLabel = ucfirst($idea->status ?? 'pending');
                                        @endphp
                                        <span class="badge {{ $statusClasses[$idea->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabel }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-secondary small mb-3">
                                    {{ Str::limit($idea->description, 180) }}
                                </p>

                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($idea->categories as $category)
                                        <span class="tag">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="author-panel h-100">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="author-avatar">{{ $initials }}</div>
                                    <div>
                                        <p class="author-name">{{ $authorName }}</p>
                                        <span class="author-role">{{ $roleLabel }}</span>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6"><div class="stat-chip"><i class="bi bi-hand-thumbs-up"></i> {{ $idea->thumbs_up_count }}</div></div>
                                    <div class="col-6"><div class="stat-chip"><i class="bi bi-hand-thumbs-down"></i> {{ $idea->thumbs_down_count }}</div></div>
                                    <div class="col-6"><div class="stat-chip"><i class="bi bi-eye"></i> {{ $idea->views_count }}</div></div>
                                    <div class="col-6"><div class="stat-chip"><i class="bi bi-chat-dots"></i> {{ $idea->comments_count }}</div></div>
                                </div>

                                <div class="score-chip">Score: {{ $idea->popularity_score }}</div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm fade-in-up">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <h5 class="mt-3">No ideas found.</h5>
                    <p class="text-muted">Try changing filters or submit a new idea!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $ideas->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animation Observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));

        // Clickable cards
        document.querySelectorAll('.idea-item[data-href]').forEach(card => {
            card.addEventListener('click', function (e) {
                if (!e.target.closest('a, button, select')) {
                    window.location.href = card.dataset.href;
                }
            });
        });

        // ===== FIX: RESET FILTERS - Just use the link directly =====
        // The reset button is already an <a> tag that goes to route('ideas.index')
        // No extra JavaScript needed!
        
        // Auto-submit when dropdowns change
        const categorySelect = document.getElementById('categorySelect');
        const sortSelect = document.getElementById('sortSelect');
        const filterForm = document.getElementById('filterForm');

        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                // When "All Categories" is selected (empty value), remove the parameter
                if (this.value === '') {
                    // Remove category from URL
                    const url = new URL(window.location.href);
                    url.searchParams.delete('category');
                    window.location.href = url.toString();
                } else {
                    filterForm.submit();
                }
            });
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });
</script>
@endsection
