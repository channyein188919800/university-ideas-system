<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Idea;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaApprovalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $departmentId = $request->input('department_id', '');
        $categoryId = $request->input('category_id', '');
        $status = $request->input('status', 'all');


        $query = Idea::with(['user', 'department', 'categories']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        if (!empty($categoryId)) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $ideas = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.ideas.approvals', compact(
            'ideas',
            'departments',
            'categories',
            'search',
            'departmentId',
            'categoryId',
            'status'
        ));
    }

    public function approve(Request $request, Idea $idea)
    {
        if (!$idea->user || !$idea->user->isStaff()) {
            return redirect()->back()->with('error', 'Only staff ideas can be approved here.');
        }

        $idea->update(['status' => 'approved']);

        AuditLogger::log(
            'APPROVE_IDEA',
            "Approved idea #{$idea->id}: {$idea->title}.",
            $idea
        );

        return redirect()->back()->with('success');
    }

    public function reject(Request $request, Idea $idea)
    {
        if (!$idea->user || !$idea->user->isStaff()) {
            return redirect()->back()->with('error', 'Only staff ideas can be rejected here.');
        }

        $idea->update(['status' => 'rejected']);

        AuditLogger::log(
            'REJECT_IDEA',
            "Rejected idea #{$idea->id}: {$idea->title}.",
            $idea
        );

        return redirect()->back()->with('success');
    }

    public function show(Idea $idea)
    {
        if (!$idea->user || !$idea->user->isStaff()) {
            return redirect()->back()->with('error', 'Only staff ideas can be viewed here.');
        }

        return view('admin.ideas.details', compact('idea'));
    }
}
