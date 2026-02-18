<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikatorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('masuk')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // PERBAIKAN: Verifikator atau Admin bisa akses
        if ($user->isVerifikator() || $user->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('landing.page')
            ->with('error', 'Anda tidak memiliki akses ke halaman verifikator.');
    }
}