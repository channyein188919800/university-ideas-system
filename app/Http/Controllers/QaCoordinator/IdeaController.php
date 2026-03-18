<?php

namespace App\Http\Controllers\QaCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    /**
     * Display department ideas in table format
     */
    public function departmentIdeas(Request $request)
    {
        $user = Auth::user();
        $departmentId = $user->department_id;
        
        $query = Idea::with(['user', 'department'])
            ->where('department_id', $departmentId)
            ->where('hidden', false);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $ideas = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('qa-coordinator.ideas-table', compact('ideas'))->with('activeTab', 'department');
    }
    
    /**
     * Display popular ideas (most viewed/voted) in table format
     */
    public function popularIdeas(Request $request)
    {
        $user = Auth::user();
        $departmentId = $user->department_id;
        
        $query = Idea::with(['user', 'department'])
            ->where('department_id', $departmentId)
            ->where('hidden', false);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Order by popularity (views + votes)
        $ideas = $query->orderBy('views_count', 'desc')
            ->orderBy('thumbs_up_count', 'desc')
            ->paginate(15);
        
        return view('qa-coordinator.ideas-table', compact('ideas'))->with('activeTab', 'popular');
    }
    
    /**
     * Display latest ideas in table format
     */
    public function latestIdeas(Request $request)
    {
        $user = Auth::user();
        $departmentId = $user->department_id;
        
        $query = Idea::with(['user', 'department'])
            ->where('department_id', $departmentId)
            ->where('hidden', false);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $ideas = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('qa-coordinator.ideas-table', compact('ideas'))->with('activeTab', 'latest');
    }
}