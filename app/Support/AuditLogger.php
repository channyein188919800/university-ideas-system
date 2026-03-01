<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuditLogger
{
    public static function log(
        string $action,
        string $details,
        ?Model $target = null,
        string $status = 'success',
        array $metadata = [],
        ?int $actorId = null
    ): void {
        try {
            AuditLog::create([
                'actor_id' => $actorId ?? Auth::id(),
                'action' => strtoupper($action),
                'target_type' => $target ? class_basename($target) : null,
                'target_id' => $target?->getKey(),
                'details' => $details,
                'status' => strtolower($status),
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'metadata' => $metadata ?: null,
            ]);
        } catch (Throwable $e) {
            // Do not block the primary user action if audit logging fails.
        }
    }
}

