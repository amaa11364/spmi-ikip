<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;

class DokumenPublikController extends Controller
{
    public function index(Request $request)
    {
        // Filter hanya dokumen publik
        $query = Dokumen::with(['unitKerja', 'iku', 'uploader'])
            ->where('is_public', true)
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan parameter
        if ($request->has('unit_kerja') && $request->unit_kerja) {
            $query->where('unit_kerja_id', $request->unit_kerja);
        }

        if ($request->has('iku_id') && $request->iku_id) {
            $query->where('iku_id', $request->iku_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dokumen', 'like', "%{$search}%")
                  ->orWhere('jenis_dokumen', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('unitKerja', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('iku', function($q) use ($search) {
                      $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                  });
            });
        }

        $dokumens = $query->paginate(12);
        $unitKerjas = UnitKerja::all();
        $ikus = Iku::all();

        // Deteksi request AJAX - multiple methods
        $isAjaxRequest = $request->ajax() || 
                         $request->wantsJson() || 
                         $request->has('ajax') || 
                         $request->header('X-Requested-With') === 'XMLHttpRequest';

        if ($isAjaxRequest) {
            try {
                $html = view('dokumen-publik.partials.dokumen-list', compact('dokumens'))->render();
                $pagination = view('dokumen-publik.partials.pagination', compact('dokumens'))->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'pagination' => $pagination,
                    'total' => $dokumens->total(),
                    'count' => $dokumens->count(),
                    'current_page' => $dokumens->currentPage(),
                    'last_page' => $dokumens->lastPage()
                ], 200, [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]);
            } catch (\Exception $e) {
                \Log::error('AJAX Response Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'html' => '<tr><td colspan="8"><div class="alert alert-danger">Error loading data</div></td></tr>'
                ], 500);
            }
        }

        // Return view biasa untuk non-AJAX
        return view('dokumen-publik.index', compact('dokumens', 'unitKerjas', 'ikus'));
    }

    public function show($id)
    {
        $dokumen = Dokumen::with(['unitKerja', 'iku', 'uploader'])
            ->where('is_public', true)
            ->findOrFail($id);

        // Get related documents (same unit kerja or same IKU)
        $relatedDocuments = Dokumen::where('is_public', true)
            ->where('id', '!=', $id)
            ->where(function($query) use ($dokumen) {
                if ($dokumen->unit_kerja_id) {
                    $query->where('unit_kerja_id', $dokumen->unit_kerja_id);
                }
                if ($dokumen->iku_id) {
                    $query->orWhere('iku_id', $dokumen->iku_id);
                }
            })
            ->with(['unitKerja', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dokumen-publik.show', compact('dokumen', 'relatedDocuments'));
    }
}