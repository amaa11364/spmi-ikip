<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function index()
    {
        $publicDokumens = collect();
        $searchTerm = request('q');
        
        if ($searchTerm) {
            $publicDokumens = Dokumen::with(['unitKerja', 'iku'])
                ->public()
                ->where(function($query) use ($searchTerm) {
                    $query->where('nama_dokumen', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('jenis_dokumen', 'LIKE', "%{$searchTerm}%")
                          ->orWhereHas('unitKerja', function($q) use ($searchTerm) {
                              $q->where('nama', 'LIKE', "%{$searchTerm}%");
                          })
                          ->orWhereHas('iku', function($q) use ($searchTerm) {
                              $q->where('kode', 'LIKE', "%{$searchTerm}%")
                                ->orWhere('nama', 'LIKE', "%{$searchTerm}%");
                          });
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('landing.index', compact('publicDokumens', 'searchTerm'));
    }

    public function searchPublic(Request $request)
    {
        return $this->index();
    }

    public function previewPublicDokumen($id)
    {
        $dokumen = Dokumen::public()->findOrFail($id);
        
        // Cek file exists
        if (!Storage::exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan di storage');
        }
        
        // Untuk PDF, tampilkan preview
        if ($dokumen->is_pdf) {
            $filePath = storage_path('app/' . $dokumen->file_path);
            
            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $dokumen->file_name . '"'
                ]);
            }
        }
        
        // Untuk file lain, redirect ke download
        return $this->downloadPublicDokumen($id);
    }

    public function downloadPublicDokumen($id)
{
    $dokumen = Dokumen::public()->findOrFail($id);
    
    \Log::info('Download attempt - File path: ' . $dokumen->file_path);
    \Log::info('Storage exists: ' . (Storage::exists($dokumen->file_path) ? 'YES' : 'NO'));
    
    // List semua file di storage untuk debug
    $allFiles = Storage::allFiles('dokumen');
    \Log::info('All files in storage: ', $allFiles);
    
    if (!Storage::exists($dokumen->file_path)) {
        abort(404, 'File tidak ditemukan. Path: ' . $dokumen->file_path);
    }
    
    return Storage::download($dokumen->file_path, $dokumen->file_name);
}
    public function about()
    {
        return view('landing.about');
    }

    public function features()
    {
        return view('landing.features');
    }

    public function contact()
    {
        return view('landing.contact');
    }
}