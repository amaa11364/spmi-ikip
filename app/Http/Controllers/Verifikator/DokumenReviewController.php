<?php
// app/Http/Controllers/Verifikator/DokumenReviewController.php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Services\DokumenWorkflowService;
use App\Http\Requests\VerifikatorReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        
        // ==================== DEBUGGING ====================
        // Log informasi user
        Log::info('VERIFIKATOR INDEX - User Info', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'unit_kerja_id' => $unitKerjaId,
            'role' => $user->role ?? 'verifikator'
        ]);
        
        // Cek total dokumen di database
        $totalAllDokumen = Dokumen::count();
        $totalPendingAll = Dokumen::where('status', 'pending')->count();
        $totalByUnitKerja = Dokumen::where('unit_kerja_id', $unitKerjaId)->count();
        $totalPendingByUnitKerja = Dokumen::where('unit_kerja_id', $unitKerjaId)
                                         ->where('status', 'pending')
                                         ->count();
        
        Log::info('VERIFIKATOR INDEX - Dokumen Stats', [
            'total_all_dokumen' => $totalAllDokumen,
            'total_pending_all' => $totalPendingAll,
            'total_by_unit_kerja' => $totalByUnitKerja,
            'total_pending_by_unit_kerja' => $totalPendingByUnitKerja
        ]);
        
        // ==================== QUERY DASAR ====================
        // Gunakan query builder untuk fleksibilitas
        $query = Dokumen::with([
            'uploader', 
            'unitKerja', 
            'iku',
            'verifier'
        ]);
        
        // FILTER UNIT KERJA - PASTIKAN INI BEKERJA
        if ($request->has('debug_mode') && $request->debug_mode == 'all') {
            // Mode debug: tampilkan semua dokumen tanpa filter unit kerja
            Log::info('VERIFIKATOR INDEX - DEBUG MODE ALL: Menampilkan semua dokumen');
        } else {
            // Mode normal: filter berdasarkan unit kerja verifikator
            $query->where('unit_kerja_id', $unitKerjaId);
            Log::info('VERIFIKATOR INDEX - Filter by unit_kerja_id', ['unit_kerja_id' => $unitKerjaId]);
        }
        
        // FILTER BERDASARKAN STATUS
        if ($request->filled('status')) {
            if ($request->status === 'all') {
                // Tampilkan semua status
                Log::info('VERIFIKATOR INDEX - Filter status: all');
            } elseif ($request->status === 'need_action') {
                // Butuh tindakan: pending dan revision
                $query->whereIn('status', ['pending', 'revision']);
                Log::info('VERIFIKATOR INDEX - Filter status: need_action (pending, revision)');
            } else {
                $query->where('status', $request->status);
                Log::info('VERIFIKATOR INDEX - Filter status', ['status' => $request->status]);
            }
        } else {
            // Default: tampilkan yang butuh tindakan (pending & revision)
            $query->whereIn('status', ['pending', 'revision']);
            Log::info('VERIFIKATOR INDEX - Default filter: pending & revision');
        }
        
        // FILTER BERDASARKAN TAHAPAN PPEPP
        if ($request->filled('tahapan')) {
            $tahapanList = ['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'];
            if (in_array($request->tahapan, $tahapanList)) {
                $query->where('tahapan', $request->tahapan);
                Log::info('VERIFIKATOR INDEX - Filter tahapan', ['tahapan' => $request->tahapan]);
            }
        }
        
        // FILTER BERDASARKAN JENIS DOKUMEN
        if ($request->filled('jenis')) {
            $query->where('jenis_dokumen', 'like', '%' . $request->jenis . '%');
        }
        
        // FILTER BERDASARKAN PENCARIAN (JUDUL, NOMOR, UPLOADER)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dokumen', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                  ->orWhere('jenis_dokumen', 'like', "%{$search}%")
                  ->orWhereHas('uploader', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
            Log::info('VERIFIKATOR INDEX - Filter search', ['search' => $search]);
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
        
        // Validasi field sorting yang diizinkan
        $allowedSortFields = ['created_at', 'nama_dokumen', 'status', 'tahapan', 'updated_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }
        
        $query->orderBy($sortField, $sortDirection);
        
        // Log query untuk debugging
        Log::info('VERIFIKATOR INDEX - Final Query', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);
        
        // Hitung total sebelum pagination
        $totalQueryResults = $query->count();
        Log::info('VERIFIKATOR INDEX - Total results before pagination', ['count' => $totalQueryResults]);
        
        // Pagination
        $perPage = $request->get('per_page', 20);
        $dokumens = $query->paginate($perPage)->withQueryString();
        
        Log::info('VERIFIKATOR INDEX - Pagination results', [
            'total' => $dokumens->total(),
            'per_page' => $dokumens->perPage(),
            'current_page' => $dokumens->currentPage(),
            'from' => $dokumens->firstItem(),
            'to' => $dokumens->lastItem()
        ]);

        // ==================== STATISTIK LENGKAP ====================
        
        // Statistik per status (hanya untuk unit kerja verifikator)
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
        
        // Statistik global (semua unit kerja) untuk perbandingan
        $globalStats = [
            'total' => Dokumen::count(),
            'pending' => Dokumen::where('status', 'pending')->count(),
            'approved' => Dokumen::where('status', 'approved')->count(),
            'rejected' => Dokumen::where('status', 'rejected')->count(),
            'revision' => Dokumen::where('status', 'revision')->count(),
        ];

        // Statistik per tahapan PPEPP
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

        // Dapatkan daftar unit kerja untuk filter (jika admin)
        $unitKerjas = [];
        if ($user->hasRole('admin')) {
            $unitKerjas = UnitKerja::where('status', true)->get();
        }

        // Jika tidak ada hasil, beri warning di log
        if ($dokumens->isEmpty()) {
            Log::warning('VERIFIKATOR INDEX - No documents found', [
                'user_id' => $user->id,
                'unit_kerja_id' => $unitKerjaId,
                'filters' => $request->all()
            ]);
        }

        return view('verifikator.dokumen.index', compact(
            'dokumens',
            'statusStats',
            'globalStats',
            'tahapanStats',
            'timelineStats',
            'verifikatorStats',
            'unitKerjas'
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
        
        // Log untuk debugging
        Log::info('VERIFIKATOR SHOW - Viewing document', [
            'dokumen_id' => $id,
            'dokumen_unit_kerja' => $dokumen->unit_kerja_id,
            'user_unit_kerja' => $user->unit_kerja_id,
            'status' => $dokumen->status
        ]);
        
        // Cek akses - VERIFIKASI UNIT KERJA
        if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
            Log::warning('VERIFIKATOR SHOW - Access denied', [
                'dokumen_id' => $id,
                'dokumen_unit_kerja' => $dokumen->unit_kerja_id,
                'user_unit_kerja' => $user->unit_kerja_id
            ]);
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
                'deadline' => $dokumen->revision_deadline instanceof \Carbon\Carbon 
                    ? $dokumen->revision_deadline->format('d M Y') 
                    : $dokumen->revision_deadline,
                'days_left' => $dokumen->revision_deadline instanceof \Carbon\Carbon 
                    ? now()->diffInDays($dokumen->revision_deadline, false) 
                    : null,
                'is_expired' => $dokumen->revision_deadline instanceof \Carbon\Carbon 
                    ? $dokumen->revision_deadline < now() 
                    : false,
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

            // Log sebelum proses
            Log::info('VERIFIKATOR TRANSITION - Starting', [
                'dokumen_id' => $id,
                'current_status' => $dokumen->status,
                'new_status' => $newStatus,
                'user_id' => $user->id
            ]);

            // Cek akses unit kerja
            if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
                throw new \Exception('Anda tidak berhak mengubah dokumen ini.');
            }

            // PASTIKAN TAHAPAN TIDAK BERUBAH
            if ($request->has('tahapan')) {
                throw new \Exception('Verifikator tidak diizinkan mengubah tahapan dokumen.');
            }

            // Data untuk transisi dari request yang sudah tervalidasi
            $data = [];
            
            if ($newStatus === 'rejected') {
                $data['reason'] = $request->alasan_penolakan ?? $request->reason;
            }
            
            if ($newStatus === 'revision') {
                $data['instructions'] = $request->instruksi_revisi ?? $request->instructions;
                $data['deadline'] = $request->deadline;
                $data['reason'] = $request->instruksi_revisi ?? $request->instructions;
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

            Log::info('VERIFIKATOR TRANSITION - Success', [
                'dokumen_id' => $id,
                'new_status' => $newStatus
            ]);

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
            
            Log::error('VERIFIKATOR TRANSITION - Error', [
                'dokumen_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
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

        // Statistik per tahapan
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
     * Debug route - Tampilkan semua dokumen
     */
    public function debug()
    {
        $user = Auth::user();
        
        $dokumens = Dokumen::with(['uploader', 'unitKerja'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'unit_kerja_id' => $user->unit_kerja_id,
                'role' => $user->role
            ],
            'total_dokumen' => Dokumen::count(),
            'dokumens' => $dokumens->map(function($d) {
                return [
                    'id' => $d->id,
                    'nama_dokumen' => $d->nama_dokumen,
                    'unit_kerja_id' => $d->unit_kerja_id,
                    'unit_kerja_nama' => $d->unitKerja->nama ?? 'N/A',
                    'status' => $d->status,
                    'tahapan' => $d->tahapan,
                    'uploader_id' => $d->uploaded_by,
                    'uploader_name' => $d->uploader->name ?? 'N/A',
                    'uploader_unit_kerja' => $d->uploader->unit_kerja_id ?? 'N/A',
                    'created_at' => $d->created_at ? $d->created_at->format('Y-m-d H:i:s') : null
                ];
            })
        ];
        
        return response()->json($data);
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