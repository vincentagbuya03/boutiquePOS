<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $action
     * @param  string  $resource
     */
    public function handle(Request $request, Closure $next, string $action, string $resource): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if (!$user->hasPermission($action, $resource)) {
            return response()->json(['message' => 'Forbidden: You do not have permission to ' . $action . ' ' . $resource], 403);
        }

        return $next($request);
    }
}
