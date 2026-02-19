<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iku;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    // ==================== IKU MANAGEMENT ====================
    
    public function indexIku()
    {
        $ikus = Iku::orderBy('kode')->get();
        return view('admin.settings.iku-index', compact('ikus'));
    }

    public function createIku()
    {
        return view('admin.settings.iku-form');
    }

    public function storeIku(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:ikus,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            Iku::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'status' => $request->has('status'),
            ]);

            return redirect()->route('admin.settings.iku.index')
                ->with('success', 'IKU berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan IKU: ' . $e->getMessage());
        }
    }

    public function editIku($id)
    {
        $iku = Iku::findOrFail($id);
        return view('admin.settings.iku-form', compact('iku'));
    }

    public function updateIku(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:ikus,kode,' . $id,
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $iku = Iku::findOrFail($id);
            $iku->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'status' => $request->has('status'),
            ]);

            return redirect()->route('admin.settings.iku.index')
                ->with('success', 'IKU berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui IKU: ' . $e->getMessage());
        }
    }

    public function destroyIku($id)
    {
        try {
            $iku = Iku::findOrFail($id);
            
            // Cek apakah IKU digunakan di dokumen
            $usedInDocuments = DB::table('dokumens')->where('iku_id', $id)->exists();
            
            if ($usedInDocuments) {
                return back()->with('error', 'IKU tidak dapat dihapus karena sudah digunakan dalam dokumen.');
            }
            
            $iku->delete();

            return redirect()->route('admin.settings.iku.index')
                ->with('success', 'IKU berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus IKU: ' . $e->getMessage());
        }
    }

    // ==================== UNIT KERJA MANAGEMENT ====================

    public function indexUnitKerja()
    {
        $unitKerjas = UnitKerja::orderBy('kode')->get();
        return view('admin.settings.unit-kerja-index', compact('unitKerjas'));
    }

    public function createUnitKerja()
    {
        return view('admin.settings.unit-kerja-form');
    }

    public function storeUnitKerja(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:unit_kerjas,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            UnitKerja::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'status' => $request->has('status'),
            ]);

            return redirect()->route('admin.settings.unit-kerja.index')
                ->with('success', 'Unit Kerja berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan Unit Kerja: ' . $e->getMessage());
        }
    }

    public function editUnitKerja($id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        return view('admin.settings.unit-kerja-form', compact('unitKerja'));
    }

    public function updateUnitKerja(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:unit_kerjas,kode,' . $id,
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $unitKerja = UnitKerja::findOrFail($id);
            $unitKerja->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'status' => $request->has('status'),
            ]);

            return redirect()->route('admin.settings.unit-kerja.index')
                ->with('success', 'Unit Kerja berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui Unit Kerja: ' . $e->getMessage());
        }
    }

    public function destroyUnitKerja($id)
    {
        try {
            $unitKerja = UnitKerja::findOrFail($id);
            
            // Cek apakah Unit Kerja digunakan di dokumen
            $usedInDocuments = DB::table('dokumens')->where('unit_kerja_id', $id)->exists();
            
            if ($usedInDocuments) {
                return back()->with('error', 'Unit Kerja tidak dapat dihapus karena sudah digunakan dalam dokumen.');
            }
            
            $unitKerja->delete();

            return redirect()->route('admin.settings.unit-kerja.index')
                ->with('success', 'Unit Kerja berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Unit Kerja: ' . $e->getMessage());
        }
    }
}