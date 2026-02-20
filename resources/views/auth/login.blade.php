@extends('layouts.app')

@section('title', 'Login - University Ideas System')

@push('styles')
<style>
    /* ── Login Page Overrides ── */
    .navbar, footer { display: none !important; }

    body.login-page {
        background: #fff;
        min-height: 100vh;
        margin: 0;
    }

    .login-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* ── Left Panel (Form) ── */
    .login-form-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 3rem 4rem;
        max-width: 560px;
    }

    .login-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
    }

    .login-brand i {
        font-size: 1.6rem;
        color: #d69e2e;
    }

    .login-brand span {
        font-weight: 700;
        font-size: 1.15rem;
        color: #1e3a5f;
    }

    .login-heading {
        font-family: 'Inter', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 0.35rem;
    }

    .login-subtext {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    /* ── Form Fields ── */
    .login-label {
        font-weight: 600;
        font-size: 0.92rem;
        color: #111;
        margin-bottom: 0.4rem;
        display: block;
    }

    .login-input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.6rem;
        padding: 0.7rem 0.9rem;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #fff;
        color: #111;
    }

    .login-input::placeholder {
        color: #9ca3af;
    }

    .login-input:focus {
        outline: none;
        border-color: #3b6b35;
        box-shadow: 0 0 0 3px rgba(59, 107, 53, 0.10);
    }

    .login-input.is-invalid {
        border-color: #e53e3e;
    }

    .field-group {
        margin-bottom: 1.25rem;
    }

    /* ── Password wrapper ── */
    .password-wrapper {
        position: relative;
    }

    .password-wrapper .login-input {
        padding-right: 2.8rem;
    }

    .toggle-password {
        position: absolute;
        right: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        font-size: 1.05rem;
        padding: 0;
        line-height: 1;
    }

    .toggle-password:hover {
        color: #111;
    }

    /* ── Remember & Terms ── */
    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        font-size: 0.88rem;
    }

    .login-options label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #374151;
        cursor: pointer;
    }

    .login-options a {
        color: #3b6b35;
        text-decoration: underline;
        font-weight: 500;
    }

    /* ── Sign In Button ── */
    .login-btn {
        width: 100%;
        padding: 0.78rem;
        border: none;
        border-radius: 0.6rem;
        background: #3b6b35;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        letter-spacing: 0.02em;
    }

    .login-btn:hover {
        background: #2f5a2a;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(59, 107, 53, 0.25);
    }

    .login-btn:active {
        transform: translateY(0);
    }

    /* ── Divider ── */
    .login-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1.5rem 0;
        color: #9ca3af;
        font-size: 0.85rem;
    }

    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    /* ── Social Buttons ── */
    .social-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .social-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.68rem 1rem;
        border: 1.5px solid #d1d5db;
        border-radius: 2rem;
        background: #fff;
        color: #374151;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.15s;
        text-decoration: none;
    }

    .social-btn:hover {
        border-color: #9ca3af;
        background: #f9fafb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        color: #374151;
        text-decoration: none;
    }

    .social-btn svg,
    .social-btn i {
        font-size: 1.15rem;
    }

    .google-icon {
        width: 18px;
        height: 18px;
    }

    /* ── Footer Link ── */
    .login-footer-text {
        text-align: center;
        margin-top: 1.75rem;
        color: #6b7280;
        font-size: 0.88rem;
    }

    .login-footer-text a {
        color: #3b6b35;
        font-weight: 600;
        text-decoration: none;
    }

    .login-footer-text a:hover {
        text-decoration: underline;
    }

    /* ── Right Panel (Image) ── */
    .login-image-panel {
        flex: 1;
        position: relative;
        overflow: hidden;
        display: flex;
    }

    .login-image-panel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .login-image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(15, 23, 42, 0.50) 0%,
            rgba(15, 23, 42, 0.25) 100%
        );
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 2.5rem;
    }

    .login-image-overlay h2 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }

    .login-image-overlay p {
        color: rgba(255,255,255,0.85);
        margin-bottom: 1rem;
        font-size: 1rem;
        max-width: 380px;
        text-shadow: 0 1px 8px rgba(0,0,0,0.2);
    }

    /* ── Responsive ── */
    @media (max-width: 991.98px) {
        .login-image-panel {
            display: none;
        }

        .login-form-panel {
            max-width: 100%;
            padding: 2rem 1.5rem;
        }

        .login-wrapper {
            justify-content: center;
        }
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {
        .login-form-panel {
            padding: 2.5rem 3rem;
        }
    }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    {{-- ─── Left Panel: Form ─── --}}
    <div class="login-form-panel">
        {{-- Brand --}}
        <div class="login-brand">
            <i class="fas fa-lightbulb"></i>
            <span>University Ideas</span>
        </div>

        <h1 class="login-heading">Welcome Back</h1>
        <p class="login-subtext">Sign in to your account to continue</p>

        {{-- Alert Messages --}}
        @if(session('status'))
            <div class="alert alert-success mb-3" style="border-radius:0.6rem">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div class="field-group">
                <label for="email" class="login-label">Email address</label>
                <input type="email"
                       class="login-input @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="Enter your email">
                @error('email')
                    <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field-group">
                <label for="password" class="login-label">Password</label>
                <div class="password-wrapper">
                    <input type="password"
                           class="login-input @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           required
                           placeholder="Enter your password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="login-options">
                <label>
                    <input type="checkbox" name="remember" id="remember"> Remember me
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="login-btn" id="signInButton">
                Sign In
            </button>
        </form>

        {{-- Divider --}}
        <div class="login-divider">Or</div>

        {{-- Social Login Buttons --}}
        <div class="social-buttons">
            <a href="#" class="social-btn" id="googleSignIn">
                {{-- Google multicolor SVG icon --}}
                <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign in with Google
            </a>
            <a href="#" class="social-btn" id="appleSignIn">
                <i class="fab fa-apple" style="font-size:1.25rem;color:#000"></i>
                Sign in with Apple
            </a>
        </div>

        {{-- Footer --}}
        <div class="login-footer-text">
            <i class="fas fa-info-circle" style="font-size:0.8rem"></i>
            Contact your administrator if you need access.
        </div>
    </div>

    {{-- ─── Right Panel: Image ─── --}}
    <div class="login-image-panel">
        <img src="https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750"
             alt="University Campus"
             loading="eager">
        <div class="login-image-overlay">
            <h2>Share Your Ideas</h2>
            <p>Help improve our university by submitting your innovative ideas and feedback.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add body class for login page
    document.body.classList.add('login-page');

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endpush
