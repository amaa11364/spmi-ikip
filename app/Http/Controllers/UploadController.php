<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function create()
{
    $unitKerjas = UnitKerja::where('status', true)->get();
    $ikus = Iku::where('status', true)->get();
    
    return view('upload-dokumen', compact('unitKerjas', 'ikus'));
}

   public function store(Request $request)
{
    try {
        $request->validate([
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'iku_id' => 'required|exists:ikus,id',
            'jenis_dokumen' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'dokumen_file' => 'required|file|max:10240', // 10MB max
            'is_public' => 'boolean'
        ]);

        // Handle file upload
        if ($request->hasFile('dokumen_file')) {
            $file = $request->file('dokumen_file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate unique filename
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            
            // Store file dengan path yang konsisten
            $filePath = $file->storeAs('dokumen', $fileName, 'local');
            
            // Create dokumen record
            Dokumen::create([
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'jenis_dokumen' => $request->jenis_dokumen,
                'nama_dokumen' => $request->nama_dokumen,
                'file_path' => $filePath,
                'file_name' => $originalName,
                'file_size' => $file->getSize(),
                'file_extension' => $extension,
                'uploaded_by' => auth()->id(),
                'is_public' => $request->is_public ?? false
            ]);

            return redirect()->route('dokumen-saya')
                ->with('success', 'Dokumen berhasil diupload!');
        }

        return back()->with('error', 'Tidak ada file yang diupload.');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
    }
}

    public function index(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'uploader', 'iku'])
                       ->where('uploaded_by', Auth::id());

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        if ($request->has('unit_kerja') && $request->unit_kerja != '') {
            $query->where('unit_kerja_id', $request->unit_kerja);
        }

        if ($request->has('iku_id') && $request->iku_id != '') {
            $query->where('iku_id', $request->iku_id);
        }

        $dokumens = $query->orderBy('created_at', 'desc')->get();
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();

        // Otomatis true karena semua route setelah login pakai /admin/
        $isAdmin = true;

        return view('dokumen-saya', compact('dokumens', 'unitKerjas', 'ikus', 'isAdmin'));
    }

    // Hapus dokumen
    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            // Hapus file dari storage
            if (Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            // Hapus dari database
            $dokumen->delete();

            return redirect()->route('dokumen-saya')->with('success', 'Dokumen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dokumen-saya')->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    // Download dokumen
    public function download($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload dokumen: ' . $e->getMessage());
        }
    }

    // Preview dokumen (khusus PDF)
    public function preview($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            // Hanya preview untuk PDF
            if ($dokumen->file_extension !== 'pdf') {
                return back()->with('info', 'Preview hanya tersedia untuk file PDF.');
            }

            $filePath = Storage::disk('public')->path($dokumen->file_path);
            
            return response()->file($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mempreview dokumen: ' . $e->getMessage());
        }
    }
}