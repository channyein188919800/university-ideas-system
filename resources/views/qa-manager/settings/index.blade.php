@extends('layouts.qa-manager')

@section('title', 'Academic Settings - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-gear"></i> Academic Year Settings</h3>
        <p>Manage academic year and closure dates</p>
    </div>
</div>

<div class="qa-card">
    <div class="qa-card-body">
        <form method="POST" action="{{ route('qa-manager.settings.update') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Academic Year</label>
                <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror"
                       value="{{ old('academic_year', $settings['academic_year']) }}" required>
                @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Idea Closure Date</label>
                <input type="date" name="idea_closure_date" class="form-control @error('idea_closure_date') is-invalid @enderror"
                       value="{{ old('idea_closure_date', optional($settings['idea_closure_date'])->format('Y-m-d')) }}" required>
                @error('idea_closure_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Final Closure Date</label>
                <input type="date" name="final_closure_date" class="form-control @error('final_closure_date') is-invalid @enderror"
                       value="{{ old('final_closure_date', optional($settings['final_closure_date'])->format('Y-m-d')) }}" required>
                @error('final_closure_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
