@extends('layouts.app')

@section('title', 'Submit Idea - University Ideas System')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-plus-circle"></i> Submit an Idea</h2>
                    <p class="text-muted mb-0">Share your innovative ideas to help improve our university</p>
                </div>
                <a href="{{ route('ideas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Ideas
                </a>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('ideas.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading"></i> Idea Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required
                                   placeholder="Enter a clear and concise title for your idea">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6" 
                                      required
                                      placeholder="Describe your idea in detail. What problem does it solve? How will it benefit the university?">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Be as specific as possible. Include any relevant details that will help others understand your idea.
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-3">
                            <label for="categories" class="form-label">
                                <i class="fas fa-tags"></i> Categories <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('categories') is-invalid @enderror" 
                                    id="categories" 
                                    name="categories[]" 
                                    multiple 
                                    required
                                    size="5">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Hold Ctrl (or Cmd on Mac) to select multiple categories.
                            </div>
                        </div>
                        
                        <!-- Documents -->
                        <div class="mb-3">
                            <label for="documents" class="form-label">
                                <i class="fas fa-paperclip"></i> Supporting Documents (Optional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror" 
                                   id="documents" 
                                   name="documents[]" 
                                   multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            @error('documents')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('documents.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> You can upload multiple files. Max size: 10MB per file. 
                                Allowed: PDF, Word, Excel, PowerPoint, Images.
                            </div>
                        </div>
                        
                        <!-- Anonymous Option -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_anonymous" 
                                       name="is_anonymous" 
                                       value="1"
                                       {{ old('is_anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">
                                    <i class="fas fa-user-secret"></i> Submit anonymously
                                </label>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Your identity will be stored in the database for administrative purposes, but your name will not be displayed publicly.
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ideas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Submit Idea
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Tips Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-lightbulb"></i> Tips for a Great Idea
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Be clear and specific about what you want to achieve</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Explain the problem your idea solves</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Include any potential benefits or cost savings</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Attach supporting documents if they help explain your idea</li>
                        <li class="mb-0"><i class="fas fa-check text-success"></i> Consider how your idea could be implemented</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show selected file names
    document.getElementById('documents').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            let fileNames = Array.from(files).map(f => f.name).join(', ');
            if (fileNames.length > 100) {
                fileNames = fileNames.substring(0, 100) + '... (' + files.length + ' files)';
            }
            // You can add a display element here if needed
        }
    });
</script>
@endpush
