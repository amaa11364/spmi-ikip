<?php

namespace App\Http\Controllers;

use App\Models\PeningkatanSPMI;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PeningkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PeningkatanSPMI::with(['dokumen', 'unitKerja', 'iku']);
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_program', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_peningkatan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
        }
        
        // Filter tipe
        if ($request->has('tipe') && $request->tipe != '' && $request->tipe != 'all') {
            $query->where('tipe_peningkatan', $request->tipe);
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
        
        $peningkatan = $query->paginate(20);
        
        // Data untuk filter dropdown
        $tahunList = PeningkatanSPMI::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $unitKerjaList = UnitKerja::where('status', true)->get();
        
        // Statistics
        $totalPeningkatan = PeningkatanSPMI::count();
        $peningkatanAktif = PeningkatanSPMI::whereIn('status', ['disetujui', 'berjalan'])->count();
        $dokumenValid = PeningkatanSPMI::where('status_dokumen', 'valid')->count();
        $dokumenBelumValid = PeningkatanSPMI::where('status_dokumen', 'belum_valid')->count();
        
        // Kelompokkan untuk tabs
        $kelompok = [
            'strategis' => PeningkatanSPMI::where('tipe_peningkatan', 'strategis')->orderBy('created_at', 'desc')->get(),
            'operasional' => PeningkatanSPMI::where('tipe_peningkatan', 'operasional')->orderBy('created_at', 'desc')->get(),
            'perbaikan' => PeningkatanSPMI::where('tipe_peningkatan', 'perbaikan')->orderBy('created_at', 'desc')->get(),
            'pengembangan' => PeningkatanSPMI::where('tipe_peningkatan', 'pengembangan')->orderBy('created_at', 'desc')->get(),
            'inovasi' => PeningkatanSPMI::where('tipe_peningkatan', 'inovasi')->orderBy('created_at', 'desc')->get(),
        ];
        
        return view('dashboard.spmi.peningkatan.index', compact(
            'peningkatan', 
            'kelompok', 
            'tahunList', 
            'unitKerjaList',
            'totalPeningkatan',
            'peningkatanAktif',
            'dokumenValid',
            'dokumenBelumValid'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.peningkatan.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_program' => 'required|string|max:255',
                'tipe_peningkatan' => 'required|in:strategis,operasional,perbaikan,pengembangan,inovasi',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:draft,disetujui,berjalan,selesai,ditunda,dibatalkan',
                'status_dokumen' => 'nullable|in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'anggaran' => 'nullable|numeric|min:0',
                'progress' => 'nullable|integer|min:0|max:100',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            ]);
            
            $peningkatan = PeningkatanSPMI::create([
                'nama_program' => $request->nama_program,
                'tipe_peningkatan' => $request->tipe_peningkatan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'anggaran' => $request->anggaran ?? 0,
                'progress' => $request->progress ?? 0,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Program peningkatan berhasil ditambahkan.',
                    'data' => $peningkatan
                ]);
            }
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', 'Program peningkatan berhasil ditambahkan.');
                
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
            $peningkatan = PeningkatanSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            $allDokumen = $peningkatan->getAllDokumen();
            
            return view('dashboard.spmi.peningkatan.show', compact('peningkatan', 'allDokumen'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.peningkatan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.peningkatan.edit', compact('peningkatan', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.peningkatan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            $request->validate([
                'nama_program' => 'required|string|max:255',
                'tipe_peningkatan' => 'required|in:strategis,operasional,perbaikan,pengembangan,inovasi',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:draft,disetujui,berjalan,selesai,ditunda,dibatalkan',
                'status_dokumen' => 'nullable|in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'anggaran' => 'nullable|numeric|min:0',
                'realisasi_anggaran' => 'nullable|numeric|min:0',
                'progress' => 'nullable|integer|min:0|max:100',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'catatan_evaluasi' => 'nullable|string',
            ]);
            
            $peningkatan->update([
                'nama_program' => $request->nama_program,
                'tipe_peningkatan' => $request->tipe_peningkatan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? $peningkatan->status_dokumen,
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'anggaran' => $request->anggaran ?? $peningkatan->anggaran,
                'realisasi_anggaran' => $request->realisasi_anggaran,
                'progress' => $request->progress ?? $peningkatan->progress,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'catatan_evaluasi' => $request->catatan_evaluasi,
                'tanggal_review' => now(),
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Program peningkatan berhasil diperbarui.',
                    'data' => $peningkatan
                ]);
            }
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', 'Program peningkatan berhasil diperbarui.');
                
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
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            // Update related dokumen metadata
            $dokumen = $peningkatan->getAllDokumen();
            foreach ($dokumen as $doc) {
                $metadata = $doc->metadata ?? [];
                if (isset($metadata['peningkatan_id']) && $metadata['peningkatan_id'] == $peningkatan->id) {
                    unset($metadata['peningkatan_id']);
                    $doc->metadata = $metadata;
                    $doc->save();
                }
            }
            
            $peningkatan->delete();
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', 'Program peningkatan berhasil dihapus.');
                
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
            $peningkatan = PeningkatanSPMI::withTrashed()->findOrFail($id);
            $peningkatan->restore();
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', 'Program peningkatan berhasil dipulihkan.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk peningkatan.
     */
    public function uploadDokumen(Request $request, $id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
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
                
                $folderPath = 'dokumen/spmi/peningkatan/' . $peningkatan->tipe_peningkatan . '/' . $peningkatan->tahun;
                
                $fileName = Str::slug($peningkatan->nama_program) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
                
                $unitKerjaId = $peningkatan->unit_kerja_id ?? 1;
                $ikuId = $peningkatan->iku_id ?? 1;
                
                $namaDokumen = $request->nama_dokumen ?? ($peningkatan->nama_program . ' - ' . ($request->jenis_dokumen ?? 'Dokumen Peningkatan'));
                
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $unitKerjaId,
                    'iku_id' => $ikuId,
                    'jenis_dokumen' => $request->jenis_dokumen ?? 'Peningkatan SPMI',
                    'nama_dokumen' => $namaDokumen,
                    'keterangan' => $request->keterangan ?? 'Dokumen program peningkatan SPMI',
                    'file_path' => $filePath,
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'is_public' => true,
                    'tahapan' => 'peningkatan',
                    'metadata' => json_encode([
                        'peningkatan_id' => $peningkatan->id,
                        'nama_program' => $peningkatan->nama_program,
                        'tipe_peningkatan' => $peningkatan->tipe_peningkatan,
                        'tahun' => $peningkatan->tahun,
                        'kode_peningkatan' => $peningkatan->kode_peningkatan,
                        'upload_source' => $request->has('upload_source') ? $request->upload_source : 'inline_form'
                    ])
                ]);
                
                $peningkatan->update([
                    'status_dokumen' => 'valid',
                    'dokumen_id' => $peningkatan->dokumen_id ?? $dokumen->id,
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Dokumen berhasil diupload.',
                        'dokumen' => $dokumen,
                        'peningkatan' => $peningkatan
                    ]);
                }
                
                return redirect()->route('spmi.peningkatan.show', $peningkatan->id)
                    ->with('success', 'Dokumen berhasil diupload dan terkait dengan program peningkatan.');
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
     * Download dokumen peningkatan.
     */
    public function downloadDokumen($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::with('dokumen')->findOrFail($id);
            
            if (!$peningkatan->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $peningkatan->dokumen;
            
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
     * Preview dokumen.
     */
    public function previewDokumen($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::with('dokumen')->findOrFail($id);
            
            if (!$peningkatan->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $peningkatan->dokumen;
            
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }
            
            if ($dokumen->file_extension !== 'pdf') {
                return back()->with('info', 'Preview hanya tersedia untuk file PDF.');
            }
            
            $filePath = Storage::disk('public')->path($dokumen->file_path);
            
            return response()->file($filePath);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mempreview dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Hapus dokumen dari peningkatan.
     */
    public function hapusDokumen($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::with('dokumen')->findOrFail($id);
            
            if (!$peningkatan->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $peningkatan->dokumen;
            
            if ($dokumen->jenis_upload === 'file' && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            $dokumen->delete();
            
            $peningkatan->update([
                'dokumen_id' => null,
                'status_dokumen' => 'belum_valid',
                'file_path' => null,
            ]);
            
            return redirect()->route('spmi.peningkatan.show', $peningkatan->id)
                ->with('success', 'Dokumen berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Update status dokumen.
     */
    public function updateStatusDokumen(Request $request, $id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            $request->validate([
                'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            $peningkatan->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan_evaluasi' => $request->catatan,
                'tanggal_review' => now(),
                'diperiksa_oleh' => auth()->user()->name ?? 'System',
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status dokumen berhasil diperbarui.',
                    'status_dokumen' => $peningkatan->status_dokumen
                ]);
            }
            
            return redirect()->route('spmi.peningkatan.show', $peningkatan->id)
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
     * AJAX: Get data for view modal
     */
    public function getPeningkatanData($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            $allDokumen = $peningkatan->getAllDokumen();
            
            $html = view('dashboard.spmi.peningkatan.partials.detail-modal', compact('peningkatan', 'allDokumen'))->render();
            
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
     * AJAX: Get form for edit modal
     */
    public function getEditForm($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            $html = view('dashboard.spmi.peningkatan.partials.edit-form', compact('peningkatan', 'unitKerjas', 'ikus'))->render();
            
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
     * AJAX: Update via AJAX
     */
    public function updateAjax(Request $request, $id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            $request->validate([
                'nama_program' => 'required|string|max:255',
                'tipe_peningkatan' => 'required|in:strategis,operasional,perbaikan,pengembangan,inovasi',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:draft,disetujui,berjalan,selesai,ditunda,dibatalkan',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            $peningkatan->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $peningkatan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Get dokumen list for peningkatan
     */
    public function getDokumenList($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            $allDokumen = $peningkatan->getAllDokumen();
            
            $html = view('dashboard.spmi.peningkatan.partials.dokumen-list', compact('allDokumen'))->render();
            
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
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $total = PeningkatanSPMI::count();
            $aktif = PeningkatanSPMI::whereIn('status', ['disetujui', 'berjalan'])->count();
            $selesai = PeningkatanSPMI::where('status', 'selesai')->count();
            $valid = PeningkatanSPMI::where('status_dokumen', 'valid')->count();
            $belumValid = PeningkatanSPMI::where('status_dokumen', 'belum_valid')->count();
            
            $byTipe = PeningkatanSPMI::select('tipe_peningkatan', DB::raw('count(*) as count'))
                ->groupBy('tipe_peningkatan')
                ->get()
                ->pluck('count', 'tipe_peningkatan');
            
            $byTahun = PeningkatanSPMI::select('tahun', DB::raw('count(*) as count'))
                ->groupBy('tahun')
                ->orderBy('tahun', 'desc')
                ->take(5)
                ->get();
            
            // Total anggaran
            $totalAnggaran = PeningkatanSPMI::sum('anggaran');
            $totalRealisasi = PeningkatanSPMI::sum('realisasi_anggaran');
            
            return response()->json([
                'success' => true,
                'statistics' => [
                    'total' => $total,
                    'aktif' => $aktif,
                    'selesai' => $selesai,
                    'valid' => $valid,
                    'belum_valid' => $belumValid,
                    'by_tipe' => $byTipe,
                    'by_tahun' => $byTahun,
                    'total_anggaran' => $totalAnggaran,
                    'total_realisasi' => $totalRealisasi,
                    'rata_progress' => $total > 0 ? round(PeningkatanSPMI::avg('progress'), 1) : 0,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all years available for filtering
     */
    public function getTahunList()
    {
        $tahunList = PeningkatanSPMI::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
            
        return response()->json([
            'success' => true,
            'tahun_list' => $tahunList
        ]);
    }

    /**
     * Get dashboard summary
     */
    public function getDashboardSummary()
    {
        try {
            $totalProgram = PeningkatanSPMI::count();
            $programBerjalan = PeningkatanSPMI::where('status', 'berjalan')->count();
            $programSelesai = PeningkatanSPMI::where('status', 'selesai')->count();
            $totalAnggaran = PeningkatanSPMI::sum('anggaran');
            $rataProgress = $totalProgram > 0 ? round(PeningkatanSPMI::avg('progress'), 1) : 0;
            
            $programTerbaru = PeningkatanSPMI::with('unitKerja')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $byTipe = PeningkatanSPMI::select('tipe_peningkatan', DB::raw('count(*) as count'))
                ->groupBy('tipe_peningkatan')
                ->get();
            
            return response()->json([
                'success' => true,
                'summary' => [
                    'total_program' => $totalProgram,
                    'program_berjalan' => $programBerjalan,
                    'program_selesai' => $programSelesai,
                    'total_anggaran' => $totalAnggaran,
                    'rata_progress' => $rataProgress,
                    'program_terbaru' => $programTerbaru,
                    'by_tipe' => $byTipe,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $query = PeningkatanSPMI::with(['unitKerja', 'iku']);
            
            if ($request->has('tahun') && $request->tahun != 'all') {
                $query->where('tahun', $request->tahun);
            }
            
            if ($request->has('tipe') && $request->tipe != 'all') {
                $query->where('tipe_peningkatan', $request->tipe);
            }
            
            $data = $query->get();
            
            // TODO: Implement Excel export using Laravel Excel or similar package
            // For now, return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Export Excel berhasil (fitur dalam pengembangan)',
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $query = PeningkatanSPMI::with(['unitKerja', 'iku']);
            
            if ($request->has('tahun') && $request->tahun != 'all') {
                $query->where('tahun', $request->tahun);
            }
            
            if ($request->has('tipe') && $request->tipe != 'all') {
                $query->where('tipe_peningkatan', $request->tipe);
            }
            
            $data = $query->get();
            
            // TODO: Implement PDF export using DomPDF or similar package
            return response()->json([
                'success' => true,
                'message' => 'Export PDF berhasil (fitur dalam pengembangan)',
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update progress multiple programs
     */
    public function updateProgressBulk(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:peningkatan_s_p_m_i_s,id',
                'progress' => 'required|integer|min:0|max:100',
            ]);
            
            $updated = PeningkatanSPMI::whereIn('id', $request->ids)
                ->update(['progress' => $request->progress]);
            
            return response()->json([
                'success' => true,
                'message' => 'Progress ' . $updated . ' program berhasil diperbarui.',
                'updated' => $updated
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get program peningkatan by status
     */
    public function getByStatus($status)
    {
        try {
            $program = PeningkatanSPMI::with(['unitKerja', 'iku'])
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $program
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get program peningkatan with progress filter
     */
    public function getByProgress(Request $request)
    {
        try {
            $min = $request->get('min', 0);
            $max = $request->get('max', 100);
            
            $program = PeningkatanSPMI::with(['unitKerja', 'iku'])
                ->whereBetween('progress', [$min, $max])
                ->orderBy('progress', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $program
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync dokumen relationships
     */
    public function syncDokumenRelationships($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            $allDokumen = $peningkatan->getAllDokumen();
            
            if (!$peningkatan->dokumen_id && $allDokumen->count() > 0) {
                $firstDokumen = $allDokumen->first();
                $peningkatan->dokumen_id = $firstDokumen->id;
                $peningkatan->save();
            }
            
            $totalDokumen = $allDokumen->count();
            $peningkatan->status_dokumen = $totalDokumen > 0 ? 'valid' : 'belum_valid';
            $peningkatan->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi dokumen berhasil.',
                'total_dokumen' => $totalDokumen
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get export data for a program
     */
    public function getExportData($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::with(['unitKerja', 'iku'])->findOrFail($id);
            $dokumen = $peningkatan->getAllDokumen();
            
            $exportData = [
                'id' => $peningkatan->id,
                'nama_program' => $peningkatan->nama_program,
                'kode_peningkatan' => $peningkatan->kode_peningkatan,
                'tipe_peningkatan' => $peningkatan->tipe_peningkatan_label,
                'tahun' => $peningkatan->tahun,
                'status' => $peningkatan->status_label,
                'status_dokumen' => $peningkatan->status_dokumen_label,
                'penanggung_jawab' => $peningkatan->penanggung_jawab,
                'unit_kerja' => $peningkatan->unitKerja->nama ?? '',
                'iku' => $peningkatan->iku->nama ?? '',
                'deskripsi' => $peningkatan->deskripsi,
                'anggaran' => $peningkatan->anggaran_formatted,
                'realisasi_anggaran' => $peningkatan->realisasi_anggaran_formatted,
                'progress' => $peningkatan->progress . '%',
                'tanggal_mulai' => $peningkatan->tanggal_mulai ? $peningkatan->tanggal_mulai->format('Y-m-d') : '',
                'tanggal_selesai' => $peningkatan->tanggal_selesai ? $peningkatan->tanggal_selesai->format('Y-m-d') : '',
                'catatan_evaluasi' => $peningkatan->catatan_evaluasi,
                'total_dokumen' => $dokumen->count(),
                'dokumen_list' => $dokumen->map(function($doc) {
                    return [
                        'nama' => $doc->nama_dokumen,
                        'jenis' => $doc->jenis_dokumen,
                        'ukuran' => $doc->file_size_formatted,
                        'tanggal_upload' => $doc->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'created_at' => $peningkatan->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $peningkatan->updated_at->format('Y-m-d H:i:s'),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $exportData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data export: ' . $e->getMessage()
            ], 500);
        }
    }
}