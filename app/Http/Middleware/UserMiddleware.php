<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('masuk')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Hanya user biasa yang bisa akses (bukan supervisor/admin)
        if ($user->hasAnyRole(['supervisor', 'admin'])) {
            return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'supervisor.dashboard')
                ->with('info', 'Anda sudah login sebagai ' . $user->role_label);
        }

        return $next($request);
    }
}