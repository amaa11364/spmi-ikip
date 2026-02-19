<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiSPMI;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvaluasiSpmController extends Controller
{
    /**
     * Display a listing of evaluasi.
     */
    public function index(Request $request)
    {
        $query = EvaluasiSPMI::with(['dokumen', 'unitKerja', 'iku']);
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }
        
        // Filter tipe
        if ($request->has('tipe') && $request->tipe != '' && $request->tipe != 'all') {
            $query->byTipe($request->tipe);
        }
        
        // Filter status
        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->byStatus($request->status);
        }
        
        // Filter status dokumen
        if ($request->has('status_dokumen') && $request->status_dokumen != '' && $request->status_dokumen != 'all') {
            $query->byStatusDokumen($request->status_dokumen);
        }
        
        // Filter tahun
        if ($request->has('tahun') && $request->tahun != '' && $request->tahun != 'all') {
            $query->byTahun($request->tahun);
        }
        
        // Filter unit kerja
        if ($request->has('unit_kerja_id') && $request->unit_kerja_id != '' && $request->unit_kerja_id != 'all') {
            $query->where('unit_kerja_id', $request->unit_kerja_id);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $evaluasi = $query->paginate(20);
        
        // Data untuk filter dropdown
        $tahunList = EvaluasiSPMI::getTahunList();
        $unitKerjaList = UnitKerja::where('status', true)->get();
        
        // Statistics
        $statistics = EvaluasiSPMI::getStatistics();
        
        // Kelompokkan untuk tabs
        $kelompok = [
            'ami' => EvaluasiSPMI::where('tipe_evaluasi', 'ami')->orderBy('created_at', 'desc')->get(),
            'edom' => EvaluasiSPMI::where('tipe_evaluasi', 'edom')->orderBy('created_at', 'desc')->get(),
            'evaluasi_layanan' => EvaluasiSPMI::where('tipe_evaluasi', 'evaluasi_layanan')->orderBy('created_at', 'desc')->get(),
            'evaluasi_kinerja' => EvaluasiSPMI::where('tipe_evaluasi', 'evaluasi_kinerja')->orderBy('created_at', 'desc')->get(),
        ];
        
        return view('dashboard.spmi.evaluasi.index', compact(
            'evaluasi', 
            'kelompok', 
            'tahunList', 
            'unitKerjaList',
            'statistics'
        ));
    }

    /**
     * Show the form for creating a new evaluasi.
     */
    public function create()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.evaluasi.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created evaluasi in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_evaluasi' => 'required|string|max:255',
                'tipe_evaluasi' => 'required|in:ami,edom,evaluasi_layanan,evaluasi_kinerja',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'periode' => 'nullable|string|max:100',
                'status' => 'required|in:aktif,nonaktif,selesai,berjalan',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'hasil_evaluasi' => 'nullable|string',
                'rekomendasi' => 'nullable|string',
                'target_waktu' => 'nullable|date',
            ]);
            
            $evaluasi = EvaluasiSPMI::create([
                'nama_evaluasi' => $request->nama_evaluasi,
                'tipe_evaluasi' => $request->tipe_evaluasi,
                'tahun' => $request->tahun,
                'periode' => $request->periode,
                'status' => $request->status,
                'status_dokumen' => 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'hasil_evaluasi' => $request->hasil_evaluasi,
                'rekomendasi' => $request->rekomendasi,
                'target_waktu' => $request->target_waktu,
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data evaluasi berhasil ditambahkan.',
                    'data' => $evaluasi
                ]);
            }
            
            return redirect()->route('spmi.evaluasi.index')
                ->with('success', 'Data evaluasi berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Display the specified evaluasi.
     */
    public function show($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            $allDokumen = $evaluasi->getAllDokumen();
            
            return view('dashboard.spmi.evaluasi.show', compact('evaluasi', 'allDokumen'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.evaluasi.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified evaluasi.
     */
    public function edit($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.evaluasi.edit', compact('evaluasi', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.evaluasi.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified evaluasi in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            
            $request->validate([
                'nama_evaluasi' => 'required|string|max:255',
                'tipe_evaluasi' => 'required|in:ami,edom,evaluasi_layanan,evaluasi_kinerja',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'periode' => 'nullable|string|max:100',
                'status' => 'required|in:aktif,nonaktif,selesai,berjalan',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'hasil_evaluasi' => 'nullable|string',
                'rekomendasi' => 'nullable|string',
                'target_waktu' => 'nullable|date',
            ]);
            
            $evaluasi->update([
                'nama_evaluasi' => $request->nama_evaluasi,
                'tipe_evaluasi' => $request->tipe_evaluasi,
                'tahun' => $request->tahun,
                'periode' => $request->periode,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? $evaluasi->status_dokumen,
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'hasil_evaluasi' => $request->hasil_evaluasi,
                'rekomendasi' => $request->rekomendasi,
                'target_waktu' => $request->target_waktu,
                'tanggal_review' => now(),
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data evaluasi berhasil diperbarui.',
                    'data' => $evaluasi
                ]);
            }
            
            return redirect()->route('spmi.evaluasi.index')
                ->with('success', 'Data evaluasi berhasil diperbarui.');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified evaluasi from storage.
     */
    public function destroy($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            $evaluasi->delete();
            
            return redirect()->route('spmi.evaluasi.index')
                ->with('success', 'Data evaluasi berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Restore soft deleted evaluasi.
     */
    public function restoreEvaluasi($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::withTrashed()->findOrFail($id);
            $evaluasi->restore();
            
            return redirect()->route('spmi.evaluasi.index')
                ->with('success', 'Data evaluasi berhasil dipulihkan.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk evaluasi.
     */
    public function uploadDokumenEvaluasi(Request $request, $id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            
            $request->validate([
                'file_dokumen' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
                'keterangan' => 'nullable|string|max:500',
                'jenis_dokumen' => 'nullable|string|max:100',
                'nama_dokumen' => 'nullable|string|max:255',
            ]);
            
            if ($request->hasFile('file_dokumen') && $request->file('file_dokumen')->isValid()) {
                $file = $request->file('file_dokumen');
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                $folderPath = 'dokumen/spmi/evaluasi/' . $evaluasi->tipe_evaluasi . '/' . $evaluasi->tahun;
                $fileName = Str::slug($evaluasi->nama_evaluasi) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
                
                $unitKerjaId = $evaluasi->unit_kerja_id ?? 1;
                $ikuId = $evaluasi->iku_id ?? 1;
                $namaDokumen = $request->nama_dokumen ?? ($evaluasi->nama_evaluasi . ' - ' . ($request->jenis_dokumen ?? 'Dokumen Evaluasi'));
                
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $unitKerjaId,
                    'iku_id' => $ikuId,
                    'jenis_dokumen' => $request->jenis_dokumen ?? 'Evaluasi SPMI',
                    'nama_dokumen' => $namaDokumen,
                    'keterangan' => $request->keterangan ?? 'Dokumen evaluasi SPMI',
                    'file_path' => $filePath,
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'is_public' => true,
                    'tahapan' => 'evaluasi',
                    'metadata' => json_encode([
                        'evaluasi_id' => $evaluasi->id,
                        'nama_evaluasi' => $evaluasi->nama_evaluasi,
                        'tipe_evaluasi' => $evaluasi->tipe_evaluasi,
                        'tahun' => $evaluasi->tahun,
                        'kode_evaluasi' => $evaluasi->kode_evaluasi,
                        'upload_source' => $request->has('upload_source') ? $request->upload_source : 'inline_form'
                    ])
                ]);
                
                $evaluasi->update([
                    'status_dokumen' => 'valid',
                    'tanggal_evaluasi' => now(),
                    'dokumen_id' => $evaluasi->dokumen_id ?? $dokumen->id,
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Dokumen berhasil diupload.',
                        'dokumen' => $dokumen,
                        'evaluasi' => $evaluasi
                    ]);
                }
                
                return redirect()->route('spmi.evaluasi.show', $evaluasi->id)
                    ->with('success', 'Dokumen berhasil diupload dan terkait dengan evaluasi.');
            }
            
            return back()->with('error', 'File tidak valid.');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumen evaluasi.
     */
    public function downloadDokumenEvaluasi($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::with('dokumen')->findOrFail($id);
            
            if (!$evaluasi->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $evaluasi->dokumen;
            
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }
            
            return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Update status dokumen evaluasi.
     */
    public function updateStatusDokumenEvaluasi(Request $request, $id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            
            $request->validate([
                'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            $evaluasi->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan_verifikasi' => $request->catatan,
                'tanggal_review' => now(),
                'diperiksa_oleh' => auth()->user()->name ?? 'System',
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status dokumen berhasil diperbarui.',
                    'status_dokumen' => $evaluasi->status_dokumen
                ]);
            }
            
            return redirect()->route('spmi.evaluasi.show', $evaluasi->id)
                ->with('success', 'Status dokumen berhasil diperbarui.');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Get data for view modal
     */
    public function getEvaluasiData($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            $allDokumen = $evaluasi->getAllDokumen();
            
            $html = view('dashboard.spmi.evaluasi.partials.detail-modal', compact('evaluasi', 'allDokumen'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get form for edit modal
     */
    public function getEditFormEvaluasi($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            $html = view('dashboard.spmi.evaluasi.partials.edit-form', compact('evaluasi', 'unitKerjas', 'ikus'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update via AJAX
     */
    public function updateEvaluasiAjax(Request $request, $id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            
            $request->validate([
                'nama_evaluasi' => 'required|string|max:255',
                'tipe_evaluasi' => 'required|in:ami,edom,evaluasi_layanan,evaluasi_kinerja',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,selesai,berjalan',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            $evaluasi->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $evaluasi
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dokumen list for evaluasi
     */
    public function getDokumenListEvaluasi($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            $allDokumen = $evaluasi->getAllDokumen();
            
            $html = view('dashboard.spmi.evaluasi.partials.dokumen-list', compact('allDokumen'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $allDokumen->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        try {
            $statistics = EvaluasiSPMI::getStatistics();
            
            return response()->json([
                'success' => true,
                'statistics' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}