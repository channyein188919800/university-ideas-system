<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('department')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        $roles = ['admin', 'qa_manager', 'qa_coordinator', 'staff'];
        return view('admin.users.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->symbols()
                    ->numbers(),
            ],
            'role'         => 'required|in:admin,qa_manager,qa_coordinator,staff',
            'department_id'=> 'nullable|exists:departments,id',
            'profile_image'=> 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AuditLogger::log(
            'CREATE_USER',
            "Created user {$user->name} ({$user->email}) with role {$user->role}.",
            $user
        );

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $departments = Department::active()->get();
        $roles = ['admin', 'qa_manager', 'qa_coordinator', 'staff'];
        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|in:admin,qa_manager,qa_coordinator,staff',
            'department_id'=> 'nullable|exists:departments,id',
            'profile_image'=> 'nullable|image|max:2048',
        ]);

        if (in_array($validated['role'], ['admin', 'qa_manager'])) {
            $validated['department_id'] = null;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $oldRole       = $user->role;
        $oldDepartment = $user->department_id;

        $user->update($validated);

        AuditLogger::log(
            'UPDATE_USER',
            "Updated user {$user->name} ({$user->email}). Role: {$oldRole} -> {$user->role}, Department: " .
                (($oldDepartment ?? 'none') . ' -> ' . ($user->department_id ?? 'none')),
            $user
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            AuditLogger::log(
                'DELETE_USER',
                'Attempted to delete own account, action blocked.',
                $user,
                'warning'
            );
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $deletedUserName  = $user->name;
        $deletedUserEmail = $user->email;

        $user->delete();

        AuditLogger::log(
            'DELETE_USER',
            "Deleted user {$deletedUserName} ({$deletedUserEmail})."
        );

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
