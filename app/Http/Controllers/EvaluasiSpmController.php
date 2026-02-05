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
        // Query dengan filter
        $query = EvaluasiSPMI::with(['dokumen', 'unitKerja', 'iku']);
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_evaluasi', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_evaluasi', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
        }
        
        // Filter tipe
        if ($request->has('tipe') && $request->tipe != '' && $request->tipe != 'all') {
            $query->where('tipe_evaluasi', $request->tipe);
        }
        
        // Filter status
        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter status dokumen
        if ($request->has('status_dokumen') && $request->status_dokumen != '' && $request->status_dokumen != 'all') {
            $query->where('status_dokumen', $request->status_dokumen);
        }
        
        // Filter tahun
        if ($request->has('tahun') && $request->tahun != '' && $request->tahun != 'all') {
            $query->where('tahun', $request->tahun);
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
        $tahunList = EvaluasiSPMI::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $unitKerjaList = UnitKerja::where('status', true)->get();
        
        // Statistics
        $totalEvaluasi = EvaluasiSPMI::count();
        $evaluasiAktif = EvaluasiSPMI::whereIn('status', ['aktif', 'selesai'])->count();
        $dokumenValid = EvaluasiSPMI::where('status_dokumen', 'valid')->count();
        $dokumenBelumValid = EvaluasiSPMI::where('status_dokumen', 'belum_valid')->count();
        
        return view('dashboard.spmi.evaluasi.index', compact(
            'evaluasi', 
            'tahunList', 
            'unitKerjaList',
            'totalEvaluasi',
            'evaluasiAktif',
            'dokumenValid',
            'dokumenBelumValid'
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
            // Validasi
            $request->validate([
                'nama_evaluasi' => 'required|string|max:255',
                'tipe_evaluasi' => 'required|in:ami,edom,evaluasi_layanan,evaluasi_kinerja',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'periode' => 'nullable|string|max:100',
                'status' => 'required|in:aktif,nonaktif,selesai,berjalan',
                'status_dokumen' => 'nullable|in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Generate kode otomatis
            $tipe = $request->tipe_evaluasi;
            $tahun = $request->tahun;
            $kode = EvaluasiSPMI::generateKode($tipe, $tahun);
            
            // Create evaluasi
            $evaluasi = EvaluasiSPMI::create([
                'nama_evaluasi' => $request->nama_evaluasi,
                'tipe_evaluasi' => $tipe,
                'tahun' => $tahun,
                'periode' => $request->periode,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'kode_evaluasi' => $kode,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
            ]);
            
            // Jika request AJAX, return JSON
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
            
            // Get all documents related to this evaluasi
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
            
            // Validasi
            $request->validate([
                'nama_evaluasi' => 'required|string|max:255',
                'tipe_evaluasi' => 'required|in:ami,edom,evaluasi_layanan,evaluasi_kinerja',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'periode' => 'nullable|string|max:100',
                'status' => 'required|in:aktif,nonaktif,selesai,berjalan',
                'status_dokumen' => 'nullable|in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Update data
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
                'tanggal_review' => now(),
            ]);
            
            // Jika request AJAX
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
            
            // Soft delete
            $evaluasi->delete();
            
            return redirect()->route('spmi.evaluasi.index')
                ->with('success', 'Data evaluasi berhasil dihapus.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk evaluasi.
     */
    public function uploadDokumen(Request $request, $id)
    {
        try {
            $evaluasi = EvaluasiSPMI::findOrFail($id);
            
            // Validasi file
            $request->validate([
                'file_dokumen' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
                'keterangan' => 'nullable|string|max:500',
                'jenis_dokumen' => 'nullable|string|max:100',
                'nama_dokumen' => 'nullable|string|max:255',
            ]);
            
            // Upload file
            if ($request->hasFile('file_dokumen') && $request->file('file_dokumen')->isValid()) {
                $file = $request->file('file_dokumen');
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                // Generate folder path based on tahun and tipe_evaluasi
                $folderPath = 'dokumen/spmi/evaluasi/' . $evaluasi->tipe_evaluasi . '/' . $evaluasi->tahun;
                
                // Generate unique filename
                $fileName = Str::slug($evaluasi->nama_evaluasi) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                // Store file
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
                
                // Unit kerja default (LPM) jika tidak ada
                $unitKerjaId = $evaluasi->unit_kerja_id ?? 1;
                $ikuId = $evaluasi->iku_id ?? 1;
                
                // Generate nama dokumen
                $namaDokumen = $request->nama_dokumen ?? ($evaluasi->nama_evaluasi . ' - ' . ($request->jenis_dokumen ?? 'Dokumen Evaluasi'));
                
                // Create dokumen record
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
                
                // Update evaluasi status dokumen
                $evaluasi->update([
                    'status_dokumen' => 'valid',
                    'tanggal_evaluasi' => now(),
                    'dokumen_id' => $evaluasi->dokumen_id ?? $dokumen->id,
                ]);
                
                // Jika request AJAX
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
     * AJAX get evaluasi data for modal
     */
    public function getEvaluasiData($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            // Get all documents related to this evaluasi
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
     * AJAX get edit form
     */
    public function getEditForm($id)
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
     * AJAX update evaluasi
     */
    public function updateAjax(Request $request, $id)
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
            
            $evaluasi->update([
                'nama_evaluasi' => $request->nama_evaluasi,
                'tipe_evaluasi' => $request->tipe_evaluasi,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'tanggal_review' => now(),
            ]);
            
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
     * Restore soft deleted evaluasi.
     */
    public function restore($id)
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
     * Download dokumen evaluasi.
     */
    public function downloadDokumen($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::with('dokumen')->findOrFail($id);
            
            if (!$evaluasi->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $evaluasi->dokumen;
            
            // Jika berupa link
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            // Jika file
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }
            
            return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Hapus dokumen dari evaluasi.
     */
    public function hapusDokumen($id)
    {
        try {
            $evaluasi = EvaluasiSPMI::with('dokumen')->findOrFail($id);
            
            if (!$evaluasi->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $evaluasi->dokumen;
            
            // Hapus file fisik jika ada
            if ($dokumen->jenis_upload === 'file' && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            // Hapus dari database
            $dokumen->delete();
            
            // Update evaluasi
            $evaluasi->update([
                'dokumen_id' => null,
                'status_dokumen' => 'belum_valid',
            ]);
            
            return redirect()->route('spmi.evaluasi.show', $evaluasi->id)
                ->with('success', 'Dokumen berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
    
    // ==================== METHOD ALTERNATIF (evaluasi-full) ====================
    
    public function indexEvaluasiFull(Request $request)
    {
        return $this->index($request);
    }
    
    public function createEvaluasiFull()
    {
        return $this->create();
    }
    
    public function storeEvaluasiFull(Request $request)
    {
        return $this->store($request);
    }
    
    public function showEvaluasiFull($id)
    {
        return $this->show($id);
    }
    
    public function editEvaluasiFull($id)
    {
        return $this->edit($id);
    }
    
    public function updateEvaluasiFull(Request $request, $id)
    {
        return $this->update($request, $id);
    }
    
    public function destroyEvaluasiFull($id)
    {
        return $this->destroy($id);
    }
    
    public function uploadDokumenEvaluasi(Request $request, $id)
    {
        return $this->uploadDokumen($request, $id);
    }
    
    public function getEvaluasiEditForm($id)
    {
        return $this->getEditForm($id);
    }
    
    public function updateEvaluasiAjax(Request $request, $id)
    {
        return $this->updateAjax($request, $id);
    }
}