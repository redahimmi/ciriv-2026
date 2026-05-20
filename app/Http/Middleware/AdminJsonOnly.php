<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminJsonOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Browser refresh (no Accept: application/json) → serve the SPA
        if (!$request->wantsJson()) {
            return response(view('app'));
        }

        return $next($request);
    }
}
