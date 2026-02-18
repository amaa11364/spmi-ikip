<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect berdasarkan role (case insensitive)
        switch (strtolower($user->role)) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'verifikator':
                return redirect()->route('verifikator.dashboard');
            case 'user':
                return redirect()->route('user.dashboard');
            default:
                return redirect()->route('landing.page');
        }
    }
}