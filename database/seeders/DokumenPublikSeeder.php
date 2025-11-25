<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\UnitKerja;
use App\Models\Iku;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DokumenPublikSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Memulai DokumenPublikSeeder...');

        // Pastikan UnitKerja dan Iku sudah ada
        if (UnitKerja::count() == 0) {
            $this->command->info('Membuat data UnitKerja...');
            $this->call(UnitKerjaSeeder::class);
        }

        if (Iku::count() == 0) {
            $this->command->info('Membuat data IKU...');
            $this->call(IkuSeeder::class);
        }

        // Get existing data
        $unitKerjas = UnitKerja::all();
        $ikus = Iku::all();
        
        $this->command->info("UnitKerja tersedia: {$unitKerjas->count()}");
        $this->command->info("IKU tersedia: {$ikus->count()}");
        
        // CARI USER YANG SUDAH ADA - coba beberapa email
        $adminUser = User::where('email', 'admin@qtrack.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::where('email', 'admin@spmi.ac.id')->first();
        }
        
        if (!$adminUser) {
            // Jika masih tidak ada, buat user baru
            $this->command->info('Membuat user admin baru...');
            $adminUser = User::create([
                'name' => 'Administrator Q-TRACK',
                'email' => 'admin@qtrack.com',
                'password' => Hash::make('password123'),
                'role' => 'administrator',
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info("Menggunakan user: {$adminUser->email} (ID: {$adminUser->id})");

        $dokumenPublik = [
            [
                'nama_dokumen' => 'Pedoman Sistem Penjaminan Mutu Internal',
                'jenis_dokumen' => 'Pedoman Mutu',
                'file_extension' => 'pdf',
                'file_size' => 2048576,
                'file_path' => 'dokumen/pedoman-spmi.pdf',
                'file_name' => 'pedoman-spmi.pdf',
                'is_public' => true,
                'deskripsi' => 'Pedoman lengkap implementasi SPMI di perguruan tinggi'
            ],
            [
                'nama_dokumen' => 'Standar Nasional Pendidikan Tinggi',
                'jenis_dokumen' => 'Regulasi',
                'file_extension' => 'pdf',
                'file_size' => 1572864,
                'file_path' => 'dokumen/standar-nasional.pdf',
                'file_name' => 'standar-nasional.pdf',
                'is_public' => true,
                'deskripsi' => 'Dokumen standar nasional pendidikan tinggi terbaru'
            ],
            [
                'nama_dokumen' => 'Laporan Audit Mutu Internal 2024',
                'jenis_dokumen' => 'Laporan Audit',
                'file_extension' => 'pdf',
                'file_size' => 3145728,
                'file_path' => 'dokumen/laporan-audit-2024.pdf',
                'file_name' => 'laporan-audit-2024.pdf',
                'is_public' => true,
                'deskripsi' => 'Laporan hasil audit mutu internal tahun 2024'
            ],
            [
                'nama_dokumen' => 'Format RPS (Rencana Pembelajaran Semester)',
                'jenis_dokumen' => 'Template',
                'file_extension' => 'docx',
                'file_size' => 51200,
                'file_path' => 'dokumen/template-rps.docx',
                'file_name' => 'template-rps.docx',
                'is_public' => true,
                'deskripsi' => 'Template standar Rencana Pembelajaran Semester'
            ],
            [
                'nama_dokumen' => 'Panduan Penyusunan Kurikulum OBE',
                'jenis_dokumen' => 'Panduan',
                'file_extension' => 'pdf',
                'file_size' => 1048576,
                'file_path' => 'dokumen/panduan-obe.pdf',
                'file_name' => 'panduan-obe.pdf',
                'is_public' => true,
                'deskripsi' => 'Panduan penyusunan kurikulum Outcome Based Education'
            ],
            [
                'nama_dokumen' => 'Data Indikator Kinerja Utama',
                'jenis_dokumen' => 'Laporan',
                'file_extension' => 'xlsx',
                'file_size' => 256000,
                'file_path' => 'dokumen/iku-data.xlsx',
                'file_name' => 'iku-data.xlsx',
                'is_public' => true,
                'deskripsi' => 'Data capaian indikator kinerja utama tahun 2024'
            ],
            [
                'nama_dokumen' => 'Prosedur Monitoring dan Evaluasi',
                'jenis_dokumen' => 'Prosedur',
                'file_extension' => 'pdf',
                'file_size' => 786432,
                'file_path' => 'dokumen/prosedur-monev.pdf',
                'file_name' => 'prosedur-monev.pdf',
                'is_public' => true,
                'deskripsi' => 'Prosedur standar monitoring dan evaluasi program studi'
            ],
            [
                'nama_dokumen' => 'Kode Etik Dosen dan Tenaga Kependidikan',
                'jenis_dokumen' => 'Kode Etik',
                'file_extension' => 'pdf',
                'file_size' => 921600,
                'file_path' => 'dokumen/kode-etik.pdf',
                'file_name' => 'kode-etik.pdf',
                'is_public' => true,
                'deskripsi' => 'Kode etik bagi dosen dan tenaga kependidikan'
            ]
        ];

        $createdCount = 0;
        foreach ($dokumenPublik as $dokumen) {
            if (!Dokumen::where('nama_dokumen', $dokumen['nama_dokumen'])->exists()) {
                Dokumen::create(array_merge($dokumen, [
                    'unit_kerja_id' => $unitKerjas->random()->id,
                    'iku_id' => $ikus->random()->id,
                    'uploaded_by' => $adminUser->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 90)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 90)),
                ]));
                $createdCount++;
                $this->command->info("Dokumen dibuat: {$dokumen['nama_dokumen']}");
            }
        }

        // Juga buat beberapa dokumen non-public untuk testing
        $dokumenNonPublik = [
            [
                'nama_dokumen' => 'Laporan Internal Keuangan',
                'jenis_dokumen' => 'Laporan Keuangan',
                'file_extension' => 'pdf',
                'file_size' => 1572864,
                'file_path' => 'dokumen/laporan-keuangan.pdf',
                'file_name' => 'laporan-keuangan.pdf',
                'is_public' => false,
                'deskripsi' => 'Laporan keuangan internal universitas'
            ],
            [
                'nama_dokumen' => 'Data Sensitif Mahasiswa',
                'jenis_dokumen' => 'Data',
                'file_extension' => 'xlsx',
                'file_size' => 102400,
                'file_path' => 'dokumen/data-mahasiswa.xlsx',
                'file_name' => 'data-mahasiswa.xlsx',
                'is_public' => false,
                'deskripsi' => 'Data sensitif mahasiswa (rahasia)'
            ]
        ];

        foreach ($dokumenNonPublik as $dokumen) {
            if (!Dokumen::where('nama_dokumen', $dokumen['nama_dokumen'])->exists()) {
                Dokumen::create(array_merge($dokumen, [
                    'unit_kerja_id' => $unitKerjas->random()->id,
                    'iku_id' => $ikus->random()->id,
                    'uploaded_by' => $adminUser->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 90)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 90)),
                ]));
                $createdCount++;
                $this->command->info("Dokumen non-public dibuat: {$dokumen['nama_dokumen']}");
            }
        }

        $this->command->info("Seeder selesai! Total dokumen dibuat: {$createdCount}");
        $this->command->info("Total dokumen publik di database: " . Dokumen::where('is_public', true)->count());
    }
}