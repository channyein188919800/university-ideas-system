@extends('layouts.app')

@section('title', 'Login - University Ideas System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center">
                    <i class="fas fa-user-circle fa-3x mb-3"></i>
                    <h4 class="mb-0">Welcome Back</h4>
                    <p class="mb-0 opacity-75">Sign in to your account</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   placeholder="Enter your email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Enter your password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle"></i> 
                            Contact your administrator if you need access.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Demo Accounts Info -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="fas fa-key"></i> Demo Accounts</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger">Admin</span></td>
                                    <td><code>admin@university.ac.uk</code></td>
                                    <td><code>password</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">QA Manager</span></td>
                                    <td><code>qamanager@university.ac.uk</code></td>
                                    <td><code>password</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">QA Coordinator</span></td>
                                    <td><code>qacoordinator@university.ac.uk</code></td>
                                    <td><code>password</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Staff</span></td>
                                    <td><code>staff@university.ac.uk</code></td>
                                    <td><code>password</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
