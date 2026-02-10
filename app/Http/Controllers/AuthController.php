<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.masuk');
    }

   public function masuk(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Redirect berdasarkan role
        $user = Auth::user();
        
        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ]);
        }
        
        $redirectTo = match($user->role) {
            'admin' => route('admin.dashboard'),
            'verifikator' => route('verifikator.dashboard'),
            'user' => route('user.dashboard'),
            default => route('landing.page'),
        };
        
        // Prioritaskan redirect_to dari input jika ada
        if ($request->input('redirect_to')) {
            $redirectTo = $request->input('redirect_to');
        }
        
        return redirect($redirectTo)->with('success', 'Login berhasil!');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->withInput();
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing.page')->with('success', 'Logout berhasil!');
    }
}