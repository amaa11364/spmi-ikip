<?php

namespace App\Http\Controllers;

use App\Models\Pelaksanaan;
use App\Models\PenetapanSPM;
use Illuminate\Http\Request;

class PelaksanaanController extends Controller
{
    public function generateKodePelaksanaan($tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        $prefix = 'PLK'; // Prefix khusus pelaksanaan
        
        $lastRecord = Pelaksanaan::whereYear('created_at', $tahun)
            ->where('kode_pelaksanaan', 'like', $prefix.'-%/'.$tahun)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lastRecord) {
            preg_match('/'.$prefix.'-(\d+)\/'.$tahun.'/', $lastRecord->kode_pelaksanaan, $matches);
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix.'-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT).'/'.$tahun;
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tipe_pelaksanaan' => 'required|in:rutin,proyek,khusus',
            'tahun' => 'required|digits:4',
            'penanggung_jawab' => 'required|string|max:100',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'penetapan_id' => 'nullable|exists:penetapan_s_p_m_s,id',
            'iku_id' => 'nullable|exists:ikus,id',
        ]);
        
        // Generate kode otomatis
        $validated['kode_pelaksanaan'] = $this->generateKodePelaksanaan($request->tahun);
        $validated['status'] = 'draft';
        $validated['status_dokumen'] = 'belum_valid';
        
        try {
            $pelaksanaan = Pelaksanaan::create($validated);
            
            return redirect()->route('pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil dibuat!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}