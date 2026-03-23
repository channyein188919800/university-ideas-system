<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\Setting;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_ideas' => Idea::count(),
            'total_comments' => Comment::count(),
            'total_users' => User::count(),
            'total_departments' => Department::count(),
        ];
        
        $departmentStats = Department::withCount(['ideas', 'users'])
            ->with(['ideas' => function ($query) {
                $query->select('department_id', 'user_id')->distinct();
            }])
            ->get()
            ->map(function ($dept) use ($stats) {
                $dept->contributors_count = $dept->ideas->pluck('user_id')->unique()->count();
                $dept->percentage = $stats['total_ideas'] > 0 
                    ? round(($dept->ideas_count / $stats['total_ideas']) * 100, 2) 
                    : 0;
                return $dept;
            });
        
        $ideaClosureDate = Setting::getIdeaClosureDate();
        $finalClosureDate = Setting::getFinalClosureDate();

        return view('qa-manager.dashboard', compact(
            'stats',
            'departmentStats',
            'ideaClosureDate',
            'finalClosureDate'
        ));
    }
}
