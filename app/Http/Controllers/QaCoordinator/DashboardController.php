<?php

namespace App\Http\Controllers\QaCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\User;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return view('qa-coordinator.dashboard', ['error' => 'You are not assigned to any department.']);
        }
        
        $departmentId = $department->id;
        
        // Stats calculation
        $totalIdeas = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->count();
            
        $ideasThisMonth = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $totalComments = Comment::whereHas('idea', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId)
                  ->where('status', 'approved');
        })->count();
        
        $commentsThisMonth = Comment::whereHas('idea', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId)
                  ->where('status', 'approved');
        })->whereMonth('comments.created_at', now()->month)
          ->whereYear('comments.created_at', now()->year)
          ->count();
        
        $totalStaff = User::where('department_id', $departmentId)
            ->where('role', 'staff')
            ->count();
        
        $contributors = User::where('department_id', $departmentId)
            ->where('role', 'staff')
            ->where(function($query) {
                $query->whereHas('ideas', function($q) {
                    $q->where('status', 'approved');
                })->orWhereHas('comments');
            })
            ->count();
        
        $nonContributors = $totalStaff - $contributors;
        $participationRate = $totalStaff > 0 ? round(($contributors / $totalStaff) * 100) : 0;
        
        $stats = [
            'total_ideas' => $totalIdeas,
            'ideas_this_month' => $ideasThisMonth,
            'total_comments' => $totalComments,
            'comments_this_month' => $commentsThisMonth,
            'total_staff' => $totalStaff,
            'contributors' => $contributors,
            'non_contributors' => $nonContributors,
            'participation_rate' => $participationRate,
        ];
        
        $recentIdeas = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->with(['user', 'categories'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $popularIdeas = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->with(['user', 'categories'])
            ->withCount('comments')
            ->orderBy('thumbs_up_count', 'desc')
            ->orderBy('thumbs_down_count', 'asc')
            ->take(5)
            ->get();
        
        $staff = User::where('department_id', $departmentId)
            ->where('role', 'staff')
            ->withCount(['ideas' => function($q) {
                $q->where('status', 'approved');
            }, 'comments'])
            ->orderBy('name')
            ->take(10)
            ->get();
        
        $ideasWithoutComments = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->doesntHave('comments')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $anonymousStats = [
            'ideas' => Idea::where('department_id', $departmentId)
                ->where('status', 'approved')
                ->where('is_anonymous', true)
                ->count(),
            'comments' => Comment::whereHas('idea', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId)
                      ->where('status', 'approved');
            })->where('is_anonymous', true)->count(),
        ];
        
        $categories = Category::where('is_active', true)->get();
        $categoryLabels = [];
        $categoryData = [];
        
        foreach ($categories as $category) {
            $categoryLabels[] = $category->name;
            $categoryData[] = Idea::where('department_id', $departmentId)
                ->where('status', 'approved')
                ->whereHas('categories', function($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })->count();
        }
        
        $chartData = [
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
        ];
        
        $ideaClosureDate = Setting::where('key', 'idea_closure_date')->first()?->value;
        $finalClosureDate = Setting::where('key', 'final_closure_date')->first()?->value;
        
        if ($ideaClosureDate) {
            $ideaClosureDate = \Carbon\Carbon::parse($ideaClosureDate);
        }
        if ($finalClosureDate) {
            $finalClosureDate = \Carbon\Carbon::parse($finalClosureDate);
        }
        
        return view('qa-coordinator.dashboard', compact(
            'department',
            'stats',
            'recentIdeas',
            'popularIdeas',
            'staff',
            'ideasWithoutComments',
            'anonymousStats',
            'chartData',
            'ideaClosureDate',
            'finalClosureDate'
        ));
    }

    /**
     * ===========================================
     * ADD THIS METHOD - Statistics Page
     * ===========================================
     */
    public function statistics()
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return redirect()->route('qa-coordinator.dashboard')->with('error', 'No department assigned');
        }
        
        $departmentId = $department->id;
        
        // Get all departments for comparison
        $allDepartments = Department::withCount(['ideas' => function($q) {
                $q->where('status', 'approved');
            }])
            ->withCount(['users as staff_count' => function($q) {
                $q->where('role', 'staff');
            }])
            ->get();
        
        // Calculate percentages and additional stats for each department
        $totalIdeasAll = $allDepartments->sum('ideas_count');
        $deptComparison = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
        
        foreach ($allDepartments as $dept) {
            $deptComparison['labels'][] = $dept->name;
            $deptComparison['data'][] = $dept->ideas_count;
            // Highlight current department
            $deptComparison['colors'][] = $dept->id === $departmentId ? '#d69e2e' : '#3577ff';
            
            // Calculate ideas percentage
            $dept->ideas_percentage = $totalIdeasAll > 0 
                ? round(($dept->ideas_count / $totalIdeasAll) * 100, 1) 
                : 0;
            
            // Count contributors in each department
            $dept->contributors_count = User::where('department_id', $dept->id)
                ->where('role', 'staff')
                ->where(function($q) {
                    $q->whereHas('ideas', function($iq) {
                        $iq->where('status', 'approved');
                    })->orWhereHas('comments');
                })
                ->count();
            
            // Count comments in each department
            $dept->comments_count = Comment::whereHas('idea', function($q) use ($dept) {
                $q->where('department_id', $dept->id)
                  ->where('status', 'approved');
            })->count();
            
            // Calculate participation rate
            $dept->participation_rate = $dept->staff_count > 0 
                ? round(($dept->contributors_count / $dept->staff_count) * 100) 
                : 0;
        }
        
        // Get current department stats
        $currentDept = $allDepartments->firstWhere('id', $departmentId);
        
        // Category stats for current department
        $categories = Category::where('is_active', true)->get();
        $categoryStats = [
            'labels' => [],
            'data' => []
        ];
        
        foreach ($categories as $category) {
            $categoryStats['labels'][] = $category->name;
            $categoryStats['data'][] = Idea::where('department_id', $departmentId)
                ->where('status', 'approved')
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->count();
        }
        
        // Monthly trends for current department (last 6 months)
        $monthlyStats = [
            'labels' => [],
            'ideas' => [],
            'comments' => []
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats['labels'][] = $month->format('M Y');
            
            $monthlyStats['ideas'][] = Idea::where('department_id', $departmentId)
                ->where('status', 'approved')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $monthlyStats['comments'][] = Comment::whereHas('idea', function($q) use ($departmentId, $month) {
                $q->where('department_id', $departmentId)
                  ->where('status', 'approved')
                  ->whereYear('created_at', $month->year)
                  ->whereMonth('created_at', $month->month);
            })->count();
        }
        
        $totalIdeas = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->count();
            
        $totalComments = Comment::whereHas('idea', function($q) use ($departmentId) {
            $q->where('department_id', $departmentId)
              ->where('status', 'approved');
        })->count();
        
        $totalStaff = User::where('department_id', $departmentId)
            ->where('role', 'staff')
            ->count();
            
        $contributors = User::where('department_id', $departmentId)
            ->where('role', 'staff')
            ->where(function($q) {
                $q->whereHas('ideas', function($iq) {
                    $iq->where('status', 'approved');
                })->orWhereHas('comments');
            })
            ->count();
            
        $participationRate = $totalStaff > 0 ? round(($contributors / $totalStaff) * 100) : 0;
        
        return view('qa-coordinator.statistics.index', compact(
            'department',
            'allDepartments',
            'deptComparison',
            'categoryStats',
            'monthlyStats',
            'totalIdeas',
            'totalComments',
            'totalStaff',
            'contributors',
            'participationRate',
            'currentDept'
        ));
    }

    /**
     * ===========================================
     * ADD THESE OTHER METHODS TOO
     * ===========================================
     */
    
    // Exception Reports
    public function exceptions()
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return redirect()->route('qa-coordinator.dashboard');
        }
        
        $departmentId = $department->id;
        
        $ideasWithoutComments = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->doesntHave('comments')
            ->with(['user', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $anonymousIdeas = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->where('is_anonymous', true)
            ->with(['user', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'ideas_page');
            
        $anonymousComments = Comment::whereHas('idea', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId)
                  ->where('status', 'approved');
            })
            ->where('is_anonymous', true)
            ->with(['user', 'idea'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'comments_page');
            
        $ideasWithoutCommentsCount = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->doesntHave('comments')
            ->count();
            
        $anonymousIdeasCount = Idea::where('department_id', $departmentId)
            ->where('status', 'approved')
            ->where('is_anonymous', true)
            ->count();
            
        $anonymousCommentsCount = Comment::whereHas('idea', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId)
                  ->where('status', 'approved');
            })
            ->where('is_anonymous', true)
            ->count();
        
        return view('qa-coordinator.reports.exceptions', compact(
            'department',
            'ideasWithoutComments',
            'anonymousIdeas',
            'anonymousComments',
            'ideasWithoutCommentsCount',
            'anonymousIdeasCount',
            'anonymousCommentsCount'
        ));
    }
    
    // Notifications
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);
        
        return view('qa-coordinator.notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
    
    // Staff List
    public function staffList()
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return redirect()->route('qa-coordinator.dashboard');
        }
        
        $staff = User::where('department_id', $department->id)
            ->where('role', 'staff')
            ->withCount(['ideas' => function($q) {
                $q->where('status', 'approved');
            }, 'comments'])
            ->orderBy('name')
            ->paginate(15);
            
        $totalStaff = $staff->total();
        $contributorsCount = User::where('department_id', $department->id)
            ->where('role', 'staff')
            ->where(function($q) {
                $q->whereHas('ideas', function($iq) {
                    $iq->where('status', 'approved');
                })->orWhereHas('comments');
            })
            ->count();
        $pendingCount = $totalStaff - $contributorsCount;
        
        return view('qa-coordinator.staff.index', compact(
            'staff',
            'totalStaff',
            'contributorsCount',
            'pendingCount',
            'department'
        ));
    }
    
    // Reminder methods
    public function remindAll(Request $request)
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return response()->json(['success' => false, 'message' => 'No department'], 403);
        }
        
        $staff = User::where('department_id', $department->id)
            ->where('role', 'staff')
            ->where('id', '!=', $user->id)
            ->get();

        $ideaClosureDate = Setting::where('key', 'idea_closure_date')->first()?->value;
        $finalClosureDate = Setting::where('key', 'final_closure_date')->first()?->value;
        $ideaClosureDate = $ideaClosureDate ? \Carbon\Carbon::parse($ideaClosureDate) : null;
        $finalClosureDate = $finalClosureDate ? \Carbon\Carbon::parse($finalClosureDate) : null;
        
        $count = 0;
        
        foreach ($staff as $member) {
            try {
                Mail::send('emails.participation-reminder', [
                    'staff' => $member,
                    'coordinator' => $user,
                    'department' => $department,
                    'ideaClosureDate' => $ideaClosureDate,
                    'finalClosureDate' => $finalClosureDate
                ], function($message) use ($member) {
                    $message->to($member->email)
                        ->subject('Submission Deadline and Final Closure (Comments)');
                });
                $count++;
            } catch (\Exception $e) {
                Log::error('Failed to send reminder: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Reminders sent to {$count} staff member" . ($count != 1 ? 's' : '')
        ]);
    }
    
    public function remindStaff(Request $request, $userId)
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return response()->json(['success' => false, 'message' => 'No department'], 403);
        }
        
        $staff = User::where('id', $userId)
            ->where('department_id', $department->id)
            ->where('role', 'staff')
            ->first();
        
        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Staff not found'], 404);
        }

        $ideaClosureDate = Setting::where('key', 'idea_closure_date')->first()?->value;
        $finalClosureDate = Setting::where('key', 'final_closure_date')->first()?->value;
        $ideaClosureDate = $ideaClosureDate ? \Carbon\Carbon::parse($ideaClosureDate) : null;
        $finalClosureDate = $finalClosureDate ? \Carbon\Carbon::parse($finalClosureDate) : null;
        
        try {
            Mail::send('emails.participation-reminder', [
                'staff' => $staff,
                'coordinator' => $user,
                'department' => $department,
                'ideaClosureDate' => $ideaClosureDate,
                'finalClosureDate' => $finalClosureDate
            ], function($message) use ($staff) {
                $message->to($staff->email)
                    ->subject('Submission Deadline and Final Closure (Comments)');
            });
            
            return response()->json([
                'success' => true,
                'message' => "Reminder sent to {$staff->name}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder'
            ], 500);
        }
    }
    
    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            return response()->json(['error' => 'No department'], 403);
        }
        
        $period = $request->get('period', 'all');
        $departmentId = $department->id;
        
        $query = Idea::where('department_id', $departmentId)
            ->where('status', 'approved');
        
        if ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }
        
        $categories = Category::where('is_active', true)->get();
        $categoryLabels = [];
        $categoryData = [];
        
        foreach ($categories as $category) {
            $catQuery = clone $query;
            $categoryLabels[] = $category->name;
            $categoryData[] = $catQuery->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })->count();
        }
        
        return response()->json([
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData
        ]);
    }
}
