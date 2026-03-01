<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('qaCoordinator')->paginate(20);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $qaCoordinators = User::where('role', 'qa_coordinator')->get();
        return view('admin.departments.create', compact('qaCoordinators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string',
            'qa_coordinator_id' => 'nullable|exists:users,id',
        ]);

        $department = Department::create($validated);

        AuditLogger::log(
            'CREATE_DEPARTMENT',
            "Created department {$department->name} ({$department->code}).",
            $department
        );

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully!');
    }

    public function edit(Department $department)
    {
        $qaCoordinators = User::where('role', 'qa_coordinator')->get();
        return view('admin.departments.edit', compact('department', 'qaCoordinators'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'qa_coordinator_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $oldCode = $department->code;
        $oldStatus = $department->is_active ? 'active' : 'inactive';

        $department->update($validated);

        AuditLogger::log(
            'UPDATE_DEPARTMENT',
            "Updated department {$department->name}. Code: {$oldCode} -> {$department->code}, Status: {$oldStatus} -> " .
                ($department->is_active ? 'active' : 'inactive') . '.',
            $department
        );

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            AuditLogger::log(
                'DELETE_DEPARTMENT',
                "Attempted to delete department {$department->name} ({$department->code}) with existing users, action blocked.",
                $department,
                'warning'
            );
            return redirect()->back()->with('error', 'Cannot delete department with existing users.');
        }

        $departmentName = $department->name;
        $departmentCode = $department->code;
        $department->delete();

        AuditLogger::log(
            'DELETE_DEPARTMENT',
            "Deleted department {$departmentName} ({$departmentCode})."
        );

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully!');
    }
}
