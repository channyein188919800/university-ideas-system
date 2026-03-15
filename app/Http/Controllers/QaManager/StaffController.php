<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;


class StaffController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $staff = User::query()
            ->with('department')
            ->whereIn('role', ['staff', 'qa_coordinator'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('qa-manager.staff.index', compact('staff', 'search'));
    }

    public function create()
    {
        $departments = Department::active()->orderBy('name')->get();
        $roles = ['staff', 'qa_coordinator'];

        return view('qa-manager.staff.create', compact('departments', 'roles'));
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

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AuditLogger::log(
            'QA_MANAGER_CREATE_STAFF',
            "QA Manager created {$user->role} account {$user->name} ({$user->email}).",
            $user
        );

        return redirect()->route('qa-manager.staff.index')->with('success', 'Staff account created successfully.');
    }

    public function edit(User $staff)
    {
        abort_if(!in_array($staff->role, ['staff', 'qa_coordinator'], true), 404);

        $departments = Department::active()->orderBy('name')->get();
        $roles = ['staff', 'qa_coordinator'];

        return view('qa-manager.staff.edit', compact('staff', 'departments', 'roles'));
    }

    public function update(Request $request, User $staff)
    {
        abort_if(!in_array($staff->role, ['staff', 'qa_coordinator'], true), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'role' => 'required|in:staff,qa_coordinator',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:active,disabled',
            'password' => 'nullable|string|min:8',
        ]);

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        AuditLogger::log(
            'QA_MANAGER_UPDATE_STAFF',
            "QA Manager updated account {$staff->name} ({$staff->email}).",
            $staff
        );

        return redirect()->route('qa-manager.staff.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $staff)
    {
        abort_if(!in_array($staff->role, ['staff', 'qa_coordinator'], true), 404);

        $name = $staff->name;
        $email = $staff->email;
        $staff->delete();

        AuditLogger::log(
            'QA_MANAGER_DELETE_STAFF',
            "QA Manager deleted staff account {$name} ({$email})."
        );

        return redirect()->route('qa-manager.staff.index')->with('success', 'Staff account deleted successfully.');
    }
}
