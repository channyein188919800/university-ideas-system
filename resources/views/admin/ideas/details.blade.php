@extends('layouts.app')

@section('title', 'Idea Details - ' . $idea->title)

@section('content')
@php
    $authorName = $idea->is_anonymous ? 'Anonymous' : ($idea->user?->name ?? 'Unknown');
    $authorRole = $idea->is_anonymous ? 'Anonymous' : \Illuminate\Support\Str::title(str_replace('_', ' ', $idea->user?->role ?? 'staff'));
@endphp

<style>
    .qa-idea-card {
        background: #ffffff;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(17, 24, 39, 0.08);
        border: 1px solid #eef2f7;
    }
    .qa-idea-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        color: #fff;
        padding: 2.75rem 2.75rem 2.25rem;
        position: relative;
    }
    .qa-idea-header::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        top: -60px;
        right: -60px;
        background: rgba(255, 255, 255, 0.08);
    }
    .qa-idea-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-bottom: 1.2rem;
    }
    .qa-idea-pill {
        background: rgba(255, 255, 255, 0.15);
        color: #f3f7ff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.35rem 1rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .qa-idea-pill.accent {
        color: #ffd166;
        border-color: rgba(255, 209, 102, 0.4);
    }
    .qa-idea-title {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    .qa-idea-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: 0.95rem;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        padding-top: 1rem;
        color: rgba(255, 255, 255, 0.9);
    }
    .qa-idea-meta i {
        color: #ffd166;
        margin-right: 0.35rem;
    }
    .qa-idea-body {
        padding: 2.5rem 2.75rem;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #4b5563;
        background: #ffffff;
    }
</style>

<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-eye me-2"></i> Idea Details</h2>
                    <p class="text-muted mb-0">Pending or rejected ideas do not show the public detail page.</p>
                </div>
            </div>

            <div class="qa-idea-card">
                <div class="qa-idea-header">
                    <div class="qa-idea-pills">
                        @foreach($idea->categories as $category)
                            <span class="qa-idea-pill">{{ $category->name }}</span>
                        @endforeach
                        @if($idea->is_anonymous)
                            <span class="qa-idea-pill accent"><i class="bi bi-incognito me-1"></i> Anonymous</span>
                        @endif
                    </div>
                    <div class="qa-idea-title">{{ $idea->title }}</div>
                    <div class="qa-idea-meta">
                        <span><i class="bi bi-building"></i>{{ $idea->department?->name ?? 'Unassigned' }}</span>
                        <span><i class="bi bi-person"></i>{{ $authorName }} • {{ $authorRole }}</span>
                        <span><i class="bi bi-calendar-event"></i>{{ $idea->created_at->format('M d, Y') }}</span>
                        <span><i class="bi bi-flag"></i>{{ ucfirst($idea->status) }}</span>
                    </div>
                </div>
                <div class="qa-idea-body">
                    {{ $idea->description }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@include('admin.partials.sidebar-assets')
