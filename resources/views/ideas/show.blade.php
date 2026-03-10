@extends('layouts.app')

@section('title', $idea->title . ' - University Ideas System')

@section('content')
<style>
    

    body {
        background-color: var(--body-bg);
        font-family: 'Nunito', 'Segoe UI', sans-serif;
    }

    /* --- Animation Keyframes --- */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translate3d(0, 30px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translate3d(-30px, 0, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }

    @keyframes pulseGold {
        0% { box-shadow: 0 0 0 0 rgba(214, 158, 46, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(214, 158, 46, 0); }
        100% { box-shadow: 0 0 0 0 rgba(214, 158, 46, 0); }
    }

    @keyframes bounceIn {
        0% { transform: scale(0.9); opacity: 0; }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Animation Utility Classes */
    .anim-fade-up {
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards;
    }

    .anim-slide-left {
        opacity: 0;
        animation: slideInLeft 0.8s ease forwards;
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    /* --- General Layout --- */
    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* --- Hero Section --- */
    .idea-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border-radius: 1.5rem;
        padding: 3rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(30, 58, 95, 0.25);
    }

    .idea-hero::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: var(--accent-color);
        opacity: 0.1;
        border-radius: 50%;
    }

    .hero-category {
        background: rgba(255, 255, 255, 0.15);
        color: var(--accent-color);
        padding: 0.4rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.8rem;
        margin-right: 0.5rem;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .hero-meta i {
        color: var(--accent-color);
        margin-right: 8px;
    }

    /* --- Content Cards --- */
    .content-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: none;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .content-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    /* --- Voting System --- */
    .voting-wrapper {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .vote-btn-group {
        display: flex;
        gap: 1rem;
    }

    .vote-action-btn {
        position: relative;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.7rem;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .vote-action-btn:active {
        transform: scale(0.95);
    }

    /* Click Ripple Effect */
    .vote-action-btn::after {
        content: "";
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
        background-repeat: no-repeat;
        background-position: 50%;
        transform: scale(10, 10);
        opacity: 0;
        transition: transform .4s, opacity .8s;
    }

    .vote-action-btn:active::after {
        transform: scale(0, 0);
        opacity: .3;
        transition: 0s;
    }

    .vote-up.active-up {
        background-color: rgba(56, 161, 105, 0.1);
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .vote-down.active-down {
        background-color: rgba(229, 62, 62, 0.1);
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    .vote-up:hover { border-color: var(--success-color); color: var(--success-color); }
    .vote-down:hover { border-color: var(--danger-color); color: var(--danger-color); }

    /* --- Stat Pills --- */
    .stat-pill {
        background-color: #f7fafc;
        color: var(--secondary-color);
        padding: 0.6rem 1.2rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* --- Comments Section --- */
    .comment-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid #edf2f7;
        transition: background 0.3s;
    }
    
    .comment-item:hover {
        background-color: #fbfbfb;
        margin: 0 -1.5rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        border-radius: 8px;
    }

    .comment-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--secondary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    .comment-anonymous-avatar {
        background: var(--warning-color);
    }

    /* --- Sidebar --- */
    .sidebar-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        position: sticky;
        top: 20px;
    }

    .sidebar-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #a0aec0;
        font-weight: 700;
        margin-bottom: 1rem;
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 0.5rem;
    }

    .score-display {
        text-align: center;
        padding: 1rem 0;
    }

    .score-number {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--accent-color);
        line-height: 1;
        text-shadow: 2px 2px 0px rgba(0,0,0,0.1);
    }

    .score-label {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--secondary-color);
        letter-spacing: 1px;
    }

    /* --- Buttons --- */
    .btn-gold {
        background: var(--accent-color);
        color: white;
        font-weight: 700;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(214, 158, 46, 0.2);
    }

    .btn-gold:hover {
        background: #b8891d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(214, 158, 46, 0.3);
    }

    .btn-gold:active {
        animation: pulseGold 0.6s;
    }

    .btn-navy {
        background: var(--primary-color);
        color: white;
        font-weight: 700;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }

    .btn-navy:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
    }

    .document-link {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        text-decoration: none;
        color: var(--primary-color);
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .document-link:hover {
        border-color: var(--accent-color);
        background: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transform: translateX(5px);
    }

</style>

<div class="page-container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 anim-fade-up">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--secondary-color)">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ideas.index') }}" style="color: var(--secondary-color)">Ideas</a></li>
            <li class="breadcrumb-item active" style="color: var(--primary-color); font-weight: 700;">{{ Str::limit($idea->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            
            <!-- Hero Section -->
            <div class="idea-hero anim-fade-up">
                <div class="mb-3 d-flex flex-wrap gap-2">
                    @foreach($idea->categories as $category)
                        <span class="hero-category">{{ $category->name }}</span>
                    @endforeach
                    @if($idea->is_anonymous)
                        <span class="hero-category" style="background: rgba(221, 107, 32, 0.2); border-color: rgba(221, 107, 32, 0.3);">
                            <i class="fas fa-user-secret"></i> Anonymous
                        </span>
                    @endif
                </div>

                <h1 style="font-weight: 800; font-size: 2.2rem; line-height: 1.3;">{{ $idea->title }}</h1>

                <div class="hero-meta">
                    <span><i class="fas fa-building"></i> {{ $idea->department->name }}</span>
                    <span><i class="fas fa-user"></i> {{ $idea->author_name }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $idea->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Description Card -->
            <div class="content-card p-4 anim-fade-up delay-1">
                <div style="font-size: 1.1rem; line-height: 1.8; color: #4a5568;">
                    {!! nl2br(e($idea->description)) !!}
                </div>
                
                <!-- Documents -->
                @if($idea->documents->count() > 0)
                    <div class="mt-5 border-top pt-4">
                        <h5 style="color: var(--primary-color); font-weight: 700;" class="mb-3">
                            <i class="fas fa-paperclip" style="color: var(--accent-color)"></i> Attachments
                        </h5>
                        @foreach($idea->documents as $document)
                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="document-link">
                                <i class="fas {{ $document->icon_class }} fa-lg me-3" style="color: var(--info-color)"></i>
                                <div class="flex-grow-1">
                                    <div style="font-weight: 600;">{{ $document->original_name }}</div>
                                    <small class="text-muted">{{ $document->file_size_formatted }}</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Voting & Engagement -->
            <div class="voting-wrapper anim-fade-up delay-2">
                @auth
                    <div class="vote-btn-group">
                        <form method="POST" action="{{ route('ideas.vote', $idea) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="vote_type" value="up">
                            <button type="submit" class="vote-action-btn vote-up {{ $userVote && $userVote->vote_type === 'up' ? 'active-up' : '' }}">
                                <i class="fas fa-thumbs-up"></i>
                                <span>{{ $idea->thumbs_up_count }}</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ideas.vote', $idea) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="vote_type" value="down">
                            <button type="submit" class="vote-action-btn vote-down {{ $userVote && $userVote->vote_type === 'down' ? 'active-down' : '' }}">
                                <i class="fas fa-thumbs-down"></i>
                                <span>{{ $idea->thumbs_down_count }}</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-light border mb-0 py-2 px-3">
                        <i class="fas fa-lock me-2"></i> 
                        <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 700;">Login</a> to vote
                    </div>
                @endauth

                <div class="d-flex gap-2">
                    <span class="stat-pill"><i class="fas fa-eye" style="color: var(--info-color)"></i> {{ $idea->views_count }}</span>
                    <span class="stat-pill"><i class="fas fa-comment" style="color: var(--accent-color)"></i> {{ $idea->comments_count }}</span>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="content-card p-4 anim-fade-up delay-3">
                <h4 style="color: var(--primary-color); font-weight: 800; margin-bottom: 1.5rem;">
                    <i class="fas fa-comments me-2" style="color: var(--accent-color)"></i> Discussion
                </h4>

                <!-- Comment Form -->
                @auth
                    @if(auth()->user()->canComment())
                        <form method="POST" action="{{ route('comments.store', $idea) }}" class="mb-4 p-3 bg-light rounded">
                            @csrf
                            <textarea class="form-control border-0 bg-transparent" name="content" rows="3" required placeholder="Share your thoughts..."></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <label class="form-check-label small text-muted">
                                    <input type="checkbox" name="is_anonymous" value="1" class="form-check-input me-1">
                                    Post Anonymously
                                </label>
                                <button type="submit" class="btn btn-navy btn-sm">
                                    <i class="fas fa-paper-plane me-1"></i> Post
                                </button>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="text-center py-4 bg-light rounded mb-4">
                        <p class="mb-0 text-muted">Please <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 700;">login</a> to comment.</p>
                    </div>
                @endauth

                <div class="comments-list">
                    @forelse($comments as $comment)
                        <div class="comment-item anim-fade-up">
                            <div class="d-flex gap-3">
                                <div class="comment-avatar {{ $comment->is_anonymous ? 'comment-anonymous-avatar' : '' }}">
                                    @if($comment->is_anonymous)
                                        <i class="fas fa-user-secret"></i>
                                    @else
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0" style="font-weight: 700; color: var(--primary-color);">
                                            {{ $comment->is_anonymous ? 'Anonymous' : $comment->user->name }}
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.8rem;">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-secondary" style="line-height: 1.6;">{!! nl2br(e($comment->content)) !!}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comment-dots fa-3x mb-3" style="opacity: 0.2;"></i>
                            <h5>No comments yet</h5>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Popularity Score -->
            <div class="sidebar-card anim-slide-left delay-1" style="border-top: 4px solid var(--accent-color);">
                <div class="score-display">
                    <div class="score-number">{{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}</div>
                    <div class="score-label">Popularity Score</div>
                </div>
            </div>

            <!-- Author Info -->
            @if(!$idea->is_anonymous)
                <div class="sidebar-card anim-slide-left delay-2">
                    <div class="sidebar-title">Submitted By</div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="comment-avatar">{{ strtoupper(substr($idea->user->name, 0, 1)) }}</div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: var(--primary-color)">{{ $idea->user->name }}</h6>
                            <small class="text-muted">{{ $idea->department->name }}</small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="sidebar-card anim-slide-left delay-3">
                <div class="sidebar-title">Quick Actions</div>
                <div class="d-grid gap-2">
                    <a href="{{ route('ideas.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Ideas
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('admin.ideas.destroy', $idea) }}" onsubmit="return confirm('Delete this idea? This action hides it from the system.');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="reason" value="Removed by administrator">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash-alt me-2"></i> Delete Idea
                                </button>
                            </form>
                        @endif
                    @endauth
                    @auth
                        @if(auth()->user()->canSubmitIdea())
                            <a href="{{ route('ideas.create') }}" class="btn btn-gold">
                                <i class="fas fa-plus-circle me-2"></i> Submit New Idea
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="sidebar-card anim-slide-left delay-4" style="border-top: 4px solid var(--danger-color);">
                <div class="sidebar-title">Report This Idea</div>
                @auth
                    <form method="POST" action="{{ route('ideas.report', $idea) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small text-muted">Reason</label>
                            <select name="reason" class="form-select form-select-sm" required>
                                <option value="">Select a reason</option>
                                <option value="Swearing">Swearing</option>
                                <option value="Libel">Libel</option>
                                <option value="Harassment">Harassment</option>
                                <option value="Spam">Spam</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Details (optional)</label>
                            <textarea name="details" class="form-control form-control-sm" rows="3" placeholder="Add extra context if needed."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-flag me-1"></i> Report
                        </button>
                    </form>
                @else
                    <p class="text-muted mb-0">
                        Please <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 700;">login</a> to report.
                    </p>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Intersection Observer Script for Scroll Animations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Select all elements with animation classes
        const animatedElements = document.querySelectorAll('.anim-fade-up, .anim-slide-left');

        animatedElements.forEach(el => {
            el.style.animationPlayState = 'paused'; // Pause initially
            observer.observe(el);
        });
    });
</script>
@endsection
