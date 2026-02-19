<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role);

        // Handle multiple roles (e.g., role:admin,verifikator)
        $roles = explode('|', $role);
        
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access. You do not have the required role.');
        }

        return $next($request);
    }
}