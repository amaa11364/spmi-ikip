<?php

namespace Database\Seeders;

use App\Models\PenetapanSPM;
use App\Models\UnitKerja;
use App\Models\Iku;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PenetapanSPMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada unit kerja LPM
        $lpm = UnitKerja::where('kode', 'LPM')->first();
        if (!$lpm) {
            $lpm = UnitKerja::create([
                'kode' => 'LPM',
                'nama' => 'Lembaga Penjaminan Mutu',
                'deskripsi' => 'Unit kerja yang menangani sistem penjaminan mutu internal',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Pastikan ada IKU SPMI
        $ikuSpmi = Iku::where('kode', 'IKU-SPMI')->first();
        if (!$ikuSpmi) {
            $ikuSpmi = Iku::create([
                'kode' => 'IKU-SPMI',
                'nama' => 'Indikator Kinerja Sistem Penjaminan Mutu Internal',
                'deskripsi' => 'Indikator kinerja untuk monitoring dan evaluasi SPMI',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Data contoh Penetapan SPMI
        $penetapanData = [
            // PENGELOLAAN
            [
                'nama_komponen' => 'Kebijakan Mutu Institusi',
                'tipe_penetapan' => 'pengelolaan',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Kebijakan mutu sebagai landasan penyelenggaraan pendidikan tinggi',
                'penanggung_jawab' => 'Rektor',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],
            [
                'nama_komponen' => 'Pedoman Sistem Penjaminan Mutu Internal (SPMI)',
                'tipe_penetapan' => 'pengelolaan',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Pedoman implementasi SPMI di seluruh unit kerja',
                'penanggung_jawab' => 'Ketua LPM',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],

            // ORGANISASI
            [
                'nama_komponen' => 'Struktur Organisasi LPM',
                'tipe_penetapan' => 'organisasi',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Struktur organisasi dan tata kelola Lembaga Penjaminan Mutu',
                'penanggung_jawab' => 'Ketua LPM',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],
            [
                'nama_komponen' => 'Tupoksi Tim SPMI Fakultas/Prodi',
                'tipe_penetapan' => 'organisasi',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'belum_valid',
                'deskripsi' => 'Tugas pokok dan fungsi tim SPMI di tingkat fakultas dan program studi',
                'penanggung_jawab' => 'Dekan/Kaprodi',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],

            // PELAKSANAAN
            [
                'nama_komponen' => 'Rencana Kerja Tahunan SPMI',
                'tipe_penetapan' => 'pelaksanaan',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Rencana kerja tahunan implementasi SPMI',
                'penanggung_jawab' => 'Ketua LPM',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],
            [
                'nama_komponen' => 'Standar Mutu Pembelajaran',
                'tipe_penetapan' => 'pelaksanaan',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'dalam_review',
                'deskripsi' => 'Standar mutu untuk proses pembelajaran',
                'penanggung_jawab' => 'Wakil Rektor I',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],

            // EVALUASI
            [
                'nama_komponen' => 'Instrumen Audit Mutu Internal (AMI)',
                'tipe_penetapan' => 'evaluasi',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Instrumen penilaian untuk audit mutu internal',
                'penanggung_jawab' => 'Ketua LPM',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],
            [
                'nama_komponen' => 'Formulir Evaluasi Dosen oleh Mahasiswa (EDOM)',
                'tipe_penetapan' => 'evaluasi',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Formulir evaluasi kinerja dosen oleh mahasiswa',
                'penanggung_jawab' => 'Wakil Rektor I',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],

            // PENGENDALIAN
            [
                'nama_komponen' => 'Formulir Tindakan Korektif dan Pencegahan',
                'tipe_penetapan' => 'pengendalian',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'valid',
                'deskripsi' => 'Formulir untuk pencatatan tindakan korektif dan pencegahan',
                'penanggung_jawab' => 'Ketua LPM',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],

            // PENINGKATAN
            [
                'nama_komponen' => 'Program Peningkatan Mutu Berkelanjutan',
                'tipe_penetapan' => 'peningkatan',
                'tahun' => 2024,
                'status' => 'aktif',
                'status_dokumen' => 'belum_valid',
                'deskripsi' => 'Program untuk peningkatan mutu secara berkelanjutan',
                'penanggung_jawab' => 'Rektor',
                'unit_kerja_id' => $lpm->id,
                'iku_id' => $ikuSpmi->id,
            ],
        ];

        foreach ($penetapanData as $data) {
            // Generate kode otomatis
            $kode = PenetapanSPM::generateKode($data['tipe_penetapan'], $data['tahun']);
            
            // Cek apakah sudah ada
            $existing = PenetapanSPM::where('nama_komponen', $data['nama_komponen'])
                                   ->where('tahun', $data['tahun'])
                                   ->first();
            
            if (!$existing) {
                PenetapanSPM::create(array_merge($data, [
                    'kode_penetapan' => $kode,
                    'tanggal_penetapan' => Carbon::now(),
                    'tanggal_review' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]));
            }
        }

        $this->command->info('✅ Data Penetapan SPMI berhasil ditambahkan!');
        $this->command->info('   Total: ' . PenetapanSPM::count() . ' komponen');
        
        // Tampilkan statistik
        $stats = PenetapanSPM::getStatistics();
        $this->command->info('   - Aktif: ' . $stats['aktif']);
        $this->command->info('   - Dokumen Valid: ' . $stats['valid']);
        
        // Tampilkan by tipe
        $byTipe = PenetapanSPM::getGroupedByTipe();
        foreach ($byTipe as $tipe => $count) {
            $label = match($tipe) {
                'pengelolaan' => 'Pengelolaan',
                'organisasi' => 'Organisasi',
                'pelaksanaan' => 'Pelaksanaan',
                'evaluasi' => 'Evaluasi',
                'pengendalian' => 'Pengendalian',
                'peningkatan' => 'Peningkatan',
                default => $tipe,
            };
            $this->command->info("   - {$label}: {$count}");
        }
    }
}