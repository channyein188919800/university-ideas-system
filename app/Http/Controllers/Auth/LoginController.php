<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Maximum number of login attempts allowed
     */
    protected $maxAttempts = 2;

    /**
     * Lockout time in minutes
     */
    protected $decayMinutes = 1;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check for too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );

            AuditLogger::log(
                'LOGIN_LOCKOUT',
                "Too many login attempts for {$request->email}. Locked out for {$seconds} seconds.",
                null,
                'warning'
            );

            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.'],
            ])->status(429);
        }

        $remember = $request->boolean('remember');
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $this->incrementLoginAttempts($request);
            
            AuditLogger::log(
                'LOGIN_FAILED',
                "Failed login attempt for {$request->email} - User not found.",
                null,
                'failed'
            );

            throw ValidationException::withMessages([
                'email' => 'Wrong email.',
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            $this->incrementLoginAttempts($request);
            
            AuditLogger::log(
                'LOGIN_FAILED',
                "Failed login attempt for {$request->email} - Wrong password.",
                null,
                'failed'
            );

            throw ValidationException::withMessages([
                'password' => 'Wrong password.',
            ]);
        }

        // Clear login attempts on successful login
        $this->clearLoginAttempts($request);

        Auth::login($user, $remember);
        
        if (Auth::check()) {
            $request->session()->regenerate();

            $user = Auth::user();
            $previousLoginAt = $user?->last_login_at;
            $user?->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ])->save();

            if ($user) {
                $request->session()->flash(
                    'login_notice',
                    $previousLoginAt
                        ? 'Last login: ' . $previousLoginAt->format('M d, Y h:i A')
                        : 'Welcome! This appears to be your first login.'
                );
            }

            AuditLogger::log(
                'LOGIN_SUCCESS',
                "User {$request->email} logged in successfully."
            );

            return redirect()->intended($this->redirectPath());
        }

        $this->incrementLoginAttempts($request);

        AuditLogger::log(
            'LOGIN_FAILED',
            "Failed login attempt for {$request->email} - Authentication failed.",
            null,
            'failed'
        );

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Check if the user has too many login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts
        );
    }

    /**
     * Increment the login attempts for the user.
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes * 60
        );
    }

    /**
     * Clear the login attempts for the user.
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     */
    protected function fireLockoutEvent(Request $request)
    {
        // You can create and dispatch a Lockout event here if needed
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Get the rate limiter instance.
     */
    protected function limiter()
    {
        return app(\Illuminate\Cache\RateLimiter::class);
    }

    public function logout(Request $request)
    {
        $name = Auth::user()?->name;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        AuditLogger::log(
            'LOGOUT',
            ($name ? "User {$name}" : 'User') . ' logged out.'
        );

        return redirect('/');
    }

    protected function redirectPath()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return route('admin.dashboard');
            case 'qa_manager':
                return route('qa-manager.dashboard');
            case 'qa_coordinator':
                return route('qa-coordinator.dashboard');
            default:
                return route('staff.dashboard');
        }
    }
}