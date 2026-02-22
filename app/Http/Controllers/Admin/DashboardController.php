<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_ideas' => Idea::count(),
            'total_comments' => Comment::count(),
            'total_categories' => Category::count(),
            'total_departments' => Department::count(),
        ];

        $recentIdeas = Idea::latest()->take(5)->get();
        $recentComments = Comment::latest()->take(5)->get();

        $ideaClosureDate = Setting::getIdeaClosureDate();
        $finalClosureDate = Setting::getFinalClosureDate();

        // Chart data: ideas per department
        $ideasPerDepartment = Department::withCount('ideas')
            ->get()
            ->map(function ($dept) {
                return ['name' => $dept->name, 'count' => $dept->ideas_count];
            })
            ->values();

        // Chart data: ideas with comments vs without
        $ideasWithComments = Idea::has('comments')->count();
        $ideasWithoutComments = Idea::doesntHave('comments')->count();

        return view('admin.dashboard', compact(
            'stats', 'recentIdeas', 'recentComments',
            'ideaClosureDate', 'finalClosureDate',
            'ideasPerDepartment', 'ideasWithComments', 'ideasWithoutComments'
        ));
    }
}
