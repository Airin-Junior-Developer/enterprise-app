<?php
// app/Http/Middleware/SessionTimeout.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if ($bearerToken) {
            $accessToken = PersonalAccessToken::findToken($bearerToken);
            $timeoutMinutes = config('session.lifetime', 120);

            if ($accessToken && $accessToken->last_used_at) {
                $idleMinutes = $accessToken->last_used_at->diffInMinutes(now());
                if ($idleMinutes >= $timeoutMinutes) {
                    $accessToken->delete();
                    return response()->json(['message' => 'Session expired due to inactivity.'], 401);
                }
            }
        }

        return $next($request);
    }
}
