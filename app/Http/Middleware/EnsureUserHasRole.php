<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Administrators have universal access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Parse comma-separated roles if provided like "role:admin,hr"
        $allowedRoles = [];
        foreach ($roles as $roleGroup) {
            $parts = explode(',', $roleGroup);
            foreach ($parts as $part) {
                $allowedRoles[] = trim($part);
            }
        }

        if (! in_array($user->role, $allowedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied: You do not have permission to access this hospital department.',
                ], 403);
            }

            return redirect()->route('welcome')
                ->with('error', 'Access denied: You do not have permission to view or manage that section.');
        }

        return $next($request);
    }
}
