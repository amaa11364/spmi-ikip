[file name]: LandingPageController.php
[file content begin]
<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $publicDokumens = collect();
        
        // Jika ada pencarian, tampilkan dokumen publik
        if (request()->has('q')) {
            $searchTerm = request('q');
            
            $publicDokumens = Dokumen::with(['unitKerja', 'iku'])
                ->where('is_public', true) // Hanya dokumen publik
                ->where(function($query) use ($searchTerm) {
                    $query->where('nama_dokumen', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%")
                          ->orWhereHas('unitKerja', function($q) use ($searchTerm) {
                              $q->where('nama', 'LIKE', "%{$searchTerm}%");
                          });
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('landing.index', compact('publicDokumens'));
    }

    public function searchPublic(Request $request)
    {
        return $this->index();
    }

    public function previewPublicDokumen($id)
    {
        $dokumen = Dokumen::where('is_public', true)->findOrFail($id);
        
        // Logic untuk preview dokumen
        if ($dokumen->is_pdf) {
            return response()->file(storage_path('app/' . $dokumen->file_path));
        }
        
        abort(404);
    }

    public function downloadPublicDokumen($id)
    {
        $dokumen = Dokumen::where('is_public', true)->findOrFail($id);
        
        // Logic untuk download dokumen
        return response()->download(storage_path('app/' . $dokumen->file_path));
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
