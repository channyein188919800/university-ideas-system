@extends('layouts.app')

@section('title', 'Edit Department - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4 admin-users-header">
                        <div>
                            <h2><i class="fas fa-edit"></i> Edit Department</h2>
                            <p class="text-muted mb-0">Update department details</p>
                        </div>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <div class="toast-container admin-users-toast">
                            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <span id="toastMessage">Department updated successfully!</span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('admin.departments.update', $department) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-building"></i> Department Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $department->name) }}"
                                               required>
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
                                               value="{{ old('code', $department->code) }}"
                                               required>
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
                                              rows="3">{{ old('description', $department->description) }}</textarea>
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
                                            <option value="{{ $coordinator->id }}" {{ old('qa_coordinator_id', $department->qa_coordinator_id) == $coordinator->id ? 'selected' : '' }}>
                                                {{ $coordinator->name }} ({{ $coordinator->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('qa_coordinator_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle"></i> Active
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Department
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

@include('admin.partials.sidebar-assets')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast('{{ session('success') }}');
        @endif
    });

    function showToast(message) {
        const toast = document.getElementById('successToast');
        const messageSpan = document.getElementById('toastMessage');

        if (toast && messageSpan) {
            messageSpan.textContent = message;
            toast.style.display = 'block';

            const bsToast = new bootstrap.Toast(toast, {
                animation: true,
                autohide: true,
                delay: 5000
            });
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', function () {
                toast.style.display = 'none';
            });
        }
    }
</script>
@endpush

@push('styles')
<style>
    .admin-users-header {
        position: relative;
    }
    .admin-users-toast {
        position: absolute;
        top: -6px;
        left: auto;
        right: 140px;
        transform: none;
        z-index: 30;
        pointer-events: none;
        display: block;
    }
    .admin-users-toast .toast {
        pointer-events: auto;
        max-width: 460px;
    }
    @media (max-width: 992px) {
        .admin-users-toast {
            position: relative;
            left: 0;
            top: 0;
            transform: none;
            margin-top: 0.75rem;
            display: block;
        }
    }
    .alert-success {
        display: none !important;
    }
</style>
@endpush
