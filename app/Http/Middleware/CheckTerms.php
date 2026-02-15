<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTerms
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->terms_accepted) {
            if (!$request->is('terms*') && !$request->is('logout')) {
                return redirect()->route('terms.show');
            }
        }

        return $next($request);
    }
}
