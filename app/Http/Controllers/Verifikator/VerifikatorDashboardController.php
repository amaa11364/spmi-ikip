<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class VerifikatorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // FILTER BERDASARKAN UNIT KERJA VERIFIKATOR
        $unitKerjaId = $user->unit_kerja_id;
        
        // HITUNG SEMUA STATISTIK
        $pendingCount = Dokumen::where('status', 'pending')
            ->where('unit_kerja_id', $unitKerjaId)
            ->count();
            
        $approvedCount = Dokumen::where('status', 'approved')
            ->where('unit_kerja_id', $unitKerjaId)
            ->count();
            
        $rejectedCount = Dokumen::where('status', 'rejected')
            ->where('unit_kerja_id', $unitKerjaId)
            ->count();
            
        $revisionCount = Dokumen::where('status', 'revision')
            ->where('unit_kerja_id', $unitKerjaId)
            ->count();
        
        $totalDocuments = Dokumen::where('unit_kerja_id', $unitKerjaId)->count();
        
        // AMBIL 10 DOKUMEN PENDING TERBARU
        $pendingDocuments = Dokumen::where('status', 'pending')
            ->where('unit_kerja_id', $unitKerjaId)
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // HITUNG PERSENTASE
        $pendingPercent = $totalDocuments > 0 ? round(($pendingCount / $totalDocuments) * 100) : 0;
        $approvedPercent = $totalDocuments > 0 ? round(($approvedCount / $totalDocuments) * 100) : 0;
        $rejectedPercent = $totalDocuments > 0 ? round(($rejectedCount / $totalDocuments) * 100) : 0;
        $revisionPercent = $totalDocuments > 0 ? round(($revisionCount / $totalDocuments) * 100) : 0;
        
        // STATISTIK MINGGUAN
        $weeklyApproved = Dokumen::where('status', 'approved')
            ->where('unit_kerja_id', $unitKerjaId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
            
        $weeklyPending = Dokumen::where('status', 'pending')
            ->where('unit_kerja_id', $unitKerjaId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
            
        $weeklyRejected = Dokumen::where('status', 'rejected')
            ->where('unit_kerja_id', $unitKerjaId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        
        // KIRIM KE VIEW
        return view('verifikator.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'revisionCount',
            'totalDocuments',
            'pendingPercent',
            'approvedPercent',
            'rejectedPercent',
            'revisionPercent',
            'pendingDocuments',
            'weeklyApproved',
            'weeklyPending',
            'weeklyRejected'
        ));
    }
    
    public function getPendingStatistics()
    {
        $user = Auth::user();
        
        $count = Dokumen::where('status', 'pending')
            ->where('unit_kerja_id', $user->unit_kerja_id)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}