<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = $this->queryLogs($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $actionTypes = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('qa-manager.audit-logs.index', [
            'logs' => $logs,
            'actionTypes' => $actionTypes,
            'filters' => [
                'search' => $request->string('search')->toString(),
                'action' => $request->string('action')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'qa-manager-audit-logs-' . now()->format('Ymd_His') . '.csv';
        $logs = $this->queryLogs($request)->latest()->get();

        AuditLogger::log(
            'QA_MANAGER_EXPORT_AUDIT_LOGS',
            'QA Manager exported audit logs to CSV.',
            null,
            'success',
            [
                'filters' => $request->only(['search', 'action', 'status']),
                'rows' => $logs->count(),
            ]
        );

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Timestamp',
                'User',
                'Action',
                'Details',
                'Target Type',
                'Target ID',
                'Status',
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at?->format('Y-m-d H:i:s'),
                    $log->actor?->name ?? 'System',
                    $log->action,
                    $log->details,
                    $log->target_type,
                    $log->target_id,
                    $log->status,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function queryLogs(Request $request)
    {
        $search = trim($request->string('search')->toString());
        $action = trim($request->string('action')->toString());
        $status = trim($request->string('status')->toString());

        return AuditLog::query()
            ->with('actor')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('action', 'like', '%' . $search . '%')
                        ->orWhere('details', 'like', '%' . $search . '%')
                        ->orWhereHas('actor', function ($actorQuery) use ($search) {
                            $actorQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($action !== '', fn ($query) => $query->where('action', $action))
            ->when(in_array($status, ['success', 'warning', 'failed'], true), fn ($query) => $query->where('status', $status));
    }
}
