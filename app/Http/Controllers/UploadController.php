<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function create()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        // Otomatis true karena semua route setelah login pakai /admin/
        $isAdmin = true;
        
        return view('upload-dokumen', compact('unitKerjas', 'ikus', 'isAdmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'iku_id' => 'required|exists:ikus,id',
            'nama_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240' // DIUBAH: hanya format doc, pdf, excel
        ], [
            'file_dokumen.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, atau XLSX.',
            'file_dokumen.max' => 'Ukuran file maksimal 10MB.'
        ]);

        try {
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/dokumen', $fileName, 'public');

                Dokumen::create([
                    'unit_kerja_id' => $request->unit_kerja_id,
                    'iku_id' => $request->iku_id,
                    'jenis_dokumen' => 'dokumen_mutu',
                    'nama_dokumen' => $request->nama_dokumen,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'uploaded_by' => Auth::id()
                ]);

                return redirect()->route('dokumen-saya')
                    ->with('success', 'Dokumen berhasil diupload!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }

        return back()->with('error', 'Gagal mengupload dokumen.');
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