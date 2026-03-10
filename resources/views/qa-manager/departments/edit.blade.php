@extends('layouts.qa-manager')

@section('title', 'Edit Department - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-pencil-square"></i> Edit Department</h3>
        <p>Update details and coordinator assignment</p>
    </div>
    <a href="{{ route('qa-manager.departments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="qa-card">
    <div class="qa-card-body">
        <form method="POST" action="{{ route('qa-manager.departments.update', $department) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $department->code) }}" required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $department->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">QA Coordinator</label>
                <select name="qa_coordinator_id" class="form-select @error('qa_coordinator_id') is-invalid @enderror">
                    <option value="">Unassigned</option>
                    @foreach($qaCoordinators as $coordinator)
                        <option value="{{ $coordinator->id }}" {{ old('qa_coordinator_id', $department->qa_coordinator_id) == $coordinator->id ? 'selected' : '' }}>
                            {{ $coordinator->name }} ({{ $coordinator->email }})
                        </option>
                    @endforeach
                </select>
                @error('qa_coordinator_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label d-block">Status</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Update Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
