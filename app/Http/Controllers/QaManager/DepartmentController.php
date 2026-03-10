<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('qaCoordinator')->latest()->paginate(15);
        return view('qa-manager.departments.index', compact('departments'));
    }

    public function create()
    {
        $qaCoordinators = User::where('role', 'qa_coordinator')->where('status', 'active')->orderBy('name')->get();
        return view('qa-manager.departments.create', compact('qaCoordinators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
            'qa_coordinator_id' => 'nullable|exists:users,id',
        ]);

        $department = Department::create($validated);

        AuditLogger::log(
            'QA_MANAGER_CREATE_DEPARTMENT',
            "QA Manager created department {$department->name} ({$department->code}).",
            $department
        );

        return redirect()->route('qa-manager.departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $qaCoordinators = User::where('role', 'qa_coordinator')->where('status', 'active')->orderBy('name')->get();
        return view('qa-manager.departments.edit', compact('department', 'qaCoordinators'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'qa_coordinator_id' => 'nullable|exists:users,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $department->update($validated);

        AuditLogger::log(
            'QA_MANAGER_UPDATE_DEPARTMENT',
            "QA Manager updated department {$department->name} ({$department->code}).",
            $department
        );

        return redirect()->route('qa-manager.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete department with users assigned.');
        }

        $name = $department->name;
        $department->delete();

        AuditLogger::log(
            'QA_MANAGER_DELETE_DEPARTMENT',
            "QA Manager deleted department {$name}."
        );

        return redirect()->route('qa-manager.departments.index')->with('success', 'Department deleted successfully.');
    }
}
