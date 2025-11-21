<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // Halaman untuk melihat semua dokumen (Google Drive style)
    public function index()
    {
        $dokumens = Dokumen::with(['programStudi', 'uploader'])
                          ->where('uploaded_by', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        // Group by jenis dokumen untuk filter
        $jenisDokumen = Dokumen::where('uploaded_by', auth()->id())
                              ->distinct()
                              ->pluck('jenis_dokumen');
        
        return view('dokumen-saya', compact('dokumens', 'jenisDokumen'));
    }

    // Halaman form upload
    public function create()
    {
        $programStudi = ProgramStudi::where('status', true)->get();
        return view('upload-dokumen', compact('programStudi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_studi' => 'required|exists:program_studis,id',
            'jenis_dokumen' => 'required',
            'nama_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:10240' // 10MB
        ]);

        try {
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/dokumen', $fileName, 'public');

                Dokumen::create([
                    'program_studi_id' => $request->program_studi,
                    'jenis_dokumen' => $request->jenis_dokumen,
                    'nama_dokumen' => $request->nama_dokumen,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'uploaded_by' => auth()->id()
                ]);

                return redirect()->route('dokumen-saya')
                    ->with('success', 'Dokumen berhasil diupload!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }

        return back()->with('error', 'Gagal mengupload dokumen.');
    }

    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', auth()->id())->findOrFail($id);
            
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

    public function download($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', auth()->id())->findOrFail($id);
            
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
            $dokumen = Dokumen::where('uploaded_by', auth()->id())->findOrFail($id);
            
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

    // Filter dokumen
    public function filter(Request $request)
    {
        $query = Dokumen::with(['programStudi', 'uploader'])
                       ->where('uploaded_by', auth()->id());

        if ($request->jenis_dokumen) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }

        if ($request->program_studi) {
            $query->where('program_studi_id', $request->program_studi);
        }

        if ($request->search) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        $dokumens = $query->orderBy('created_at', 'desc')->get();
        $jenisDokumen = Dokumen::where('uploaded_by', auth()->id())
                              ->distinct()
                              ->pluck('jenis_dokumen');

        return view('dokumen-saya', compact('dokumens', 'jenisDokumen'));
    }
}