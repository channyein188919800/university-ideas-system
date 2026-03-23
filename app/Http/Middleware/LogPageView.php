<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Support\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldLog($request)) {
            $routeName = $request->route()?->getName();
            $path = '/' . ltrim($request->path(), '/');
            $label = $routeName ?: $path;
            $pageKey = $routeName ?: $path;
            $viewerKey = $this->viewerKey($request);

            $existing = AuditLog::query()
                ->where('action', 'PAGE_VIEW')
                ->whereDate('created_at', now()->toDateString())
                ->where('metadata->page_key', $pageKey)
                ->where('metadata->viewer_key', $viewerKey)
                ->when(Auth::check(), fn ($query) => $query->where('actor_id', Auth::id()))
                ->when(!Auth::check(), fn ($query) => $query->whereNull('actor_id'))
                ->first();

            if ($existing) {
                $currentCount = (int) ($existing->metadata['view_count'] ?? 1);
                $metadata = array_merge($existing->metadata ?? [], [
                    'route' => $routeName,
                    'path' => $path,
                    'page_key' => $pageKey,
                    'viewer_key' => $viewerKey,
                    'last_seen_at' => now()->toDateTimeString(),
                    'view_count' => $currentCount + 1,
                ]);

                $existing->forceFill([
                    'details' => 'Page view: ' . $label,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => $metadata,
                ])->save();
            } else {
                AuditLogger::log(
                    'PAGE_VIEW',
                    'Page view: ' . $label,
                    null,
                    'success',
                    [
                        'route' => $routeName,
                        'path' => $path,
                        'page_key' => $pageKey,
                        'viewer_key' => $viewerKey,
                        'last_seen_at' => now()->toDateTimeString(),
                        'view_count' => 1,
                    ]
                );
            }
        }

        return $response;
    }

    protected function shouldLog(Request $request): bool
    {
        if (!$request->isMethod('get')) {
            return false;
        }

        if ($request->route() === null) {
            return false;
        }

        if ($request->ajax()) {
            return false;
        }

        $path = $request->path();
        if (Str::startsWith($path, ['storage', 'images', 'css', 'js', 'build'])) {
            return false;
        }

        return true;
    }

    protected function viewerKey(Request $request): string
    {
        if (Auth::check()) {
            return 'user:' . Auth::id();
        }

        $sessionId = $request->hasSession() ? $request->session()->getId() : 'no-session';
        return 'guest:' . sha1($sessionId . '|' . ($request->ip() ?? ''));
    }
}
