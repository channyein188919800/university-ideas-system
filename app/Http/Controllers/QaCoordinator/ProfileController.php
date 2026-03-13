<?php

namespace App\Http\Controllers\QaCoordinator;

use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('qa-coordinator.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        AuditLogger::log(
            'QA_COORDINATOR_UPDATE_PROFILE',
            "QA Coordinator updated own profile ({$user->email}).",
            $user
        );

        return redirect()->route('qa-coordinator.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
