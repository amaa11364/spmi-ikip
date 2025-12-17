<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    /**
     * Menampilkan halaman pencarian utama dengan semua dokumen
     */
    public function index()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        // Tampilkan SEMUA dokumen saat pertama kali load
        $dokumens = Dokumen::with(['unitKerja', 'iku', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('search.index', compact('dokumens', 'unitKerjas', 'ikus'));
    }

    /**
     * Menangani proses pencarian dan filtering (untuk form submit biasa)
     */
    public function search(Request $request)
    {
        $dokumens = $this->getSearchResults($request);
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();

        return view('search.index', compact('dokumens', 'unitKerjas', 'ikus'));
    }

    /**
     * API untuk pencarian AJAX real-time
     */
    public function ajaxSearch(Request $request)
    {
        $dokumens = $this->getSearchResults($request);
        
        return response()->json([
            'success' => true,
            'dokumens' => $dokumens->map(function($dokumen) {
                return [
                    'id' => $dokumen->id,
                    'nama_dokumen' => $dokumen->nama_dokumen,
                    'jenis_dokumen' => $dokumen->jenis_dokumen,
                    'file_icon' => $dokumen->file_icon,
                    'file_size_formatted' => $dokumen->file_size_formatted,
                    'upload_time_ago' => $dokumen->upload_time_ago,
                    'is_pdf' => $dokumen->is_pdf,
                    'unit_kerja' => $dokumen->unitKerja->nama,
                    'iku' => $dokumen->iku ? [
                        'kode' => $dokumen->iku->kode,
                        'nama' => $dokumen->iku->nama
                    ] : null,
                    'uploader' => $dokumen->uploader->name,
                    'preview_url' => route('search.dokumen.preview', $dokumen->id),
                    'download_url' => route('search.dokumen.download', $dokumen->id)
                ];
            }),
            'count' => $dokumens->count()
        ]);
    }

    /**
     * Helper method untuk mendapatkan hasil pencarian
     */
    private function getSearchResults(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'iku', 'uploader']);
        
        // Filter berdasarkan pencarian teks
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_dokumen', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('jenis_dokumen', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('unitKerja', function($q2) use ($searchTerm) {
                      $q2->where('nama', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('iku', function($q2) use ($searchTerm) {
                      $q2->where('kode', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('nama', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('uploader', function($q2) use ($searchTerm) {
                      $q2->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Filter berdasarkan unit kerja
        if ($request->has('unit_kerja') && !empty($request->unit_kerja)) {
            $query->where('unit_kerja_id', $request->unit_kerja);
        }

        // Filter berdasarkan IKU
        if ($request->has('iku_id') && !empty($request->iku_id)) {
            $query->where('iku_id', $request->iku_id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
 * Preview dokumen (khusus PDF)
 */
public function preview($id)
{
    $dokumen = Dokumen::with(['unitKerja', 'iku'])->findOrFail($id);
    
    // Debug info
    \Log::info("Preview attempt - Dokumen ID: {$id}");
    \Log::info("File path: {$dokumen->file_path}");
    \Log::info("Storage exists: " . (Storage::exists($dokumen->file_path) ? 'YES' : 'NO'));
    
    // Cek file exists
    if (!Storage::exists($dokumen->file_path)) {
        \Log::error("File not found: {$dokumen->file_path}");
        
        // Return error message instead of 404
        return back()->with('error', 'File tidak ditemukan: ' . $dokumen->file_path);
    }
    
    // Untuk PDF, tampilkan preview
    if ($dokumen->is_pdf) {
        $filePath = storage_path('app/' . $dokumen->file_path);
        
        if (file_exists($filePath)) {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $dokumen->file_name . '"'
            ]);
        } else {
            \Log::error("PDF file not found in storage path: {$filePath}");
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
    }
    
    // Untuk file lain, redirect ke download
    return $this->download($id);
}

/**
 * Download dokumen
 */
public function download($id)
{
    $dokumen = Dokumen::findOrFail($id);
    
    // Debug info
    \Log::info("Download attempt - Dokumen ID: {$id}");
    \Log::info("File path: {$dokumen->file_path}");
    \Log::info("File name: {$dokumen->file_name}");
    \Log::info("Storage exists: " . (Storage::exists($dokumen->file_path) ? 'YES' : 'NO'));
    
    // List files untuk debugging
    $allFiles = Storage::allFiles('dokumen');
    \Log::info("All files in dokumen directory:", $allFiles);
    
    $allFilesUploads = Storage::allFiles('uploads/dokumen');
    \Log::info("All files in uploads/dokumen directory:", $allFilesUploads);
    
    // Cek file exists
    if (!Storage::exists($dokumen->file_path)) {
        \Log::error("File not found for download: {$dokumen->file_path}");
        
        // Coba cari file dengan nama yang sama
        $allFiles = Storage::allFiles();
        $foundFiles = [];
        foreach ($allFiles as $file) {
            if (strpos($file, $dokumen->file_name) !== false) {
                $foundFiles[] = $file;
            }
        }
        \Log::info("Files with similar name found:", $foundFiles);
        
        return back()->with('error', 'File tidak ditemukan. Path: ' . $dokumen->file_path);
    }
    
    return Storage::download($dokumen->file_path, $dokumen->file_name);
}
}