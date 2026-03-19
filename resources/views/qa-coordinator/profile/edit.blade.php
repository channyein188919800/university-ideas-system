@php($hideNavFooter = true)
@extends('layouts.qa-coordinator')

@section('title', 'My Profile - University Ideas System')

@section('content')
<div class="staff-shell">
    <section class="staff-main d-flex flex-column">
        <div class="staff-topbar">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-gear"></i> My Profile</h3>
                <p class="text-muted mb-0">Manage your account details, password, and profile photo.</p>
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

        <form method="POST" action="{{ route('qa-coordinator.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-layout">
                
                {{-- Left Column: Avatar Card --}}
                <div class="profile-avatar-card">
                    <div class="avatar-wrapper" id="avatarWrapper">
                        @if($user->profile_image_url)
                            <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" id="avatarPreview" class="avatar-img">
                        @else
                            <div class="avatar-initials" id="avatarInitials">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img src="" alt="" id="avatarPreview" class="avatar-img" style="display:none;">
                        @endif
                        <label for="profile_image" class="avatar-upload-overlay" title="Change photo">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                    </div>

                    <div class="avatar-name">{{ $user->name }}</div>
                    <div class="avatar-role">QA Coordinator</div>
                    <div class="avatar-email text-muted small">{{ $user->email }}</div>

                    <input type="file" name="profile_image" id="profile_image" 
                           class="d-none @error('profile_image') is-invalid @enderror" 
                           accept="image/*">
                    @error('profile_image')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block text-center mt-1">JPG, PNG or GIF · Max 2MB</small>

                    <button type="submit" class="btn btn-primary w-100 mt-4">
                        <i class="bi bi-check-circle me-1"></i> Save Changes
                    </button>
                </div>

                {{-- Right Column: Form Fields --}}
                <div class="profile-fields">
                    
                    {{-- Personal Information --}}
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
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Department</label>
                                    <input type="text" class="form-control" value="{{ $user->department->name ?? 'Not Assigned' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Role</label>
                                    <input type="text" class="form-control" value="QA Coordinator" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Password Section --}}
                    <div class="profile-section">
                        <div class="profile-section-header">
                            <i class="bi bi-shield-lock-fill"></i> Change Password
                        </div>
                        <div class="profile-section-body">
                            <p class="text-muted small mb-3">Leave blank to keep your current password. Password must be at least 8 characters with mixed case and a symbol.</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordInput"
                                               class="form-control @error('password') is-invalid @enderror" 
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

                    {{-- Account Details Section --}}
                    <div class="profile-section">
                        <div class="profile-section-header">
                            <i class="bi bi-info-circle-fill"></i> Account Details
                        </div>
                        <div class="profile-section-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Member Since</label>
                                    <input type="text" class="form-control" value="{{ $user->created_at->format('F d, Y') }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Last Login</label>
                                    <input type="text" class="form-control" value="{{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'N/A' }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </section>
</div>
@endsection



@push('styles')
<style>
    .staff-main {
        flex: 1; padding: 1.5rem; overflow-x: hidden;
    }

    .staff-topbar {
        display: flex; justify-content: space-between; align-items: center;
        gap: 1rem; margin-bottom: 1.5rem;
    }
    .staff-topbar h3 { font-weight: 700; color: #1c2a45; }

    /* Profile Layout */
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
        width: 110px; height: 110px; border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e4ebf8;
        box-shadow: 0 4px 16px rgba(27,52,102,0.12);
        display: block;
    }

    .avatar-initials {
        width: 110px; height: 110px; border-radius: 50%;
        background: linear-gradient(135deg, #3577ff, #5ea8ff);
        border: 3px solid #e4ebf8;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 2.5rem; font-weight: 700;
        box-shadow: 0 4px 16px rgba(27,52,102,0.12);
    }

    .avatar-upload-overlay {
        position: absolute;
        bottom: 0; right: 0;
        width: 34px; height: 34px; border-radius: 50%;
        background: #3577ff;
        border: 2px solid #fff;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 0.95rem;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(53,119,255,0.4);
        transition: background 0.2s;
    }
    .avatar-upload-overlay:hover { background: #1f5fd6; }

    .avatar-name { font-size: 1.05rem; font-weight: 700; color: #1c2a45; text-align: center; }
    .avatar-role { font-size: 0.82rem; font-weight: 600; color: #3577ff; text-transform: uppercase; letter-spacing: 0.05em; }
    .avatar-email { font-size: 0.83rem; text-align: center; }

    /* Form Sections */
    .profile-fields { display: flex; flex-direction: column; gap: 1.2rem; }

    .profile-section {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e4ebf8;
        box-shadow: 0 6px 18px rgba(27,52,102,0.06);
        overflow: hidden;
    }

    .profile-section-header {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.9rem 1.2rem;
        background: linear-gradient(90deg, #f9fbff, #f3f7ff);
        border-bottom: 1px solid #edf2fb;
        font-weight: 600; color: #253558; font-size: 0.95rem;
    }
    .profile-section-header i { color: #3577ff; font-size: 1rem; }

    .profile-section-body { padding: 1.2rem; }

    .form-control:disabled {
        background: #f8fafc; color: #64748b;
        border-color: #e9eef6; cursor: not-allowed;
    }

    @media (max-width: 991.98px) {
        .profile-layout { grid-template-columns: 1fr; }
        .profile-avatar-card { position: static; }
    }

    @media (max-width: 767.98px) {
        .staff-topbar { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Avatar live preview
        const fileInput = document.getElementById('profile_image');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarInitials = document.getElementById('avatarInitials');

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

        // Password visibility toggles
        const passwordInput = document.getElementById('passwordInput');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        if (togglePassword) {
            togglePassword.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePasswordIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }

        const confirmInput = document.getElementById('confirmPassword');
        const toggleConfirm = document.getElementById('toggleConfirm');
        const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
        if (toggleConfirm) {
            toggleConfirm.addEventListener('click', () => {
                const isHidden = confirmInput.type === 'password';
                confirmInput.type = isHidden ? 'text' : 'password';
                toggleConfirmIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }

        // Real-time password matching feedback
        const matchMsg = document.getElementById('passwordMatchMsg');
        function checkMatch() {
            const p = passwordInput.value;
            const c = confirmInput.value;
            if (!p && !c) { matchMsg.textContent = ''; return; }
            if (p === c) {
                matchMsg.textContent = '✓ Passwords match';
                matchMsg.style.color = '#22a959';
            } else {
                matchMsg.textContent = '✗ Passwords do not match';
                matchMsg.style.color = '#e53e3e';
            }
        }
        if (confirmInput) {
            confirmInput.addEventListener('input', checkMatch);
            passwordInput.addEventListener('input', checkMatch);
        }
    });
</script>
@endpush
