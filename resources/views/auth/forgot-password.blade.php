@extends('layouts.app')

@section('title', 'Forgot Password - University Ideas System')

@push('styles')
<style>
    .navbar,
    footer {
        display: none !important;
    }

    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #0c1222 0%, #1a2744 50%, #0f172a 100%);
    }

    .auth-wrap {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 1.5rem;
    }

    .auth-card {
        width: 100%;
        max-width: 520px;
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.28);
    }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="card auth-card">
        <div class="card-body p-4 p-md-5">
            <h1 class="h4 fw-bold mb-2">Forgot Password</h1>
            <p class="text-muted mb-4">Enter your email and we will send a password reset link.</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@university.edu"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

