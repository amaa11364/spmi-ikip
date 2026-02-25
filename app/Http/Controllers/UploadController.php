<?php
// app/Http/Controllers/UploadController.php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use App\Models\PenetapanSPM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Show the upload form
     */
    public function create()
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('user.upload-dokumen', compact('unitKerjas', 'ikus'));
    }

    /**
     * Show the form for creating with context.
     */
    public function createWithContext(Request $request, $context = null, $id = null)
    {
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        $data = compact('unitKerjas', 'ikus');
        
        // Set konteks berdasarkan parameter
        switch ($context) {
            case 'spmi-penetapan':
                if ($id) {
                    $penetapan = PenetapanSPM::find($id);
                    $data['context'] = 'spmi-penetapan';
                    $data['penetapanId'] = $id;
                    $data['komponenNama'] = $penetapan->nama_komponen ?? '';
                    $data['tahun'] = $penetapan->tahun ?? date('Y');
                    $data['tipePenetapan'] = $penetapan->tipe_penetapan ?? '';
                }
                break;
                
            case 'spmi-pelaksanaan':
                $data['context'] = 'spmi-pelaksanaan';
                break;
                
            case 'spmi-evaluasi':
                $data['context'] = 'spmi-evaluasi';
                break;
                
            case 'spmi-pengendalian':
                $data['context'] = 'spmi-pengendalian';
                break;
                
            case 'spmi-peningkatan':
                $data['context'] = 'spmi-peningkatan';
                break;
                
            default:
                $data['context'] = 'general';
                break;
        }
        
        return view('upload-dokumen', $data);
    }

    /**
     * Store uploaded document
     */
    public function store(Request $request)
    {
        try {
            // Validasi dasar
            $request->validate([
                'tahapan' => 'nullable|in:penetapan,pelaksanaan,evaluasi,pengendalian,peningkatan',
                'unit_kerja_id' => 'required|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
                'jenis_upload' => 'required|in:file,link',
                'nama_dokumen' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:500',
                'is_public' => 'boolean'
            ]);

            // Validasi conditional berdasarkan jenis upload
            if ($request->jenis_upload === 'file') {
                $request->validate([
                    'file_dokumen' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png'
                ]);
            } else {
                $request->validate([
                    'link_dokumen' => 'required|url|max:500'
                ]);
            }

            // Validasi dinamis berdasarkan tahapan
            if ($request->tahapan) {
                $dynamicRules = $this->getDynamicValidationRules($request->tahapan);
                $request->validate($dynamicRules);
            }

            // Handle metadata berdasarkan konteks
            $metadata = [];
            $tahapan = $request->tahapan;
            
            // Konteks SPMI Penetapan
            if ($request->has('penetapan_id')) {
                $penetapan = PenetapanSPM::find($request->penetapan_id);
                if ($penetapan) {
                    $metadata = [
                        'penetapan_id' => $penetapan->id,
                        'nama_komponen' => $penetapan->nama_komponen,
                        'tipe_penetapan' => $penetapan->tipe_penetapan,
                        'tahun' => $penetapan->tahun,
                        'kode_penetapan' => $penetapan->kode_penetapan,
                    ];
                    $tahapan = 'penetapan';
                }
            }
            
            // Kumpulkan metadata dari field dinamis
            if ($request->tahapan) {
                $metadata = array_merge($metadata, $this->collectMetadata($request->tahapan, $request));
            }
            
            // Tambahkan metadata lainnya jika ada
            if ($request->has('metadata')) {
                $metadata = array_merge($metadata, $request->metadata);
            }

            // Handle berdasarkan jenis upload
            if ($request->jenis_upload === 'file') {
                // Upload file
                if ($request->hasFile('file_dokumen') && $request->file('file_dokumen')->isValid()) {
                    $file = $request->file('file_dokumen');
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    
                    // Generate folder path
                    $folderPath = 'dokumen';
                    if ($tahapan) {
                        $folderPath .= '/' . $tahapan;
                    }
                    if (isset($metadata['tipe_penetapan']) && isset($metadata['tahun'])) {
                        $folderPath .= '/' . $metadata['tipe_penetapan'] . '/' . $metadata['tahun'];
                    } else {
                        $folderPath .= '/' . date('Y');
                    }
                    
                    // Generate unique filename
                    $fileName = time() . '_' . Str::random(10) . '.' . $extension;
                    
                    // Store file
                    $filePath = $file->storeAs($folderPath, $fileName, 'public');
                    
                    // Create dokumen record
                    $dokumen = Dokumen::create([
                        'unit_kerja_id' => $request->unit_kerja_id,
                        'iku_id' => $request->iku_id,
                        'jenis_dokumen' => $request->jenis_dokumen ?? ($tahapan ? ucfirst($tahapan) . ' SPMI' : 'File Upload'),
                        'nama_dokumen' => $request->nama_dokumen,
                        'keterangan' => $request->keterangan ?? ($tahapan ? 'Dokumen ' . $tahapan . ' SPMI' : null),
                        'file_path' => $filePath,
                        'file_name' => $originalName,
                        'file_size' => $file->getSize(),
                        'file_extension' => $extension,
                        'jenis_upload' => 'file',
                        'uploaded_by' => auth()->id(),
                        'is_public' => $request->is_public ?? false,
                        'tahapan' => $tahapan,
                        'status' => 'pending',
                        'metadata' => !empty($metadata) ? json_encode($metadata) : null
                    ]);

                    // Jika ada penetapan_id, update status dokumen penetapan
                    if (isset($metadata['penetapan_id'])) {
                        $penetapan = PenetapanSPM::find($metadata['penetapan_id']);
                        if ($penetapan) {
                            $penetapan->update([
                                'status_dokumen' => 'valid',
                                'tanggal_penetapan' => now(),
                            ]);
                        }
                    }

                    // Redirect berdasarkan konteks
                    if (isset($metadata['penetapan_id'])) {
                        return redirect()->route('spmi.penetapan.show', $metadata['penetapan_id'])
                            ->with('success', 'Dokumen berhasil diupload ke repository penetapan!');
                    } else {
                        return redirect()->route('user.dokumen-saya.index')
                            ->with('success', 'Dokumen berhasil diupload dan menunggu verifikasi!');
                    }
                } else {
                    return back()->with('error', 'File tidak valid atau gagal diupload.')->withInput();
                }
            } else {
                // Upload link
                $link = $request->link_dokumen;

                // Create dokumen record untuk link
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $request->unit_kerja_id,
                    'iku_id' => $request->iku_id,
                    'jenis_dokumen' => 'Link External',
                    'nama_dokumen' => $request->nama_dokumen,
                    'keterangan' => $request->keterangan,
                    'file_path' => $link,
                    'file_name' => 'Link External',
                    'file_size' => 0,
                    'file_extension' => 'link',
                    'jenis_upload' => 'link',
                    'uploaded_by' => auth()->id(),
                    'is_public' => $request->is_public ?? false,
                    'tahapan' => $tahapan,
                    'status' => 'pending',
                    'metadata' => !empty($metadata) ? json_encode($metadata) : null
                ]);

                // Redirect berdasarkan konteks
                if (isset($metadata['penetapan_id'])) {
                    return redirect()->route('spmi.penetapan.show', $metadata['penetapan_id'])
                        ->with('success', 'Link dokumen berhasil ditambahkan ke repository penetapan!');
                } else {
                    return redirect()->route('user.dokumen-saya.index')
                        ->with('success', 'Link dokumen berhasil disimpan!');
                }
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Get dynamic validation rules based on tahapan
     */
    private function getDynamicValidationRules($tahapan)
    {
        $rules = [];
        
        switch ($tahapan) {
            case 'penetapan':
                $rules['kode_penetapan'] = 'required|string|max:50';
                $rules['tahun_penetapan'] = 'required|integer|min:2000|max:' . (date('Y') + 5);
                $rules['status_penetapan'] = 'required|in:aktif,revisi,kadaluarsa';
                break;
                
            case 'pelaksanaan':
                $rules['keterangan_pelaksanaan'] = 'required|string|max:500';
                break;
                
            case 'evaluasi':
                $rules['periode_evaluasi'] = 'required|string|max:100';
                $rules['hasil_evaluasi'] = 'required|string|max:2000';
                break;
                
            case 'pengendalian':
                $rules['sumber_temuan'] = 'required|string|max:255';
                $rules['prioritas'] = 'required|in:tinggi,sedang,rendah';
                $rules['target_selesai'] = 'required|date|after:today';
                break;
                
            case 'peningkatan':
                $rules['program_peningkatan'] = 'required|string|max:255';
                $rules['anggaran'] = 'required|numeric|min:0';
                $rules['jenis_peningkatan'] = 'required|in:strategis,operasional,perbaikan';
                break;
        }
        
        return $rules;
    }

    /**
     * Collect metadata from dynamic fields
     */
    private function collectMetadata($tahapan, Request $request)
    {
        $metadata = [];
        
        switch ($tahapan) {
            case 'penetapan':
                $metadata['kode_penetapan'] = $request->kode_penetapan;
                $metadata['tahun_penetapan'] = $request->tahun_penetapan;
                $metadata['status_penetapan'] = $request->status_penetapan;
                break;
                
            case 'pelaksanaan':
                $metadata['keterangan_dokumen'] = $request->keterangan_pelaksanaan;
                break;
                
            case 'evaluasi':
                $metadata['periode_evaluasi'] = $request->periode_evaluasi;
                $metadata['hasil_evaluasi'] = $request->hasil_evaluasi;
                break;
                
            case 'pengendalian':
                $metadata['sumber_temuan'] = $request->sumber_temuan;
                $metadata['prioritas'] = $request->prioritas;
                $metadata['target_selesai'] = $request->target_selesai;
                break;
                
            case 'peningkatan':
                $metadata['program_peningkatan'] = $request->program_peningkatan;
                $metadata['anggaran'] = $request->anggaran;
                $metadata['jenis_peningkatan'] = $request->jenis_peningkatan;
                break;
        }
        
        return $metadata;
    }

    /**
     * Index user's documents - DIPERBAIKI
     */
    public function index(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'uploader', 'iku'])
                       ->where('uploaded_by', Auth::id());

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan unit kerja
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja_id', $request->unit_kerja);
        }

        // Filter berdasarkan IKU
        if ($request->filled('iku_id')) {
            $query->where('iku_id', $request->iku_id);
        }

        // Filter berdasarkan tahapan
        if ($request->filled('tahapan')) {
            $query->where('tahapan', $request->tahapan);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Hitung statistik
        $statistics = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];

        // Pagination - INI YANG DIPERBAIKI
        $dokumens = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();

        return view('user.dokumen-saya', compact('dokumens', 'statistics', 'unitKerjas', 'ikus'));
    }

    /**
     * Download document
     */
    public function download($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            // Jika berupa link, redirect ke link tersebut
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Preview document
     */
    public function preview($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            // Jika berupa link, redirect ke link tersebut
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            if ($dokumen->file_extension !== 'pdf') {
                return back()->with('info', 'Preview hanya tersedia untuk file PDF.');
            }

            $filePath = Storage::disk('public')->path($dokumen->file_path);
            
            return response()->file($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mempreview dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Delete document
     */
    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            if ($dokumen->jenis_upload === 'file' && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            $dokumen->delete();

            return redirect()->route('user.dokumen-saya.index')
                ->with('success', 'Dokumen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('user.dokumen-saya.index')
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
}