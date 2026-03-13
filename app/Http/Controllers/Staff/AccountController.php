<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit()
    {
        return view('staff.account', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->symbols()],
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->update($validated);

        AuditLogger::log(
            'UPDATE_SELF',
            "Updated own account info ({$user->email}).",
            $user
        );

        return redirect()->route('staff.account.edit')->with('success', 'Account updated successfully.');
    }
}
