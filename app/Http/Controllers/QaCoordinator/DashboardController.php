<?php

namespace App\Http\Controllers\QaCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $department = Auth::user()->department;
        
        if (!$department) {
            return view('qa-coordinator.dashboard', ['error' => 'You are not assigned to any department.']);
        }
        
        $stats = [
            'total_ideas' => Idea::byDepartment($department->id)->count(),
            'total_comments' => Comment::whereHas('idea', function ($query) use ($department) {
                $query->byDepartment($department->id);
            })->count(),
            'contributors' => Idea::byDepartment($department->id)->select('user_id')->distinct()->count(),
        ];
        
        $recentIdeas = Idea::byDepartment($department->id)->latest()->take(5)->get();
        $popularIdeas = Idea::byDepartment($department->id)->popular()->take(5)->get();
        
        $ideaClosureDate = Setting::getIdeaClosureDate();
        $finalClosureDate = Setting::getFinalClosureDate();
        
        return view('qa-coordinator.dashboard', compact('department', 'stats', 'recentIdeas', 'popularIdeas', 'ideaClosureDate', 'finalClosureDate'));
    }
}
