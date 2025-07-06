<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogBroadcastAuth
{
    public function handle($request, Closure $next)
    {
        Log::info('Broadcasting auth debug', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'has_auth_header' => $request->hasHeader('Authorization'),
            'bearer_token' => $request->bearerToken() ? 'present' : 'missing',
            'user' => Auth::user(),
            'auth_header' => $request->header('Authorization'),
            'all_headers' => $request->headers->all(),
        ]);

        return $next($request);
    }
}
