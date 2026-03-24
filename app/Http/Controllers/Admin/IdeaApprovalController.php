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
        // Check if user is staff
        if (!$idea->user || !$idea->user->isStaff()) {
            return redirect()->back()->with('error', 'Only staff ideas can be approved here.');
        }

        // Allow approving pending or rejected ideas
        if ($idea->status !== 'pending' && $idea->status !== 'rejected') {
            return redirect()->back()->with('error', 'Only pending or rejected ideas can be approved.');
        }

        $oldStatus = $idea->status;
        $idea->update(['status' => 'approved']);

        $actionMessage = $oldStatus === 'rejected' 
            ? "Admin re-approved rejected idea #{$idea->id}: {$idea->title}."
            : "Admin approved idea #{$idea->id}: {$idea->title}.";

        AuditLogger::log(
            'APPROVE_IDEA',
            $actionMessage,
            $idea
        );

        $successMessage = $oldStatus === 'rejected' 
            ? 'Idea re-approved successfully.' 
            : 'Idea approved successfully.';

        return redirect()->back()->with('success', $successMessage);
    }

    public function reject(Request $request, Idea $idea)
    {
        if (!$idea->user || !$idea->user->isStaff()) {
            return redirect()->back()->with('error', 'Only staff ideas can be rejected here.');
        }

        // Only allow rejecting pending ideas
        if ($idea->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending ideas can be rejected.');
        }

        $idea->update(['status' => 'rejected']);

        AuditLogger::log(
            'REJECT_IDEA',
            "Admin rejected idea #{$idea->id}: {$idea->title}.",
            $idea
        );

        return redirect()->back()->with('success', 'Idea rejected successfully.');
    }

    public function show(Idea $idea)
    {
        if (!$idea->user || !$idea->user->isStaff()) {
            return redirect()->back()->with('error', 'Only staff ideas can be viewed here.');
        }

        return view('admin.ideas.details', compact('idea'));
    }
}