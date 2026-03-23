@extends('layouts.qa-manager')

@section('title', 'Add Category - University Ideas System')

@section('content')
    <div class="qa-topbar">
        <div>
            <h3><i class="bi bi-plus-circle"></i> Add Category</h3>
            <p>Create a new category for idea classification</p>
        </div>
        <a href="{{ route('qa-manager.categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="qa-card">
                <div class="qa-card-header">
                    <h5><i class="bi bi-tag"></i> Category Details</h5>
                </div>
                <div class="qa-card-body">
                    <form method="POST" action="{{ route('qa-manager.categories.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required
                                placeholder="e.g., Infrastructure, Teaching, Research">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3"
                                placeholder="Brief description of this category">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
