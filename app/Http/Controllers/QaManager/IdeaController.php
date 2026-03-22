<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\Category;
use App\Models\Department;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    /**
     * Display all ideas with table view (similar to QA Coordinator)
     */
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        $view = $request->input('view', 'all'); // all or popular
        $search = $request->input('search', '');
        $departmentId = $request->input('department_id', '');
        $categoryId = $request->input('category_id', '');
        $status = $request->input('status', 'all');

        $query = Idea::with(['user', 'department', 'categories'])
            ->where('hidden', false); // Don't show hidden ideas in main views

        // Apply filters
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        if (!empty($categoryId)) {
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply sorting based on view
        if ($view === 'popular') {
            $query->orderBy('views_count', 'desc')
                  ->orderBy('thumbs_up_count', 'desc');
        } else {
            $query->latest();
        }

        $ideas = $query->paginate(15)->withQueryString();

        // Get data for filters
        $departments = Department::orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();

        // Get counts for stats
        $totalIdeas = Idea::where('hidden', false)->count();
        $totalApproved = Idea::where('hidden', false)->where('status', 'approved')->count();
        $totalPending = Idea::where('hidden', false)->where('status', 'pending')->count();
        $totalComments = \App\Models\Comment::whereIn('idea_id', Idea::where('hidden', false)->pluck('id'))->count();

        return view('qa-manager.ideas.index', compact(
            'ideas',
            'departments',
            'categories',
            'view',
            'search',
            'departmentId',
            'categoryId',
            'status',
            'totalIdeas',
            'totalApproved',
            'totalPending',
            'totalComments'
        ));
    }

    /**
     * Toggle idea visibility (hide/unhide)
     */
    public function toggleHidden(Request $request, Idea $idea)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        $idea->hidden = !$idea->hidden;
        $idea->save();

        $action = $idea->hidden ? 'HIDE_IDEA' : 'UNHIDE_IDEA';
        $actionText = $idea->hidden ? 'hidden' : 'unhidden';

        AuditLogger::log(
            $action,
            "QA Manager {$actionText} idea #{$idea->id}: {$idea->title}.",
            $idea
        );

        $message = $idea->hidden 
            ? 'Idea has been hidden successfully.' 
            : 'Idea has been unhidden successfully.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show a simple idea details page for QA Manager.
     */
    public function show(Idea $idea)
    {
        if (!Auth::check() || Auth::user()->role !== 'qa_manager') {
            abort(403, 'Unauthorized access.');
        }

        return view('qa-manager.ideas.details', compact('idea'));
    }
}
