<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\Comment;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HiddenContentController extends Controller
{
    /**
     * Display hidden ideas and comments
     */
    public function index(Request $request)
    {
        // Check if user is authenticated and is QA Manager
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        // Get filter inputs
        $search = $request->input('search', '');
        $departmentId = $request->input('department_id', '');
        $dateFrom = $request->input('date_from', '');
        $dateTo = $request->input('date_to', '');

        // Hidden Ideas Query
        $ideasQuery = Idea::with(['user', 'department', 'categories'])
            ->where('hidden', true);

        // Hidden Comments Query
        $commentsQuery = Comment::with(['user', 'idea', 'idea.department'])
            ->where('hidden', true);

        // Apply filters to ideas
        if (!empty($search)) {
            $ideasQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($departmentId)) {
            $ideasQuery->where('department_id', $departmentId);
        }

        if (!empty($dateFrom)) {
            $ideasQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $ideasQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Apply filters to comments
        if (!empty($search)) {
            $commentsQuery->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('idea', function($ideaQuery) use ($search) {
                      $ideaQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($departmentId)) {
            $commentsQuery->whereHas('idea', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if (!empty($dateFrom)) {
            $commentsQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $commentsQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Get paginated results
        $hiddenIdeas = $ideasQuery->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'ideas_page');
        $hiddenComments = $commentsQuery->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'comments_page');

        // Get departments for filter dropdown
        $departments = \App\Models\Department::orderBy('name')->get();

        // Get counts
        $totalHiddenIdeas = Idea::where('hidden', true)->count();
        $totalHiddenComments = Comment::where('hidden', true)->count();

        return view('qa-manager.hidden.index', compact(
            'hiddenIdeas',
            'hiddenComments',
            'departments',
            'search',
            'departmentId',
            'dateFrom',
            'dateTo',
            'totalHiddenIdeas',
            'totalHiddenComments'
        ));
    }

    /**
     * Unhide a specific idea
     */
    public function unhideIdea(Request $request, Idea $idea)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        $idea->hidden = false;
        $idea->save();

        AuditLogger::log(
            'UNHIDE_IDEA',
            "QA Manager unhid idea #{$idea->id}: {$idea->title}.",
            $idea
        );

        return redirect()->back()->with('success', 'Idea has been unhidden successfully.');
    }

    /**
     * Unhide a specific comment
     */
    public function unhideComment(Request $request, Comment $comment)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        $comment->hidden = false;
        $comment->save();

        // Update the idea's comment count
        $comment->idea->updateCommentsCount();

        AuditLogger::log(
            'UNHIDE_COMMENT',
            "QA Manager unhid comment #{$comment->id} on idea #{$comment->idea_id}.",
            $comment
        );

        return redirect()->back()->with('success', 'Comment has been unhidden successfully.');
    }

    /**
     * Bulk unhide selected ideas
     */
    public function bulkUnhideIdeas(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'idea_ids' => 'required|array',
            'idea_ids.*' => 'exists:ideas,id'
        ]);

        $count = Idea::whereIn('id', $request->idea_ids)
            ->where('hidden', true)
            ->update(['hidden' => false]);

        AuditLogger::log(
            'BULK_UNHIDE_IDEAS',
            "QA Manager bulk unhid {$count} ideas.",
            null,
            'success',
            ['count' => $count, 'idea_ids' => $request->idea_ids]
        );

        return redirect()->back()->with('success', "{$count} ideas have been unhidden successfully.");
    }

    /**
     * Bulk unhide selected comments
     */
    public function bulkUnhideComments(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id'
        ]);

        $comments = Comment::whereIn('id', $request->comment_ids)
            ->where('hidden', true)
            ->get();

        $count = 0;
        $ideaIds = [];

        foreach ($comments as $comment) {
            $comment->hidden = false;
            $comment->save();
            $ideaIds[] = $comment->idea_id;
            $count++;
        }

        // Update comment counts for affected ideas
        foreach (array_unique($ideaIds) as $ideaId) {
            $idea = Idea::find($ideaId);
            if ($idea) {
                $idea->updateCommentsCount();
            }
        }

        AuditLogger::log(
            'BULK_UNHIDE_COMMENTS',
            "QA Manager bulk unhid {$count} comments.",
            null,
            'success',
            ['count' => $count, 'comment_ids' => $request->comment_ids]
        );

        return redirect()->back()->with('success', "{$count} comments have been unhidden successfully.");
    }
}