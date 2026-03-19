@extends('layouts.qa-manager')

@section('title', 'Academic Settings - University Ideas System')

@section('content')
<div class="admin-shell">

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h2><i class="fas fa-cog"></i> Closure Dates</h2>
                        <p class="text-muted mb-0">Configure system closure dates and academic year</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt"></i> Closure Dates
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('qa-manager.settings.update') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="academic_year" class="form-label">
                                        <i class="fas fa-graduation-cap"></i> Academic Year <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('academic_year') is-invalid @enderror" 
                                           id="academic_year" 
                                           name="academic_year" 
                                           value="{{ old('academic_year', $settings['academic_year']) }}" 
                                           required
                                           placeholder="e.g., 2024-2025">
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Format: YYYY-YYYY (e.g., 2024-2025)
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="idea_closure_date" class="form-label">
                                        <i class="fas fa-clock"></i> Idea Submission Closure Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('idea_closure_date') is-invalid @enderror" 
                                           id="idea_closure_date" 
                                           name="idea_closure_date" 
                                           value="{{ old('idea_closure_date', optional($settings['idea_closure_date'])->format('Y-m-d')) }}" 
                                           required>
                                    @error('idea_closure_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> After this date, staff will no longer be able to submit new ideas.
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="final_closure_date" class="form-label">
                                        <i class="fas fa-calendar-times"></i> Final Closure Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('final_closure_date') is-invalid @enderror" 
                                           id="final_closure_date" 
                                           name="final_closure_date" 
                                           value="{{ old('final_closure_date', optional($settings['final_closure_date'])->format('Y-m-d')) }}" 
                                           required>
                                    @error('final_closure_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> After this date, all commenting will be closed. This must be on or after the idea closure date.
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Current Status:</strong>
                                    @php
                                        $ideaClosed = $settings['idea_closure_date'] && now()->gte($settings['idea_closure_date']);
                                        $finalClosed = $settings['final_closure_date'] && now()->gte($settings['final_closure_date']);
                                    @endphp
                                    <ul class="mb-0 mt-2">
                                        <li>Idea Submission: {{ $ideaClosed ? 'Closed' : 'Open' }}</li>
                                        <li>Commenting: {{ $finalClosed ? 'Closed' : 'Open' }}</li>
                                    </ul>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-info-circle"></i> About Closure Dates
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>Idea Closure Date:</strong> Staff can submit ideas until this date.
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>Final Closure Date:</strong> Staff can comment on ideas until this date.
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>After Final Closure:</strong> QA Manager can download all data for transfer.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    /* Card Styles */
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border-radius: 0.75rem 0.75rem 0 0 !important;
        font-weight: 600;
        padding: 1rem 1.25rem;
    }
    
    .card-header i {
        margin-right: 0.5rem;
        color: var(--accent-color);
    }
    
    .card-header.bg-info {
        background: var(--info-color) !important;
    }
    
    .card-header.bg-info i {
        color: white;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Form Styles */
    .form-label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    
    .form-label i {
        color: var(--primary-color);
        margin-right: 0.25rem;
    }
    
    .form-control {
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        outline: none;
    }
    
    .form-text {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .form-text i {
        color: var(--primary-color);
        margin-right: 0.25rem;
    }
    
    /* Alert Styles */
    .alert-info {
        background-color: #e1f0fa;
        border: 1px solid #b8dafc;
        border-radius: 0.5rem;
        color: var(--text-primary);
        padding: 1rem 1.25rem;
    }
    
    .alert-info i {
        color: var(--info-color);
        margin-right: 0.5rem;
    }
    
    .alert-info ul {
        list-style-type: none;
        padding-left: 1.5rem;
    }
    
    .alert-info li {
        margin-bottom: 0.25rem;
    }
    
    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        padding: 0.6rem 1.8rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
    }
    
    .btn-primary i {
        margin-right: 0.5rem;
    }
    
    /* Header Styles */
    h2 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    h2 i {
        color: var(--accent-color);
        margin-right: 0.5rem;
    }
    
    .text-muted {
        color: var(--text-secondary) !important;
    }
    
    /* List Styles */
    .list-unstyled li {
        color: var(--text-secondary);
    }
    
    .list-unstyled i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }
    
    .text-success {
        color: var(--success-color) !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }
    }
</style>
@endpush