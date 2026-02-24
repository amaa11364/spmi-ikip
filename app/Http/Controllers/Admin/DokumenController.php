<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Prodi;
use App\Models\Iku;
use App\Services\DokumenWorkflowService;
use App\Http\Requests\AdminDokumenUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    protected $workflowService;

    public function __construct(DokumenWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Display a listing of all documents.
     */
    public function index(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'prodi', 'iku', 'uploader', 'verifier']);
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'all') {
                // tampilkan semua
            } else {
                $query->byStatus($request->status);
            }
        }
        
        // ✅ FILTER BERDASARKAN TAHAPAN PPEPP
        if ($request->filled('tahapan')) {
            $tahapanList = ['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'];
            if (in_array($request->tahapan, $tahapanList)) {
                $query->byTahapan($request->tahapan);
            }
        }
        
        // Filter berdasarkan unit kerja
        if ($request->filled('unit_kerja_id')) {
            $query->byUnitKerja($request->unit_kerja_id);
        }
        
        // Filter berdasarkan prodi
        if ($request->filled('prodi_id')) {
            $query->byProdi($request->prodi_id);
        }
        
        // Filter berdasarkan jenis upload
        if ($request->filled('jenis_upload')) {
            $query->byJenisUpload($request->jenis_upload);
        }
        
        // Filter tanggal
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }
        
        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $dokumens = $query->paginate(15)->withQueryString();
        
        // Data untuk filter dropdown
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $ikus = Iku::orderBy('nama')->get();
        
        // ✅ Statistik lengkap dengan filter tahapan
        $statistics = [
            'total' => Dokumen::count(),
            'pending' => Dokumen::pending()->count(),
            'approved' => Dokumen::approved()->count(),
            'rejected' => Dokumen::rejected()->count(),
            'revision' => Dokumen::where('status', 'revision')->count(),
            'public' => Dokumen::public()->count(),
            'private' => Dokumen::where('is_public', false)->count(),
        ];
        
        // ✅ Statistik per tahapan PPEPP
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
            $queryTahapan = Dokumen::where('tahapan', $tahapan);
            
            $tahapanStats[$tahapan] = [
                'kode' => $tahapan,
                'label' => $tahapanLabels[$tahapan],
                'total' => (clone $queryTahapan)->count(),
                'pending' => (clone $queryTahapan)->where('status', 'pending')->count(),
                'approved' => (clone $queryTahapan)->where('status', 'approved')->count(),
                'rejected' => (clone $queryTahapan)->where('status', 'rejected')->count(),
                'revision' => (clone $queryTahapan)->where('status', 'revision')->count(),
            ];
        }
        
        return view('admin.dokumen.index', compact(
            'dokumens', 
            'unitKerjas', 
            'prodis', 
            'ikus',
            'statistics',
            'tahapanStats'
        ));
    }

    /**
     * Display the specified document.
     */
    public function show($id)
    {
        $dokumen = Dokumen::with([
                'unitKerja', 
                'prodi', 
                'iku', 
                'uploader', 
                'verifier', 
                'comments.user'
            ])
            ->findOrFail($id);
        
        // Dapatkan history workflow
        $workflowHistory = $this->workflowService->getWorkflowHistory($dokumen);
        
        // Dapatkan status yang mungkin untuk admin
        $possibleNextStatuses = $this->workflowService->getPossibleNextStatuses($dokumen, auth()->user());
            
        return view('admin.dokumen.show', compact(
            'dokumen',
            'workflowHistory',
            'possibleNextStatuses'
        ));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $ikus = Iku::orderBy('nama')->get();
        
        $tahapanOptions = [
            'penetapan' => 'Penetapan SPMI',
            'pelaksanaan' => 'Pelaksanaan SPMI',
            'evaluasi' => 'Evaluasi SPMI',
            'pengendalian' => 'Pengendalian SPMI',
            'peningkatan' => 'Peningkatan SPMI',
        ];
        
        $statusOptions = [
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi',
        ];
        
        return view('admin.dokumen.edit', compact(
            'dokumen', 
            'unitKerjas', 
            'prodis', 
            'ikus',
            'tahapanOptions',
            'statusOptions'
        ));
    }

    /**
     * Update the specified document.
     */
    public function update(AdminDokumenUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $dokumen = Dokumen::findOrFail($id);
            $oldStatus = $dokumen->status;
            $oldTahapan = $dokumen->tahapan;
            
            // Data sudah tervalidasi oleh AdminDokumenUpdateRequest
            $validated = $request->validated();
            
            // Handle is_public checkbox
            $validated['is_public'] = $request->boolean('is_public', false);
            
            // Cek apakah status berubah
            $statusChanged = isset($validated['status']) && $validated['status'] != $oldStatus;
            
            // Update dokumen
            $dokumen->update($validated);
            
            // Jika status berubah, catat transisi dan trigger event
            if ($statusChanged) {
                // Gunakan workflow service untuk transisi status
                $this->workflowService->transition(
                    $dokumen,
                    $validated['status'],
                    auth()->user(),
                    [
                        'reason' => $request->input('alasan_perubahan', 'Diubah oleh admin'),
                        'notes' => $request->input('catatan_admin'),
                    ]
                );
            }
            
            // Jika tahapan berubah, catat perubahan
            if (isset($validated['tahapan']) && $validated['tahapan'] != $oldTahapan) {
                // Log perubahan tahapan
                activity()
                    ->performedOn($dokumen)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old_tahapan' => $oldTahapan,
                        'new_tahapan' => $validated['tahapan'],
                    ])
                    ->log('admin_mengubah_tahapan');
            }

            DB::commit();

            return redirect()
                ->route('admin.dokumen.show', $dokumen->id)
                ->with('success', 'Dokumen berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Gagal memperbarui dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update status dokumen secara spesifik
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,revision',
            'alasan' => 'required_if:status,rejected,revision|nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $dokumen = Dokumen::findOrFail($id);
            $oldStatus = $dokumen->status;

            // Gunakan workflow service untuk transisi
            $dokumen = $this->workflowService->transition(
                $dokumen,
                $request->status,
                auth()->user(),
                [
                    'reason' => $request->alasan,
                    'notes' => $request->catatan,
                ]
            );

            // Tambahkan komentar jika ada
            if ($request->filled('komentar')) {
                $dokumen->comments()->create([
                    'user_id' => auth()->id(),
                    'content' => $request->komentar,
                    'type' => 'status_change'
                ]);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', "Status dokumen berhasil diubah dari '{$oldStatus}' menjadi '{$request->status}'.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update status untuk multiple dokumen
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'dokumen_ids' => 'required|array',
            'dokumen_ids.*' => 'exists:dokumens,id',
            'status' => 'required|in:pending,approved,rejected,revision',
            'alasan' => 'required_if:status,rejected,revision|nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $updated = 0;
            $failed = 0;

            foreach ($request->dokumen_ids as $id) {
                try {
                    $dokumen = Dokumen::find($id);
                    $this->workflowService->transition(
                        $dokumen,
                        $request->status,
                        auth()->user(),
                        ['reason' => $request->alasan]
                    );
                    $updated++;
                } catch (\Exception $e) {
                    $failed++;
                    \Log::warning("Gagal update status dokumen ID {$id}: " . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', "{$updated} dokumen berhasil diperbarui. {$failed} dokumen gagal.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Gagal melakukan bulk update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified document.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $dokumen = Dokumen::findOrFail($id);
            
            // Hapus file fisik jika ada
            if ($dokumen->jenis_upload === 'file' && $dokumen->fileExists()) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            // Hapus komentar terkait
            $dokumen->comments()->delete();
            
            // Hapus dokumen
            $dokumen->delete();

            DB::commit();

            return redirect()
                ->route('admin.dokumen.index')
                ->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Toggle public status of the document.
     */
    public function togglePublic($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);
            
            $oldValue = $dokumen->is_public;
            $dokumen->update([
                'is_public' => !$dokumen->is_public
            ]);
            
            $status = $dokumen->is_public ? 'dipublikasikan' : 'ditutup';
            
            // Log aktivitas
            activity()
                ->performedOn($dokumen)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_public' => $oldValue,
                    'new_public' => $dokumen->is_public,
                ])
                ->log('admin_toggle_public');
            
            return redirect()
                ->route('admin.dokumen.index')
                ->with('success', "Dokumen berhasil {$status}.");

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengubah status publik: ' . $e->getMessage());
        }
    }

    /**
     * Export documents data.
     */
    public function export(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'iku', 'uploader']);
        
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
        
        // Generate Excel
        // Implementasi sesuai kebutuhan
        
        return redirect()
            ->route('admin.dokumen.index')
            ->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}