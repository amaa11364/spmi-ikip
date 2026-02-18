<?php

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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi dasar
            $request->validate([
                'unit_kerja_id' => 'required|exists:unit_kerjas,id',
                'iku_id' => 'required|exists:ikus,id',
                'jenis_upload' => 'required|in:file,link',
                'nama_dokumen' => 'required|string|max:255',
                'is_public' => 'boolean'
            ]);

            // Validasi conditional berdasarkan jenis upload
            if ($request->jenis_upload === 'file') {
                $request->validate([
                    'file_dokumen' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png'
                ]);
            } else {
                $request->validate([
                    'link_dokumen' => 'required|max:500'
                ]);
            }

            // Handle metadata berdasarkan konteks
            $metadata = [];
            $tahapan = null;
            
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
            
            // Konteks tahapan SPMI lainnya
            if ($request->has('tahapan') && in_array($request->tahapan, ['pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'])) {
                $tahapan = $request->tahapan;
                $metadata['tahapan'] = $tahapan;
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
                    
                    // Generate folder path berdasarkan konteks
                    $folderPath = 'dokumen';
                    if ($tahapan) {
                        $folderPath .= '/' . $tahapan;
                    }
                    if (isset($metadata['tipe_penetapan']) && isset($metadata['tahun'])) {
                        $folderPath .= '/' . $metadata['tipe_penetapan'] . '/' . $metadata['tahun'];
                    }
                    
                    // Generate unique filename
                    $fileName = time() . '_' . Str::random(10) . '.' . $extension;
                    
                    // Store file dengan path yang konsisten
                    $filePath = $file->storeAs($folderPath, $fileName, 'public');
                    
                    // Create dokumen record
                    $dokumen = Dokumen::create([
                        'unit_kerja_id' => $request->unit_kerja_id,
                        'iku_id' => $request->iku_id,
                        'jenis_dokumen' => $request->jenis_dokumen ?? 'File Upload',
                        'nama_dokumen' => $request->nama_dokumen,
                        'keterangan' => $request->keterangan,
                        'file_path' => $filePath,
                        'file_name' => $originalName,
                        'file_size' => $file->getSize(),
                        'file_extension' => $extension,
                        'jenis_upload' => 'file',
                        'uploaded_by' => auth()->id(),
                        'is_public' => $request->is_public ?? false,
                        'tahapan' => $tahapan,
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
                            ->with('success', 'Dokumen berhasil diupload!');
                    }
                } else {
                    return back()->with('error', 'File tidak valid atau gagal diupload.');
                }
            } else {
                // Upload link - tambahkan http:// jika tidak ada
                $link = $request->link_dokumen;
                if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
                    $link = "http://" . $link;
                }

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
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'uploader', 'iku'])
                       ->where('uploaded_by', Auth::id());

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        if ($request->has('unit_kerja') && $request->unit_kerja != '') {
            $query->where('unit_kerja_id', $request->unit_kerja);
        }

        if ($request->has('iku_id') && $request->iku_id != '') {
            $query->where('iku_id', $request->iku_id);
        }
        
        if ($request->has('tahapan') && $request->tahapan != '') {
            $query->where('tahapan', $request->tahapan);
        }

        $dokumens = $query->orderBy('created_at', 'desc')->get();
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();

        $isAdmin = true;

        return view('user.dokumen-saya', compact('dokumens', 'unitKerjas', 'ikus', 'isAdmin'));
    }

    // Hapus dokumen
    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::where('uploaded_by', Auth::id())->findOrFail($id);
            
            // Hanya hapus file fisik jika jenis upload adalah file
            if ($dokumen->jenis_upload === 'file' && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            // Hapus dari database
            $dokumen->delete();

            return redirect()->route('user.dokumen-saya.index')->with('success', 'Dokumen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('user.dokumen-saya.index')->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    // Download dokumen
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

    // Preview dokumen
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

            // Hanya preview untuk PDF
            if ($dokumen->file_extension !== 'pdf') {
                return back()->with('info', 'Preview hanya tersedia untuk file PDF.');
            }

            $filePath = Storage::disk('public')->path($dokumen->file_path);
            
            return response()->file($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mempreview dokumen: ' . $e->getMessage());
        }
    }
}