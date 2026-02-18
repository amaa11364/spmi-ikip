<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use App\Models\HeroContent;
use App\Models\Berita;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        $searchTerm = request('q');
        $publicDokumens = collect();
        
        // Search functionality
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

        // Get data for landing page components
        $heroContent = HeroContent::active()->first();
        
        // Get published news - limit 4 for landing page
        $beritas = Berita::where('is_published', true)
            ->with('user')
            ->latest()
            ->limit(4)
            ->get();
        
        // Get upcoming active jadwal - limit 5 for landing page
        $jadwals = Jadwal::where('is_active', true)
            ->where('status', '!=', 'dibatalkan')
            ->where('status', '!=', 'selesai')
            ->where('tanggal', '>=', now()->startOfDay())
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->limit(5)
            ->get();

        return view('landing.index', compact(
            'publicDokumens', 
            'searchTerm',
            'heroContent',
            'beritas',
            'jadwals'
        ));
    }

    /**
     * Display list of all berita
     */
    public function beritaIndex()
    {
        $beritas = Berita::where('is_published', true)
            ->with('user')
            ->latest()
            ->paginate(12);

        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Display single berita detail
     */
    public function beritaShow($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->with('user')
            ->firstOrFail();
        
        // Increment views
        $berita->increment('views');
        
        // Get related berita (excluding current)
        $beritaLainnya = Berita::where('is_published', true)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->limit(3)
            ->get();
        
        return view('landing.berita.show', compact('berita', 'beritaLainnya'));
    }

    /**
     * Search public dokumen (alias for index with search)
     */
    public function searchPublic(Request $request)
    {
        return $this->index();
    }

    /**
     * Preview public dokumen
     */
    public function previewPublicDokumen($id)
    {
        try {
            $dokumen = Dokumen::public()->findOrFail($id);
            
            // Check if file exists
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                Log::error('File not found for preview', [
                    'dokumen_id' => $id,
                    'file_path' => $dokumen->file_path
                ]);
                abort(404, 'File tidak ditemukan di storage');
            }
            
            // Get full path
            $fullPath = Storage::disk('public')->path($dokumen->file_path);
            
            // For PDF, show preview
            if ($dokumen->is_pdf) {
                return response()->file($fullPath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $dokumen->file_name . '"'
                ]);
            }
            
            // For images, show preview
            if ($dokumen->is_image) {
                return response()->file($fullPath, [
                    'Content-Type' => $dokumen->mime_type,
                    'Content-Disposition' => 'inline; filename="' . $dokumen->file_name . '"'
                ]);
            }
            
            // For other files, redirect to download
            return redirect()->route('landing.download-dokumen', $id);
            
        } catch (\Exception $e) {
            Log::error('Error previewing document', [
                'error' => $e->getMessage(),
                'dokumen_id' => $id
            ]);
            abort(500, 'Terjadi kesalahan saat membuka file');
        }
    }

    /**
     * Download public dokumen
     */
    public function downloadPublicDokumen($id)
    {
        try {
            $dokumen = Dokumen::public()->findOrFail($id);
            
            Log::info('Download attempt', [
                'dokumen_id' => $id,
                'file_path' => $dokumen->file_path,
                'disk' => 'public'
            ]);
            
            // Check if file exists in public disk
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                Log::error('File not found', [
                    'dokumen_id' => $id,
                    'file_path' => $dokumen->file_path
                ]);
                
                // Try to find file in alternative locations
                $alternativePaths = [
                    $dokumen->file_path,
                    'dokumen/' . basename($dokumen->file_path),
                    'uploads/' . basename($dokumen->file_path)
                ];
                
                $found = false;
                foreach ($alternativePaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        $dokumen->file_path = $path;
                        $found = true;
                        Log::info('Found file at alternative path', ['path' => $path]);
                        break;
                    }
                }
                
                if (!$found) {
                    abort(404, 'File tidak ditemukan. Silakan hubungi administrator.');
                }
            }
            
            // Download the file
            return Storage::disk('public')->download(
                $dokumen->file_path, 
                $dokumen->file_name,
                [
                    'Content-Type' => $dokumen->mime_type ?? 'application/octet-stream',
                ]
            );
            
        } catch (\Exception $e) {
            Log::error('Error downloading document', [
                'error' => $e->getMessage(),
                'dokumen_id' => $id
            ]);
            abort(500, 'Terjadi kesalahan saat mengunduh file');
        }
    }

    /**
     * Get upcoming jadwal for API/AJAX
     */
    public function getUpcomingJadwal()
    {
        $jadwals = Jadwal::where('is_active', true)
            ->where('status', '!=', 'dibatalkan')
            ->where('status', '!=', 'selesai')
            ->where('tanggal', '>=', now()->startOfDay())
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->get()
            ->map(function($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'kegiatan' => $jadwal->kegiatan,
                    'tanggal' => $jadwal->tanggal->format('d/m/Y'),
                    'tanggal_full' => $jadwal->tanggal->translatedFormat('l, d F Y'),
                    'hari' => $jadwal->hari,
                    'waktu' => $jadwal->waktu_formatted,
                    'tempat' => $jadwal->tempat,
                    'penanggung_jawab' => $jadwal->penanggung_jawab,
                    'deskripsi' => $jadwal->deskripsi,
                    'status' => $jadwal->status_label,
                    'status_class' => $jadwal->status_class,
                    'warna' => $jadwal->warna
                ];
            });

        return response()->json($jadwals);
    }

    /**
     * About page
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Features page
     */
    public function features()
    {
        return view('landing.features');
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return view('landing.contact');
    }
}