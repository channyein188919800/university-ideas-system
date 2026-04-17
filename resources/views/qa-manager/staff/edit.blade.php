@php($hideNavFooter = true)
@extends('layouts.qa-manager')

@section('title', 'Edit User - University Ideas System')

@section('content')
<div class="staff-topbar">
    <div class="qa-header-section mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="qa-header-title">
                <i class="fas fa-user-edit"></i> Edit User: {{ $staff->name }}
            </h1>
            <p class="qa-header-subtitle">Update account details and role permissions.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('qa-manager.staff.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('qa-manager.staff.update', $staff) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="profile-layout">

        <div class="profile-avatar-card">
            <div class="avatar-wrapper" id="avatarWrapper">
                @if($staff->profile_image_url)
                    <img src="{{ $staff->profile_image_url }}" alt="{{ $staff->name }}" id="avatarPreview" class="avatar-img">
                @else
                    <div class="avatar-initials" id="avatarInitials">
                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                    </div>
                    <img src="" alt="" id="avatarPreview" class="avatar-img" style="display:none;">
                @endif
                <label for="profile_image" class="avatar-upload-overlay" title="Change photo">
                    <i class="bi bi-camera-fill"></i>
                </label>
            </div>

            <div class="avatar-name">{{ $staff->name }}</div>
            <div class="avatar-role">
                {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $staff->role)) }}
            </div>
            <div class="avatar-email text-muted small">{{ $staff->email }}</div>

            <input type="file" name="profile_image" id="profile_image"
                   class="d-none @error('profile_image') is-invalid @enderror"
                   accept="image/*">
            @error('profile_image')
                <div class="invalid-feedback d-block text-center">{{ $message }}</div>
            @enderror
            <small class="text-muted d-block text-center mt-1">JPG, PNG or GIF · Max 2MB</small>
            @if($staff->profile_image_url)
                <button type="submit" name="remove_profile_image" value="1" class="btn btn-outline-danger w-100 mt-2">
                    <i class="bi bi-trash3 me-1"></i> Remove Photo
                </button>
            @endif

            <button type="submit" class="btn btn-primary w-100 mt-4">
                <i class="bi bi-check-circle me-1"></i> Save Changes
            </button>
        </div>

        <div class="profile-fields">
            <div class="profile-section">
                <div class="profile-section-header">
                    <i class="bi bi-person-lines-fill"></i> Personal Information
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $staff->name) }}"
                                   placeholder="Full name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $staff->email) }}"
                                   placeholder="user@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department</label>
                            @if($staff->role === 'staff')
                                <select class="form-select @error('department_id') is-invalid @enderror"
                                        name="department_id">
                                    <option value="">-- None --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $staff->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <input type="hidden" name="department_id" value="{{ $staff->department_id }}">
                                <input type="text" class="form-control"
                                       value="{{ $staff->department->name ?? 'No Department Assigned' }}" disabled>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <input type="hidden" name="role" value="{{ $staff->role }}">
                            <input type="text" class="form-control"
                                   value="{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $staff->role)) }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-section-header">
                    <i class="bi bi-shield-lock-fill"></i> Change Password
                </div>
                <div class="profile-section-body">
                    <p class="text-muted small mb-3">Leave blank to keep the current password. If providing one, it must be at least 8 characters with mixed case and a symbol.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Leave blank to keep current"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" id="confirmPassword"
                                       class="form-control"
                                       placeholder="Repeat new password"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                    <i class="bi bi-eye" id="toggleConfirmIcon"></i>
                                </button>
                            </div>
                            <div id="passwordMatchMsg" class="form-text"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-section-header">
                    <i class="bi bi-info-circle-fill"></i> Account Details
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Member Since</label>
                            <input type="text" class="form-control" value="{{ $staff->created_at->format('F d, Y') }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Last Login</label>
                            <input type="text" class="form-control" value="{{ $staff->last_login_at ? $staff->last_login_at->format('M d, Y h:i A') : 'N/A' }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    .qa-header-section {
        background: white;
        border-radius: 20px;
        padding: 1rem 1.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
    }

    .qa-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0 0 0.5rem 0;
        display: inline-flex;
        align-items: center;
    }

    .qa-header-title i {
        color: #d69e2e;
        font-size: 1.5rem;
        margin-right: 0.75rem;
    }

    .qa-header-subtitle {
        color: #4a5568;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .qa-header-subtitle:before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 4px;
        background: #d69e2e;
        border-radius: 50%;
        margin-right: 0.75rem;
    }

    .profile-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    .profile-avatar-card {
        background: #fff;
        border-radius: 1.2rem;
        border: 1px solid #e4ebf8;
        box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        position: sticky;
        top: 1.5rem;
    }

    .avatar-wrapper {
        position: relative;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        margin-bottom: 0.75rem;
        cursor: pointer;
    }

    .avatar-img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e4ebf8;
        box-shadow: 0 4px 16px rgba(27,52,102,0.12);
        display: block;
    }

    .avatar-initials {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3577ff, #5ea8ff);
        border: 3px solid #e4ebf8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2.5rem;
        font-weight: 700;
        box-shadow: 0 4px 16px rgba(27,52,102,0.12);
    }

    .avatar-upload-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #3577ff;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.95rem;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(53,119,255,0.4);
        transition: background 0.2s;
    }

    .avatar-upload-overlay:hover {
        background: #1f5fd6;
    }

    .avatar-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1c2a45;
        text-align: center;
    }

    .avatar-role {
        font-size: 0.82rem;
        font-weight: 600;
        color: #3577ff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .avatar-email {
        font-size: 0.83rem;
        text-align: center;
    }

    .profile-fields {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    .profile-section {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e4ebf8;
        box-shadow: 0 6px 18px rgba(27,52,102,0.06);
        overflow: hidden;
    }

    .profile-section-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.9rem 1.2rem;
        background: linear-gradient(90deg, #f9fbff, #f3f7ff);
        border-bottom: 1px solid #edf2fb;
        font-weight: 600;
        color: #253558;
        font-size: 0.95rem;
    }

    .profile-section-header i {
        color: #3577ff;
        font-size: 1rem;
    }

    .profile-section-body {
        padding: 1.2rem;
    }

    .form-control:disabled {
        background: #f8fafc;
        color: #64748b;
        border-color: #e9eef6;
        cursor: not-allowed;
    }

    @media (max-width: 991.98px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }

        .profile-avatar-card {
            position: static;
        }
    }

    @media (max-width: 767.98px) {
        .staff-topbar {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('profile_image');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarInitials = document.getElementById('avatarInitials');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        avatarPreview.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        if (avatarInitials) avatarInitials.style.display = 'none';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        const passwordInput = document.getElementById('passwordInput');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePasswordIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }

        const confirmInput = document.getElementById('confirmPassword');
        const toggleConfirm = document.getElementById('toggleConfirm');
        const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
        if (toggleConfirm && confirmInput) {
            toggleConfirm.addEventListener('click', () => {
                const isHidden = confirmInput.type === 'password';
                confirmInput.type = isHidden ? 'text' : 'password';
                toggleConfirmIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }

        const matchMsg = document.getElementById('passwordMatchMsg');
        function checkMatch() {
            if (!passwordInput || !confirmInput || !matchMsg) return;
            const p = passwordInput.value;
            const c = confirmInput.value;
            if (!p && !c) {
                matchMsg.textContent = '';
                return;
            }
            if (p === c) {
                matchMsg.textContent = 'Passwords match';
                matchMsg.style.color = '#22a959';
            } else {
                matchMsg.textContent = 'Passwords do not match';
                matchMsg.style.color = '#e53e3e';
            }
        }

        if (confirmInput && passwordInput) {
            confirmInput.addEventListener('input', checkMatch);
            passwordInput.addEventListener('input', checkMatch);
        }
    });
</script>
@endpush
