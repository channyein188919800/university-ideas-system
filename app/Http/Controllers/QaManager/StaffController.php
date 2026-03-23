<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->withCount(['ideas', 'comments'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("CASE WHEN status = 'disabled' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('qa-manager.staff.index', compact('staff', 'search'));
    }

    public function toggleStatus(User $user)
    {
        if (in_array($user->role, ['admin', 'qa_manager'], true)) {
            return redirect()->back()->with('error', 'You cannot moderate admin or QA Manager accounts.');
        }

        DB::transaction(function () use ($user) {
            $disableUser = $user->status !== 'disabled';

            $user->update([
                'status' => $disableUser ? 'disabled' : 'active',
            ]);

            Idea::where('user_id', $user->id)->update([
                'hidden' => $disableUser,
            ]);

            Comment::where('user_id', $user->id)->update([
                'hidden' => $disableUser,
            ]);

            $ideaIds = Comment::where('user_id', $user->id)->pluck('idea_id')->unique()->all();
            if (!empty($ideaIds)) {
                Idea::whereIn('id', $ideaIds)->get()->each->updateCommentsCount();
            }

            AuditLogger::log(
                $disableUser ? 'DISABLE_USER' : 'RESTORE_USER',
                ($disableUser ? 'Disabled' : 'Restored') . " user #{$user->id} ({$user->email}) and " .
                    ($disableUser ? 'hid' : 'restored') . " related ideas/comments.",
                $user
            );
        });

        return redirect()->back()->with(
            'success',
            $user->status === 'disabled'
                ? 'User disabled and their ideas/comments are now hidden.'
                : 'User restored and their ideas/comments are now visible again.'
        );
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
            'role'         => 'required|in:staff,qa_coordinator',
            'department_id'=> 'nullable|exists:departments,id',
            'profile_image'=> 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

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

        return view('qa-manager.staff.edit', compact('staff', 'departments'));
    }

    public function update(Request $request, User $staff)
    {
        abort_if(!in_array($staff->role, ['staff', 'qa_coordinator'], true), 404);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $staff->id,
            'role'         => 'required|in:staff,qa_coordinator',
            'department_id'=> 'nullable|exists:departments,id',
            'profile_image'=> 'nullable|image|max:2048',
            'password'     => [
                'nullable',
                Password::min(8)
                    ->mixedCase()
                    ->symbols()
                    ->numbers(),
            ],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // Keep QA Coordinator department locked from this screen (matches admin edit behavior).
        if (($validated['role'] ?? $staff->role) === 'qa_coordinator') {
            $validated['department_id'] = $staff->department_id;
        }

        if ($request->boolean('remove_profile_image') && !$request->hasFile('profile_image')) {
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $validated['profile_image'] = null;
        }

        if ($request->hasFile('profile_image')) {
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $oldRole = $staff->role;
        $oldDepartment = $staff->department_id;

        $staff->update($validated);

        AuditLogger::log(
            'QA_MANAGER_UPDATE_STAFF',
            "QA Manager updated account {$staff->name} ({$staff->email}). Role: {$oldRole} -> {$staff->role}, Department: " .
                (($oldDepartment ?? 'none') . ' -> ' . ($staff->department_id ?? 'none')),
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
