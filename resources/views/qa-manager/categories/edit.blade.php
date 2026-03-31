@extends('layouts.qa-manager')

@section('title', 'Edit Category - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-pencil-square"></i> Edit Category</h3>
        <p>Update category details and active status</p>
    </div>
    <a href="{{ route('qa-manager.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="qa-card">
            <div class="qa-card-header">
                <h5><i class="bi bi-sliders"></i> Category Settings</h5>
            </div>
            <div class="qa-card-body">
                <form method="POST" action="{{ route('qa-manager.categories.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $category->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox"
                               class="form-check-input"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <div class="alert alert-info">
                        <strong>Slug:</strong> <code>{{ $category->slug }}</code>
                        <br><small>Slug is generated automatically from the category name.</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection
