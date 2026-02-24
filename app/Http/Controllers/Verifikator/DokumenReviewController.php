<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Services\DokumenWorkflowService;
use App\Http\Requests\VerifikatorReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DokumenReviewController extends Controller
{
    protected $workflowService;

    public function __construct(DokumenWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
        $this->middleware(['auth', 'verifikator']);
    }

    /**
     * Daftar dokumen untuk direview dengan filtering lengkap
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        // Query dasar
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['uploader', 'unitKerja', 'iku']);

        // FILTER BERDASARKAN STATUS
        if ($request->filled('status')) {
            if ($request->status === 'all') {
                // Tampilkan semua
            } elseif ($request->status === 'need_action') {
                // Butuh tindakan: pending dan revision
                $query->whereIn('status', ['pending', 'revision']);
            } else {
                $query->where('status', $request->status);
            }
        } else {
            // Default: tampilkan yang butuh tindakan
            $query->whereIn('status', ['pending', 'revision']);
        }

        // ✅ FILTER BERDASARKAN TAHAPAN PPEPP
        if ($request->filled('tahapan')) {
            $tahapanList = ['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'];
            if (in_array($request->tahapan, $tahapanList)) {
                $query->where('tahapan', $request->tahapan);
            }
        }

        // FILTER BERDASARKAN PENCARIAN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dokumen', 'like', "%{$search}%")
                  ->orWhere('jenis_dokumen', 'like', "%{$search}%")
                  ->orWhereHas('uploader', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // FILTER BERDASARKAN TANGGAL
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // FILTER BERDASARKAN IKU
        if ($request->filled('iku_id')) {
            $query->where('iku_id', $request->iku_id);
        }

        // SORTING
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $dokumens = $query->paginate(20)->withQueryString();

        // ==================== STATISTIK LENGKAP ====================
        
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

        // ✅ Statistik per tahapan PPEPP
        $tahapanStats = [];
        $tahapanList = ['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'];
        $tahapanLabels = [
            'penetapan' => 'Penetapan',
            'pelaksanaan' => 'Pelaksanaan',
            'evaluasi' => 'Evaluasi',
            'pengendalian' => 'Pengendalian',
            'peningkatan' => 'Peningkatan',
        ];
        
        foreach ($tahapanList as $tahapan) {
            $queryTahapan = Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('tahapan', $tahapan);
            
            $tahapanStats[$tahapan] = [
                'total' => (clone $queryTahapan)->count(),
                'pending' => (clone $queryTahapan)->where('status', 'pending')->count(),
                'approved' => (clone $queryTahapan)->where('status', 'approved')->count(),
                'rejected' => (clone $queryTahapan)->where('status', 'rejected')->count(),
                'revision' => (clone $queryTahapan)->where('status', 'revision')->count(),
                'label' => $tahapanLabels[$tahapan],
                'icon' => $this->getTahapanIcon($tahapan),
                'color' => $this->getTahapanColor($tahapan),
            ];
        }

        // Statistik timeline (7 hari terakhir)
        $timelineStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $timelineStats[$date] = [
                'date' => $date,
                'label' => now()->subDays($i)->format('d M'),
                'total' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                    ->whereDate('created_at', $date)->count(),
                'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                    ->whereDate('created_at', $date)
                    ->where('status', 'approved')->count(),
            ];
        }

        // Statistik verifikator (kinerja)
        $verifikatorStats = [
            'total_verified' => Dokumen::where('verified_by', $user->id)->count(),
            'today_verified' => Dokumen::where('verified_by', $user->id)
                ->whereDate('verified_at', today())->count(),
            'week_verified' => Dokumen::where('verified_by', $user->id)
                ->whereBetween('verified_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'avg_response_time' => $this->getAverageResponseTime($user->id),
        ];

        return view('verifikator.dokumen.index', compact(
            'dokumens',
            'statusStats',
            'tahapanStats',
            'timelineStats',
            'verifikatorStats'
        ));
    }

    /**
     * Detail dokumen untuk review
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $dokumen = Dokumen::with([
                'uploader',
                'unitKerja',
                'iku',
                'verifier',
                'comments' => function($query) {
                    $query->with('user')->latest();
                }
            ])
            ->findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
            abort(403, 'Anda tidak berhak mengakses dokumen ini.');
        }

        // Dapatkan status yang mungkin untuk role ini
        $possibleNextStatuses = $this->workflowService->getPossibleNextStatuses($dokumen, $user);
        
        // Dapatkan history workflow
        $workflowHistory = $this->workflowService->getWorkflowHistory($dokumen);

        // Cek apakah dokumen perlu direvisi dan deadline
        $revisionInfo = null;
        if ($dokumen->status === 'revision' && $dokumen->revision_deadline) {
            $revisionInfo = [
                'deadline' => $dokumen->revision_deadline->format('d M Y'),
                'days_left' => now()->diffInDays($dokumen->revision_deadline, false),
                'is_expired' => $dokumen->revision_deadline < now(),
                'instructions' => $dokumen->revision_instructions,
            ];
        }

        return view('verifikator.dokumen.show', compact(
            'dokumen',
            'possibleNextStatuses',
            'workflowHistory',
            'revisionInfo'
        ));
    }

    /**
     * Approve dokumen
     */
    public function approve(VerifikatorReviewRequest $request, $id)
    {
        return $this->processTransition($id, 'approved', $request);
    }

    /**
     * Reject dokumen
     */
    public function reject(VerifikatorReviewRequest $request, $id)
    {
        return $this->processTransition($id, 'rejected', $request);
    }

    /**
     * Minta revisi dokumen
     */
    public function requestRevision(VerifikatorReviewRequest $request, $id)
    {
        return $this->processTransition($id, 'revision', $request);
    }

    /**
     * Proses transisi status menggunakan workflow service
     */
    protected function processTransition($id, $newStatus, VerifikatorReviewRequest $request)
    {
        try {
            DB::beginTransaction();

            $dokumen = Dokumen::findOrFail($id);
            $user = Auth::user();

            // Cek akses unit kerja
            if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
                throw new \Exception('Anda tidak berhak mengubah dokumen ini.');
            }

            // ✅ PASTIKAN TAHAPAN TIDAK BERUBAH
            // Verifikator tidak boleh mengubah tahapan, jadi kita pastikan tidak ada field tahapan di request
            if ($request->has('tahapan')) {
                throw new \Exception('Verifikator tidak diizinkan mengubah tahapan dokumen.');
            }

            // Data untuk transisi dari request yang sudah tervalidasi
            $data = [];
            
            if ($newStatus === 'rejected') {
                $data['reason'] = $request->alasan_penolakan;
            }
            
            if ($newStatus === 'revision') {
                $data['instructions'] = $request->instruksi_revisi;
                $data['deadline'] = $request->deadline;
                $data['reason'] = $request->instruksi_revisi;
            }

            // Lakukan transisi menggunakan workflow service
            $dokumen = $this->workflowService->transition(
                $dokumen,
                $newStatus,
                $user,
                $data
            );

            // Tambahkan komentar jika ada
            if ($request->filled('komentar')) {
                $dokumen->comments()->create([
                    'user_id' => $user->id,
                    'content' => $request->komentar,
                    'type' => $newStatus
                ]);
            }

            DB::commit();

            // Redirect dengan pesan sukses
            $messages = [
                'approved' => 'Dokumen berhasil disetujui.',
                'rejected' => 'Dokumen telah ditolak.',
                'revision' => 'Permintaan revisi telah dikirim.'
            ];

            return redirect()
                ->route('verifikator.dokumen.index')
                ->with('success', $messages[$newStatus]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update dokumen setelah direvisi oleh user
     */
    public function updateAfterRevision(Request $request, $id)
    {
        $request->validate([
            'file_dokumen' => 'required|file|max:10240|mimes:pdf,doc,docx,xlsx,jpg,jpeg,png',
            'keterangan_revisi' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $dokumen = Dokumen::findOrFail($id);
            $user = Auth::user();

            // Cek akses
            if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
                throw new \Exception('Anda tidak berhak mengubah dokumen ini.');
            }

            // Cek status harus revision
            if ($dokumen->status !== 'revision') {
                throw new \Exception('Dokumen ini tidak dalam status revisi.');
            }

            // Upload file baru
            if ($request->hasFile('file_dokumen') && $request->file('file_dokumen')->isValid()) {
                $file = $request->file('file_dokumen');
                
                // Hapus file lama
                if ($dokumen->fileExists()) {
                    Storage::disk('public')->delete($dokumen->file_path);
                }
                
                // Upload file baru
                $path = $file->store('dokumen/revisi', 'public');
                
                // Update dokumen
                $dokumen->update([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                ]);
            }

            // Ubah status kembali ke pending untuk diverifikasi ulang
            $this->workflowService->transition(
                $dokumen,
                'pending',
                $user,
                ['reason' => 'Dokumen telah direvisi']
            );

            DB::commit();

            return redirect()
                ->route('verifikator.dokumen.show', $dokumen->id)
                ->with('success', 'Dokumen revisi berhasil diupload dan menunggu verifikasi ulang.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Gagal mengupload revisi: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumen
     */
    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            abort(403);
        }
        
        if ($dokumen->jenis_upload === 'link') {
            return redirect()->away($dokumen->file_path);
        }
        
        $filePath = storage_path('app/public/' . $dokumen->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return response()->download($filePath, $dokumen->file_name);
    }

    /**
     * Preview dokumen (PDF)
     */
    public function preview($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            abort(403);
        }
        
        if ($dokumen->jenis_upload === 'link') {
            return redirect()->away($dokumen->file_path);
        }
        
        if ($dokumen->file_extension !== 'pdf') {
            return back()->with('info', 'Preview hanya tersedia untuk file PDF.');
        }
        
        $filePath = storage_path('app/public/' . $dokumen->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return response()->file($filePath);
    }

    /**
     * Tambah komentar
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|min:3|max:500'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            return back()->with('error', 'Akses ditolak.');
        }
        
        $dokumen->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->comment,
            'type' => 'comment'
        ]);
        
        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Statistik lengkap verifikator
     */
    public function statistics()
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        // Statistik umum
        $statistics = [
            'total' => Dokumen::where('unit_kerja_id', $unitKerjaId)->count(),
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'pending')->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'rejected')->count(),
            'revision' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'revision')->count(),
        ];

        // ✅ Statistik per tahapan
        $byTahapan = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->select('tahapan', DB::raw('count(*) as total'))
            ->groupBy('tahapan')
            ->get();

        // Statistik per jenis dokumen
        $byJenis = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->select('jenis_dokumen', DB::raw('count(*) as total'))
            ->groupBy('jenis_dokumen')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Statistik per bulan
        $byMonth = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Statistik kinerja verifikator
        $performance = [
            'total_verified' => Dokumen::where('verified_by', $user->id)->count(),
            'by_status' => Dokumen::where('verified_by', $user->id)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'daily_avg' => Dokumen::where('verified_by', $user->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count() / 30,
        ];

        return view('verifikator.statistik.index', compact(
            'statistics',
            'byTahapan',
            'byJenis',
            'byMonth',
            'performance'
        ));
    }

    /**
     * Export laporan
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['uploader', 'unitKerja', 'iku']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('tahapan')) {
            $query->where('tahapan', $request->tahapan);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $dokumens = $query->get();
        
        // Generate Excel/PDF
        // Implementasi export sesuai kebutuhan
        
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    /**
     * Helper: Get average response time
     */
    protected function getAverageResponseTime($verifikatorId)
    {
        $avg = Dokumen::where('verified_by', $verifikatorId)
            ->whereNotNull('verified_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, verified_at)) as avg_hours'))
            ->first();
            
        if (!$avg || !$avg->avg_hours) {
            return 'N/A';
        }
        
        $hours = round($avg->avg_hours);
        
        if ($hours < 24) {
            return $hours . ' jam';
        } else {
            return round($hours / 24, 1) . ' hari';
        }
    }

    /**
     * Helper: Get icon for tahapan
     */
    protected function getTahapanIcon($tahapan)
    {
        $icons = [
            'penetapan' => 'fas fa-file-signature',
            'pelaksanaan' => 'fas fa-play-circle',
            'evaluasi' => 'fas fa-chart-line',
            'pengendalian' => 'fas fa-tasks',
            'peningkatan' => 'fas fa-arrow-up',
        ];
        
        return $icons[$tahapan] ?? 'fas fa-file';
    }

    /**
     * Helper: Get color for tahapan
     */
    protected function getTahapanColor($tahapan)
    {
        $colors = [
            'penetapan' => 'primary',
            'pelaksanaan' => 'success',
            'evaluasi' => 'warning',
            'pengendalian' => 'info',
            'peningkatan' => 'purple',
        ];
        
        return $colors[$tahapan] ?? 'secondary';
    }
}