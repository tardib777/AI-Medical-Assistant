<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $allowed = match($role) {
            'super_admin'   => ['super_admin'],
            'admin'         => ['admin', 'super_admin'],
            'moderator'     => ['admin', 'super_admin', 'moderator'],
            'support_agent' => ['admin', 'super_admin', 'moderator', 'support_agent'],
            default         => ['admin', 'super_admin'],
        };

        if (!in_array($user->role, $allowed)) {
            return response()->json(['message' => 'Forbidden — insufficient permissions'], 403);
        }

        return $next($request);
    }
}
