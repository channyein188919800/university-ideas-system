@extends('layouts.app')

@section('title', 'Add User - University Ideas System')

@section('content')
<style>
    .user-create-hero {
        position: relative;
        min-height: calc(100vh - 160px);
        padding: 2.5rem 1rem 3rem;
        background-image:
            linear-gradient(135deg, rgba(15, 23, 42, 0.86), rgba(15, 23, 42, 0.58)),
            url('https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .user-create-shell {
        max-width: 920px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: rgba(255, 255, 255, 0.72);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.16);
        background: rgba(255, 255, 255, 0.06);
        border-radius: 999px;
        padding: 0.45rem 1rem;
        margin-bottom: 1.3rem;
        transition: all 0.25s ease;
    }

    .back-link:hover {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.12);
        transform: translateX(-3px);
    }

    .glass-card {
        position: relative;
        border-radius: 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(15, 23, 42, 0.5);
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        padding: 2rem 1.6rem;
        color: #e2e8f0;
        overflow: hidden;
    }

    .glass-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: -130%;
        width: 65%;
        height: 100%;
        background: linear-gradient(110deg, transparent, rgba(255, 255, 255, 0.08), transparent);
        transform: skewX(-15deg);
        animation: card-sweep 6.2s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes card-sweep {
        0%, 30% { left: -130%; }
        65%, 100% { left: 150%; }
    }

    .create-header {
        text-align: center;
        margin-bottom: 1.8rem;
        position: relative;
    }

    .create-header-badge {
        width: 68px;
        height: 68px;
        margin: 0 auto 0.85rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: #fbbf24;
        background: linear-gradient(145deg, rgba(251, 191, 36, 0.25), rgba(251, 191, 36, 0.07));
        border: 1px solid rgba(251, 191, 36, 0.32);
        box-shadow: 0 8px 24px rgba(251, 191, 36, 0.2);
    }

    .create-title {
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
        color: #f8fafc;
    }

    .create-subtitle {
        margin: 0;
        color: rgba(226, 232, 240, 0.76);
        font-size: 0.92rem;
    }

    .field-wrap {
        margin-bottom: 1.15rem;
    }

    .glass-label {
        display: flex;
        align-items: center;
        gap: 0.48rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #dbeafe;
        margin-bottom: 0.45rem;
    }

    .label-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.14);
        background: rgba(255, 255, 255, 0.08);
        font-size: 0.82rem;
    }

    .required-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #f87171;
        margin-left: 0.1rem;
    }

    .glass-input,
    .glass-select {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.13);
        background: rgba(255, 255, 255, 0.07);
        color: #f1f5f9;
        padding: 0.72rem 0.95rem;
        transition: all 0.25s ease;
    }

    .glass-input::placeholder {
        color: rgba(241, 245, 249, 0.45);
    }

    .glass-input:focus,
    .glass-select:focus {
        outline: none;
        border-color: rgba(96, 165, 250, 0.8);
        background: rgba(255, 255, 255, 0.12);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.18);
    }

    .glass-select option {
        background: #1f2937;
        color: #f8fafc;
    }

    .glass-error {
        margin-top: 0.4rem;
        font-size: 0.8rem;
        color: #fca5a5;
        display: flex;
        align-items: center;
        gap: 0.32rem;
    }

    .glass-input.is-invalid,
    .glass-select.is-invalid {
        border-color: rgba(248, 113, 113, 0.6);
    }

    .upload-box {
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 0.9rem;
        background: rgba(255, 255, 255, 0.04);
        padding: 1rem;
        text-align: center;
        position: relative;
        transition: all 0.25s ease;
    }

    .upload-box:hover,
    .upload-box.drag-over {
        border-color: rgba(96, 165, 250, 0.8);
        background: rgba(59, 130, 246, 0.12);
    }

    .upload-box input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 1.7rem;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 0.3rem;
    }

    .upload-title {
        font-size: 0.88rem;
        color: #bfdbfe;
    }

    .upload-sub {
        font-size: 0.78rem;
        color: rgba(226, 232, 240, 0.58);
    }

    .preview-avatar {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.35);
        display: none;
        margin: 0.8rem auto 0;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
    }

    .divider-line {
        height: 1px;
        margin: 1.4rem 0 1.2rem;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
    }

    .btn-glass-outline,
    .btn-glass-solid {
        border-radius: 0.75rem;
        border: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.68rem 1.35rem;
        transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-glass-outline {
        color: rgba(241, 245, 249, 0.86);
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.07);
    }

    .btn-glass-outline:hover {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.35);
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-1px);
    }

    .btn-glass-solid {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.34);
    }

    .btn-glass-solid:hover {
        transform: translateY(-1px);
        box-shadow: 0 11px 26px rgba(37, 99, 235, 0.45);
    }

    .btn-glass-outline:active,
    .btn-glass-solid:active {
        transform: scale(0.97);
    }

    .ripple-container {
        position: relative;
        overflow: hidden;
    }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.32);
        transform: scale(0);
        animation: ripple-expand 0.62s ease-out forwards;
        pointer-events: none;
    }

    @keyframes ripple-expand {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .fade-in-up {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.55s ease, transform 0.55s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-delay-1 { transition-delay: 0.08s; }
    .fade-delay-2 { transition-delay: 0.16s; }
    .fade-delay-3 { transition-delay: 0.24s; }
    .fade-delay-4 { transition-delay: 0.32s; }

    @media (max-width: 767.98px) {
        .glass-card {
            padding: 1.5rem 1rem;
        }

        .create-title {
            font-size: 1.4rem;
        }

        .action-row {
            flex-direction: column;
            gap: 0.7rem;
        }

        .btn-glass-outline,
        .btn-glass-solid {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<section class="user-create-hero">
    <div class="user-create-shell">
        <a href="{{ route('admin.users.index') }}" class="back-link fade-in-up ripple-container">
            <i class="bi bi-arrow-left-short"></i>
            Back to Users
        </a>

        <div class="glass-card fade-in-up fade-delay-1">
            <div class="create-header">
                <div class="create-header-badge">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h1 class="create-title">Create New User</h1>
                <p class="create-subtitle">Add a user account with role, department, and optional profile photo.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 field-wrap fade-in-up fade-delay-1">
                        <label class="glass-label" for="name">
                            <span class="label-icon"><i class="bi bi-person"></i></span>
                            Full Name
                            <span class="required-dot"></span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="glass-input @error('name') is-invalid @enderror"
                               placeholder="Enter full name"
                               required>
                        @error('name')
                            <div class="glass-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 field-wrap fade-in-up fade-delay-1">
                        <label class="glass-label" for="email">
                            <span class="label-icon"><i class="bi bi-envelope"></i></span>
                            Email
                            <span class="required-dot"></span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="glass-input @error('email') is-invalid @enderror"
                               placeholder="name@university.edu"
                               required>
                        @error('email')
                            <div class="glass-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 field-wrap fade-in-up fade-delay-2">
                        <label class="glass-label" for="password">
                            <span class="label-icon"><i class="bi bi-shield-lock"></i></span>
                            Password
                            <span class="required-dot"></span>
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="glass-input @error('password') is-invalid @enderror"
                               placeholder="Minimum 8 characters"
                               required>
                        @error('password')
                            <div class="glass-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 field-wrap fade-in-up fade-delay-2">
                        <label class="glass-label" for="role">
                            <span class="label-icon"><i class="bi bi-person-badge"></i></span>
                            Role
                            <span class="required-dot"></span>
                        </label>
                        <select id="role" name="role" class="glass-select @error('role') is-invalid @enderror" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="glass-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 field-wrap fade-in-up fade-delay-3">
                        <label class="glass-label" for="department_id">
                            <span class="label-icon"><i class="bi bi-building"></i></span>
                            Department
                        </label>
                        <select id="department_id" name="department_id" class="glass-select @error('department_id') is-invalid @enderror">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="glass-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 field-wrap fade-in-up fade-delay-3">
                        <label class="glass-label">
                            <span class="label-icon"><i class="bi bi-image"></i></span>
                            Profile Image
                        </label>
                        <div class="upload-box ripple-container" id="avatarUploadBox">
                            <input type="file"
                                   id="profile_image"
                                   name="profile_image"
                                   accept="image/png,image/jpeg,image/jpg,image/webp">
                            <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="upload-title">Click to upload profile image</div>
                            <div class="upload-sub">JPG, PNG, WEBP • Max 2MB</div>
                        </div>
                        <img id="avatarPreview" class="preview-avatar" alt="Avatar preview">
                        @error('profile_image')
                            <div class="glass-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="divider-line"></div>

                <div class="d-flex justify-content-between align-items-center action-row fade-in-up fade-delay-4">
                    <a href="{{ route('admin.users.index') }}" class="btn-glass-outline ripple-container">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-glass-solid ripple-container">
                        <i class="bi bi-check2-circle"></i>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var animated = document.querySelectorAll('.fade-in-up');

    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        animated.forEach(function (el) { observer.observe(el); });
    } else {
        animated.forEach(function (el) { el.classList.add('visible'); });
    }

    document.querySelectorAll('.ripple-container').forEach(function (el) {
        el.addEventListener('click', function (e) {
            var rect = el.getBoundingClientRect();
            var ripple = document.createElement('span');
            var size = Math.max(rect.width, rect.height);

            ripple.className = 'ripple';
            ripple.style.width = size + 'px';
            ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';

            el.appendChild(ripple);
            ripple.addEventListener('animationend', function () { ripple.remove(); });
        });
    });

    var fileInput = document.getElementById('profile_image');
    var preview = document.getElementById('avatarPreview');
    var uploadBox = document.getElementById('avatarUploadBox');

    if (fileInput && preview) {
        fileInput.addEventListener('change', function () {
            var file = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;

            if (!file) {
                preview.style.display = 'none';
                preview.removeAttribute('src');
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });
    }

    if (uploadBox) {
        ['dragenter', 'dragover'].forEach(function (evt) {
            uploadBox.addEventListener(evt, function (e) {
                e.preventDefault();
                uploadBox.classList.add('drag-over');
            });
        });

        ['dragleave', 'drop'].forEach(function (evt) {
            uploadBox.addEventListener(evt, function (e) {
                e.preventDefault();
                uploadBox.classList.remove('drag-over');
            });
        });
    }
});
</script>
@endsection
