<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request with comprehensive error handling
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'error' => 'Authentication Required',
                    'message' => 'You must be logged in to access this resource.',
                    'details' => 'Please login with valid credentials and try again.',
                    'required_role' => $role,
                    'status_code' => 401
                ], 401);
            }

            $user = auth()->user();

            if (!$user || $user->role !== $role) {
                return response()->json([
                    'error' => 'Insufficient Privileges',
                    'message' => 'You do not have the required role to access this resource.',
                    'details' => "This endpoint requires '{$role}' role access.",
                    'your_role' => $user ? $user->role : 'none',
                    'required_role' => $role,
                    'status_code' => 403
                ], 403);
            }

            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authorization Check Failed',
                'message' => 'An error occurred while checking your permissions.',
                'details' => 'Please try again later or contact support.',
                'status_code' => 500
            ], 500);
        }
    }
}
