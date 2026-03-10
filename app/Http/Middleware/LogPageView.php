<?php

namespace App\Http\Middleware;

use App\Support\AuditLogger;
use Closure;
use Illuminate\Http\Request;
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

            AuditLogger::log(
                'PAGE_VIEW',
                'Page view: ' . $label,
                null,
                'success',
                [
                    'route' => $routeName,
                    'path' => $path,
                ]
            );
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
}
