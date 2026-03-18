@extends('layouts.qa-coordinator')

@section('title')
    @if($activeTab == 'department')
        Department Ideas - {{ auth()->user()->department->name }}
    @elseif($activeTab == 'popular')
        Popular Ideas - {{ auth()->user()->department->name }}
    @else
        Latest Ideas - {{ auth()->user()->department->name }}
    @endif
@endsection

@section('content')
<div class="qa-coordinator-layout">
    <main class="qa-main-content">
        <!-- Header Section -->
        <div class="qa-header-section mb-4">
            <h1 class="qa-header-title">
                @if($activeTab == 'department')
                    <i class="bi bi-lightbulb me-2"></i>Department Ideas
                @elseif($activeTab == 'popular')
                    <i class="bi bi-fire me-2"></i>Popular Ideas
                @else
                    <i class="bi bi-clock-history me-2"></i>Latest Ideas
                @endif
            </h1>
            <p class="qa-header-subtitle">
                {{ auth()->user()->department->name }} • 
                @if($activeTab == 'department')
                    Viewing all department ideas
                @elseif($activeTab == 'popular')
                    Most viewed and voted ideas
                @else
                    Most recent submissions
                @endif
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="qa-filter-bar mb-4">
            <form method="GET" action="{{ request()->url() }}" class="d-flex flex-wrap gap-3 align-items-center">
                <!-- Search -->
                <div class="qa-search-wrapper flex-grow-1">
                    <i class="bi bi-search qa-search-icon"></i>
                    <input type="text" 
                           name="search" 
                           class="qa-search-input" 
                           placeholder="Search ideas by title or description..."
                           value="{{ request('search') }}">
                </div>
                
                <!-- Status Filter -->
                <select name="status" class="qa-filter-select">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
                
                <button type="submit" class="qa-btn-filter">
                    <i class="bi bi-funnel me-2"></i>Apply Filters
                </button>
                
                @if(request('search') || request('status') != 'all')
                    <a href="{{ request()->url() }}" class="qa-btn-clear">
                        <i class="bi bi-x-circle me-2"></i>Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Ideas Table -->
        <div class="qa-table-container">
            <table class="qa-ideas-table">
                <thead>
                    <tr>
                        <th width="25%" class="title-col">Idea Title</th>
                        <th width="25%" class="desc-col">Description</th>
                        <th width="15%" class="user-col">Username</th>
                        @if($activeTab == 'popular')
                        <th width="10%" class="votes-col">Votes</th>
                        @endif
                        <th width="15%" class="status-col">Status</th>
                        <th width="15%" class="action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ideas as $idea)
                        <tr>
                            <td class="qa-title-cell" data-label="Idea Title">
                                <strong>{{ $idea->title }}</strong>
                                <!-- Mobile meta info - only visible on mobile -->
                                <div class="mobile-meta">
                                    @if($idea->is_anonymous)
                                        <span class="mobile-anonymous">Anonymous</span>
                                    @else
                                        <span class="mobile-user">{{ $idea->user->name }}</span>
                                    @endif
                                    <span class="mobile-status {{ $idea->status }}">{{ ucfirst(str_replace('_', ' ', $idea->status)) }}</span>
                                </div>
                            </td>
                            <td class="qa-description-cell" data-label="Description">
                                {{ Str::limit($idea->description, 80) }}
                            </td>
                            <td class="qa-user-cell" data-label="Username">
                                @if($idea->is_anonymous)
                                    <span class="qa-anonymous-badge">
                                        <i class="bi bi-incognito me-1"></i>Anonymous
                                    </span>
                                @else
                                    {{ $idea->user->name }}
                                @endif
                            </td>
                            @if($activeTab == 'popular')
                            <td class="qa-votes-cell" data-label="Votes">
                                <div class="votes-stacked">
                                    <span class="vote-badge up">
                                        <i class="bi bi-hand-thumbs-up"></i> {{ $idea->thumbs_up_count }}
                                    </span>
                                    <span class="vote-badge down">
                                        <i class="bi bi-hand-thumbs-down"></i> {{ $idea->thumbs_down_count }}
                                    </span>
                                </div>
                            </td>
                            @endif
                            <td class="qa-status-cell" data-label="Status">
                                @php
                                    $statusClasses = [
                                        'pending' => 'status-pending',
                                        'approved' => 'status-approved',
                                        'rejected' => 'status-rejected',
                                        'under_review' => 'status-review'
                                    ];
                                @endphp
                                <span class="qa-status-badge {{ $statusClasses[$idea->status] ?? 'status-pending' }}">
                                    {{ ucfirst(str_replace('_', ' ', $idea->status)) }}
                                </span>
                            </td>
                            <td class="qa-action-cell" data-label="Action">
                                <a href="{{ route('ideas.show', $idea) }}" class="qa-btn-view">
                                    <i class="bi bi-eye me-2"></i>View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $activeTab == 'popular' ? '6' : '5' }}" class="qa-empty-state">
                                <div class="qa-empty-icon">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <h3>No ideas found</h3>
                                <p>There are no ideas matching your criteria.</p>
                                <a href="{{ route('ideas.create') }}" class="qa-btn-primary mt-3">
                                    <i class="bi bi-plus-lg me-2"></i>Submit Your First Idea
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="qa-pagination-wrapper">
            {{ $ideas->appends(request()->query())->links() }}
        </div>
    </main>
