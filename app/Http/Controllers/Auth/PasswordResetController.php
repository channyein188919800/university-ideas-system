<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            AuditLogger::log(
                'PASSWORD_RESET_LINK_SENT',
                "Password reset link sent to {$request->email}."
            );
            return back()->with('status', __($status));
        }

        AuditLogger::log(
            'PASSWORD_RESET_LINK_FAILED',
            "Password reset link request failed for {$request->email}.",
            null,
            'failed'
        );

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            AuditLogger::log(
                'PASSWORD_RESET_SUCCESS',
                "Password reset completed for {$request->email}."
            );
            return redirect()->route('login')->with('status', __($status));
        }

        AuditLogger::log(
            'PASSWORD_RESET_FAILED',
            "Password reset failed for {$request->email}.",
            null,
            'failed'
        );

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => [__($status)]]);
    }
}
