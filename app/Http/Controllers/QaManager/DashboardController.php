<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\Setting;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Support\Facades\DB;

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

        $usersForModeration = User::query()
            ->whereNotIn('role', ['admin', 'qa_manager'])
            ->with('department')
            ->withCount(['ideas', 'comments'])
            ->orderByRaw("CASE WHEN status = 'disabled' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->paginate(10, ['*'], 'users_page');

        return view('qa-manager.dashboard', compact(
            'stats',
            'departmentStats',
            'ideaClosureDate',
            'finalClosureDate',
            'usersForModeration'
        ));
    }

    public function toggleUserStatus(User $user)
    {
        if (!auth()->check() || !auth()->user()->isQaManager()) {
            abort(403, 'Unauthorized access.');
        }

        if (in_array($user->role, ['admin', 'qa_manager'], true)) {
            return redirect()->back()->with('error', 'You cannot moderate admin or QA Manager accounts.');
        }

        DB::transaction(function () use ($user) {
            $disableUser = $user->status !== 'disabled';

            $user->update([
                'status' => $disableUser ? 'disabled' : 'active',
            ]);

            Idea::where('user_id', $user->id)->update([
                'hidden' => $disableUser,
            ]);

            Comment::where('user_id', $user->id)->update([
                'hidden' => $disableUser,
            ]);

            $ideaIds = Comment::where('user_id', $user->id)->pluck('idea_id')->unique()->all();
            if (!empty($ideaIds)) {
                Idea::whereIn('id', $ideaIds)->get()->each->updateCommentsCount();
            }

            AuditLogger::log(
                $disableUser ? 'DISABLE_USER' : 'RESTORE_USER',
                ($disableUser ? 'Disabled' : 'Restored') . " user #{$user->id} ({$user->email}) and " .
                    ($disableUser ? 'hid' : 'restored') . " related ideas/comments.",
                $user
            );
        });

        return redirect()->back()->with(
            'success',
            $user->status === 'disabled'
                ? 'User disabled and their ideas/comments are now hidden.'
                : 'User restored and their ideas/comments are now visible again.'
        );
    }
}
