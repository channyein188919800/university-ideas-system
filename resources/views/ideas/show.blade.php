@extends('layouts.app')

@section('title', $idea->title . ' - University Ideas System')

@section('content')
<style>
    :root {
        --primary-navy: #1e3a5f;
        --secondary-navy: #2c5282;
        --accent-gold: #d69e2e;
        --body-bg: #f0f4f8;
        --white: #ffffff;
        --text-main: #2d3748;
        --text-muted: #718096;
        --danger-red: #e53e3e;
        --success-green: #38a169;
        --warning-orange: #dd6b20;
    }

    body {
        background-color: var(--body-bg);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: "Merriweather", serif;
        color: var(--primary-navy);
    }

    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 1rem;
    }

    /* --- UNIFIED IDEA BLOCK (Realistic & Consistent) --- */
    .idea-master-card {
        background: var(--white);
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.02);
        position: relative;
    }

    /* Hidden Idea Overlay */
    .hidden-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.8);
        color: white;
        text-align: center;
        padding: 0.75rem;
        z-index: 10;
        font-weight: 700;
        backdrop-filter: blur(4px);
        border-radius: 1.5rem 1.5rem 0 0;
    }

    .hidden-overlay i {
        color: var(--accent-gold);
        margin-right: 0.5rem;
    }

    /* Top Zone: Header */
    .idea-header-zone {
        background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
        padding: 3rem;
        color: white;
        position: relative;
    }

    .idea-header-zone.hidden-idea {
        opacity: 0.7;
        filter: grayscale(0.5);
    }

    .idea-header-zone::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        top: -40px;
        right: -40px;
    }

    .category-pill {
        background: rgba(255, 255, 255, 0.15);
        color: var(--accent-gold);
        padding: 0.4rem 1.2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: inline-block;
        margin-bottom: 1.25rem;
    }

    .idea-display-title {
        font-size: 2.75rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: white;
        line-height: 1.2;
    }

    .header-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        font-size: 0.95rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 1.5rem;
    }

    .header-meta-row i { color: var(--accent-gold); }

    /* Middle Zone: Description */
    .idea-body-zone {
        padding: 3rem;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4a5568;
    }

    .idea-body-zone.hidden-idea {
        opacity: 0.7;
        filter: grayscale(0.5);
    }

    /* Bottom Zone: Interaction Bar */
    .idea-interaction-zone {
        padding: 1.5rem 3rem;
        background: #fcfdfe;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f0f4f8;
    }

    .idea-interaction-zone.hidden-idea {
        opacity: 0.7;
        filter: grayscale(0.5);
    }

    .vote-group { display: flex; gap: 1rem; }

    .btn-vote-pill {
        border: 2px solid #e2e8f0;
        background: white;
        padding: 0.6rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.2s ease;
    }

    .btn-vote-pill.active-up { background: #f0fff4; border-color: #38a169; color: #38a169; }
    .btn-vote-pill.active-down { background: #fff5f5; border-color: var(--danger-red); color: var(--danger-red); }

    .view-count-badge {
        background: #f1f5f9;
        color: var(--secondary-navy);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* --- SIDEBAR STYLE --- */
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

    .score-display { text-align: center; padding: 1rem 0; }
    .score-number { font-size: 3.5rem; font-weight: 800; color: var(--accent-gold); line-height: 1; text-shadow: 2px 2px 0px rgba(0,0,0,0.1); }
    .score-label { text-transform: uppercase; font-size: 0.75rem; font-weight: 700; color: var(--secondary-navy); letter-spacing: 1px; }

    /* QA Manager Controls */
    .qa-controls-card {
        border-left: 4px solid var(--accent-gold);
        background: linear-gradient(135deg, #fff9e6, #ffffff);
    }

    .visibility-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
    }

    .visibility-toggle .status-badge {
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .status-badge.visible {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-badge.hidden {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-qa {
        padding: 0.6rem 1.2rem;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-qa-hide {
        background: var(--danger-red);
        color: white;
    }

    .btn-qa-hide:hover {
        background: #c53030;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
    }

    .btn-qa-unhide {
        background: var(--success-green);
        color: white;
    }

    .btn-qa-unhide:hover {
        background: #2f855a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 161, 105, 0.3);
    }

    .hidden-warning {
        background: #fff5f5;
        border: 1px solid #feb2b2;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        color: #c53030;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .hidden-warning i {
        font-size: 1.25rem;
    }

    /* --- COMMENTS HIDE/SHOW LOGIC --- */
    .hidden-comments { display: none; }
    
    .comment-item { 
        padding: 1.5rem 0; 
        border-bottom: 1px solid #edf2f7; 
        transition: background 0.3s; 
    }
    
    .comment-item.hidden-comment {
        opacity: 0.6;
        background: #fef5f5;
    }

    .comment-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        background: var(--secondary-navy); color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; border: 2px solid var(--accent-gold);
    }

    .btn-gold { 
        background: var(--accent-gold); 
        color: white; 
        font-weight: 700; 
        border: none; 
        padding: 0.6rem 1.5rem; 
        border-radius: 0.5rem; 
        transition: 0.3s; 
        box-shadow: 0 4px 6px rgba(214, 158, 46, 0.2); 
    }
    
    .btn-gold:hover { 
        background: #b8891d; 
        transform: translateY(-2px); 
    }

    /* Comment moderation badge */
    .moderation-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 50px;
        background: #fed7d7;
        color: #742a2a;
        margin-left: 0.5rem;
    }
</style>

<div class="page-container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--secondary-navy)">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ideas.index') }}" class="text-decoration-none" style="color: var(--secondary-navy)">Ideas</a></li>
            <li class="breadcrumb-item active fw-bold" style="color: var(--primary-navy)">Detailed Idea</li>
        </ol>
    </nav>

    @if($idea->hidden)
        <div class="alert alert-warning mb-4 fade-in-up" style="background: #fff3cd; border: 1px solid #ffeeba; border-radius: 1rem;">
            <i class="fas fa-eye-slash me-2"></i>
            <strong>This idea is currently hidden</strong> - It is only visible to administrators and QA managers.
            @auth
                @if(auth()->user()->isQaManager())
                    <span class="ms-2">(You can manage visibility in the QA Controls panel)</span>
                @endif
            @endauth
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="idea-master-card anim-fade-up">
                @if($idea->hidden)
                    <div class="hidden-overlay">
                        <i class="fas fa-eye-slash"></i> HIDDEN IDEA - Only visible to staff
                    </div>
                @endif
                
                <div class="idea-header-zone {{ $idea->hidden ? 'hidden-idea' : '' }}">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($idea->categories as $category)
                            <span class="category-pill">{{ $category->name }}</span>
                        @endforeach
                        @if($idea->is_anonymous)
                            <span class="category-pill" style="background: rgba(214, 158, 46, 0.3);"><i class="fas fa-mask me-1"></i> Anonymous</span>
                        @endif
                    </div>
                    <h1 class="idea-display-title">{{ $idea->title }}</h1>
                    <div class="header-meta-row">
                        <span><i class="fas fa-university"></i> {{ $idea->department->name }}</span>
                        <span><i class="fas fa-user"></i> {{ $idea->author_name }}</span>
                        <span><i class="fas fa-calendar-alt"></i> {{ $idea->created_at->format('M d, Y') }}</span>
                        @if($idea->hidden)
                            <span class="text-warning"><i class="fas fa-eye-slash"></i> Hidden</span>
                        @endif
                    </div>
                </div>

                <div class="idea-body-zone {{ $idea->hidden ? 'hidden-idea' : '' }}">
                    {!! nl2br(e($idea->description)) !!}
                </div>

                <div class="idea-interaction-zone {{ $idea->hidden ? 'hidden-idea' : '' }}">
                    <div class="vote-group">
                        @auth
                            @if(!$idea->hidden || auth()->user()->isQaManager())
                                <form method="POST" action="{{ route('ideas.vote', $idea) }}" class="d-inline">
                                    @csrf <input type="hidden" name="vote_type" value="up">
                                    <button type="submit" class="btn-vote-pill {{ $userVote && $userVote->vote_type === 'up' ? 'active-up' : '' }}" {{ $idea->hidden && !auth()->user()->isQaManager() ? 'disabled' : '' }}>
                                        <i class="fas fa-thumbs-up"></i> {{ $idea->thumbs_up_count }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('ideas.vote', $idea) }}" class="d-inline">
                                    @csrf <input type="hidden" name="vote_type" value="down">
                                    <button type="submit" class="btn-vote-pill {{ $userVote && $userVote->vote_type === 'down' ? 'active-down' : '' }}" {{ $idea->hidden && !auth()->user()->isQaManager() ? 'disabled' : '' }}>
                                        <i class="fas fa-thumbs-down"></i> {{ $idea->thumbs_down_count }}
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <div class="d-flex gap-2">
                        <span class="view-count-badge"><i class="fas fa-eye text-info"></i> {{ $idea->views_count }}</span>
                        <span class="view-count-badge"><i class="fas fa-comment text-warning"></i> {{ $idea->comments_count }}</span>
                    </div>
                </div>
            </div>

            <div class="content-card p-4 anim-fade-up delay-3">
                <h4 class="fw-bold mb-4"><i class="fas fa-comments text-warning me-2"></i>Discussion</h4>
                
                @auth
                    @if(auth()->user()->canComment() && (!$idea->hidden || auth()->user()->isQaManager()))
                        <form method="POST" action="{{ route('comments.store', $idea) }}" class="mb-4 p-3 bg-light rounded">
                            @csrf
                            <textarea class="form-control border-0 bg-white shadow-sm mb-3" name="content" rows="3" required placeholder="Share your thoughts..."></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="small text-muted fw-bold"><input type="checkbox" name="is_anonymous" value="1" class="me-1"> Post Anonymously</label>
                                <button type="submit" class="btn btn-gold px-4">Post</button>
                            </div>
                        </form>
                    @endif
                @endauth

                <div class="comment-list" id="commentList">
                    @forelse($comments as $index => $comment)
                        <div class="comment-item {{ $index > 0 ? 'hidden-comments' : '' }} {{ $comment->hidden ? 'hidden-comment' : '' }}" id="comment-{{ $comment->id }}">
                            <div class="d-flex gap-3">
                                <div class="comment-avatar">
                                    @if($comment->is_anonymous) 
                                        <i class="fas fa-user-secret"></i> 
                                    @else 
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }} 
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <h6 class="fw-bold m-0 text-primary">
                                                {{ $comment->is_anonymous ? 'Anonymous' : $comment->user->name }}
                                                @if($comment->hidden)
                                                    <span class="moderation-badge"><i class="fas fa-eye-slash me-1"></i>Hidden</span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            @auth
                                                @if(auth()->user()->isQaManager())
                                                    <form method="POST" action="{{ route('qa-manager.comments.toggle-hidden', $comment) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm {{ $comment->hidden ? 'btn-success' : 'btn-outline-danger' }}" title="{{ $comment->hidden ? 'Unhide comment' : 'Hide comment' }}">
                                                            <i class="fas {{ $comment->hidden ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                    <p class="mb-0 text-secondary small">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No comments yet.</p>
                    @endforelse
                </div>

                @if($comments->count() > 1)
                    <div class="text-center mt-3">
                        <button id="toggleCommentsBtn" class="btn btn-sm btn-outline-secondary rounded-pill px-4 fw-bold">
                            View All Comments ({{ $comments->count() }})
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-card anim-slide-left delay-1" style="border-top: 4px solid var(--accent-gold);">
                <div class="score-display">
                    <div class="score-number">{{ $idea->popularity_score > 0 ? '+' : '' }}{{ $idea->popularity_score }}</div>
                    <div class="score-label">Popularity Score</div>
                </div>
            </div>

            @auth
                @if(auth()->user()->isQaManager())
                    <!-- QA Manager Controls -->
                    <div class="sidebar-card qa-controls-card anim-slide-left delay-2">
                        <div class="sidebar-title">
                            <i class="fas fa-shield-alt me-2" style="color: var(--accent-gold);"></i>
                            QA Manager Controls
                        </div>
                        
                        <div class="visibility-toggle">
                            <span class="fw-bold">Current Status:</span>
                            <span class="status-badge {{ $idea->hidden ? 'hidden' : 'visible' }}">
                                <i class="fas {{ $idea->hidden ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                {{ $idea->hidden ? 'Hidden' : 'Visible' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('qa-manager.ideas.toggle-hidden', $idea) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-qa {{ $idea->hidden ? 'btn-qa-unhide' : 'btn-qa-hide' }}">
                                <i class="fas {{ $idea->hidden ? 'fa-eye' : 'fa-eye-slash' }} me-2"></i>
                                {{ $idea->hidden ? 'Unhide this Idea' : 'Hide this Idea' }}
                            </button>
                        </form>

                        <div class="small text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Hidden ideas are only visible to QA managers and administrators.
                        </div>

                        @if($idea->hidden)
                            <div class="hidden-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>This idea is currently hidden from regular users.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Moderation Stats -->
                    <div class="sidebar-card anim-slide-left delay-2">
                        <div class="sidebar-title">
                            <i class="fas fa-chart-bar me-2" style="color: var(--accent-gold);"></i>
                            Moderation Stats
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Reports:</span>
                            <span class="fw-bold">{{ $idea->reports_count ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Comments Hidden:</span>
                            <span class="fw-bold">{{ $idea->comments()->where('hidden', true)->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Last Reported:</span>
                            <span class="fw-bold">{{ $idea->last_reported_at ? $idea->last_reported_at->diffForHumans() : 'Never' }}</span>
                        </div>
                    </div>
                @endif
            @endauth

            <div class="sidebar-card anim-slide-left delay-3">
                <div class="sidebar-title">Quick Actions</div>
                <div class="d-grid gap-2">
                    <a href="{{ route('ideas.index') }}" class="btn btn-outline-secondary fw-bold">
                        <i class="fas fa-arrow-left me-2"></i> All Ideas
                    </a>
                    @auth
                        @if(auth()->user()->canSubmitIdea())
                            <a href="{{ route('ideas.create') }}" class="btn btn-gold">
                                <i class="fas fa-plus-circle me-2"></i> Submit New Idea
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="sidebar-card anim-slide-left delay-4" style="border-top: 4px solid var(--danger-red);">
                <div class="sidebar-title">Report This Idea</div>
                @auth
                    @if(!$idea->hidden || auth()->user()->isQaManager())
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
                                    <option value="Inappropriate">Inappropriate Content</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Details (optional)</label>
                                <textarea name="details" class="form-control form-control-sm" rows="3" placeholder="Provide additional context..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold">
                                <i class="fas fa-flag me-2"></i>Report Idea
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Comments Logic
        const toggleBtn = document.getElementById('toggleCommentsBtn');
        const hiddenComments = document.querySelectorAll('.hidden-comments');
        let isExpanded = false;

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                isExpanded = !isExpanded;
                hiddenComments.forEach(el => el.style.display = isExpanded ? 'block' : 'none');
                toggleBtn.textContent = isExpanded ? 'Hide Comments' : 'View All Comments ({{ $comments->count() }})';
            });
        }

        // QA Manager Comment Toggle - AJAX for better UX
        document.querySelectorAll('[data-toggle-comment]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const commentId = form.action.split('/').pop();
                const commentDiv = document.getElementById(`comment-${commentId}`);
                
                fetch(form.action, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button appearance
                        const btn = form.querySelector('button');
                        if (data.hidden) {
                            btn.classList.remove('btn-outline-danger');
                            btn.classList.add('btn-success');
                            btn.innerHTML = '<i class="fas fa-eye"></i>';
                            btn.title = 'Unhide comment';
                            commentDiv.classList.add('hidden-comment');
                        } else {
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-danger');
                            btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                            btn.title = 'Hide comment';
                            commentDiv.classList.remove('hidden-comment');
                        }
                        
                        // Update hidden badge
                        const nameDiv = commentDiv.querySelector('.text-primary');
                        const existingBadge = nameDiv.querySelector('.moderation-badge');
                        if (data.hidden && !existingBadge) {
                            nameDiv.innerHTML += '<span class="moderation-badge"><i class="fas fa-eye-slash me-1"></i>Hidden</span>';
                        } else if (!data.hidden && existingBadge) {
                            existingBadge.remove();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.anim-fade-up, .anim-slide-left').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        // Real-time visibility feedback
        const visibilityForm = document.querySelector('form[action*="toggle-hidden"]');
        if (visibilityForm) {
            visibilityForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch(this.action, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to reflect visibility changes
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
@endsection