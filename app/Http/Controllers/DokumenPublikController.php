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
        // Pastikan tidak ada redirect di sini
        $query = Dokumen::with(['unitKerja', 'iku', 'uploader'])
            ->orderBy('created_at', 'desc');

        // Filter
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
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $dokumens = $query->paginate(12);
        $unitKerjas = UnitKerja::all();
        $ikus = Iku::all();

        // Langsung return view, tidak ada redirect
        return view('dokumen-publik.index', compact('dokumens', 'unitKerjas', 'ikus'));
    }

    public function show($id)
    {
        $dokumen = Dokumen::with(['unitKerja', 'iku', 'uploader'])
            ->findOrFail($id);

        // Langsung return view, tidak ada redirect
        return view('dokumen-publik.show', compact('dokumen'));
    }
}