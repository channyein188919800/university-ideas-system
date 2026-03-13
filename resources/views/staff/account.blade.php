@php($hideNavFooter = true)
@extends('layouts.app')

@section('title', 'Edit Account Info - University Ideas System')

@section('content')
<div class="admin-shell">
    @include('staff.partials.sidebar')

    <section class="admin-main">
        <div class="staff-topbar">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-gear"></i> Edit Account Info</h3>
                <p class="text-muted mb-0">Update your profile details and password.</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-badge"></i> Account Details
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('staff.account.update') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">New Password (optional)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Profile Image (optional)</label>
                        <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                        @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Max size 2MB. JPG, PNG, or GIF.</small>
                    </div>

                    <div class="col-12 d-flex align-items-center gap-3">
                        @if($user->profile_image_url)
                            <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="profile-avatar" style="width:52px;height:52px;">
                        @else
                            <span class="profile-avatar-fallback" style="width:52px;height:52px;">
                                <i class="bi bi-person-fill"></i>
                            </span>
                        @endif
                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .admin-brand h5 {
        color: #f4f7ff;
        font-weight: 700;
    }

    .admin-brand small {
        color: #98aed6;
    }

    .admin-brand-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.13);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #8ab5ff;
    }

    .admin-nav-title {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #8fa6cf;
        margin-bottom: 0.55rem;
        padding: 0 0.7rem;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.72rem 0.78rem;
        border-radius: 10px;
        color: #d5e1f5;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 0.3rem;
    }

    .admin-nav-link i {
        width: 18px;
        text-align: center;
    }

    .admin-nav-link:hover,
    .admin-nav-link.active {
        background: rgba(122, 164, 255, 0.18);
        color: #fff;
    }

    .admin-main {
        flex: 1;
        padding: 1.5rem;
        overflow-x: hidden;
        margin-left: 280px;
    }

    .staff-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.2rem;
    }

    .admin-menu-toggle {
        position: fixed;
        top: 84px;
        left: 14px;
        z-index: 1200;
        background: #0f1f3a;
        color: #fff;
        border: 0;
        border-radius: 10px;
        width: 42px;
        height: 42px;
        box-shadow: 0 10px 26px rgba(12, 25, 52, 0.3);
    }

    .admin-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(6, 12, 26, 0.45);
        z-index: 1090;
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            transform: translateX(-105%);
            height: 100vh;
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }

        .admin-main {
            width: 100%;
            padding-top: 2.2rem;
            margin-left: 0;
        }

        .staff-topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .admin-backdrop.open {
            display: block;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('adminMenuToggle');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');

        if (!toggleButton || !sidebar || !backdrop) {
            return;
        }

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        });

        backdrop.addEventListener('click', () => {
            sidebar.classList.remove('open');
            backdrop.classList.remove('open');
        });
    });
</script>
@endpush
