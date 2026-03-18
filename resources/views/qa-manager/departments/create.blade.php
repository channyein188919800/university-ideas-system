@extends('layouts.qa-manager')

@section('title', 'Create Department - University Ideas System')


@section('content')
<div class="admin-shell">

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-4 admin-users-header">
                        <h2><i class="bi bi-building-add"></i> Add Department</h2>
                        <p class="text-muted mb-0">Create a new department and assign QA coordinator</p>
                        <div class="toast-container admin-users-toast">
                            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <span id="toastMessage">Department created successfully!</span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('qa-manager.departments.store') }}">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-building"></i> Department Name <span class="text-danger">*</span>
                                        </label>
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
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="code" class="form-label">
                                            <i class="fas fa-code"></i> Department Code <span class="text-danger">*</span>
                                        </label>
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
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
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
                                    <label for="qa_coordinator_id" class="form-label">
                                        <i class="fas fa-user-tie"></i> QA Coordinator
                                    </label>
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
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Department
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
