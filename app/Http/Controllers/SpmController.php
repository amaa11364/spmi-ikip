<?php

namespace App\Http\Controllers;

use App\Models\PenetapanSPM;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SpmController extends Controller
{
    // ==================== PENETAPAN SPMI (CRUD LENGKAP) ====================
    
    /**
     * Display a listing of the resource.
     */
    public function indexPenetapan(Request $request)
    {
        // Query dengan filter
        $query = PenetapanSPM::query();
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        
        // Filter tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe_penetapan', $request->tipe);
        }
        
        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }
        
        $penetapan = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Data untuk filter dropdown
        $tahunList = PenetapanSPM::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        
        // Kelompokkan untuk tabs
        $kelompok = [
            'pengelolaan' => PenetapanSPM::where('tipe_penetapan', 'pengelolaan')->orderBy('created_at', 'desc')->get(),
            'organisasi' => PenetapanSPM::where('tipe_penetapan', 'organisasi')->orderBy('created_at', 'desc')->get(),
            'pelaksanaan' => PenetapanSPM::where('tipe_penetapan', 'pelaksanaan')->orderBy('created_at', 'desc')->get(),
            'evaluasi' => PenetapanSPM::where('tipe_penetapan', 'evaluasi')->orderBy('created_at', 'desc')->get(),
            'pengendalian' => PenetapanSPM::where('tipe_penetapan', 'pengendalian')->orderBy('created_at', 'desc')->get(),
            'peningkatan' => PenetapanSPM::where('tipe_penetapan', 'peningkatan')->orderBy('created_at', 'desc')->get(),
        ];
        
        return view('dashboard.spmi.penetapan.index', compact('penetapan', 'kelompok', 'tahunList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createPenetapan()
    {
        // Data untuk dropdown
        $unitKerjas = UnitKerja::where('status', true)->get();
        $ikus = Iku::where('status', true)->get();
        
        return view('dashboard.spmi.penetapan.create', compact('unitKerjas', 'ikus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storePenetapan(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'nama_komponen' => 'required|string|max:255',
                'tipe_penetapan' => 'required|in:pengelolaan,organisasi,pelaksanaan,evaluasi,pengendalian,peningkatan',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,revisi',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Generate kode otomatis
            $tipe = $request->tipe_penetapan;
            $tahun = $request->tahun;
            $count = PenetapanSPM::where('tipe_penetapan', $tipe)->where('tahun', $tahun)->count() + 1;
            $kode = strtoupper(substr($tipe, 0, 3)) . '-' . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . $tahun;
            
            // Create penetapan
            $penetapan = PenetapanSPM::create([
                'nama_komponen' => $request->nama_komponen,
                'tipe_penetapan' => $tipe,
                'tahun' => $tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? 'belum_valid',
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'kode_penetapan' => $kode,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
            ]);
            
            // Jika ada file langsung diupload
            if ($request->hasFile('file_dokumen')) {
                return $this->uploadDokumen($request, $penetapan->id);
            }
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function showPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::with(['dokumen', 'unitKerja', 'iku'])->findOrFail($id);
            
            return view('dashboard.spmi.penetapan.show', compact('penetapan'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.penetapan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            $unitKerjas = UnitKerja::where('status', true)->get();
            $ikus = Iku::where('status', true)->get();
            
            return view('dashboard.spmi.penetapan.edit', compact('penetapan', 'unitKerjas', 'ikus'));
            
        } catch (\Exception $e) {
            return redirect()->route('spmi.penetapan.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePenetapan(Request $request, $id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            // Validasi
            $request->validate([
                'nama_komponen' => 'required|string|max:255',
                'tipe_penetapan' => 'required|in:pengelolaan,organisasi,pelaksanaan,evaluasi,pengendalian,peningkatan',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'status' => 'required|in:aktif,nonaktif,revisi',
                'status_dokumen' => 'in:valid,belum_valid,dalam_review',
                'deskripsi' => 'nullable|string',
                'penanggung_jawab' => 'nullable|string|max:255',
                'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
                'iku_id' => 'nullable|exists:ikus,id',
            ]);
            
            // Update data
            $penetapan->update([
                'nama_komponen' => $request->nama_komponen,
                'tipe_penetapan' => $request->tipe_penetapan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'status_dokumen' => $request->status_dokumen ?? $penetapan->status_dokumen,
                'deskripsi' => $request->deskripsi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'unit_kerja_id' => $request->unit_kerja_id,
                'iku_id' => $request->iku_id,
                'tanggal_review' => now(),
            ]);
            
            // Jika ada file baru
            if ($request->hasFile('file_dokumen')) {
                return $this->uploadDokumen($request, $penetapan->id);
            }
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            // Hapus file dokumen terkait jika ada
            if ($penetapan->dokumen_id) {
                $dokumen = Dokumen::find($penetapan->dokumen_id);
                if ($dokumen && $dokumen->jenis_upload === 'file' && Storage::disk('public')->exists($dokumen->file_path)) {
                    Storage::disk('public')->delete($dokumen->file_path);
                    $dokumen->delete();
                }
            }
            
            // Soft delete penetapan
            $penetapan->delete();
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Restore soft deleted resource.
     */
    public function restorePenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::withTrashed()->findOrFail($id);
            $penetapan->restore();
            
            return redirect()->route('spmi.penetapan.index')
                ->with('success', 'Data penetapan berhasil dipulihkan.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen untuk penetapan.
     */
    public function uploadDokumenPenetapan(Request $request, $id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            // Validasi file
            $request->validate([
                'file_dokumen' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'keterangan' => 'nullable|string|max:500',
            ]);
            
            // Upload file
            if ($request->hasFile('file_dokumen') && $request->file('file_dokumen')->isValid()) {
                $file = $request->file('file_dokumen');
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                // Generate unique filename
                $fileName = 'penetapan_' . $penetapan->id . '_' . time() . '_' . Str::random(10) . '.' . $extension;
                
                // Store file
                $filePath = $file->storeAs('dokumen/penetapan-spmi', $fileName, 'public');
                
                // Unit kerja default (LPM) jika tidak ada
                $unitKerjaId = $penetapan->unit_kerja_id ?? 1; // ID LPM
                $ikuId = $penetapan->iku_id ?? 1; // ID IKU SPMI
                
                // Create dokumen record
                $dokumen = Dokumen::create([
                    'unit_kerja_id' => $unitKerjaId,
                    'iku_id' => $ikuId,
                    'jenis_dokumen' => 'Penetapan SPMI',
                    'nama_dokumen' => 'Penetapan SPMI - ' . $penetapan->nama_komponen . ' (' . $penetapan->tahun . ')',
                    'keterangan' => $request->keterangan ?? 'Dokumen penetapan SPMI',
                    'file_path' => $filePath,
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'file_extension' => $extension,
                    'jenis_upload' => 'file',
                    'uploaded_by' => auth()->id(),
                    'is_public' => true,
                    'tahapan' => 'penetapan',
                    'metadata' => json_encode([
                        'penetapan_id' => $penetapan->id,
                        'nama_komponen' => $penetapan->nama_komponen,
                        'tipe_penetapan' => $penetapan->tipe_penetapan,
                        'tahun' => $penetapan->tahun,
                        'penanggung_jawab' => $penetapan->penanggung_jawab,
                    ])
                ]);
                
                // Update penetapan dengan dokumen_id
                $penetapan->update([
                    'dokumen_id' => $dokumen->id,
                    'status_dokumen' => 'valid',
                    'tanggal_penetapan' => now(),
                    'file_path' => $filePath,
                ]);
                
                return redirect()->route('spmi.penetapan.show', $penetapan->id)
                    ->with('success', 'Dokumen berhasil diupload dan terkait dengan penetapan.');
            }
            
            return back()->with('error', 'File tidak valid.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumen penetapan.
     */
    public function downloadDokumenPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::with('dokumen')->findOrFail($id);
            
            if (!$penetapan->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $penetapan->dokumen;
            
            // Jika berupa link
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            // Jika file
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }
            
            return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Preview dokumen.
     */
    public function previewDokumenPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::with('dokumen')->findOrFail($id);
            
            if (!$penetapan->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $penetapan->dokumen;
            
            // Jika link
            if ($dokumen->jenis_upload === 'link') {
                return redirect()->away($dokumen->file_path);
            }
            
            // Jika file
            if (!Storage::disk('public')->exists($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }
            
            // Hanya preview PDF
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
     * Hapus dokumen dari penetapan.
     */
    public function hapusDokumenPenetapan($id)
    {
        try {
            $penetapan = PenetapanSPM::with('dokumen')->findOrFail($id);
            
            if (!$penetapan->dokumen) {
                return back()->with('error', 'Dokumen tidak ditemukan.');
            }
            
            $dokumen = $penetapan->dokumen;
            
            // Hapus file fisik jika ada
            if ($dokumen->jenis_upload === 'file' && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            // Hapus dari database
            $dokumen->delete();
            
            // Update penetapan
            $penetapan->update([
                'dokumen_id' => null,
                'status_dokumen' => 'belum_valid',
                'file_path' => null,
            ]);
            
            return redirect()->route('spmi.penetapan.show', $penetapan->id)
                ->with('success', 'Dokumen berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Update status dokumen.
     */
    public function updateStatusDokumen(Request $request, $id)
    {
        try {
            $penetapan = PenetapanSPM::findOrFail($id);
            
            $request->validate([
                'status_dokumen' => 'required|in:valid,belum_valid,dalam_review',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            $penetapan->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan_verifikasi' => $request->catatan,
                'tanggal_review' => now(),
                'diperiksa_oleh' => auth()->user()->name ?? 'System',
            ]);
            
            return redirect()->route('spmi.penetapan.show', $penetapan->id)
                ->with('success', 'Status dokumen berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    // ==================== METODE BANTUAN PRIVAT ====================
    
    /**
     * Helper untuk upload dokumen.
     */
    private function uploadDokumen(Request $request, $penetapanId)
    {
        $uploadRequest = new Request([
            'file_dokumen' => $request->file('file_dokumen'),
            'keterangan' => $request->keterangan ?? 'Upload dari form penetapan',
        ]);
        
        return $this->uploadDokumenPenetapan($uploadRequest, $penetapanId);
    }

    // ==================== PELAKSANAAN ====================
    public function indexPelaksanaan()
    {
        $implementations = [
            [
                'id' => 1, 
                'kegiatan' => 'Implementasi Standar 1 - Mutu Pembelajaran',
                'deskripsi' => 'Penerapan standar mutu pembelajaran di semua prodi',
                'unit_kerja' => 'LPM & Prodi',
                'status' => 'berjalan',
                'progress' => 75,
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai' => '2024-06-30'
            ],
            [
                'id' => 2, 
                'kegiatan' => 'Integrasi SIAKAD dengan SPMI',
                'deskripsi' => 'Integrasi sistem informasi akademik dengan sistem penjaminan mutu',
                'unit_kerja' => 'TIK & LPM',
                'status' => 'selesai',
                'progress' => 100,
                'tanggal_mulai' => '2024-01-10',
                'tanggal_selesai' => '2024-03-15'
            ],
        ];
        
        $integrations = [
            'siakad' => [
                'status' => 'Terintegrasi',
                'last_sync' => '2 hari lalu',
                'features' => ['KRS', 'Nilai', 'Transkrip', 'Jadwal']
            ],
            'pddikti' => [
                'status' => 'Terintegrasi', 
                'last_sync' => '1 hari lalu',
                'features' => ['Data Dosen', 'Data Mahasiswa', 'Kurikulum', 'Lulusan']
            ]
        ];
        
        return view('dashboard.spmi.pelaksanaan.index', compact('implementations', 'integrations'));
    }

    // ==================== EVALUASI ====================
    public function indexEvaluasi()
    {
        $evaluations = [
            'ami' => [
                [
                    'id' => 1, 
                    'tahun' => '2024', 
                    'auditor' => 'Tim Auditor A',
                    'unit_audit' => 'Prodi Ilmu Pendidikan',
                    'hasil' => 'Baik',
                    'rekomendasi' => 3,
                    'status' => 'Selesai'
                ],
                [
                    'id' => 2, 
                    'tahun' => '2023', 
                    'auditor' => 'Tim Auditor B',
                    'unit_audit' => 'Prodi Pendidikan Bahasa',
                    'hasil' => 'Cukup',
                    'rekomendasi' => 5,
                    'status' => 'Selesai'
                ],
            ],
            'edom' => [
                [
                    'id' => 3, 
                    'semester' => 'Ganjil 2024', 
                    'mata_kuliah' => 'Pengembangan Kurikulum',
                    'dosen' => 'Dr. Ahmad, M.Pd.',
                    'rata_rata' => '4.5',
                    'responden' => 45
                ],
                [
                    'id' => 4, 
                    'semester' => 'Genap 2023', 
                    'mata_kuliah' => 'Metodologi Penelitian',
                    'dosen' => 'Dr. Siti, M.Si.',
                    'rata_rata' => '4.2',
                    'responden' => 38
                ],
            ]
        ];
        
        return view('dashboard.spmi.evaluasi.index', compact('evaluations'));
    }

    public function showEvaluasi($type)
    {
        $typeNames = [
            'ami' => 'Audit Mutu Internal',
            'edom' => 'Evaluasi Dosen oleh Mahasiswa',
            'evaluasi_layanan' => 'Evaluasi Layanan',
            'evaluasi_kinerja' => 'Evaluasi Kinerja'
        ];
        
        $typeName = $typeNames[$type] ?? 'Evaluasi';
        
        // Data contoh berdasarkan tipe
        $data = [];
        if ($type == 'ami') {
            $data = [
                ['tahun' => '2024', 'auditor' => 'Tim A', 'unit' => 'Prodi IP', 'temuan' => 3, 'status' => 'Selesai'],
                ['tahun' => '2023', 'auditor' => 'Tim B', 'unit' => 'Prodi PBI', 'temuan' => 5, 'status' => 'Selesai'],
            ];
        } elseif ($type == 'edom') {
            $data = [
                ['semester' => 'Ganjil 2024', 'matkul' => 'Pengembangan Kurikulum', 'dosen' => 'Dr. Ahmad', 'nilai' => '4.5', 'responden' => 45],
                ['semester' => 'Genap 2023', 'matkul' => 'Metodologi Penelitian', 'dosen' => 'Dr. Siti', 'nilai' => '4.2', 'responden' => 38],
            ];
        }
        
        return view('dashboard.spmi.evaluasi.show', compact('type', 'typeName', 'data'));
    }

    // ==================== PENGENDALIAN ====================
    public function indexPengendalian()
    {
        $controls = [
            [
                'id' => 1, 
                'tindakan' => 'Perbaikan Kurikulum Prodi Ilmu Pendidikan', 
                'deskripsi' => 'Revisi kurikulum berdasarkan hasil evaluasi',
                'status' => 'Selesai', 
                'deadline' => '2024-03-15',
                'penanggung_jawab' => 'Ketua Prodi',
                'progress' => 100
            ],
            [
                'id' => 2, 
                'tindakan' => 'Pelatihan Dosen tentang Pembelajaran Aktif', 
                'deskripsi' => 'Workshop peningkatan kompetensi dosen',
                'status' => 'Berjalan', 
                'deadline' => '2024-04-30',
                'penanggung_jawab' => 'LPM',
                'progress' => 60
            ],
            [
                'id' => 3, 
                'tindakan' => 'Penambahan Fasilitas Laboratorium', 
                'deskripsi' => 'Pengadaan alat laboratorium baru',
                'status' => 'Tertunda', 
                'deadline' => '2024-05-15',
                'penanggung_jawab' => 'Bagian Umum',
                'progress' => 20
            ],
        ];
        
        $statusSummary = [
            'selesai' => 1,
            'berjalan' => 1,
            'tertunda' => 1,
        ];
        
        return view('dashboard.spmi.pengendalian.index', compact('controls', 'statusSummary'));
    }

    // ==================== PENINGKATAN ====================
    public function indexPeningkatan()
    {
        $improvements = [
            [
                'id' => 1, 
                'program' => 'Peningkatan Kualitas Pembelajaran Daring', 
                'deskripsi' => 'Pengembangan platform e-learning dan pelatihan dosen',
                'status' => 'Berjalan',
                'tahun' => '2024',
                'anggaran' => 'Rp 150.000.000',
                'penanggung_jawab' => 'LPM & TIK'
            ],
            [
                'id' => 2, 
                'program' => 'Digitalisasi Layanan Administrasi', 
                'deskripsi' => 'Transformasi layanan administrasi ke digital',
                'status' => 'Selesai',
                'tahun' => '2023',
                'anggaran' => 'Rp 200.000.000',
                'penanggung_jawab' => 'TIK & Bagian Umum'
            ],
            [
                'id' => 3, 
                'program' => 'Pengembangan Pusat Karir Mahasiswa', 
                'deskripsi' => 'Pembuatan pusat karir dan inkubasi bisnis',
                'status' => 'Berjalan',
                'tahun' => '2024',
                'anggaran' => 'Rp 100.000.000',
                'penanggung_jawab' => 'BAAK & LPM'
            ],
        ];
        
        return view('dashboard.spmi.peningkatan.index', compact('improvements'));
    }

    // ==================== AKREDITASI ====================
    public function indexAkreditasi()
    {
        $akreditasi = [
            'prodi' => [
                [
                    'id' => 1,
                    'nama' => 'Ilmu Pendidikan', 
                    'jenjang' => 'S1',
                    'akreditasi' => 'A', 
                    'masa_berlaku' => '2027',
                    'badan_akreditasi' => 'BAN-PT',
                    'status' => 'Aktif'
                ],
                [
                    'id' => 2,
                    'nama' => 'Pendidikan Bahasa Indonesia', 
                    'jenjang' => 'S1',
                    'akreditasi' => 'B', 
                    'masa_berlaku' => '2026',
                    'badan_akreditasi' => 'BAN-PT',
                    'status' => 'Aktif'
                ],
                [
                    'id' => 3,
                    'nama' => 'Pendidikan Matematika', 
                    'jenjang' => 'S1',
                    'akreditasi' => 'A', 
                    'masa_berlaku' => '2028',
                    'badan_akreditasi' => 'BAN-PT',
                    'status' => 'Aktif'
                ],
            ],
            'institusi' => [
                [
                    'id' => 4,
                    'nama' => 'Akreditasi Institusi', 
                    'jenjang' => 'Perguruan Tinggi',
                    'akreditasi' => 'B', 
                    'masa_berlaku' => '2025',
                    'badan_akreditasi' => 'BAN-PT',
                    'status' => 'Aktif'
                ],
            ]
        ];
        
        $instruments = [
            ['id' => 1, 'nama' => 'Instrumen Akreditasi Prodi', 'versi' => '2023', 'file' => 'instrument-prodi-2023.pdf'],
            ['id' => 2, 'nama' => 'Instrumen Akreditasi Institusi', 'versi' => '2024', 'file' => 'instrument-institusi-2024.pdf'],
        ];
        
        return view('dashboard.spmi.akreditasi.index', compact('akreditasi', 'instruments'));
    }
}