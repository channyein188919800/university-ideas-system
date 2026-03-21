@extends('layouts.app')

@section('title', 'Idea Approval - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-check2-square"></i> Idea Approval</h2>
                    <p class="text-muted mb-0">Review staff ideas and approve or reject them.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Staff</th>
                                    <th>Department</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ideas as $idea)
                                    <tr>
                                        <td>
                                            <a href="{{ route('ideas.show', $idea) }}" class="text-decoration-none">
                                                {{ $idea->title }}
                                            </a>
                                        </td>
                                        <td>{{ $idea->user?->name ?? 'Unknown' }}</td>
                                        <td>{{ $idea->department?->name ?? 'Unassigned' }}</td>
                                        <td>{{ $idea->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('admin.idea-approvals.approve', $idea) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.idea-approvals.reject', $idea) }}" class="d-inline ms-1">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-x-lg"></i> Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No pending staff ideas for approval.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $ideas->links() }}
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    footer {
        display: none !important;
    }
</style>
@endpush

@include('admin.partials.sidebar-assets')
