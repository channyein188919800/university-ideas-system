@extends('layouts.app')

@section('title', 'Idea Approval - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="qa-manager-layout">
            <main class="qa-main-content">
                <!-- Header Section -->
                <div class="qa-header-section mb-4">
                    <h1 class="qa-header-title">
                        <i class="bi bi-check2-square"></i>Idea Approval
                    </h1>
                    <p class="qa-header-subtitle">
                        Review pending staff ideas and approve or reject them
                    </p>
                </div>

                <!-- Filter Bar -->
                <div class="qa-filter-bar mb-4">
                    <form method="GET" action="{{ route('admin.idea-approvals.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <div class="qa-search-wrapper">
                                <i class="bi bi-search qa-search-icon"></i>
                                <input type="text"
                                       name="search"
                                       class="qa-search-input"
                                       placeholder="Search ideas by title..."
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
                            <select name="category_id" class="qa-filter-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="qa-filter-select">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="under_review" {{ $status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="qa-btn-filter flex-grow-1">
                                <i class="bi bi-funnel me-2"></i>Apply Filters
                            </button>

                            @if($search || $departmentId || $categoryId)
                                <a href="{{ route('admin.idea-approvals.index') }}" class="qa-btn-clear">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Ideas Table -->
                <div class="qa-table-container">
                    <table class="qa-ideas-table">
                        <thead>
                                <th class="title-col">Idea Title</th>
                                <th class="author-col">Author</th>
                                <th class="dept-col">Department</th>
                                <th class="cat-col">Categories</th>
                                <th class="status-col">Status</th>
                                <th class="action-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ideas as $idea)
                                <tr>
                                    <td class="qa-title-cell" data-label="Idea Title">
                                        <strong>{{ $idea->title }}</strong>
                                        <div class="qa-meta-info desktop-only">
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
                                    <td class="author-cell" data-label="Author">
                                        @if($idea->is_anonymous)
                                            <span class="qa-anonymous-badge">Anonymous</span>
                                        @else
                                            <div class="qa-user-info">
                                                <span>{{ $idea->user?->name ?? 'Unknown' }}</span>
                                            </div>
                                            <div class="qa-user-role">
                                                {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $idea->user?->role ?? 'staff')) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="dept-cell" data-label="Department">{{ $idea->department?->name ?? 'N/A' }}</td>
                                    <td class="cat-cell" data-label="Categories">
                                        @foreach($idea->categories->take(2) as $category)
                                            <span class="badge bg-light text-dark border">{{ $category->name }}</span>
                                        @endforeach
                                        @if($idea->categories->count() > 2)
                                            <span class="badge bg-light text-dark">+{{ $idea->categories->count() - 2 }}</span>
                                        @endif
                                    </td>
                                    <td class="status-cell" data-label="Status">
                                        <span class="qa-status-badge status-{{ $idea->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $idea->status)) }}
                                        </span>
                                    </td>
                                    <td class="action-cell" data-label="Actions">
                                        <div class="action-dropdown desktop-only">
                                            <button class="action-dropdown-toggle" onclick="toggleDropdown({{ $idea->id }})">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <div class="action-dropdown-menu" id="dropdown-{{ $idea->id }}">
                                                @php
                                                    $adminDetailUrl = ($idea->status === 'approved')
                                                        ? route('ideas.show', $idea)
                                                        : route('admin.idea-approvals.show', $idea);
                                                @endphp
                                                <a class="action-dropdown-item" href="{{ $adminDetailUrl }}">
                                                    <i class="bi bi-eye me-2"></i>View Details
                                                </a>
                                                
                                                {{-- Approve action for pending or rejected ideas --}}
                                                @if($idea->status === 'pending' || $idea->status === 'rejected')
                                                    <form method="POST" action="{{ route('admin.idea-approvals.approve', $idea) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($idea->status === 'rejected')
                                                            <button type="submit" class="action-dropdown-item text-success" onclick="return confirm('Are you sure you want to re-approve this rejected idea?')">
                                                                <i class="bi bi-arrow-repeat me-2"></i>Re-approve
                                                            </button>
                                                        @else
                                                            <button type="submit" class="action-dropdown-item text-success">
                                                                <i class="bi bi-check-lg me-2"></i>Approve
                                                            </button>
                                                        @endif
                                                    </form>
                                                @endif
                                                
                                                {{-- Reject action only for pending ideas --}}
                                                @if($idea->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.idea-approvals.reject', $idea) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-dropdown-item text-danger" onclick="return confirm('Are you sure you want to reject this idea?')">
                                                            <i class="bi bi-x-lg me-2"></i>Reject
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mobile-actions">
                                            <a href="{{ $adminDetailUrl }}" class="mobile-action-btn view-btn" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            {{-- Approve action for pending or rejected ideas --}}
                                            @if($idea->status === 'pending' || $idea->status === 'rejected')
                                                <form method="POST" action="{{ route('admin.idea-approvals.approve', $idea) }}" class="d-inline mobile-action-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($idea->status === 'rejected')
                                                        <button type="submit" class="mobile-action-btn reapprove-btn" title="Re-approve Idea" onclick="return confirm('Re-approve this rejected idea?')">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </button>
                                                    @else
                                                        <button type="submit" class="mobile-action-btn approve-btn" title="Approve Idea">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            @endif
                                            
                                            {{-- Reject action only for pending ideas --}}
                                            @if($idea->status === 'pending')
                                                <form method="POST" action="{{ route('admin.idea-approvals.reject', $idea) }}" class="d-inline mobile-action-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="mobile-action-btn reject-btn" title="Reject Idea" onclick="return confirm('Reject this idea?')">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="qa-empty-state">
                                        <div class="qa-empty-icon"><i class="bi bi-lightbulb"></i></div>
                                        <h3>No ideas found</h3>
                                        <p>No ideas found for the selected filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="qa-table-footer">
                        <div class="showing-info">
                            Showing {{ $ideas->firstItem() }}-{{ $ideas->lastItem() }} of {{ $ideas->total() }} ideas
                        </div>
                        <div class="pagination-wrapper">
                            {{ $ideas->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </section>
</div>
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

.qa-manager-layout {
    display: flex;
    min-height: 100vh;
    /* background: var(--light-bg); */
}

.qa-main-content {
    flex: 1;
    transition: margin-left 0.3s ease;
}

.qa-header-section {
    background: white;
    border-radius: 20px;
    padding: 1rem 1.2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    border: 1px solid var(--border-color);
    /* margin: 2rem 2rem 1.5rem 2rem; */
}

.qa-header-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
}

.qa-header-title i {
    color: var(--accent-color);
    font-size: 1.5rem;
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

.qa-filter-bar {
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color);
    /* margin: 0 2rem 1.5rem 2rem; */
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

.qa-table-container {
    background: white;
    border-radius: 20px;
    overflow: visible;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    /* margin: 0 2rem 2rem 2rem; */
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
.qa-user-role {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 0.2rem;
}

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
    z-index: 1000;
    display: none;
    min-width: 160px;
    padding: 0.5rem 0;
    margin: 0.25rem 0 0;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.action-dropdown-menu.show {
    display: block;
}

.action-dropdown-item {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
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

.action-dropdown-item.text-danger {
    color: var(--danger-color) !important;
}

.action-dropdown-item.text-danger:hover {
    background: rgba(229, 62, 62, 0.1);
}

.qa-table-footer {
    padding: 1rem 1.5rem;
    border-top: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafd;
}

.showing-info {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

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

.desktop-only {
    display: flex;
}

.mobile-actions,
.mobile-meta {
    display: none;
}

.mobile-action-btn.approve-btn {
    background: rgba(56, 161, 105, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(56, 161, 105, 0.2);
}

.mobile-action-btn.reject-btn {
    background: rgba(229, 62, 62, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(229, 62, 62, 0.2);
}

.mobile-action-btn.reapprove-btn {
    background: rgba(56, 161, 105, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(56, 161, 105, 0.2);
}

.mobile-action-btn.reapprove-btn:hover {
    background: rgba(56, 161, 105, 0.2);
}

@media (max-width: 992px) {
    .qa-header-section,
    .qa-filter-bar,
    .qa-table-container {
        margin-left: 1rem;
        margin-right: 1rem;
    }
}

@media (max-width: 768px) {
    .qa-header-section {
        padding: 1.25rem;
        margin: 1rem;
    }

    .qa-filter-bar {
        padding: 1rem;
        margin: 0 1rem 1rem 1rem;
    }

    .qa-btn-filter, .qa-btn-clear {
        width: 100%;
        justify-content: center;
    }

    .qa-table-container {
        margin: 0 1rem 1rem 1rem;
    }

    .qa-ideas-table thead {
        display: none;
    }

    .qa-ideas-table,
    .qa-ideas-table tbody,
    .qa-ideas-table tr,
    .qa-ideas-table td {
        display: block;
    }

    .qa-ideas-table tr {
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: white;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .qa-ideas-table td {
        padding: 0;
        border: none;
    }

    .author-cell,
    .dept-cell,
    .cat-cell,
    .status-cell {
        display: none !important;
    }

    .desktop-only {
        display: none !important;
    }

    .mobile-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.75rem;
        justify-content: flex-start;
    }

    .mobile-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
function toggleDropdown(id) {
    document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
        if (menu.id !== 'dropdown-' + id) {
            menu.classList.remove('show');
        }
    });
    const dropdown = document.getElementById('dropdown-' + id);
    if (dropdown) dropdown.classList.toggle('show');
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.action-dropdown')) {
        document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
</script>
@endpush

@include('admin.partials.sidebar-assets')