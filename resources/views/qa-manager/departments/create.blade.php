@extends('layouts.qa-manager')

@section('title', 'Create Department - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-building-add"></i> Add Department</h3>
        <p>Create a new department and assign QA coordinator</p>
    </div>
    <a href="{{ route('qa-manager.departments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="qa-card">
    <div class="qa-card-header">
        <h5><i class="bi bi-building"></i> Department Details</h5>
    </div>
    <div class="qa-card-body">
        <form method="POST" action="{{ route('qa-manager.departments.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required
                       placeholder="e.g., Computer Science">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('code') is-invalid @enderror" 
                       id="code" 
                       name="code" 
                       value="{{ old('code') }}" 
                       required
                       placeholder="e.g., CS">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="3"
                          placeholder="Brief description of the department">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="qa_coordinator_id" class="form-label">QA Coordinator</label>
                <select class="form-select @error('qa_coordinator_id') is-invalid @enderror" 
                        id="qa_coordinator_id" 
                        name="qa_coordinator_id">
                    <option value="">-- Select QA Coordinator --</option>
                    @foreach($qaCoordinators as $coordinator)
                        <option value="{{ $coordinator->id }}" {{ old('qa_coordinator_id') == $coordinator->id ? 'selected' : '' }}>
                            {{ $coordinator->name }} ({{ $coordinator->email }})
                        </option>
                    @endforeach
                </select>
                @error('qa_coordinator_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle"></i> Only users with QA Coordinator role are shown.
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <!-- <a href="{{ route('qa-manager.departments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Cancel
                </a> -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Department
                </button>
            </div>
        </form>
    </div>
</div>

@endsection