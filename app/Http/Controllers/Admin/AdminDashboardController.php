<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $totalBerita = Berita::count();
        $publishedBerita = Berita::where('is_published', true)->count();
        $totalJadwal = Jadwal::count();
        $activeJadwal = Jadwal::where('status', 'aktif')->count();
        $totalUsers = User::count();
        $totalDokumen = Dokumen::count();
        
        $recentBerita = Berita::latest()->limit(5)->get();
        $upcomingJadwals = Jadwal::where('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->limit(5)
            ->get();
        
        // Statistik tambahan
        $userStats = [
            'admin' => User::where('role', 'admin')->count(),
            'verifikator' => User::where('role', 'verifikator')->count(),
            'user' => User::where('role', 'user')->count(),
        ];
        
        $dokumenStats = [
            'pending' => Dokumen::where('status', 'pending')->count(),
            'approved' => Dokumen::where('status', 'approved')->count(),
            'rejected' => Dokumen::where('status', 'rejected')->count(),
            'revision' => Dokumen::where('status', 'revision')->count(),
        ];
        
        $weeklyStats = [
            'users' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'dokumen' => Dokumen::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
        
        return view('admin.dashboard', compact(
            'totalBerita',
            'publishedBerita',
            'totalJadwal',
            'activeJadwal',
            'totalUsers',
            'totalDokumen',
            'recentBerita',
            'upcomingJadwals',
            'userStats',
            'dokumenStats',
            'weeklyStats'
        ));
    }
}