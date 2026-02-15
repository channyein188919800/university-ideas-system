<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClosureDate
{
    public function handle(Request $request, Closure $next, $type = 'idea'): Response
    {
        if ($type === 'idea') {
            if (!Setting::isIdeaSubmissionOpen()) {
                return redirect()->back()->with('error', 'Idea submission is now closed.');
            }
        } elseif ($type === 'comment') {
            if (!Setting::isCommentingOpen()) {
                return redirect()->back()->with('error', 'Commenting is now closed.');
            }
        }

        return $next($request);
    }
}
