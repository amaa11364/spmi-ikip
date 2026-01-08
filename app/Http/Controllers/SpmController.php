<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpmController extends Controller
{
    // ==================== PENETAPAN ====================
    public function indexPenetapan()
    {
        // PAKAI DATA ARRAY DULU (TANPA DATABASE)
        $dokumen = [
            'peraturan_rektor' => [
                [
                    'id' => 1, 
                    'judul' => 'Peraturan Rektor tentang Sistem Penjaminan Mutu Internal', 
                    'kode_dokumen' => 'PR-001/2024',
                    'kategori' => 'peraturan_rektor',
                    'deskripsi' => 'Regulasi tentang implementasi SPMI di lingkungan institusi',
                    'versi' => '1.0',
                    'tanggal_terbit' => '2024-01-15',
                    'penanggung_jawab' => 'Rektor',
                    'status' => 'aktif'
                ],
                [
                    'id' => 2, 
                    'judul' => 'Peraturan Mutu Akademik', 
                    'kode_dokumen' => 'PR-002/2024',
                    'kategori' => 'peraturan_rektor',
                    'deskripsi' => 'Standar mutu akademik institusi',
                    'versi' => '2.1',
                    'tanggal_terbit' => '2024-02-20',
                    'penanggung_jawab' => 'Wakil Rektor I',
                    'status' => 'aktif'
                ],
            ],
            'dokumen_spmi' => [
                [
                    'id' => 3, 
                    'judul' => 'Kebijakan Mutu SPMI', 
                    'kode_dokumen' => 'SPMI-001',
                    'kategori' => 'dokumen_spmi',
                    'deskripsi' => 'Pedoman umum penjaminan mutu pendidikan tinggi',
                    'versi' => '2.1',
                    'tanggal_terbit' => '2024-02-20',
                    'penanggung_jawab' => 'LPM',
                    'status' => 'aktif'
                ],
                [
                    'id' => 4, 
                    'judul' => 'Manual Mutu', 
                    'kode_dokumen' => 'SPMI-002',
                    'kategori' => 'dokumen_spmi',
                    'deskripsi' => 'Panduan pelaksanaan sistem mutu',
                    'versi' => '1.5',
                    'tanggal_terbit' => '2024-03-05',
                    'penanggung_jawab' => 'LPM',
                    'status' => 'revisi'
                ],
            ],
            'standar_pendidikan' => [
                [
                    'id' => 5, 
                    'judul' => 'Standar Nasional Pendidikan Tinggi', 
                    'kode_dokumen' => 'SN-DIKTI-2023',
                    'kategori' => 'standar_pendidikan',
                    'deskripsi' => 'Standar nasional yang wajib dipenuhi oleh perguruan tinggi',
                    'versi' => '2023',
                    'tanggal_terbit' => '2023-12-01',
                    'penanggung_jawab' => 'Kemendikbud',
                    'status' => 'aktif'
                ],
                [
                    'id' => 6, 
                    'judul' => 'Standar Institusi', 
                    'kode_dokumen' => 'SI-001',
                    'kategori' => 'standar_pendidikan',
                    'deskripsi' => 'Standar mutu internal institusi',
                    'versi' => '3.0',
                    'tanggal_terbit' => '2024-02-28',
                    'penanggung_jawab' => 'LPM',
                    'status' => 'aktif'
                ],
            ]
        ];
        
        return view('dashboard.spmi.penetapan.index', compact('dokumen'));
    }

    public function showPenetapan($id)
    {
        // Data contoh untuk detail
        $dokumen = [
            'id' => $id,
            'judul' => 'Contoh Dokumen SPMI ' . $id,
            'kode_dokumen' => 'DOC-' . $id,
            'kategori' => 'standar_pendidikan',
            'deskripsi' => 'Ini adalah contoh deskripsi dokumen SPMI yang lengkap dengan semua detail yang diperlukan untuk pemahaman yang komprehensif.',
            'versi' => '1.0',
            'tanggal_terbit' => '2024-01-15',
            'penanggung_jawab' => 'Rektor',
            'status' => 'Aktif',
            'file_path' => '/documents/spmi/doc-' . $id . '.pdf',
            'created_at' => '2024-01-15 10:30:00',
            'updated_at' => '2024-01-15 10:30:00'
        ];
        
        return view('dashboard.spmi.penetapan.show', compact('dokumen'));
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