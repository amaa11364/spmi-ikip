<?php

namespace App\Http\Controllers;

use App\Models\PengendalianSPMI;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengendalianSpmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dengan filter
        $query = PengendalianSPMI::with(['dokumen', 'unitKerja', 'iku']);
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tindakan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi_masalah', 'like', '%' . $search . '%')
                  ->orWhere('tindakan_perbaikan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%')
                  ->orWhere('sumber_evaluasi', 'like', '%' . $search . '%');
            });
        }
        
        // Filter status pelaksanaan
        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->where('status_pelaksanaan', $request->status);
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
        
        $pengendalian = $query->paginate(20);
        
        // Data untuk filter dropdown
        $tahunList = PengendalianSPMI::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $unitKerjaList = UnitKerja::where('status', true)->get();
        
        // Statistics
        $totalPengendalian = PengendalianSPMI::count();
        $selesai = PengendalianSPMI::whereIn('status_pelaksanaan', ['selesai', 'terverifikasi'])->count();
        $berjalan = PengendalianSPMI::where('status_pelaksanaan', 'berjalan')->count();
        $tertunda = PengendalianSPMI::where('status_pelaksanaan', 'tertunda')->count();
        
        return view('dashboard.spmi.pengendalian.index', compact(
            'pengendalian', 
            'tahunList', 
            'unitKerjaList',
            'totalPengendalian',
            'selesai',
            'berjalan',
            'tertunda'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.pengendalian.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'nama_tindakan' => 'required|string|max:255',
                'sumber_evaluasi' => 'nullable|string|max:255',
                'deskripsi_masalah' => 'required|string',
                'tindakan_perbaikan' => 'required|string',
                'penanggung_jawab' => 'required|string|max:255',
                'target_waktu' => 'required|date',
                'status_pelaksanaan' => 'required|in:rencana,berjalan,selesai,terverifikasi,tertunda',
                'progress' => 'required|integer|min:0|max:100',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'catatan' => 'nullable|string',
            ]);
            
            // Create pengendalian
            $pengendalian = PengendalianSPMI::create([
                'nama_tindakan' => $request->nama_tindakan,
                'sumber_evaluasi' => $request->sumber_evaluasi,
                'deskripsi_masalah' => $request->deskripsi_masalah,
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'penanggung_jawab' => $request->penanggung_jawab,
                'target_waktu' => $request->target_waktu,
                'status_pelaksanaan' => $request->status_pelaksanaan,
                'progress' => $request->progress,
                'tahun' => $request->tahun,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'catatan' => $request->catatan,
                'status_dokumen' => 'belum_valid',
                'created_by' => auth()->id(),
            ]);
            
            // Jika request AJAX, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pengendalian berhasil ditambahkan.',
                    'data' => $pengendalian
                ]);
            }
            
            return redirect()->route('spmi.pengendalian.index')
                ->with('success', 'Data pengendalian berhasil ditambahkan.');
            
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
    public function show($id)
    {
        try {
            $pengendalian = PengendalianSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            // Get all documents related to this pengendalian
            $allDokumen = $pengendalian->getAllDokumen();
            
            return view('dashboard.spmi.pengendalian.show', compact('pengendalian', 'allDokumen'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.pengendalian.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $pengendalian = PengendalianSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.pengendalian.edit', compact('pengendalian', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.pengendalian.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $pengendalian = PengendalianSPMI::findOrFail($id);
            
            // Validasi
            $request->validate([
                'nama_tindakan' => 'required|string|max:255',
                'sumber_evaluasi' => 'nullable|string|max:255',
                'deskripsi_masalah' => 'required|string',
                'tindakan_perbaikan' => 'required|string',
                'penanggung_jawab' => 'required|string|max:255',
                'target_waktu' => 'required|date',
                'status_pelaksanaan' => 'required|in:rencana,berjalan,selesai,terverifikasi,tertunda',
                'progress' => 'required|integer|min:0|max:100',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'catatan' => 'nullable|string',
                'hasil_verifikasi' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            ]);
            
            // Update data
            $pengendalian->update([
                'nama_tindakan' => $request->nama_tindakan,
                'sumber_evaluasi' => $request->sumber_evaluasi,
                'deskripsi_masalah' => $request->deskripsi_masalah,
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'penanggung_jawab' => $request->penanggung_jawab,
                'target_waktu' => $request->target_waktu,
                'status_pelaksanaan' => $request->status_pelaksanaan,
                'progress' => $request->progress,
                'tahun' => $request->tahun,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'catatan' => $request->catatan,
                'hasil_verifikasi' => $request->hasil_verifikasi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
            
            // Jika request AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pengendalian berhasil diperbarui.',
                    'data' => $pengendalian
                ]);
            }
            
            return redirect()->route('spmi.pengendalian.index')
                ->with('success', 'Data pengendalian berhasil diperbarui.');
            
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
    public function destroy($id)
    {
        try {
            $pengendalian = PengendalianSPMI::findOrFail($id);
            
            // Soft delete
            $pengendalian->delete();
            
            return redirect()->route('spmi.pengendalian.index')
                ->with('success', 'Data pengendalian berhasil dihapus.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Restore soft deleted resource.
     */
    public function restore($id)
    {
        try {
            $pengendalian = PengendalianSPMI::withTrashed()->findOrFail($id);
            $pengendalian->restore();
            
            return redirect()->route('spmi.pengendalian.index')
                ->with('success', 'Data pengendalian berhasil dipulihkan.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk pengendalian.
     */
    public function uploadDokumen(Request $request, $id)
    {
        try {
            $pengendalian = PengendalianSPMI::findOrFail($id);
            
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
                
                // Generate folder path
                $folderPath = 'dokumen/spmi/pengendalian/' . $pengendalian->tahun;
                
                // Generate unique filename
                $fileName = Str::slug($pengendalian->nama_tindakan) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                // Store file
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
                
                // Unit kerja default
                $unitKerjaId = $pengendalian->unit_kerja_id ?? 1;
                $ikuId = $pengendalian->iku_id ?? 1;
                
                // Generate nama dokumen
                $namaDokumen = $request->nama_dokumen ?? ($pengendalian->nama_tindakan . ' - ' . ($request->jenis_dokumen ?? 'Dokumen Pengendalian'));
                
                // Create dokumen record
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $unitKerjaId,
                    'iku_id' => $ikuId,
                    'jenis_dokumen' => $request->jenis_dokumen ?? 'Pengendalian SPMI',
                    'nama_dokumen' => $namaDokumen,
                    'keterangan' => $request->keterangan ?? 'Dokumen pengendalian SPMI',
                    'file_path' => $filePath,
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'is_public' => true,
                    'tahapan' => 'pengendalian',
                    'metadata' => json_encode([
                        'pengendalian_id' => $pengendalian->id,
                        'nama_tindakan' => $pengendalian->nama_tindakan,
                        'tahun' => $pengendalian->tahun,
                        'penanggung_jawab' => $pengendalian->penanggung_jawab,
                        'upload_source' => $request->has('upload_source') ? $request->upload_source : 'inline_form'
                    ])
                ]);
                
                // Update pengendalian status dokumen
                $pengendalian->update([
                    'status_dokumen' => 'valid',
                    'dokumen_id' => $pengendalian->dokumen_id ?? $dokumen->id,
                ]);
                
                // Jika request AJAX
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Dokumen berhasil diupload.',
                        'dokumen' => $dokumen,
                        'pengendalian' => $pengendalian
                    ]);
                }
                
                return redirect()->route('spmi.pengendalian.show', $pengendalian->id)
                    ->with('success', 'Dokumen berhasil diupload dan terkait dengan pengendalian.');
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
     * Download dokumen pengendalian.
     */
    public function downloadDokumen($id)
    {
        try {
            $pengendalian = PengendalianSPMI::with('dokumen')->findOrFail($id);
            
            if (!$pengendalian->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $pengendalian->dokumen;
            
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
     * Update status dokumen pengendalian.
     */
    public function updateStatusDokumen(Request $request, $id)
    {
        try {
            $pengendalian = PengendalianSPMI::findOrFail($id);
            
            $request->validate([
                'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            $pengendalian->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan' => $request->catatan,
                'diperiksa_oleh' => auth()->user()->name ?? 'System',
            ]);
            
            // Jika request AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status dokumen berhasil diperbarui.',
                    'status_dokumen' => $pengendalian->status_dokumen
                ]);
            }
            
            return redirect()->route('spmi.pengendalian.show', $pengendalian->id)
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
     * AJAX get pengendalian data for modal
     */
    public function getPengendalianData($id)
    {
        try {
            $pengendalian = PengendalianSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            // Get all documents related to this pengendalian
            $allDokumen = $pengendalian->getAllDokumen();
            
            $html = view('dashboard.spmi.pengendalian.partials.detail-modal', compact('pengendalian', 'allDokumen'))->render();
            
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
            $pengendalian = PengendalianSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            $html = view('dashboard.spmi.pengendalian.partials.edit-form', compact('pengendalian', 'unitKerjas', 'ikus'))->render();
            
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
     * AJAX update pengendalian
     */
    public function updateAjax(Request $request, $id)
    {
        try {
            $pengendalian = PengendalianSPMI::findOrFail($id);
            
            $request->validate([
                'nama_tindakan' => 'required|string|max:255',
                'status_pelaksanaan' => 'required|in:rencana,berjalan,selesai,terverifikasi,tertunda',
                'progress' => 'required|integer|min:0|max:100',
                'penanggung_jawab' => 'required|string|max:255',
                'target_waktu' => 'required|date',
            ]);
            
            $pengendalian->update([
                'nama_tindakan' => $request->nama_tindakan,
                'status_pelaksanaan' => $request->status_pelaksanaan,
                'progress' => $request->progress,
                'penanggung_jawab' => $request->penanggung_jawab,
                'target_waktu' => $request->target_waktu,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $pengendalian
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $total = PengendalianSPMI::count();
            $selesai = PengendalianSPMI::whereIn('status_pelaksanaan', ['selesai', 'terverifikasi'])->count();
            $berjalan = PengendalianSPMI::where('status_pelaksanaan', 'berjalan')->count();
            $tertunda = PengendalianSPMI::where('status_pelaksanaan', 'tertunda')->count();
            
            // Group by tahun
            $byTahun = PengendalianSPMI::select('tahun', \DB::raw('count(*) as count'))
                ->groupBy('tahun')
                ->orderBy('tahun', 'desc')
                ->take(5)
                ->get();
            
            // Group by status
            $byStatus = PengendalianSPMI::select('status_pelaksanaan', \DB::raw('count(*) as count'))
                ->groupBy('status_pelaksanaan')
                ->get()
                ->pluck('count', 'status_pelaksanaan');
            
            return response()->json([
                'success' => true,
                'statistics' => [
                    'total' => $total,
                    'selesai' => $selesai,
                    'berjalan' => $berjalan,
                    'tertunda' => $tertunda,
                    'by_tahun' => $byTahun,
                    'by_status' => $byStatus
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}