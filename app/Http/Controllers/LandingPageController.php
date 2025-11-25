<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $publicDokumens = collect();
        $searchTerm = request('q');
        
        // Jika ada pencarian, tampilkan dokumen publik
        if ($searchTerm) {
            $publicDokumens = Dokumen::with(['unitKerja', 'iku'])
                ->where('is_public', true) // Hanya dokumen publik
                ->where(function($query) use ($searchTerm) {
                    $query->where('nama_dokumen', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('jenis_dokumen', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%")
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
        $dokumen = Dokumen::where('is_public', true)->findOrFail($id);
        
        // Logic untuk preview dokumen PDF
        if ($dokumen->is_pdf) {
            // Untuk testing, kita return success message dulu
            // Nanti bisa diimplementasi preview file asli
            return response()->json([
                'message' => 'Preview dokumen: ' . $dokumen->nama_dokumen,
                'type' => 'pdf'
            ]);
        }
        
        return response()->json([
            'message' => 'Preview hanya tersedia untuk file PDF',
            'type' => 'other'
        ], 400);
    }

    public function downloadPublicDokumen($id)
    {
        $dokumen = Dokumen::where('is_public', true)->findOrFail($id);
        
        // Untuk testing, kita return success message dulu
        // Nanti bisa diimplementasi download file asli
        return response()->json([
            'message' => 'Download dokumen: ' . $dokumen->nama_dokumen,
            'file_name' => $dokumen->file_name,
            'type' => $dokumen->file_extension
        ]);
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
