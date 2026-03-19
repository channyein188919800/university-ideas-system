<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('qa-manager.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Password::min(8)->mixedCase()->symbols()]
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->update($validated);

        AuditLogger::log(
            'QA_MANAGER_UPDATE_PROFILE',
            "QA Manager updated own profile ({$user->email}).",
            $user
        );

        return redirect()->route('qa-manager.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
