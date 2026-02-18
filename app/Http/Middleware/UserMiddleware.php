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
        
        // PERBAIKAN: Hanya user dengan role 'user' yang bisa akses
        if (!$user->isUser()) {  // GANTI INI!
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('info', 'Anda login sebagai Administrator');
            }
            
            if ($user->isVerifikator()) {
                return redirect()->route('verifikator.dashboard')
                    ->with('info', 'Anda login sebagai Verifikator');
            }
        }

        return $next($request);
    }
}