@php($hideNavFooter = true)
@extends('layouts.app')

@section('title', 'My Profile - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('staff.partials.sidebar')

    <section class="admin-main">
        <div class="staff-topbar">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-gear"></i> My Profile</h3>
                <p class="text-muted mb-0">Manage your account details, password, and profile photo.</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
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

        <form method="POST" action="{{ route('staff.account.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-layout">

                {{-- Left: Avatar Card --}}
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
                    <div class="avatar-role">
                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $user->role)) }}
                    </div>
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

                {{-- Right: Form Fields --}}
                <div class="profile-fields">

                    {{-- Profile Info Section --}}
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
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Your full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="your@email.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Department</label>
                                    <input type="text" class="form-control" value="{{ $user->department->name ?? 'Not Assigned' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Role</label>
                                    <input type="text" class="form-control"
                                           value="{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $user->role)) }}" disabled>
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
    .admin-shell {
        display: flex;
        min-height: 100vh;
        background: radial-gradient(circle at 0% 0%, #e9f2ff 0%, #f4f7fc 35%, #f8fafc 100%);
        position: relative;
    }

    .admin-sidebar {
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background: linear-gradient(180deg, #0f1f3a 0%, #15294a 100%);
        color: #cdd8ee;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        box-shadow: 0 16px 40px rgba(12, 25, 52, 0.28);
        z-index: 1100;
        transition: transform 0.25s ease;
        overflow-y: auto;
    }

    .admin-brand { display: flex; align-items: center; gap: 0.8rem; }
    .admin-brand h5 { color: #f4f7ff; font-weight: 700; }
    .admin-brand small { color: #98aed6; }

    .admin-profile {
        display: flex; align-items: center; gap: 0.9rem;
        padding: 0.85rem 0.9rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(7,16,33,0.35);
    }
    .admin-profile-avatar {
        width: 50px; height: 50px; border-radius: 50%;
        background: linear-gradient(135deg, rgba(138,181,255,0.25), rgba(255,255,255,0.12));
        border: 2px solid rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        color: #f8fbff; font-weight: 700; font-size: 1.1rem;
        text-transform: uppercase; overflow: hidden;
    }
    .admin-profile-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .admin-profile-meta { display: flex; flex-direction: column; gap: 0.2rem; min-width: 0; }
    .admin-profile-name { color: #f3f7ff; font-weight: 700; font-size: 0.98rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .admin-profile-role { color: #a9c2f0; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; }

    .admin-nav-title {
        font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em;
        color: #8fa6cf; margin-bottom: 0.55rem; padding: 0 0.7rem;
    }
    .admin-nav-link {
        display: flex; align-items: center; gap: 0.7rem;
        padding: 0.72rem 0.78rem; border-radius: 10px;
        color: #d5e1f5; text-decoration: none;
        transition: all 0.2s ease; margin-bottom: 0.3rem;
    }
    .admin-nav-link i { width: 18px; text-align: center; }
    .admin-nav-link:hover, .admin-nav-link.active {
        background: rgba(122, 164, 255, 0.18); color: #fff;
    }

    .admin-main {
        flex: 1; padding: 1.5rem; overflow-x: hidden; margin-left: 280px;
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

    /* Sections */
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

    .admin-menu-toggle {
        position: fixed; top: 14px; left: 14px; z-index: 1200;
        background: #0f1f3a; color: #fff; border: 0;
        border-radius: 10px; width: 42px; height: 42px;
        box-shadow: 0 10px 26px rgba(12,25,52,0.3);
    }

    .admin-backdrop {
        display: none; position: fixed; inset: 0;
        background: rgba(6,12,26,0.45); z-index: 1090;
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            position: fixed; inset: 0 auto 0 0;
            transform: translateX(-105%); height: 100vh;
        }
        .admin-sidebar.open { transform: translateX(0); }
        .admin-main { width: 100%; padding-top: 3.5rem; margin-left: 0; }
        .admin-backdrop.open { display: block; }
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
        // Sidebar toggle
        const toggleButton = document.getElementById('adminMenuToggle');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');
        if (toggleButton && sidebar && backdrop) {
            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('open');
            });
            backdrop.addEventListener('click', () => {
                sidebar.classList.remove('open');
                backdrop.classList.remove('open');
            });
        }

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

        // Password toggle
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

        // Password match indicator
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
