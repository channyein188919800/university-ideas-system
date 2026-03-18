@extends('layouts.qa-manager')

@section('title', 'Hidden Content - University Ideas System')

@section('content')
<div class="qa-manager-layout">
    
    <main class="qa-main-content">
        <!-- Header Section -->
        <div class="qa-header-section mb-4">
            <h1 class="qa-header-title">
                <i class="bi bi-eye-slash me-2"></i>Hidden Content
            </h1>
            <p class="qa-header-subtitle">
                Manage hidden ideas and comments • 
                {{ $totalHiddenIdeas }} hidden ideas, {{ $totalHiddenComments }} hidden comments
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="qa-filter-bar mb-4">
            <form method="GET" action="{{ route('qa-manager.hidden.index') }}" class="row g-3">
                <div class="col-md-3">
                    <div class="qa-search-wrapper">
                        <i class="bi bi-search qa-search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="qa-search-input" 
                               placeholder="Search by title, content, or author..."
                               value="{{ $search }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <select name="department_id" class="qa-filter-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <input type="date" name="date_from" class="qa-filter-select" value="{{ $dateFrom }}" placeholder="From Date">
                </div>
                
                <div class="col-md-2">
                    <input type="date" name="date_to" class="qa-filter-select" value="{{ $dateTo }}" placeholder="To Date">
                </div>
                
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="qa-btn-filter flex-grow-1">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                    
                    @if($search || $departmentId || $dateFrom || $dateTo)
                        <a href="{{ route('qa-manager.hidden.index') }}" class="qa-btn-clear">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Hidden Ideas Table -->
        <div class="qa-table-container mb-4">
            <div class="qa-table-header">
                <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Hidden Ideas</h5>
                @if($hiddenIdeas->count() > 0)
                    <button class="qa-btn-unhide-all" onclick="unhideAllIdeas()">
                        <i class="bi bi-eye me-2"></i>Unhide All
                    </button>
                @endif
            </div>
            
            <table class="qa-ideas-table">
                <thead>
                    <tr>
                        <th width="5%">
                            <input type="checkbox" id="selectAllIdeas" onchange="toggleAllIdeas(this)">
                        </th>
                        <th width="25%">Idea Title</th>
                        <th width="15%">Author</th>
                        <th width="12%">Department</th>
                        <th width="12%">Status</th>
                        <th width="12%">Hidden Date</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hiddenIdeas as $idea)
                        <tr>
                            <td>
                                <input type="checkbox" name="idea_ids[]" value="{{ $idea->id }}" class="idea-checkbox">
                            </td>
                            <td class="qa-title-cell" data-label="Idea Title">
                                <strong>{{ $idea->title }}</strong>
                                <div class="qa-meta-info">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-eye"></i> {{ $idea->views_count }}
                                    </span>
                                    <span class="badge bg-light text-success">
                                        <i class="bi bi-hand-thumbs-up"></i> {{ $idea->thumbs_up_count }}
                                    </span>
                                    <span class="badge bg-light text-danger">
                                        <i class="bi bi-hand-thumbs-down"></i> {{ $idea->thumbs_down_count }}
                                    </span>
                                    <span class="badge bg-light text-muted">
                                        <i class="bi bi-calendar3"></i> {{ $idea->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </td>
                            <td data-label="Author">
                                @if($idea->is_anonymous)
                                    <span class="qa-anonymous-badge">Anonymous</span>
                                @else
                                    <div class="qa-user-info">
                                        <span>{{ $idea->user->name }}</span>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Department">{{ $idea->department->name ?? 'N/A' }}</td>
                            <td data-label="Status">
                                <span class="qa-status-badge status-{{ $idea->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $idea->status)) }}
                                </span>
                            </td>
                            <td data-label="Hidden Date">{{ $idea->updated_at->format('M d, Y') }}</td>
                            <td data-label="Actions" class="actions-cell">
                                <div class="action-dropdown">
                                    <button class="action-dropdown-toggle" onclick="toggleDropdown({{ $idea->id }})">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown-menu" id="dropdown-{{ $idea->id }}">
                                        <a class="action-dropdown-item" href="{{ route('ideas.show', $idea) }}">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                        <form method="POST" action="{{ route('qa-manager.hidden.unhide-idea', $idea) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-dropdown-item text-success" onclick="return confirm('Unhide this idea?')">
                                                <i class="bi bi-eye-slash me-2"></i>Unhide
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="qa-empty-state">
                                <div class="qa-empty-icon"><i class="bi bi-eye-slash"></i></div>
                                <h3>No hidden ideas</h3>
                                <p>There are no hidden ideas at the moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="qa-table-footer">
                <div class="bulk-actions" id="bulkActionsIdeas" style="display: none;">
                    <span class="selected-count" id="selectedIdeasCount">0</span> items selected
                    <button class="btn btn-sm btn-success" onclick="bulkUnhideIdeas()">
                        <i class="bi bi-eye me-1"></i>Unhide Selected
                    </button>
                </div>
                <div class="pagination-wrapper">
                    {{ $hiddenIdeas->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <!-- Hidden Comments Table -->
        <div class="qa-table-container">
            <div class="qa-table-header">
                <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Hidden Comments</h5>
                @if($hiddenComments->count() > 0)
                    <button class="qa-btn-unhide-all" onclick="unhideAllComments()">
                        <i class="bi bi-eye me-2"></i>Unhide All
                    </button>
                @endif
            </div>
            
            <table class="qa-ideas-table">
                <thead>
                    <tr>
                        <th width="5%">
                            <input type="checkbox" id="selectAllComments" onchange="toggleAllComments(this)">
                        </th>
                        <th width="25%">Comment</th>
                        <th width="20%">On Idea</th>
                        <th width="15%">Author</th>
                        <th width="12%">Department</th>
                        <th width="12%">Hidden Date</th>
                        <th width="11%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hiddenComments as $comment)
                        <tr>
                            <td>
                                <input type="checkbox" name="comment_ids[]" value="{{ $comment->id }}" class="comment-checkbox">
                            </td>
                            <td class="qa-description-cell" data-label="Comment">
                                <div class="qa-description-wrapper">
                                    {{ Str::limit($comment->content, 80) }}
                                </div>
                            </td>
                            <td data-label="On Idea">
                                <a href="{{ route('ideas.show', $comment->idea) }}" class="text-decoration-none">
                                    {{ Str::limit($comment->idea->title, 40) }}
                                </a>
                                <div class="qa-meta-info">
                                    <span class="badge bg-light text-muted">
                                        <i class="bi bi-calendar3"></i> {{ $comment->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </td>
                            <td data-label="Author">
                                @if($comment->is_anonymous)
                                    <span class="qa-anonymous-badge">Anonymous</span>
                                @else
                                    <div class="qa-user-info">
                                        <span>{{ $comment->user->name }}</span>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Department">{{ $comment->idea->department->name ?? 'N/A' }}</td>
                            <td data-label="Hidden Date">{{ $comment->updated_at->format('M d, Y') }}</td>
                            <td data-label="Actions" class="actions-cell">
                                <div class="action-dropdown">
                                    <button class="action-dropdown-toggle" onclick="toggleDropdown('comment-{{ $comment->id }}')">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown-menu" id="dropdown-comment-{{ $comment->id }}">
                                        <a class="action-dropdown-item" href="{{ route('ideas.show', $comment->idea) }}#comment-{{ $comment->id }}">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                        <form method="POST" action="{{ route('qa-manager.hidden.unhide-comment', $comment) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-dropdown-item text-success" onclick="return confirm('Unhide this comment?')">
                                                <i class="bi bi-eye-slash me-2"></i>Unhide
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="qa-empty-state">
                                <div class="qa-empty-icon"><i class="bi bi-chat-dots"></i></div>
                                <h3>No hidden comments</h3>
                                <p>There are no hidden comments at the moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="qa-table-footer">
                <div class="bulk-actions" id="bulkActionsComments" style="display: none;">
                    <span class="selected-count" id="selectedCommentsCount">0</span> items selected
                    <button class="btn btn-sm btn-success" onclick="bulkUnhideComments()">
                        <i class="bi bi-eye me-1"></i>Unhide Selected
                    </button>
                </div>
                <div class="pagination-wrapper">
                    {{ $hiddenComments->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Bulk Unhide Forms -->
<form id="bulkUnhideIdeasForm" method="POST" action="{{ route('qa-manager.hidden.bulk-unhide-ideas') }}" style="display: none;">
    @csrf
    <input type="hidden" name="idea_ids" id="bulkIdeaIds">
</form>

<form id="bulkUnhideCommentsForm" method="POST" action="{{ route('qa-manager.hidden.bulk-unhide-comments') }}" style="display: none;">
    @csrf
    <input type="hidden" name="comment_ids" id="bulkCommentIds">
</form>
@endsection

@push('styles')
<style>
:root {
    --primary-color: #1e3a5f;
    --secondary-color: #2c5282;
    --accent-color: #d69e2e;
    --success-color: #38a169;
    --danger-color: #e53e3e;
    --warning-color: #dd6b20;
    --info-color: #3182ce;
    --light-bg: #f7fafc;
    --border-color: #e2e8f0;
    --text-primary: #1a202c;
    --text-secondary: #4a5568;
    --text-muted: #718096;
}

/* Main Layout */
.qa-manager-layout {
    display: flex;
    min-height: 100vh;
    background: var(--light-bg);
}

.qa-main-content {
    flex: 1;
    transition: margin-left 0.3s ease;
}

/* Header Section */
.qa-header-section {
    background: white;
    border-radius: 20px;
    padding: 1.5rem 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    border: 1px solid var(--border-color);
}

.qa-header-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
}

.qa-header-title i {
    color: var(--accent-color);
    font-size: 2rem;
}

.qa-header-subtitle {
    color: var(--text-secondary);
    font-size: 1rem;
    margin: 0;
    display: flex;
    align-items: center;
}

.qa-header-subtitle:before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 4px;
    background: var(--accent-color);
    border-radius: 50%;
    margin-right: 0.75rem;
}

/* Filter Bar */
.qa-filter-bar {
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color);
}

.qa-search-wrapper {
    position: relative;
    width: 100%;
}

.qa-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 1rem;
}

.qa-search-input {
    width: 100%;
    padding: 0.7rem 1rem 0.7rem 2.8rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 0.95rem;
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.qa-search-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1);
}

.qa-filter-select {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 0.95rem;
    color: var(--text-primary);
    background: white;
    cursor: pointer;
}

.qa-filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.qa-btn-filter {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
}

.qa-btn-filter:hover {
    background: var(--secondary-color);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
}

.qa-btn-clear {
    background: #f8f9fa;
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
    padding: 0.7rem 1.2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.2s ease;
}

.qa-btn-clear:hover {
    background: var(--border-color);
    color: var(--primary-color);
}

/* Table Styles */
.qa-table-container {
    background: white;
    border-radius: 20px;
    overflow: visible;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    position: relative;
    z-index: 1;
}

.qa-table-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafd;
}

.qa-table-header h5 {
    color: var(--primary-color);
    font-weight: 600;
    margin: 0;
}

.qa-btn-unhide-all {
    background: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.qa-btn-unhide-all:hover {
    background: #2f8a52;
    transform: translateY(-1px);
}

.qa-ideas-table {
    width: 100%;
    border-collapse: collapse;
}

.qa-ideas-table th {
    background: #f8fafd;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.9rem;
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 2px solid var(--border-color);
}

.qa-ideas-table td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
    color: var(--text-primary);
}

.qa-ideas-table tr:last-child td {
    border-bottom: none;
}

.qa-ideas-table tr:hover td {
    background: #fafcff;
}

/* Title Cell */
.qa-title-cell {
    font-weight: 500;
}

.qa-meta-info {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
    flex-wrap: wrap;
}

.qa-meta-info .badge {
    padding: 0.3rem 0.6rem;
    font-weight: 500;
    font-size: 0.7rem;
    border-radius: 20px;
}

/* Description Cell */
.qa-description-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    line-height: 1.5;
    color: var(--text-secondary);
}

/* User Info */
.qa-anonymous-badge {
    background: #f0f4fa;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    font-weight: 500;
}

.qa-user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Status Badges */
.qa-status-badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-pending {
    background: rgba(221, 107, 32, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(221, 107, 32, 0.2);
}

.status-approved {
    background: rgba(56, 161, 105, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(56, 161, 105, 0.2);
}

.status-rejected {
    background: rgba(229, 62, 62, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(229, 62, 62, 0.2);
}

.status-review {
    background: rgba(49, 130, 206, 0.1);
    color: var(--info-color);
    border: 1px solid rgba(49, 130, 206, 0.2);
}

/* Action Dropdown - Fixed */
.actions-cell {
    position: relative;
}

.action-dropdown {
    position: relative;
    display: inline-block;
}

.action-dropdown-toggle {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-size: 1.2rem;
    padding: 0.3rem 0.5rem;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.action-dropdown-toggle:hover {
    background: #f0f4fa;
    color: var(--primary-color);
}

.action-dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 9999;
    display: none;
    min-width: 160px;
    padding: 0.5rem 0;
    margin: 0.25rem 0 0;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.action-dropdown-menu.show {
    display: block;
}

.action-dropdown-item {
    display: block;
    width: 100%;
    padding: 0.6rem 1.2rem;
    clear: both;
    font-weight: 400;
    color: var(--text-primary);
    text-align: left;
    text-decoration: none;
    white-space: nowrap;
    background: transparent;
    border: 0;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.85rem;
}

.action-dropdown-item:hover {
    background: #f8fafd;
    color: var(--primary-color);
}

.action-dropdown-item.text-success {
    color: var(--success-color) !important;
}

.action-dropdown-item.text-success:hover {
    background: rgba(56, 161, 105, 0.1);
}

/* Table Footer */
.qa-table-footer {
    padding: 1rem 1.5rem;
    border-top: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafd;
}

.bulk-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.selected-count {
    font-weight: 600;
    color: var(--primary-color);
}

/* Empty State */
.qa-empty-state {
    text-align: center;
    padding: 3rem 2rem !important;
    color: var(--text-secondary);
}

.qa-empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--border-color);
}

.qa-empty-state h3 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.qa-empty-state p {
    color: var(--text-secondary);
    margin-bottom: 0;
    font-size: 0.9rem;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
}

.pagination-wrapper .pagination {
    gap: 0.25rem;
    margin: 0;
}

.pagination-wrapper .page-link {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 0.4rem 0.8rem;
    color: var(--primary-color);
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination-wrapper .page-link:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination-wrapper .active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

/* Responsive */
@media (max-width: 1200px) {
    .qa-main-content {
        margin-left: 0;
    }
}

@media (max-width: 992px) {
    .qa-main-content {
        padding: 1.5rem;
    }
    
    .qa-ideas-table {
        display: block;
        overflow-x: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Dropdown functionality
function toggleDropdown(id) {
    // Close all other dropdowns
    document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
        if (menu.id !== 'dropdown-' + id && menu.id !== 'dropdown-comment-' + id) {
            menu.classList.remove('show');
        }
    });
    
    // Toggle current dropdown
    const dropdown = document.getElementById('dropdown-' + id);
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.action-dropdown')) {
        document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

// Bulk selection for ideas
function toggleAllIdeas(checkbox) {
    const checkboxes = document.querySelectorAll('.idea-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActionsIdeas();
}

document.querySelectorAll('.idea-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActionsIdeas);
});

function updateBulkActionsIdeas() {
    const checkboxes = document.querySelectorAll('.idea-checkbox:checked');
    const bulkActions = document.getElementById('bulkActionsIdeas');
    const selectedCount = document.getElementById('selectedIdeasCount');
    
    if (checkboxes.length > 0) {
        selectedCount.textContent = checkboxes.length;
        bulkActions.style.display = 'flex';
    } else {
        bulkActions.style.display = 'none';
        document.getElementById('selectAllIdeas').checked = false;
    }
}

// Bulk selection for comments
function toggleAllComments(checkbox) {
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActionsComments();
}

document.querySelectorAll('.comment-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActionsComments);
});

function updateBulkActionsComments() {
    const checkboxes = document.querySelectorAll('.comment-checkbox:checked');
    const bulkActions = document.getElementById('bulkActionsComments');
    const selectedCount = document.getElementById('selectedCommentsCount');
    
    if (checkboxes.length > 0) {
        selectedCount.textContent = checkboxes.length;
        bulkActions.style.display = 'flex';
    } else {
        bulkActions.style.display = 'none';
        document.getElementById('selectAllComments').checked = false;
    }
}

// Bulk unhide ideas
function bulkUnhideIdeas() {
    const checkboxes = document.querySelectorAll('.idea-checkbox:checked');
    if (checkboxes.length === 0) return;
    
    if (!confirm(`Unhide ${checkboxes.length} selected ideas?`)) return;
    
    const ideaIds = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('bulkIdeaIds').value = JSON.stringify(ideaIds);
    document.getElementById('bulkUnhideIdeasForm').submit();
}

// Bulk unhide comments
function bulkUnhideComments() {
    const checkboxes = document.querySelectorAll('.comment-checkbox:checked');
    if (checkboxes.length === 0) return;
    
    if (!confirm(`Unhide ${checkboxes.length} selected comments?`)) return;
    
    const commentIds = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('bulkCommentIds').value = JSON.stringify(commentIds);
    document.getElementById('bulkUnhideCommentsForm').submit();
}

// Unhide all ideas
function unhideAllIdeas() {
    const totalIdeas = {{ $hiddenIdeas->total() }};
    if (totalIdeas === 0) return;
    
    if (!confirm(`Unhide all ${totalIdeas} hidden ideas?`)) return;
    
    const ideaIds = @json($hiddenIdeas->pluck('id'));
    document.getElementById('bulkIdeaIds').value = JSON.stringify(ideaIds);
    document.getElementById('bulkUnhideIdeasForm').submit();
}

// Unhide all comments
function unhideAllComments() {
    const totalComments = {{ $hiddenComments->total() }};
    if (totalComments === 0) return;
    
    if (!confirm(`Unhide all ${totalComments} hidden comments?`)) return;
    
    const commentIds = @json($hiddenComments->pluck('id'));
    document.getElementById('bulkCommentIds').value = JSON.stringify(commentIds);
    document.getElementById('bulkUnhideCommentsForm').submit();
}
</script>
@endpush