<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Prodi;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
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
            $query->byStatus($request->status);
        }
        
        // Filter berdasarkan tahapan
        if ($request->filled('tahapan')) {
            $query->byTahapan($request->tahapan);
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
        
        // PERBAIKAN: Gunakan field yang benar sesuai model
        $unitKerjas = UnitKerja::orderBy('nama')->get(); // Ubah dari 'nama_unit' ke 'nama'
        $prodis = Prodi::orderBy('nama_prodi')->get(); // Ini sudah benar karena di model Prodi ada field 'nama_prodi'
        $ikus = Iku::orderBy('nama')->get(); // Ubah dari 'nama_iku' ke 'nama' (sesuai model Iku)
        
        // Statistik
        $statistics = [
            'total' => Dokumen::count(),
            'pending' => Dokumen::pending()->count(),
            'approved' => Dokumen::approved()->count(),
            'rejected' => Dokumen::rejected()->count(),
            'public' => Dokumen::public()->count(),
            'private' => Dokumen::where('is_public', false)->count(),
        ];
        
        return view('admin.dokumen.index', compact(
            'dokumens', 
            'unitKerjas', 
            'prodis', 
            'ikus',
            'statistics'
        ));
    }

    /**
     * Display the specified document.
     */
    public function show($id)
    {
        $dokumen = Dokumen::with(['unitKerja', 'prodi', 'iku', 'uploader', 'verifier', 'comments.user'])
            ->findOrFail($id);
            
        return view('admin.dokumen.show', compact('dokumen'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // PERBAIKAN: Gunakan field yang benar sesuai model
        $unitKerjas = UnitKerja::orderBy('nama')->get(); // Ubah dari 'nama_unit' ke 'nama'
        $prodis = Prodi::orderBy('nama_prodi')->get(); // Ini sudah benar
        $ikus = Iku::orderBy('nama')->get(); // Ubah dari 'nama_iku' ke 'nama'
        
        $tahapanOptions = [
            'penetapan' => 'Penetapan SPMI',
            'pelaksanaan' => 'Pelaksanaan SPMI',
            'evaluasi' => 'Evaluasi SPMI',
            'pengendalian' => 'Pengendalian SPMI',
            'peningkatan' => 'Peningkatan SPMI',
        ];
        
        return view('admin.dokumen.edit', compact(
            'dokumen', 
            'unitKerjas', 
            'prodis', 
            'ikus',
            'tahapanOptions'
        ));
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'jenis_dokumen' => 'required|string|max:100',
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'prodi_id' => 'nullable|exists:prodis,id',
            'iku_id' => 'nullable|exists:ikus,id',
            'tahapan' => 'nullable|string|in:penetapan,pelaksanaan,evaluasi,pengendalian,peningkatan',
            'is_public' => 'boolean',
            'metadata' => 'nullable|array',
        ]);
        
        // Handle is_public checkbox
        $validated['is_public'] = $request->has('is_public');
        
        $dokumen->update($validated);
        
        return redirect()
            ->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified document.
     */
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Hapus file fisik jika ada
        if ($dokumen->jenis_upload === 'file' && $dokumen->fileExists()) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        
        $dokumen->delete();
        
        return redirect()
            ->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Toggle public status of the document.
     */
    public function togglePublic($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $dokumen->update([
            'is_public' => !$dokumen->is_public
        ]);
        
        $status = $dokumen->is_public ? 'dipublikasikan' : 'ditutup';
        
        return redirect()
            ->route('admin.dokumen.index')
            ->with('success', "Dokumen berhasil {$status}.");
    }

    /**
     * Export documents data.
     */
    public function export(Request $request)
    {
        // Logika export (PDF/Excel)
        // Bisa dikembangkan sesuai kebutuhan
        
        return redirect()
            ->route('admin.dokumen.index')
            ->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}