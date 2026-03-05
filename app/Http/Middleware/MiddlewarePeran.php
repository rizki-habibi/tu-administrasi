<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MiddlewarePeran
{
    /**
     * Handle an incoming request.
     * Supports multiple roles: role:admin,staff,kepegawaian
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->aktif) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Hubungi admin.');
        }

        // Check if user's role matched any of the allowed roles
        // Also support 'all_staff' alias for all staff-level roles
        $allowedRoles = [];
        foreach ($roles as $role) {
            if ($role === 'all_staff') {
                $allowedRoles = array_merge($allowedRoles, \App\Models\Pengguna::STAFF_ROLES);
            } else {
                $allowedRoles[] = $role;
            }
        }

        if (!in_array(auth()->user()->peran, $allowedRoles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
