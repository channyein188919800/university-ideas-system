@extends('layouts.qa-manager')

@section('title', 'Edit Staff Account - University Ideas System')

@section('content')
<div class="qa-topbar">
    <div>
        <h3><i class="bi bi-person-gear"></i> Edit Staff Account</h3>
        <p>Update role, department, and account status</p>
    </div>
    <a href="{{ route('qa-manager.staff.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="qa-card">
    <div class="qa-card-body">
        <form method="POST" action="{{ route('qa-manager.staff.update', $staff) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $staff->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $staff->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">New Password (optional)</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $staff->role) === $role ? 'selected' : '' }}>
                            {{ str_replace('_', ' ', ucfirst($role)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                    <option value="">No Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (int) old('department_id', $staff->department_id) === $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status', $staff->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="disabled" {{ old('status', $staff->status) === 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Update Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
