<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BridgeTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Bridge-Token');

        if ($token !== config('app.bridge_token', 'cornelia-bridge-2026')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}