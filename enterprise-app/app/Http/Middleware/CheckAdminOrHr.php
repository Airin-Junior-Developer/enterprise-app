<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrHr
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // New RBAC check: HR admin permission OR legacy position-name check
        if ($user->hasPermission('HR', 'create') || $user->isAdminOrHr()) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
