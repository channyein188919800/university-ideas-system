<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
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

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            AuditLogger::log(
                'LOGIN_SUCCESS',
                "User {$request->email} logged in successfully."
            );

            return redirect()->intended($this->redirectPath());
        }

        AuditLogger::log(
            'LOGIN_FAILED',
            "Failed login attempt for {$request->email}.",
            null,
            'failed'
        );

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
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
