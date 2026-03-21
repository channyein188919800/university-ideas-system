<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaApprovalController extends Controller
{
    public function index(Request $request)
    {
        $ideas = Idea::where('status', 'pending')
            ->whereHas('user', function ($q) {
                $q->where('role', 'staff');
            })
            ->latest()
            ->paginate(10);

        return view('admin.ideas.approvals', compact('ideas'));
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

        return redirect()->back()->with('success', 'Idea approved successfully.');
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

        return redirect()->back()->with('success', 'Idea rejected successfully.');
    }
}
