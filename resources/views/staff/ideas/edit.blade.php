@extends('layouts.app')

@section('title', 'Edit Idea - ' . $idea->title)

@section('content')
<style>
    .edit-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .edit-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .edit-header {
        background: linear-gradient(135deg, #1e3a5f, #2c5282);
        padding: 1.5rem 2rem;
        color: white;
    }

    .edit-header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
    }

    .edit-header p {
        margin: 0.5rem 0 0;
        opacity: 0.8;
    }

    .edit-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #1e3a5f;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #d69e2e;
        box-shadow: 0 0 0 3px rgba(214, 158, 46, 0.1);
    }

    .form-control.is-invalid {
        border-color: #e53e3e;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .category-checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .category-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f8fafc;
        border-radius: 2rem;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .category-checkbox:hover {
        border-color: #d69e2e;
        background: #fff9e6;
    }

    .category-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .category-checkbox span {
        font-size: 0.9rem;
        color: #4a5568;
    }

    .category-checkbox input:checked + span {
        color: #d69e2e;
        font-weight: 600;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e3a5f, #2c5282);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #4a5568;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffecb5;
        color: #856404;
    }

    .anonymous-toggle {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        cursor: pointer;
    }

    .anonymous-toggle input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .anonymous-toggle label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    .anonymous-toggle small {
        color: #718096;
        font-size: 0.8rem;
    }

    .text-danger {
        color: #e53e3e;
    }
</style>

<div class="edit-container">
    <div class="edit-card">
        <div class="edit-header">
            <h1><i class="bi bi-pencil-square me-2"></i>Edit Idea</h1>
            <p>Update your idea - {{ Str::limit($idea->title, 50) }}</p>
        </div>
        
        <div class="edit-body">
            @if($idea->status != 'pending')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    This idea has been <strong>{{ ucfirst($idea->status) }}</strong>. You can still edit it, but changes may require re-approval.
                </div>
            @endif

            <form method="POST" action="{{ route('staff.ideas.update', $idea) }}">
                @csrf
                @method('PUT')
                
                <!-- Title -->
                <div class="form-group">
                    <label class="form-label" for="title">Idea Title <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $idea->title) }}"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="6"
                              required>{{ old('description', $idea->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Categories -->
                <div class="form-group">
                    <label class="form-label">Categories <span class="text-danger">*</span></label>
                    <div class="category-checkbox-group">
                        @foreach($categories as $category)
                            <label class="category-checkbox">
                                <input type="checkbox" 
                                       name="categories[]" 
                                       value="{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', $idea->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Anonymous Toggle -->
                <div class="form-group">
                    <label class="anonymous-toggle">
                        <input type="checkbox" 
                               name="is_anonymous" 
                               value="1"
                               {{ old('is_anonymous', $idea->is_anonymous) ? 'checked' : '' }}>
                        <div>
                            <label class="mb-0">Submit Anonymously</label>
                            <small class="d-block">Your identity will be hidden from other users</small>
                        </div>
                    </label>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('staff.ideas.show', $idea) }}" class="btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-check-circle"></i> Update Idea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection