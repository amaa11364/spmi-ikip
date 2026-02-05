<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiSpm;
use App\Models\UnitKerja;
use App\Models\Iku;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EvaluasiSpmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $tahun = $request->get('tahun', 'all');
        $tipe = $request->get('tipe', 'all');
        $status = $request->get('status', 'all');
        $periode = $request->get('periode', 'all');
        $statusDokumen = $request->get('status_dokumen', 'all');

        // Query evaluasi
        $evaluasi = EvaluasiSpm::query()
            ->with('unitKerja')
            ->search($search)
            ->byTahun($tahun)
            ->byTipe($tipe)
            ->byStatus($status)
            ->byPeriode($periode)
            ->byStatusDokumen($statusDokumen)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Get filter lists
        $tahunList = EvaluasiSpm::getTahunList();
        $periodeList = EvaluasiSpm::getPeriodeList();
        $unitKerjaList = UnitKerja::all();

        // Get statistics
        $statistics = EvaluasiSpm::getStatistics();

        return view('spmi.evaluasi.index', compact(
            'evaluasi',
            'tahunList',
            'periodeList',
            'unitKerjaList',
            'statistics'
        ))->with([
            'totalEvaluasi' => $statistics['total'],
            'evaluasiSelesai' => $statistics['selesai'],
            'dokumenValid' => $statistics['valid'],
            'dokumenBelumValid' => $statistics['belum_valid'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitKerjas = UnitKerja::all();
        $ikus = Iku::all();
        
        return view('spmi.evaluasi.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_komponen' => 'required|string|max:255',
            'tipe_evaluasi' => 'required|in:internal,eksternal,berkala,khusus',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'periode' => 'required|string|max:50',
            'status' => 'required|in:draft,proses,selesai,ditunda',
            'deskripsi' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string|max:100',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'iku_id' => 'nullable|exists:ikus,id',
            'tanggal_evaluasi' => 'nullable|date',
            'hasil_evaluasi' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $evaluasi = EvaluasiSpm::create([
            'nama_komponen' => $request->nama_komponen,
            'tipe_evaluasi' => $request->tipe_evaluasi,
            'tahun' => $request->tahun,
            'periode' => $request->periode,
            'status' => $request->status,
            'status_dokumen' => 'belum_valid',
            'deskripsi' => $request->deskripsi,
            'penanggung_jawab' => $request->penanggung_jawab,
            'unit_kerja_id' => $request->unit_kerja_id,
            'iku_id' => $request->iku_id,
            'tanggal_evaluasi' => $request->tanggal_evaluasi,
            'hasil_evaluasi' => $request->hasil_evaluasi,
            'rekomendasi' => $request->rekomendasi,
            'diperiksa_oleh' => auth()->user()->name,
        ]);

        return redirect()->route('spmi.evaluasi.index')
            ->with('success', 'Evaluasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiSpm::with(['unitKerja', 'iku'])->findOrFail($id);
        $allDokumen = $evaluasi->getAllDokumen();

        return view('spmi.evaluasi.show', compact('evaluasi', 'allDokumen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);
        $unitKerjas = UnitKerja::all();
        $ikus = Iku::all();

        return view('spmi.evaluasi.edit', compact('evaluasi', 'unitKerjas', 'ikus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_komponen' => 'required|string|max:255',
            'tipe_evaluasi' => 'required|in:internal,eksternal,berkala,khusus',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'periode' => 'required|string|max:50',
            'status' => 'required|in:draft,proses,selesai,ditunda',
            'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
            'deskripsi' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string|max:100',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'iku_id' => 'nullable|exists:ikus,id',
            'tanggal_evaluasi' => 'nullable|date',
            'hasil_evaluasi' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $evaluasi->update([
            'nama_komponen' => $request->nama_komponen,
            'tipe_evaluasi' => $request->tipe_evaluasi,
            'tahun' => $request->tahun,
            'periode' => $request->periode,
            'status' => $request->status,
            'status_dokumen' => $request->status_dokumen,
            'deskripsi' => $request->deskripsi,
            'penanggung_jawab' => $request->penanggung_jawab,
            'unit_kerja_id' => $request->unit_kerja_id,
            'iku_id' => $request->iku_id,
            'tanggal_evaluasi' => $request->tanggal_evaluasi,
            'hasil_evaluasi' => $request->hasil_evaluasi,
            'rekomendasi' => $request->rekomendasi,
        ]);

        return redirect()->route('spmi.evaluasi.show', $id)
            ->with('success', 'Evaluasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);
        $evaluasi->delete();

        return redirect()->route('spmi.evaluasi.index')
            ->with('success', 'Evaluasi berhasil dihapus.');
    }

    /**
     * Restore soft deleted evaluasi.
     */
    public function restoreEvaluasi($id)
    {
        $evaluasi = EvaluasiSpm::withTrashed()->findOrFail($id);
        $evaluasi->restore();

        return redirect()->route('spmi.evaluasi.index')
            ->with('success', 'Evaluasi berhasil dipulihkan.');
    }

    /**
     * Upload dokumen for evaluasi.
     */
    public function uploadDokumenEvaluasi(Request $request, $id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'file_dokumen' => 'required|file|max:10240', // 10MB max
            'keterangan' => 'nullable|string|max:255',
            'jenis_dokumen' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file_dokumen');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = 'EVAL_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $file->storeAs($evaluasi->folder_path, $fileName, 'public');
            $fileSize = $file->getSize();

            // Create dokumen record
            $dokumen = Dokumen::create([
                'nama_dokumen' => $originalName,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_extension' => $extension,
                'jenis_dokumen' => $request->jenis_dokumen ?? 'Dokumen Evaluasi',
                'keterangan' => $request->keterangan ?? 'Dokumen evaluasi SPMI',
                'tahapan' => 'evaluasi',
                'metadata' => [
                    'evaluasi_id' => $evaluasi->id,
                    'nama_komponen' => $evaluasi->nama_komponen,
                    'kode_evaluasi' => $evaluasi->kode_evaluasi,
                    'tahun' => $evaluasi->tahun,
                    'periode' => $evaluasi->periode,
                    'upload_source' => $request->upload_source ?? 'upload_form',
                ],
                'uploader_id' => auth()->id(),
            ]);

            // If this is the first dokumen, set as main dokumen
            if (!$evaluasi->dokumen_id) {
                $evaluasi->dokumen_id = $dokumen->id;
                $evaluasi->save();
            }

            // Update status dokumen
            $evaluasi->update(['status_dokumen' => 'valid']);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload.',
                'data' => $dokumen
            ]);

        } catch (\Exception $e) {
            \Log::error('Upload dokumen error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download dokumen.
     */
    public function downloadDokumenEvaluasi($id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);
        
        if (!$evaluasi->dokumen_id) {
            return redirect()->back()->with('error', 'Tidak ada dokumen terkait.');
        }

        $dokumen = Dokumen::findOrFail($evaluasi->dokumen_id);
        
        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->nama_dokumen);
    }

    /**
     * Update status dokumen.
     */
    public function updateStatusDokumenEvaluasi(Request $request, $id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);

        $request->validate([
            'status_dokumen' => 'required|in:valid,belum_valid,dalam_review'
        ]);

        $evaluasi->update([
            'status_dokumen' => $request->status_dokumen,
            'tanggal_selesai' => now(),
            'diperiksa_oleh' => auth()->user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status dokumen berhasil diperbarui.'
        ]);
    }

    /**
     * Get evaluasi data for AJAX detail.
     */
    public function getEvaluasiData($id)
    {
        $evaluasi = EvaluasiSpm::with(['unitKerja', 'iku'])->findOrFail($id);
        $allDokumen = $evaluasi->getAllDokumen();

        $html = view('spmi.evaluasi.detail-modal', compact('evaluasi', 'allDokumen'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get edit form for AJAX.
     */
    public function getEditFormEvaluasi($id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);
        $unitKerjas = UnitKerja::all();
        $ikus = Iku::all();

        $html = view('spmi.evaluasi.edit-form', compact('evaluasi', 'unitKerjas', 'ikus'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Update evaluasi via AJAX.
     */
    public function updateEvaluasiAjax(Request $request, $id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_komponen' => 'required|string|max:255',
            'tipe_evaluasi' => 'required|in:internal,eksternal,berkala,khusus',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'periode' => 'required|string|max:50',
            'status' => 'required|in:draft,proses,selesai,ditunda',
            'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
            'deskripsi' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string|max:100',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'iku_id' => 'nullable|exists:ikus,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422);
        }

        $evaluasi->update($request->only([
            'nama_komponen', 'tipe_evaluasi', 'tahun', 'periode', 'status', 
            'status_dokumen', 'deskripsi', 'penanggung_jawab', 'unit_kerja_id', 'iku_id'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Evaluasi berhasil diperbarui.'
        ]);
    }

    /**
     * Get dokumen list for AJAX.
     */
    public function getDokumenListEvaluasi($id)
    {
        $evaluasi = EvaluasiSpm::findOrFail($id);
        $allDokumen = $evaluasi->getAllDokumen();

        $html = view('spmi.evaluasi.dokumen-list', compact('allDokumen'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $allDokumen->count()
        ]);
    }

    /**
     * Bulk action for evaluasi.
     */
    public function bulkActionEvaluasi(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item yang dipilih.'
            ]);
        }

        switch ($action) {
            case 'delete':
                EvaluasiSpm::whereIn('id', $ids)->delete();
                break;
            case 'aktif':
                EvaluasiSpm::whereIn('id', $ids)->update(['status' => 'selesai']);
                break;
            case 'nonaktif':
                EvaluasiSpm::whereIn('id', $ids)->update(['status' => 'ditunda']);
                break;
            case 'valid':
                EvaluasiSpm::whereIn('id', $ids)->update(['status_dokumen' => 'valid']);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Aksi berhasil dilakukan.'
        ]);
    }

    /**
     * Export to Excel.
     */
    public function exportExcelEvaluasi()
    {
        // Implementation for Excel export
        return response()->json([
            'success' => true,
            'message' => 'Export feature akan segera diimplementasikan.'
        ]);
    }

    /**
     * Get statistics for AJAX.
     */
    public function getStatistics()
    {
        $statistics = EvaluasiSpm::getStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }
}