</div>
@endsection

@push('styles')
<style>
/* Main Layout */
.qa-coordinator-layout {
    display: flex;
    min-height: 100vh;
    background: #f4f7fc;
}

.qa-main-content {
    flex: 1;
    padding: 0 2.5rem;
    transition: margin-left 0.3s ease;
}

/* Header Section */
.qa-header-section {
    background: white;
    border-radius: 20px;
    padding: 1.5rem 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}

.qa-header-title {
    font-size: 1.25rem;
    font-weight: 790;
    color: #0a1a2f;
    margin: 0 1rem 0.7rem 0;
    display: flex;
    align-items: center;
}

.qa-header-title i {
    color: #4361ee;
    font-size: 2rem;
    padding-right: 0.5rem;
}

.qa-header-subtitle {
    color: #5f6c80;
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
    background: #4361ee;
    border-radius: 50%;
    margin-right: 0.75rem;
}

/* Filter Bar */
.qa-filter-bar {
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.qa-search-wrapper {
    position: relative;
    min-width: 300px;
}

.qa-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #8f9eb2;
    font-size: 1rem;
}

.qa-search-input {
    width: 100%;
    padding: 0.7rem 1rem 0.7rem 2.8rem;
    border: 2px solid #e9ecf0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.qa-search-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.qa-filter-select {
    padding: 0.7rem 2rem 0.7rem 1rem;
    border: 2px solid #e9ecf0;
    border-radius: 12px;
    font-size: 0.95rem;
    color: #0a1a2f;
    background: white;
    cursor: pointer;
    min-width: 160px;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%235f6c80' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
}

.qa-filter-select:focus {
    outline: none;
    border-color: #4361ee;
}

.qa-btn-filter {
    background: #4361ee;
    color: white;
    border: none;
    padding: 0.7rem 1.8rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
    cursor: pointer;
}

.qa-btn-filter:hover {
    background: #3451d1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.qa-btn-clear {
    background: #f8f9fa;
    color: #5f6c80;
    border: 2px solid #e9ecf0;
    padding: 0.7rem 1.8rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.2s ease;
}

.qa-btn-clear:hover {
    background: #e9ecf0;
    color: #0a1a2f;
}

/* Table Styles */
.qa-table-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.qa-ideas-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.qa-ideas-table th {
    background: #f8fafd;
    color: #1e2b3c;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 1.25rem 1.5rem;
    text-align: left;
    border-bottom: 2px solid #eef2f6;
}

.qa-ideas-table td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #eef2f6;
    vertical-align: middle;
    color: #2a3b4f;
    word-wrap: break-word;
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

/* Mobile meta - hidden by default */
.mobile-meta {
    display: none;
}

/* Description Cell */
.qa-description-cell {
    color: #3e4e62;
    line-height: 1.5;
    font-size: 0.9rem;
}

/* User Info */
.qa-anonymous-badge {
    background: #f0f4fa;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #5f6c80;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
}

/* Votes Cell - Stacked Layout */
.qa-votes-cell {
    vertical-align: middle;
}

.votes-stacked {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.vote-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    width: fit-content;
}

.vote-badge.up {
    background: #e3f5e9;
    color: #0b5e2e;
}

.vote-badge.down {
    background: #fee9e7;
    color: #b3261e;
}

.vote-badge i {
    margin-right: 0.3rem;
}

/* Status Badges */
.qa-status-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending {
    background: #fff9e6;
    color: #b78103;
    border: 1px solid #ffecb3;
}

.status-approved {
    background: #e3f5e9;
    color: #0b5e2e;
    border: 1px solid #b8e0c5;
}

