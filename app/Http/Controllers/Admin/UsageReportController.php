<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsageReportController extends Controller
{
    public function index()
    {
        $since = now()->subDays(30);

        $pageViewLogs = AuditLog::query()
            ->where('action', 'PAGE_VIEW')
            ->where('created_at', '>=', $since)
            ->get(['details', 'metadata', 'user_agent', 'actor_id']);

        $topPages = $pageViewLogs
            ->groupBy('details')
            ->map(function ($group, $details) {
                return (object) [
                    'details' => $details,
                    'total' => $group->sum(fn ($log) => (int) ($log->metadata['view_count'] ?? 1)),
                ];
            })
            ->sortByDesc('total')
            ->take(10)
            ->values();

        $activeUsersRaw = AuditLog::query()
            ->whereNotNull('actor_id')
            ->where('created_at', '>=', $since)
            ->whereNotIn('action', ['PAGE_VIEW', 'LOGIN_FAILED'])
            ->select('actor_id', DB::raw('count(*) as total'))
            ->groupBy('actor_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $users = User::whereIn('id', $activeUsersRaw->pluck('actor_id'))
            ->get()
            ->keyBy('id');

        $activeUsers = $activeUsersRaw->map(function ($row) use ($users) {
            return [
                'user' => $users->get($row->actor_id),
                'total' => $row->total,
            ];
        });

        $browserCounts = $pageViewLogs
            ->whereNotNull('user_agent')
            ->groupBy(fn ($log) => $this->detectBrowser($log->user_agent))
            ->map(function ($group) {
                return $group
                    ->map(function ($log) {
                        return $log->metadata['viewer_key']
                            ?? ($log->actor_id ? 'user:' . $log->actor_id : 'legacy:' . sha1(($log->user_agent ?? 'unknown')));
                    })
                    ->unique()
                    ->count();
            })
            ->sortDesc()
            ->take(10);

        $openReports = Report::with(['idea', 'reporter'])
            ->where('status', 'open')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.usage', [
            'since' => $since,
            'topPages' => $topPages,
            'activeUsers' => $activeUsers,
            'browserCounts' => $browserCounts,
            'openReports' => $openReports,
        ]);
    }

    protected function detectBrowser(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        if (stripos($userAgent, 'Edg') !== false) {
            return 'Edge';
        }

        if (stripos($userAgent, 'OPR') !== false || stripos($userAgent, 'Opera') !== false) {
            return 'Opera';
        }

        if (stripos($userAgent, 'Chrome') !== false && stripos($userAgent, 'Chromium') === false) {
            return 'Chrome';
        }

        if (stripos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        }

        if (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) {
            return 'Safari';
        }

        return 'Other';
    }
}
