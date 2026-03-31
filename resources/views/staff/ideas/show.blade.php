@extends('layouts.app')

@section('title', $idea->title . ' - My Idea')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <a href="{{ route('ideas.index', ['my_ideas' => 1]) }}" class="btn btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Back to My Ideas
            </a>

            <!-- Status Banner -->
            @if($idea->status == 'pending')
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-clock-history me-2"></i>
                    <strong>Pending Review</strong> - Your idea is waiting for approval from QA Manager.
                </div>
            @elseif($idea->status == 'under_review')
                <div class="alert alert-info mb-4">
                    <i class="bi bi-eye me-2"></i>
                    <strong>Under Review</strong> - QA Manager is reviewing your idea.
                </div>
            @elseif($idea->status == 'rejected')
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-x-circle me-2"></i>
                    <strong>Rejected</strong> - Your idea was not approved.
                </div>
            @endif

            <!-- Idea Card -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="card-title mb-0">{{ $idea->title }}</h2>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.ideas.edit', $idea) }}">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Idea
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('staff.ideas.destroy', $idea) }}" class="d-inline w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this idea?')">
                                            <i class="bi bi-trash3 me-2"></i> Delete Idea
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-4">
                        @foreach($idea->categories as $category)
                            <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <p class="text-secondary">{{ $idea->description }}</p>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-calendar3 me-1"></i> Submitted: {{ $idea->created_at->format('M d, Y') }}</span>
                            <span><i class="bi bi-chat-dots me-1"></i> {{ $idea->comments_count }} comments</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection