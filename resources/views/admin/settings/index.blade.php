@extends('layouts.app')

@section('title', 'System Settings - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <section class="admin-main">
        <div class="container-fluid py-2">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h2><i class="fas fa-cog"></i> System Settings</h2>
                        <p class="text-muted mb-0">Configure system closure dates and academic year</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt"></i> Closure Dates
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('admin.settings.update') }}">
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
                                    <input type="datetime-local" 
                                           class="form-control @error('idea_closure_date') is-invalid @enderror" 
                                           id="idea_closure_date" 
                                           name="idea_closure_date" 
                                           value="{{ old('idea_closure_date', $settings['idea_closure_date']?->format('Y-m-d\TH:i')) }}" 
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
                                    <input type="datetime-local" 
                                           class="form-control @error('final_closure_date') is-invalid @enderror" 
                                           id="final_closure_date" 
                                           name="final_closure_date" 
                                           value="{{ old('final_closure_date', $settings['final_closure_date']?->format('Y-m-d\TH:i')) }}" 
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

@include('admin.partials.sidebar-assets')