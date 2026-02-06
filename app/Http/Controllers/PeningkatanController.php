<?php

namespace App\Http\Controllers;

use App\Models\PeningkatanSPMI;
use App\Models\UnitKerja;
use App\Models\Iku;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeningkatanController extends Controller
{
    // INDEX - LIST DATA
    public function index(Request $request)
    {
        // Tambahkan flash message jika dari redirect
        if (session('from_action')) {
            session()->flash('info', 'Anda berada di halaman daftar program peningkatan SPMI');
        }
        
        $query = PeningkatanSPMI::query();
        
        if ($request->has('search')) {
            $query->where('nama_program', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_peningkatan', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('tipe') && $request->tipe != 'all') {
            $query->where('tipe_peningkatan', $request->tipe);
        }
        
        if ($request->has('tahun') && $request->tahun != 'all') {
            $query->where('tahun', $request->tahun);
        }
        
        $peningkatan = $query->with('unitKerja')->orderBy('created_at', 'desc')->paginate(10);
        
        $data = [
            'peningkatan' => $peningkatan,
            'totalPeningkatan' => PeningkatanSPMI::count(),
            'peningkatanAktif' => PeningkatanSPMI::where('status', 'berjalan')->count(),
            'dokumenValid' => PeningkatanSPMI::where('status_dokumen', 'valid')->count(),
            'dokumenBelumValid' => PeningkatanSPMI::where('status_dokumen', 'belum_valid')->count(),
            'tahunList' => PeningkatanSPMI::select('tahun')->distinct()->orderBy('tahun', 'desc')->get(),
            'unitKerjaList' => UnitKerja::where('status', true)->get(),
            'kelompok' => []
        ];
        
        return view('dashboard.spmi.peningkatan.index', $data);
    }

    // CREATE - FORM TAMBAH
    public function create()
    {
        // Flash message untuk create
        session()->flash('menu_info', [
            'title' => 'Tambah Program Baru',
            'description' => 'Form untuk menambahkan program peningkatan SPMI baru. Isi semua data dengan lengkap.',
            'icon' => 'fas fa-plus-circle',
            'color' => 'success'
        ]);
        
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.peningkatan.create', compact('unitKerjas', 'ikus'));
    }

    // STORE - SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'tipe_peningkatan' => 'required|in:strategis,operasional,perbaikan,pengembangan,inovasi',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'status' => 'required|in:draft,disetujui,berjalan,selesai',
            'deskripsi' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string|max:255',
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'iku_id' => 'nullable|exists:ikus,id',
            'anggaran' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);
        
        try {
            $tahun = $request->tahun;
            $count = PeningkatanSPMI::where('tahun', $tahun)->count() + 1;
            $kode = 'PEN-' . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . $tahun;
            
            $peningkatan = PeningkatanSPMI::create([
                'nama_program' => $request->nama_program,
                'tipe_peningkatan' => $request->tipe_peningkatan,
                'tahun' => $tahun,
                'status' => $request->status,
                'status_dokumen' => 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'kode_peningkatan' => $kode,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'anggaran' => $request->anggaran ?? 0,
                'progress' => $request->progress ?? 0,
            ]);
            
            // Tambahkan session untuk info di halaman index
            session()->flash('from_action', 'create');
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', '✅ Program <strong>' . $request->nama_program . '</strong> berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menyimpan: ' . $e->getMessage())
                         ->withInput();
        }
    }

    // SHOW - DETAIL
    public function show($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::with(['unitKerja', 'iku'])->findOrFail($id);
            
            // Flash message untuk show
            session()->flash('menu_info', [
                'title' => 'Detail Program',
                'description' => 'Menampilkan detail lengkap program ' . $peningkatan->nama_program,
                'icon' => 'fas fa-eye',
                'color' => 'info'
            ]);
            
            return view('dashboard.spmi.peningkatan.show', compact('peningkatan'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.peningkatan.index')
                ->with('error', '❌ Data tidak ditemukan');
        }
    }

    // EDIT - FORM EDIT
    public function edit($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            // Flash message untuk edit
            session()->flash('menu_info', [
                'title' => 'Edit Program',
                'description' => 'Mengedit data program ' . $peningkatan->nama_program . '. Perubahan akan disimpan setelah tombol Update diklik.',
                'icon' => 'fas fa-edit',
                'color' => 'warning'
            ]);
            
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.peningkatan.edit', compact('peningkatan', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.peningkatan.index')
                ->with('error', '❌ Data tidak ditemukan');
        }
    }

    // UPDATE - UPDATE DATA
    public function update(Request $request, $id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            $request->validate([
                'nama_program' => 'required|string|max:255',
                'tipe_peningkatan' => 'required|in:strategis,operasional,perbaikan,pengembangan,inovasi',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:draft,disetujui,berjalan,selesai',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'anggaran' => 'nullable|numeric|min:0',
                'progress' => 'nullable|integer|min:0|max:100',
            ]);
            
            $peningkatan->update([
                'nama_program' => $request->nama_program,
                'tipe_peningkatan' => $request->tipe_peningkatan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'anggaran' => $request->anggaran ?? $peningkatan->anggaran,
                'progress' => $request->progress ?? $peningkatan->progress,
            ]);
            
            // Tambahkan session untuk info di halaman index
            session()->flash('from_action', 'update');
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', '✅ Program <strong>' . $request->nama_program . '</strong> berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal update: ' . $e->getMessage())
                         ->withInput();
        }
    }

    // DESTROY - HAPUS DATA
    public function destroy($id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            $programName = $peningkatan->nama_program;
            $peningkatan->delete();
            
            // Tambahkan session untuk info di halaman index
            session()->flash('from_action', 'delete');
            
            return redirect()->route('spmi.peningkatan.index')
                ->with('success', '🗑️ Program <strong>' . $programName . '</strong> berhasil dihapus!');
                
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menghapus: ' . $e->getMessage());
        }
    }

    // UPLOAD DOKUMEN
    public function uploadDokumen(Request $request, $id)
    {
        try {
            $peningkatan = PeningkatanSPMI::findOrFail($id);
            
            // Flash message untuk upload
            session()->flash('menu_info', [
                'title' => 'Upload Dokumen',
                'description' => 'Upload dokumen untuk program ' . $peningkatan->nama_program,
                'icon' => 'fas fa-upload',
                'color' => 'primary'
            ]);
            
            $request->validate([
                'file_dokumen' => 'required|file|max:10240',
                'keterangan' => 'nullable|string|max:500',
            ]);
            
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('dokumen/peningkatan', $fileName, 'public');
                
                Dokumen::create([
                    'unit_kerja_id' => $peningkatan->unit_kerja_id,
                    'iku_id' => $peningkatan->iku_id,
                    'jenis_dokumen' => 'Peningkatan SPMI',
                    'nama_dokumen' => $peningkatan->nama_program . ' - ' . ($request->keterangan ?? 'Dokumen'),
                    'keterangan' => $request->keterangan,
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'metadata' => json_encode(['peningkatan_id' => $peningkatan->id]),
                ]);
                
                $peningkatan->update(['status_dokumen' => 'valid']);
                
                return back()->with('success', '📄 Dokumen berhasil diupload untuk program ' . $peningkatan->nama_program . '!');
            }
            
            return back()->with('error', '❌ File tidak valid');
            
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal upload: ' . $e->getMessage());
        }
    }
}