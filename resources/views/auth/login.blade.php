@extends('layouts.app')

@section('title', 'Login - University Ideas System')

@push('styles')
<style>
    .navbar,
    footer {
        display: none !important;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body.login-page {
        min-height: 100vh;
        overflow-x: hidden;
        background: linear-gradient(135deg, #0c1222 0%, #1a2744 50%, #0f172a 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Animated Background Particles */
    .particles-container {
        position: fixed;
        inset: 0;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(214, 158, 46, 0.6);
        border-radius: 50%;
        animation: float-particle 15s infinite ease-in-out;
    }

    .particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; animation-duration: 12s; }
    .particle:nth-child(2) { left: 20%; top: 80%; animation-delay: 2s; animation-duration: 14s; }
    .particle:nth-child(3) { left: 60%; top: 40%; animation-delay: 4s; animation-duration: 16s; }
    .particle:nth-child(4) { left: 80%; top: 60%; animation-delay: 1s; animation-duration: 13s; }
    .particle:nth-child(5) { left: 90%; top: 10%; animation-delay: 3s; animation-duration: 15s; }
    .particle:nth-child(6) { left: 30%; top: 50%; animation-delay: 5s; animation-duration: 11s; }
    .particle:nth-child(7) { left: 50%; top: 70%; animation-delay: 2.5s; animation-duration: 14s; }
    .particle:nth-child(8) { left: 70%; top: 30%; animation-delay: 1.5s; animation-duration: 12s; }
    .particle:nth-child(9) { left: 15%; top: 90%; animation-delay: 3.5s; animation-duration: 17s; }
    .particle:nth-child(10) { left: 85%; top: 85%; animation-delay: 0.5s; animation-duration: 13s; }

    /* Glowing Orbs */
    .glow-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.4;
        animation: pulse-glow 8s ease-in-out infinite alternate;
        pointer-events: none;
        z-index: 0;
    }

    .glow-orb.primary {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(214, 158, 46, 0.5), transparent 70%);
        top: -150px;
        left: -100px;
        animation-delay: 0s;
    }

    .glow-orb.secondary {
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(56, 161, 105, 0.4), transparent 70%);
        bottom: -100px;
        right: -50px;
        animation-delay: 4s;
    }

    .glow-orb.accent {
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.3), transparent 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation-delay: 2s;
    }

    /* Page Loader */
    .page-loader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0c1222 0%, #1a2744 100%);
        transition: opacity 0.6s ease, visibility 0.6s ease;
    }

    .page-loader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .loader-content {
        text-align: center;
    }

    .loader-ring {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.1);
        border-top-color: #d69e2e;
        border-right-color: #d69e2e;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
        position: relative;
    }

    .loader-ring::before {
        content: '';
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 2px solid rgba(214, 158, 46, 0.2);
        animation: spin 2s linear infinite reverse;
    }

    .loader-text {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        animation: fade-pulse 1.5s ease-in-out infinite;
    }

    /* Main Container */
    .login-stage {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        z-index: 1;
    }

    /* Login Card */
    .login-card {
        display: flex;
        width: 100%;
        max-width: 1100px;
        min-height: 650px;
        border-radius: 2rem;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 
            0 32px 64px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        animation: card-entrance 1s ease-out forwards;
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }

    /* Brand Section */
    .login-brand {
        flex: 1;
        padding: 3.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.7) 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        position: relative;
        overflow: hidden;
    }

    .login-brand::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 30%, rgba(214, 158, 46, 0.1) 0%, transparent 50%);
        animation: rotate-gradient 20s linear infinite;
    }

    .logo-container {
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
        animation: float-logo 6s ease-in-out infinite;
    }

    .logo-space {
        width: 260px;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .logo-space img {
        width: 220px;
        height: auto;
        max-height: 140px;
        object-fit: contain;
        filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.5));
        animation: logo-glow 3s ease-in-out infinite alternate;
    }

    .logo-ring {
        position: absolute;
        inset: -20px;
        border: 2px solid rgba(214, 158, 46, 0.2);
        border-radius: 50%;
        animation: spin-slow 20s linear infinite;
    }

    .logo-ring::before {
        content: '';
        position: absolute;
        top: -4px;
        left: 50%;
        width: 8px;
        height: 8px;
        background: #d69e2e;
        border-radius: 50%;
        box-shadow: 0 0 20px rgba(214, 158, 46, 0.8);
    }

    .brand-title {
        position: relative;
        z-index: 1;
        font-size: 2.2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: fade-slide-up 0.8s ease-out 0.3s forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .brand-copy {
        position: relative;
        z-index: 1;
        font-size: 1rem;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.6);
        max-width: 320px;
        animation: fade-slide-up 0.8s ease-out 0.5s forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    /* Form Section */
    .login-form-wrap {
        flex: 1;
        padding: 3.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(241, 245, 249, 0.98) 100%);
        position: relative;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2.5rem;
        animation: fade-slide-up 0.8s ease-out 0.2s forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .login-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .login-subtitle {
        color: #64748b;
        font-size: 0.95rem;
    }

    /* Form Fields */
    .field-block {
        margin-bottom: 1.5rem;
        animation: fade-slide-up 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(15px);
    }

    .field-block:nth-child(1) { animation-delay: 0.4s; }
    .field-block:nth-child(2) { animation-delay: 0.5s; }

    .field-label {
        display: block;
        margin-bottom: 0.6rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .field-shell {
        position: relative;
    }

    .field-shell > .bi {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
        transition: color 0.3s ease;
        z-index: 2;
    }

    .login-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        font-size: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        color: #0f172a;
        transition: all 0.3s ease;
        outline: none;
    }

    .login-input::placeholder {
        color: #94a3b8;
    }

    .login-input:focus {
        border-color: #d69e2e;
        box-shadow: 0 0 0 4px rgba(214, 158, 46, 0.1);
        transform: translateY(-2px);
    }

    .login-input:focus + .bi,
    .field-shell:focus-within > .bi {
        color: #d69e2e;
    }

    /* Input Highlight Effect */
    .input-highlight {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #d69e2e, transparent);
        transition: all 0.4s ease;
        transform: translateX(-50%);
    }

    .login-input:focus ~ .input-highlight {
        width: 100%;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.5rem;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .password-toggle:hover {
        color: #d69e2e;
        transform: translateY(-50%) scale(1.1);
    }

    /* Checkbox & Links Row */
    .login-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        animation: fade-slide-up 0.6s ease-out 0.6s forwards;
        opacity: 0;
        transform: translateY(15px);
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        cursor: pointer;
        font-size: 0.9rem;
        color: #475569;
    }

    .checkbox-container input[type="checkbox"] {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .checkbox-container:hover .checkmark {
        border-color: #d69e2e;
    }

    .checkbox-container input:checked + .checkmark {
        background: linear-gradient(135deg, #d69e2e, #b7791f);
        border-color: #d69e2e;
    }

    .checkbox-container input:checked + .checkmark::after {
        content: '✓';
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .hint-link {
        color: #0f172a;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        position: relative;
        transition: color 0.3s ease;
    }

    .hint-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #d69e2e, #38a169);
        transition: width 0.3s ease;
    }

    .hint-link:hover {
        color: #d69e2e;
    }

    .hint-link:hover::after {
        width: 100%;
    }

    /* Login Button */
    .login-btn {
        width: 100%;
        padding: 1.1rem 2rem;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
        background-size: 200% 200%;
        border: none;
        border-radius: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
        animation: fade-slide-up 0.6s ease-out 0.7s forwards, gradient-shift 3s ease infinite;
        opacity: 0;
        transform: translateY(15px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.3);
    }

    .login-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.2) 50%, transparent 100%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .login-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(15, 23, 42, 0.4);
        background-position: 100% 0;
    }

    .login-btn:hover::before {
        transform: translateX(100%);
    }

    .login-btn:active {
        transform: translateY(-1px);
    }

    .btn-icon {
        margin-right: 0.5rem;
        transition: transform 0.3s ease;
    }

    .login-btn:hover .btn-icon {
        transform: translateX(4px);
    }

    /* Form Note */
    .form-note {
        margin-top: 2rem;
        text-align: center;
        color: #94a3b8;
        font-size: 0.85rem;
        animation: fade-slide-up 0.6s ease-out 0.8s forwards;
        opacity: 0;
        transform: translateY(15px);
    }

    /* Error Messages */
    .field-error {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #ef4444;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        animation: shake 0.4s ease;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(56, 161, 105, 0.1), rgba(56, 161, 105, 0.05));
        border: 1px solid rgba(56, 161, 105, 0.3);
        color: #2f855a;
        padding: 1rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        animation: fade-slide-up 0.5s ease-out;
    }

    /* Keyframe Animations */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @keyframes spin-slow {
        to { transform: rotate(360deg); }
    }

    @keyframes float-particle {
        0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.6;
        }
        25% {
            transform: translateY(-100px) translateX(50px);
            opacity: 1;
        }
        50% {
            transform: translateY(-200px) translateX(-30px);
            opacity: 0.4;
        }
        75% {
            transform: translateY(-150px) translateX(80px);
            opacity: 0.8;
        }
    }

    @keyframes pulse-glow {
        0% { opacity: 0.3; transform: scale(1); }
        100% { opacity: 0.5; transform: scale(1.1); }
    }

    @keyframes rotate-gradient {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes card-entrance {
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes fade-slide-up {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float-logo {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes logo-glow {
        0% { filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.5)); }
        100% { filter: drop-shadow(0 20px 40px rgba(214, 158, 46, 0.3)); }
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes fade-pulse {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 1; }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .login-card {
            flex-direction: column;
            max-width: 500px;
        }

        .login-brand {
            padding: 2.5rem 2rem;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo-space {
            width: 200px;
            height: 140px;
        }

        .logo-space img {
            width: 180px;
        }

        .brand-title {
            font-size: 1.8rem;
        }

        .login-form-wrap {
            padding: 2.5rem 2rem;
        }

        .glow-orb.primary {
            width: 300px;
            height: 300px;
        }

        .glow-orb.secondary {
            width: 250px;
            height: 250px;
        }
    }

    @media (max-width: 576px) {
        .login-stage {
            padding: 1rem;
        }

        .login-brand {
            padding: 2rem 1.5rem;
        }

        .login-form-wrap {
            padding: 2rem 1.5rem;
        }

        .login-title {
            font-size: 1.8rem;
        }

        .brand-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Loader -->
<div id="pageLoader" class="page-loader">
    <div class="loader-content">
        <div class="loader-ring"></div>
        <div class="loader-text">Loading</div>
    </div>
</div>

<!-- Animated Background -->
<div class="particles-container">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<div class="glow-orb primary"></div>
<div class="glow-orb secondary"></div>
<div class="glow-orb accent"></div>

<!-- Login Stage -->
<section class="login-stage">
    <div class="login-card">
        <!-- Brand Section -->
        <aside class="login-brand">
            <div class="logo-container">
                <div class="logo-ring"></div>
                <div class="logo-space">
                    <img src="{{ asset('images/logo.png') }}" alt="University Logo">
                </div>
            </div>
            <h2 class="brand-title">University Ideas</h2>
            <p class="brand-copy">
                Keep ideas flowing across departments with one secure and simple login experience.
            </p>
        </aside>

        <!-- Form Section -->
        <div class="login-form-wrap">
            <div class="form-header">
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to continue your submission journey</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="field-block">
                    <label for="email" class="field-label">Email Address</label>
                    <div class="field-shell">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="login-input @error('email') is-invalid @enderror"
                            placeholder="name@university.edu"
                            autocomplete="email"
                            required
                            autofocus>
                        <i class="bi bi-envelope-fill"></i>
                        <div class="input-highlight"></div>
                    </div>
                    @error('email')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-block">
                    <label for="password" class="field-label">Password</label>
                    <div class="field-shell">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="login-input @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required>
                        <i class="bi bi-shield-lock-fill"></i>
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                        </button>
                        <div class="input-highlight"></div>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="login-row">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember" name="remember">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                    <a href="#" class="hint-link">Forgot password?</a>
                </div>

                <button type="submit" id="loginButton" class="login-btn">
                    <i class="bi bi-box-arrow-in-right btn-icon"></i>
                    Sign In
                </button>

                <div class="form-note">
                    <i class="bi bi-shield-check me-1"></i>
                    Access is provided by your university administrator.
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.body.classList.add('login-page');

    // Page Loader
    const pageLoader = document.getElementById('pageLoader');
    window.addEventListener('load', function() {
        setTimeout(() => {
            pageLoader.classList.add('hidden');
        }, 800);
    });

    // Password Toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    togglePassword.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        togglePasswordIcon.className = isPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        
        // Add animation
        this.style.transform = 'translateY(-50%) scale(1.2)';
        setTimeout(() => {
            this.style.transform = 'translateY(-50%) scale(1)';
        }, 150);
    });

    // Input Focus Animation
    const inputs = document.querySelectorAll('.login-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Button Ripple Effect
    const loginBtn = document.getElementById('loginButton');
    loginBtn.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });

    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Form submission loading state
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginButton');
        btn.innerHTML = '<i class="bi bi-arrow-repeat spin-icon"></i> Signing in...';
        btn.style.pointerEvents = 'none';
        
        const spinStyle = document.createElement('style');
        spinStyle.textContent = `.spin-icon { animation: spin 1s linear infinite; }`;
        document.head.appendChild(spinStyle);
    });
</script>
@endpush