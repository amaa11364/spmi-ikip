<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerifikatorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        // ==================== STATISTIK UTAMA ====================
        
        // Statistik per status
        $statusStats = [
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'pending')->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'rejected')->count(),
            'revision' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'revision')->count(),
        ];
        
        $totalDocuments = array_sum($statusStats);
        
        // ==================== ✅ STATISTIK PER TAHAPAN PPEPP ====================
        
        $tahapanStats = [];
        $tahapanList = ['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'];
        $tahapanLabels = [
            'penetapan' => 'Penetapan SPMI',
            'pelaksanaan' => 'Pelaksanaan SPMI',
            'evaluasi' => 'Evaluasi SPMI',
            'pengendalian' => 'Pengendalian SPMI',
            'peningkatan' => 'Peningkatan SPMI',
        ];
        $tahapanIcons = [
            'penetapan' => 'fa-file-signature',
            'pelaksanaan' => 'fa-play-circle',
            'evaluasi' => 'fa-chart-line',
            'pengendalian' => 'fa-tasks',
            'peningkatan' => 'fa-arrow-up',
        ];
        $tahapanColors = [
            'penetapan' => 'primary',
            'pelaksanaan' => 'success',
            'evaluasi' => 'warning',
            'pengendalian' => 'info',
            'peningkatan' => 'purple',
        ];
        
        foreach ($tahapanList as $tahapan) {
            $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('tahapan', $tahapan);
            
            $total = (clone $query)->count();
            $pending = (clone $query)->where('status', 'pending')->count();
            $approved = (clone $query)->where('status', 'approved')->count();
            $rejected = (clone $query)->where('status', 'rejected')->count();
            $revision = (clone $query)->where('status', 'revision')->count();
            
            $tahapanStats[$tahapan] = [
                'id' => $tahapan,
                'label' => $tahapanLabels[$tahapan],
                'icon' => $tahapanIcons[$tahapan],
                'color' => $tahapanColors[$tahapan],
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'revision' => $revision,
                'progress' => $total > 0 ? round(($approved / $total) * 100) : 0,
                'pending_percent' => $total > 0 ? round(($pending / $total) * 100) : 0,
            ];
        }
        
        // ==================== DOKUMEN BUTUH TINDAKAN ====================
        
        // Dokumen pending terbaru
        $pendingDocuments = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->where('status', 'pending')
            ->with(['uploader', 'unitKerja'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Dokumen revisi terbaru
        $revisionDocuments = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->where('status', 'revision')
            ->with(['uploader', 'unitKerja'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // ==================== STATISTIK WAKTU ====================
        
        // Hari ini
        $todayStats = [
            'uploaded' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->whereDate('created_at', today())->count(),
            'verified' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->whereDate('verified_at', today())->count(),
        ];
        
        // Minggu ini
        $weekStats = [
            'uploaded' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->whereBetween('verified_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->whereBetween('verified_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('status', 'rejected')->count(),
        ];
        
        // ==================== GRAFIK PER TAHAPAN ====================
        
        $chartData = [];
        foreach ($tahapanList as $tahapan) {
            $chartData['labels'][] = $tahapanLabels[$tahapan];
            $chartData['pending'][] = $tahapanStats[$tahapan]['pending'];
            $chartData['approved'][] = $tahapanStats[$tahapan]['approved'];
            $chartData['revision'][] = $tahapanStats[$tahapan]['revision'];
        }
        
        // ==================== STATISTIK BULANAN ====================
        
        $monthlyStats = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        // ==================== PERINGATAN ====================
        
        $alerts = [];
        
        // Peringatan jika banyak pending
        if ($statusStats['pending'] > 10) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Terdapat {$statusStats['pending']} dokumen yang menunggu verifikasi.",
                'icon' => 'fa-exclamation-triangle'
            ];
        }
        
        // Peringatan jika ada revisi deadline
        $expiredRevisions = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->where('status', 'revision')
            ->whereNotNull('revision_deadline')
            ->where('revision_deadline', '<', now())
            ->count();
            
        if ($expiredRevisions > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$expiredRevisions} dokumen revisi telah melewati deadline.",
                'icon' => 'fa-clock'
            ];
        }
        
        // ==================== KINERJA VERIFIKATOR ====================
        
        $myStats = [
            'verified_today' => Dokumen::where('verified_by', $user->id)
                ->whereDate('verified_at', today())->count(),
            'verified_week' => Dokumen::where('verified_by', $user->id)
                ->whereBetween('verified_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'verified_month' => Dokumen::where('verified_by', $user->id)
                ->whereMonth('verified_at', now()->month)->count(),
        ];
        
        return view('verifikator.dashboard', compact(
            'statusStats',
            'totalDocuments',
            'tahapanStats',
            'pendingDocuments',
            'revisionDocuments',
            'todayStats',
            'weekStats',
            'chartData',
            'monthlyStats',
            'alerts',
            'myStats'
        ));
    }
    
    /**
     * API endpoint untuk mendapatkan statistik pending
     */
    public function getPendingStatistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total' => Dokumen::where('unit_kerja_id', $user->unit_kerja_id)
                ->where('status', 'pending')->count(),
            'by_tahapan' => Dokumen::where('unit_kerja_id', $user->unit_kerja_id)
                ->where('status', 'pending')
                ->select('tahapan', DB::raw('count(*) as count'))
                ->groupBy('tahapan')
                ->get()
        ];
        
        return response()->json($stats);
    }
    
    /**
     * API endpoint untuk statistik realtime
     */
    public function getRealtimeStats()
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        return response()->json([
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'pending')->count(),
            'approved_today' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'approved')
                ->whereDate('verified_at', today())->count(),
            'revision_expired' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'revision')
                ->whereNotNull('revision_deadline')
                ->where('revision_deadline', '<', now())
                ->count(),
        ]);
    }
}