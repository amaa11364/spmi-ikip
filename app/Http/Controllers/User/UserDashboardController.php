<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Services\DokumenWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    protected $workflowService;

    public function __construct(DokumenWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function index()
    {
        $user = auth()->user();
        
        if (!$user || !$user->isUser()) {
            abort(403, 'Unauthorized access');
        }
        
        // ==================== STATISTIK UTAMA ====================
        
        $statistics = [
            'my_documents' => $user->dokumens()->count(),
            'storage_used' => $this->getFormattedStorageUsed($user->id),
            'storage_raw' => Dokumen::where('uploaded_by', $user->id)->sum('file_size'),
            'storage_limit' => 100 * 1024 * 1024, // 100 MB contoh
        ];
        
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
        
        foreach ($tahapanList as $tahapan) {
            $query = $user->dokumens()->where('tahapan', $tahapan);
            
            $tahapanStats[$tahapan] = [
                'label' => $tahapanLabels[$tahapan],
                'total' => (clone $query)->count(),
                'pending' => (clone $query)->where('status', 'pending')->count(),
                'approved' => (clone $query)->where('status', 'approved')->count(),
                'rejected' => (clone $query)->where('status', 'rejected')->count(),
                'revision' => (clone $query)->where('status', 'revision')->count(),
            ];
        }
        
        // ==================== STATISTIK STATUS ====================
        
        $statusStats = [
            'pending' => $user->dokumens()->where('status', 'pending')->count(),
            'approved' => $user->dokumens()->where('status', 'approved')->count(),
            'rejected' => $user->dokumens()->where('status', 'rejected')->count(),
            'revision' => $user->dokumens()->where('status', 'revision')->count(),
        ];
        
        // ==================== DOKUMEN TERBARU ====================
        
        $recentUploads = $user->dokumens()
            ->with(['unitKerja', 'iku'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($dokumen) {
                $dokumen->status_badge = $this->workflowService->getStatusBadge($dokumen->status);
                return $dokumen;
            });
        
        // ==================== DOKUMEN BUTUH TINDAKAN ====================
        
        // Dokumen yang perlu direvisi
        $needRevision = $user->dokumens()
            ->where('status', 'revision')
            ->with(['unitKerja', 'iku'])
            ->latest('updated_at')
            ->get()
            ->map(function($dokumen) {
                $deadline = $dokumen->revision_deadline;
                $dokumen->deadline_info = $deadline ? [
                    'date' => $deadline->format('d M Y'),
                    'days_left' => now()->diffInDays($deadline, false),
                    'is_expired' => $deadline < now()
                ] : null;
                return $dokumen;
            });
        
        // ==================== GRAFIK BULANAN ====================
        
        $monthlyStats = $user->dokumens()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved")
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();
        
        // Format untuk chart
        $chartMonths = [];
        $chartTotals = [];
        $chartApproved = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyStats->firstWhere('month', $i);
            $chartMonths[] = date('M', mktime(0, 0, 0, $i, 1));
            $chartTotals[] = $monthData->total ?? 0;
            $chartApproved[] = $monthData->approved ?? 0;
        }
        
        // ==================== STATISTIK PER UNIT KERJA ====================
        
        $unitKerjaStats = $user->dokumens()
            ->select('unit_kerja_id', DB::raw('count(*) as total'))
            ->with('unitKerja')
            ->groupBy('unit_kerja_id')
            ->get();
        
        // ==================== PERSENTASE PENYELESAIAN ====================
        
        $totalDocs = array_sum($statusStats);
        $completionRate = $totalDocs > 0 
            ? round(($statusStats['approved'] / $totalDocs) * 100, 1)
            : 0;
        
        // ==================== AKTIVITAS TERBARU ====================
        
        $recentActivity = collect();
        
        // Aktivitas upload
        foreach ($recentUploads as $upload) {
            $recentActivity->push([
                'type' => 'upload',
                'message' => "Mengupload dokumen: {$upload->nama_dokumen}",
                'time' => $upload->created_at,
                'icon' => 'fa-upload',
                'color' => 'primary',
                'tahapan' => $upload->tahapan,
            ]);
        }
        
        // Aktivitas verifikasi
        $recentVerifications = $user->dokumens()
            ->whereNotNull('verified_at')
            ->latest('verified_at')
            ->take(5)
            ->get();
            
        foreach ($recentVerifications as $verif) {
            $recentActivity->push([
                'type' => 'verification',
                'message' => "Dokumen {$verif->nama_dokumen} telah {$verif->status_label}",
                'time' => $verif->verified_at,
                'icon' => $verif->status === 'approved' ? 'fa-check-circle' : 'fa-times-circle',
                'color' => $verif->status === 'approved' ? 'success' : 'danger',
                'tahapan' => $verif->tahapan,
            ]);
        }
        
        $recentActivity = $recentActivity->sortByDesc('time')->take(10);
        
        // ==================== PERINGATAN ====================
        
        $alerts = [];
        
        // Peringatan storage
        $storagePercent = ($statistics['storage_raw'] / $statistics['storage_limit']) * 100;
        if ($storagePercent > 80) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Penyimpanan Anda hampir penuh ({$storagePercent}%). Hapus file yang tidak diperlukan.",
                'icon' => 'fa-database'
            ];
        }
        
        // Peringatan revisi deadline
        foreach ($needRevision as $rev) {
            if ($rev->deadline_info && $rev->deadline_info['is_expired']) {
                $alerts[] = [
                    'type' => 'danger',
                    'message' => "Dokumen '{$rev->nama_dokumen}' telah melewati deadline revisi.",
                    'icon' => 'fa-clock'
                ];
                break;
            }
        }
        
        // Peringatan dokumen pending lama
        $oldPending = $user->dokumens()
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDays(7))
            ->count();
            
        if ($oldPending > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$oldPending} dokumen Anda masih menunggu verifikasi lebih dari 7 hari.",
                'icon' => 'fa-hourglass-half'
            ];
        }
        
        return view('user.dashboard', compact(
            'statistics',
            'tahapanStats',
            'statusStats',
            'recentUploads',
            'needRevision',
            'monthlyStats',
            'chartMonths',
            'chartTotals',
            'chartApproved',
            'unitKerjaStats',
            'completionRate',
            'recentActivity',
            'alerts'
        ));
    }
    
    /**
     * Get formatted storage used
     */
    private function getFormattedStorageUsed($userId)
    {
        $totalSize = Dokumen::where('uploaded_by', $userId)->sum('file_size');
        
        if ($totalSize < 1024) {
            return $totalSize . ' B';
        } elseif ($totalSize < 1048576) {
            return round($totalSize / 1024, 2) . ' KB';
        } elseif ($totalSize < 1073741824) {
            return round($totalSize / 1048576, 2) . ' MB';
        } else {
            return round($totalSize / 1073741824, 2) . ' GB';
        }
    }
    
    /**
     * API endpoint untuk statistik realtime user
     */
    public function getRealtimeStats()
    {
        $user = auth()->user();
        
        return response()->json([
            'pending' => $user->dokumens()->where('status', 'pending')->count(),
            'revision' => $user->dokumens()->where('status', 'revision')->count(),
            'revision_expired' => $user->dokumens()
                ->where('status', 'revision')
                ->whereNotNull('revision_deadline')
                ->where('revision_deadline', '<', now())
                ->count(),
            'storage_percent' => $this->getStoragePercentage($user->id),
        ]);
    }
    
    /**
     * Get storage percentage
     */
    private function getStoragePercentage($userId)
    {
        $used = Dokumen::where('uploaded_by', $userId)->sum('file_size');
        $limit = 100 * 1024 * 1024; // 100 MB
        
        return round(($used / $limit) * 100, 1);
    }
}