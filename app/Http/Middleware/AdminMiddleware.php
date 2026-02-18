<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('masuk')->with('error', 'Silakan login terlebih dahulu.');
        }

        // PERBAIKAN: Pake isAdmin() aja
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('landing.page')
            ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}