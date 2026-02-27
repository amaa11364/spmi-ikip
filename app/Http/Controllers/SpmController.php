<?php

namespace App\Http\Controllers;

use App\Models\PenetapanSPM;
use App\Models\PelaksanaanSPMI;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpmController extends Controller
{
    // ==================== PENETAPAN SPMI (CRUD LENGKAP) ====================
    
    /**
     * Display a listing of the resource - Repository View
     */
    public function indexPenetapan(Request $request)
    {
        // Query dengan filter
        $query = PenetapanSPM::with(['dokumen', 'unitKerja', 'iku']);
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_penetapan', 'like', '%' . $search . '%');
            });
        }
        
        // Filter tipe
        if ($request->has('tipe') && $request->tipe != '' && $request->tipe != 'all') {
            $query->where('tipe_penetapan', $request->tipe);
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
        
        $penetapan = $query->paginate(20);
        
        // Data untuk filter dropdown
        $tahunList = PenetapanSPM::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $unitKerjaList = UnitKerja::where('status', true)->get();
        
        // Statistics
        $totalPenetapan = PenetapanSPM::count();
        $penetapanAktif = PenetapanSPM::where('status', 'aktif')->count();
        $dokumenValid = PenetapanSPM::where('status_dokumen', 'valid')->count();
        $dokumenBelumValid = PenetapanSPM::where('status_dokumen', 'belum_valid')->count();
        
        return view('admin.dashboard.spmi.penetapan.index', compact(
            'penetapan', 
            'tahunList', 
            'unitKerjaList',
            'totalPenetapan',
            'penetapanAktif',
            'dokumenValid',
            'dokumenBelumValid'
        ));
    }

    /**
     * AJAX: Get detail for modal - PENETAPAN
     */
    public function ajaxDetailPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::with(['unitKerja', 'iku'])->findOrFail($id);
            
            // Get dokumen terkait
            $allDokumen = Dokumen::where('penetapan_spmi_id', $id)
                ->orWhere('metadata', 'like', '%"penetapan_id":' . $id . '%')
                ->orWhere('metadata', 'like', '%' . $penetapan->kode_penetapan . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $html = view('spmi.penetapan.detail-modal', compact('penetapan', 'allDokumen'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('AJAX Detail Penetapan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Get edit form for modal - PENETAPAN
     */
    public function ajaxEditFormPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            $html = view('spmi.penetapan.edit-form', compact('penetapan', 'unitKerjas', 'ikus'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('AJAX Edit Form Penetapan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat form edit. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createPenetapan()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.penetapan.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storePenetapan(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'nama_komponen' => 'required|string|max:255',
                'tipe_penetapan' => 'required|in:pengelolaan,organisasi,pelaksanaan,evaluasi,pengendalian,peningkatan',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,revisi',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Generate kode otomatis
            $tipe = $request->tipe_penetapan;
            $tahun = $request->tahun;
            $count = PenetapanSPM::where('tipe_penetapan', $tipe)
                ->where('tahun', $tahun)
                ->count() + 1;
            
            $kode = strtoupper(substr($tipe, 0, 3)) . '-' . $tahun . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            
            // Create penetapan
            $penetapan = PenetapanSPM::create([
                'nama_komponen' => $request->nama_komponen,
                'tipe_penetapan' => $tipe,
                'tahun' => $tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'kode_penetapan' => $kode,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
            ]);
            
            // Jika request AJAX, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data penetapan berhasil ditambahkan.',
                    'data' => $penetapan
                ]);
            }
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil ditambahkan.');
                
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
     * Display the specified resource.
     */
    public function showPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            // Get all documents related to this penetapan
            $allDokumen = Dokumen::where('penetapan_spmi_id', $id)
                ->orWhere('metadata', 'like', '%"penetapan_id":' . $id . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('dashboard.spmi.penetapan.show', compact('penetapan', 'allDokumen'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.penetapan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.penetapan.edit', compact('penetapan', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.penetapan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePenetapan(Request $request, $id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            // Validasi
            $request->validate([
                'nama_komponen' => 'required|string|max:255',
                'tipe_penetapan' => 'required|in:pengelolaan,organisasi,pelaksanaan,evaluasi,pengendalian,peningkatan',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,revisi',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Update data
            $penetapan->update([
                'nama_komponen' => $request->nama_komponen,
                'tipe_penetapan' => $request->tipe_penetapan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? $penetapan->status_dokumen,
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
                    'message' => 'Data penetapan berhasil diperbarui.',
                    'data' => $penetapan
                ]);
            }
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil diperbarui.');
                
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
     * Remove the specified resource from storage.
     */
    public function destroyPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            // Soft delete penetapan
            $penetapan->delete();
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk penetapan.
     */
    public function uploadDokumenPenetapan(Request $request, $id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
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
                
                // Generate folder path based on tahun and tipe_penetapan
                $folderPath = 'dokumen/spmi/penetapan/' . $penetapan->tipe_penetapan . '/' . $penetapan->tahun;
                
                // Generate unique filename
                $fileName = Str::slug($penetapan->nama_komponen) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                // Store file
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
                
                // Unit kerja default (LPM) jika tidak ada
                $unitKerjaId = $penetapan->unit_kerja_id ?? 1; // ID LPM
                $ikuId = $penetapan->iku_id ?? 1; // ID IKU SPMI
                
                // Generate nama dokumen
                $namaDokumen = $request->nama_dokumen ?? ($penetapan->nama_komponen . ' - ' . ($request->jenis_dokumen ?? 'Dokumen Penetapan'));
                
                // Create dokumen record dengan metadata yang benar
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $unitKerjaId,
                    'iku_id' => $ikuId,
                    'penetapan_spmi_id' => $penetapan->id, // Relasi ke penetapan
                    'jenis_dokumen' => $request->jenis_dokumen ?? 'Penetapan SPMI',
                    'nama_dokumen' => $namaDokumen,
                    'keterangan' => $request->keterangan ?? 'Dokumen penetapan SPMI',
                    'file_path' => $filePath,
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'is_public' => true,
                    'tahapan' => 'penetapan',
                    'metadata' => json_encode([
                        'penetapan_id' => $penetapan->id,
                        'nama_komponen' => $penetapan->nama_komponen,
                        'tipe_penetapan' => $penetapan->tipe_penetapan,
                        'tahun' => $penetapan->tahun,
                        'penanggung_jawab' => $penetapan->penanggung_jawab,
                        'kode_penetapan' => $penetapan->kode_penetapan,
                        'upload_source' => $request->input('upload_source', 'inline_form')
                    ])
                ]);
                
                // Update dokumen count
                $dokumenCount = Dokumen::where('penetapan_spmi_id', $penetapan->id)->count();
                
                $penetapan->update([
                    'status_dokumen' => 'valid',
                    'tanggal_penetapan' => now(),
                    'dokumen_count' => $dokumenCount
                ]);
                
                // Jika request AJAX
                if ($request->ajax() || $request->input('upload_source') === 'inline_modal') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Dokumen berhasil diupload.',
                        'dokumen' => $dokumen,
                        'penetapan' => $penetapan,
                        'dokumen_count' => $dokumenCount
                    ]);
                }
                
                return redirect()->route('spmi.penetapan.show', $penetapan->id)
                    ->with('success', 'Dokumen berhasil diupload dan terkait dengan penetapan.');
            }
            
            return back()->with('error', 'File tidak valid.');
            
        } catch (\Exception $e) {
            Log::error('Upload Dokumen Penetapan Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->input('upload_source') === 'inline_modal') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    // ==================== PELAKSANAAN SPMI (CRUD LENGKAP) ====================
    
    /**
     * Display a listing of pelaksanaan.
     */
    public function indexPelaksanaan(Request $request)
    {
        // Query dengan filter
        $query = PelaksanaanSPMI::with(['dokumen', 'unitKerja', 'iku']);
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kegiatan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_pelaksanaan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
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
        
        $pelaksanaan = $query->paginate(20);
        
        // Data untuk filter dropdown
        $tahunList = PelaksanaanSPMI::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $unitKerjaList = UnitKerja::where('status', true)->get();
        
        // Statistics
        $totalPelaksanaan = PelaksanaanSPMI::count();
        $pelaksanaanAktif = PelaksanaanSPMI::where('status', 'aktif')->count();
        $dokumenValid = PelaksanaanSPMI::where('status_dokumen', 'valid')->count();
        $dokumenBelumValid = PelaksanaanSPMI::where('status_dokumen', 'belum_valid')->count();
        
        return view('admin.dashboard.spmi.pelaksanaan.index', compact(
            'pelaksanaan', 
            'tahunList', 
            'unitKerjaList',
            'totalPelaksanaan',
            'pelaksanaanAktif',
            'dokumenValid',
            'dokumenBelumValid'
        ));
    }

    /**
     * AJAX: Get detail for modal - PELAKSANAAN
     */
    public function ajaxDetailPelaksanaan($id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::with(['unitKerja', 'iku'])->findOrFail($id);
            
            // Get dokumen terkait
            $allDokumen = Dokumen::where('pelaksanaan_spmi_id', $id)
                ->orWhere('metadata', 'like', '%"pelaksanaan_id":' . $id . '%')
                ->orWhere('metadata', 'like', '%' . $pelaksanaan->kode_pelaksanaan . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $html = view('spmi.pelaksanaan.detail-modal', compact('pelaksanaan', 'allDokumen'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('AJAX Detail Pelaksanaan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Get edit form for modal - PELAKSANAAN
     */
    public function ajaxEditFormPelaksanaan($id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            $html = view('spmi.pelaksanaan.edit-form', compact('pelaksanaan', 'unitKerjas', 'ikus'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('AJAX Edit Form Pelaksanaan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat form edit. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new pelaksanaan.
     */
    public function createPelaksanaan()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.pelaksanaan.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created pelaksanaan in storage.
     */
    public function storePelaksanaan(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'nama_kegiatan' => 'required|string|max:255',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,revisi',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Generate kode otomatis
            $tahun = $request->tahun;
            $count = PelaksanaanSPMI::where('tahun', $tahun)->count() + 1;
            $kode = 'PLK-' . $tahun . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            
            // Create pelaksanaan
            $pelaksanaan = PelaksanaanSPMI::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tahun' => $tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'kode_pelaksanaan' => $kode,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
            ]);
            
            // Jika request AJAX, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pelaksanaan berhasil ditambahkan.',
                    'data' => $pelaksanaan
                ]);
            }
            
            return redirect()->route('spmi.pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil ditambahkan.');
            
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
     * Display the specified pelaksanaan.
     */
    public function showPelaksanaan($id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            // Get all documents related to this pelaksanaan
            $allDokumen = Dokumen::where('pelaksanaan_spmi_id', $id)
                ->orWhere('metadata', 'like', '%"pelaksanaan_id":' . $id . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('dashboard.spmi.pelaksanaan.show', compact('pelaksanaan', 'allDokumen'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.pelaksanaan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified pelaksanaan.
     */
    public function editPelaksanaan($id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.pelaksanaan.edit', compact('pelaksanaan', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.pelaksanaan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified pelaksanaan in storage.
     */
    public function updatePelaksanaan(Request $request, $id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            
            // Validasi
            $request->validate([
                'nama_kegiatan' => 'required|string|max:255',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,revisi',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Update data
            $pelaksanaan->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? $pelaksanaan->status_dokumen,
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
                    'message' => 'Data pelaksanaan berhasil diperbarui.',
                    'data' => $pelaksanaan
                ]);
            }
            
            return redirect()->route('spmi.pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil diperbarui.');
            
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
     * Remove the specified pelaksanaan from storage.
     */
    public function destroyPelaksanaan($id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            
            // Soft delete
            $pelaksanaan->delete();
            
            return redirect()->route('spmi.pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil dihapus.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk pelaksanaan.
     */
    public function uploadDokumenPelaksanaan(Request $request, $id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            
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
                
                // Generate folder path based on tahun
                $folderPath = 'dokumen/spmi/pelaksanaan/' . $pelaksanaan->tahun;
                
                // Generate unique filename
                $fileName = Str::slug($pelaksanaan->nama_kegiatan) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                // Store file
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
                
                // Unit kerja default (LPM) jika tidak ada
                $unitKerjaId = $pelaksanaan->unit_kerja_id ?? 1; // ID LPM
                $ikuId = $pelaksanaan->iku_id ?? 1; // ID IKU SPMI
                
                // Generate nama dokumen
                $namaDokumen = $request->nama_dokumen ?? ($pelaksanaan->nama_kegiatan . ' - ' . ($request->jenis_dokumen ?? 'Dokumen Pelaksanaan'));
                
                // Create dokumen record dengan metadata yang benar
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $unitKerjaId,
                    'iku_id' => $ikuId,
                    'pelaksanaan_spmi_id' => $pelaksanaan->id, // Relasi ke pelaksanaan
                    'jenis_dokumen' => $request->jenis_dokumen ?? 'Pelaksanaan SPMI',
                    'nama_dokumen' => $namaDokumen,
                    'keterangan' => $request->keterangan ?? 'Dokumen pelaksanaan SPMI',
                    'file_path' => $filePath,
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'is_public' => true,
                    'tahapan' => 'pelaksanaan',
                    'metadata' => json_encode([
                        'pelaksanaan_id' => $pelaksanaan->id,
                        'nama_kegiatan' => $pelaksanaan->nama_kegiatan,
                        'kode_pelaksanaan' => $pelaksanaan->kode_pelaksanaan,
                        'tahun' => $pelaksanaan->tahun,
                        'penanggung_jawab' => $pelaksanaan->penanggung_jawab,
                        'upload_source' => $request->input('upload_source', 'inline_form')
                    ])
                ]);
                
                // Update dokumen count
                $dokumenCount = Dokumen::where('pelaksanaan_spmi_id', $pelaksanaan->id)->count();
                
                $pelaksanaan->update([
                    'status_dokumen' => 'valid',
                    'tanggal_pelaksanaan' => now(),
                    'dokumen_count' => $dokumenCount
                ]);
                
                // Jika request AJAX
                if ($request->ajax() || $request->input('upload_source') === 'inline_modal') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Dokumen berhasil diupload.',
                        'dokumen' => $dokumen,
                        'pelaksanaan' => $pelaksanaan,
                        'dokumen_count' => $dokumenCount
                    ]);
                }
                
                return redirect()->route('spmi.pelaksanaan.show', $pelaksanaan->id)
                    ->with('success', 'Dokumen berhasil diupload dan terkait dengan pelaksanaan.');
            }
            
            return back()->with('error', 'File tidak valid.');
            
        } catch (\Exception $e) {
            Log::error('Upload Dokumen Pelaksanaan Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->input('upload_source') === 'inline_modal') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    // ==================== METHOD AJAX LAINNYA ====================
    
    /**
     * Get dokumen list for penetapan
     */
    public function getDokumenListPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            $allDokumen = Dokumen::where('penetapan_spmi_id', $id)
                ->orWhere('metadata', 'like', '%"penetapan_id":' . $id . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $html = view('spmi.penetapan.partials.dokumen-list', compact('allDokumen'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $allDokumen->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Dokumen List Penetapan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get dokumen list for pelaksanaan
     */
    public function getDokumenListPelaksanaan($id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            $allDokumen = Dokumen::where('pelaksanaan_spmi_id', $id)
                ->orWhere('metadata', 'like', '%"pelaksanaan_id":' . $id . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $html = view('spmi.pelaksanaan.partials.dokumen-list', compact('allDokumen'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $allDokumen->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Dokumen List Pelaksanaan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== METODE BANTUAN ====================
    
    /**
     * Helper untuk generate response JSON
     */
    private function jsonResponse($success, $message, $data = null, $status = 200)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $status);
    }
    
    /**
     * Update status dokumen.
     */
    public function updateStatusDokumen(Request $request, $id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            $request->validate([
                'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            $penetapan->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan_verifikasi' => $request->catatan,
                'tanggal_review' => now(),
                'diperiksa_oleh' => auth()->user()->name ?? 'System',
            ]);
            
            // Jika request AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status dokumen berhasil diperbarui.',
                    'status_dokumen' => $penetapan->status_dokumen
                ]);
            }
            
            return redirect()->route('spmi.penetapan.show', $penetapan->id)
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
     * Update status dokumen pelaksanaan.
     */
    public function updateStatusDokumenPelaksanaan(Request $request, $id)
    {
        try {
            $pelaksanaan = PelaksanaanSPMI::findOrFail($id);
            
            $request->validate([
                'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            $pelaksanaan->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan_verifikasi' => $request->catatan,
                'tanggal_review' => now(),
                'diperiksa_oleh' => auth()->user()->name ?? 'System',
            ]);
            
            // Jika request AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status dokumen berhasil diperbarui.',
                    'status_dokumen' => $pelaksanaan->status_dokumen
                ]);
            }
            
            return redirect()->route('spmi.pelaksanaan.show', $pelaksanaan->id)
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
}