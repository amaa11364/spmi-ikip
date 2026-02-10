<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokumen;
use App\Models\User;
use App\Models\Berita; 
use App\Models\Jadwal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'verifikator':
                return $this->verifikatorDashboard();
            case 'user':
                return $this->userDashboard();
            default:
                return redirect()->route('landing.page');
        }
    }

     public function adminDashboard()
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
        
        return view('admin.dashboard', compact(
            'totalBerita',
            'publishedBerita',
            'totalJadwal',
            'activeJadwal',
            'totalUsers',
            'totalDokumen',
            'recentBerita',
            'upcomingJadwals'
        ));
    }
    
    public function verifikatorDashboard()
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $statistics = [
            'unit_documents' => Dokumen::where('unit_kerja_id', $unitKerjaId)->count(),
            'unit_users' => User::where('unit_kerja_id', $unitKerjaId)->count(),
            'pending_reviews' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'pending')
                ->count(),
            'recent_uploads' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('verifikator.dashboard', compact('statistics'));
    }

    public function userDashboard()
    {
        $user = Auth::user();
        
        $statistics = [
            'my_documents' => $user->dokumens()->count(),
            'storage_used' => Dokumen::getFormattedStorageUsed($user->id),
            'recent_uploads' => $user->dokumens()->latest()->take(5)->get(),
            'pending_approvals' => $user->dokumens()->where('status', 'pending')->count(),
        ];

        return view('user.dashboard', compact('statistics'));
    }
}