.status-rejected {
    background: #fee9e7;
    color: #b3261e;
    border: 1px solid #f9c2bd;
}

.status-review {
    background: #e6edff;
    color: #1a4cbc;
    border: 1px solid #b8cdfc;
}

/* Action Button */
.qa-btn-view {
    display: inline-flex;
    align-items: center;
    background: #4361ee;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    border: none;
}

.qa-btn-view:hover {
    background: #3451d1;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

/* Empty State */
.qa-empty-state {
    text-align: center;
    padding: 4rem 2rem !important;
    color: #5f6c80;
}

.qa-empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #d0d9e8;
}

.qa-empty-state h3 {
    color: #0a1a2f;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.qa-empty-state p {
    color: #5f6c80;
    margin-bottom: 1rem;
}

/* Primary Button */
.qa-btn-primary {
    background: #4361ee;
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.qa-btn-primary:hover {
    background: #3451d1;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

/* Pagination */
.qa-pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.qa-pagination-wrapper .pagination {
    gap: 0.5rem;
}

.qa-pagination-wrapper .page-link {
    border: 2px solid #e9ecf0;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    color: #4361ee;
    font-weight: 500;
}

.qa-pagination-wrapper .page-link:hover {
    background: #4361ee;
    color: white;
    border-color: #4361ee;
}

.qa-pagination-wrapper .active .page-link {
    background: #4361ee;
    border-color: #4361ee;
    color: white;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .qa-main-content {
        padding: 1.5rem;
        margin-left: 0;
    }
}

@media (max-width: 992px) {
    .qa-filter-bar form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .qa-search-wrapper {
        min-width: auto;
    }
}

/* Mobile View */
@media (max-width: 768px) {
    .qa-main-content {
        padding: 1rem;
    }
    
    .qa-header-section {
        padding: 1.25rem;
    }
    
    .qa-header-title {
        font-size: 1.5rem;
    }
    
    .qa-header-title i {
        font-size: 1.7rem;
    }
    
    .qa-filter-bar {
        padding: 1rem;
    }
    
    .qa-filter-bar form {
        gap: 0.75rem;
    }
    
    .qa-search-wrapper {
        width: 100%;
    }
    
    .qa-filter-select {
        width: 100%;
    }
    
    .qa-btn-filter, .qa-btn-clear {
        width: 100%;
        justify-content: center;
    }
    
    /* Table Responsive */
    .qa-ideas-table {
        display: block;
    }
    
    .qa-ideas-table thead {
        display: none;
    }
    
    .qa-ideas-table tbody {
        display: block;
    }
    
    .qa-ideas-table tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        background: white;
        padding: 1rem;
    }
    
    .qa-ideas-table td {
        display: block;
        padding: 0.5rem 0;
        border: none;
        text-align: left;
    }
    
    .qa-ideas-table td:last-child {
        border-bottom: none;
    }
    
    .qa-ideas-table td:before {
        content: attr(data-label);
        font-weight: 600;
        color: #5f6c80;
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.25rem;
    }
    
    /* Hide all columns except title and action on mobile */
    .qa-description-cell,
    .qa-user-cell,
    .qa-votes-cell,
    .qa-status-cell {
        display: none !important;
    }
    
    /* Show mobile meta info */
    .mobile-meta {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }
    
    .mobile-anonymous {
        background: #f0f4fa;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #5f6c80;
    }
    
    .mobile-user {
        font-size: 0.85rem;
        color: #4361ee;
    }
    
    .mobile-status {
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .mobile-status.pending {
        background: #fff9e6;
        color: #b78103;
    }
    
    .mobile-status.approved {
        background: #e3f5e9;
        color: #0b5e2e;
    }
    
    .mobile-status.rejected {
        background: #fee9e7;
        color: #b3261e;
    }
    
    .mobile-status.under_review {
        background: #e6edff;
        color: #1a4cbc;
    }
    
    .qa-btn-view {
        width: 100%;
        justify-content: center;
        margin-top: 0.5rem;
    }
    
    /* Stacked votes on mobile if they were visible */
    .votes-stacked {
        flex-direction: row;
    }
}

/* Small Mobile View */
@media (max-width: 480px) {
    .qa-main-content {
        padding: 0.75rem;
    }
    
    .qa-header-section {
        padding: 1rem;
    }
    
    .qa-header-title {
        font-size: 1.3rem;
    }
    
    .qa-filter-bar {
        padding: 0.75rem;
    }
    
    .qa-ideas-table tr {
        padding: 0.75rem;
    }
}
</style>
@endpush