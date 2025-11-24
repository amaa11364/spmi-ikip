<?php
namespace Database\Seeders;

use App\Models\Iku;
use Illuminate\Database\Seeder;

class IkuSeeder extends Seeder
{
    public function run()
    {
        $ikus = [
            [
                'kode' => 'IKU1',
                'nama' => 'Lulusan Mendapat Pekerjaan yang Layak',
                'deskripsi' => 'Indikator kinerja utama untuk memastikan lulusan mendapatkan pekerjaan yang layak sesuai dengan bidang keilmuannya.',
                'status' => true
            ],
            [
                'kode' => 'IKU2',
                'nama' => 'Mahasiswa Mendapat Pengalaman di Luar Kampus', 
                'deskripsi' => 'Indikator kinerja utama untuk memastikan mahasiswa mendapatkan pengalaman praktis di luar lingkungan kampus.',
                'status' => true
            ],
            [
                'kode' => 'IKU3',
                'nama' => 'Dosen Berkegiatan di Luar Kampus',
                'deskripsi' => 'Indikator kinerja utama untuk mendorong dosen beraktivitas dan berkontribusi di luar kampus.',
                'status' => true
            ],
            [
                'kode' => 'IKU4',
                'nama' => 'Praktisi Mengajar di Dalam Kampus', 
                'deskripsi' => 'Indikator kinerja utama untuk meningkatkan kualitas pembelajaran dengan melibatkan praktisi industri.',
                'status' => true
            ],
            [
                'kode' => 'IKU5',
                'nama' => 'Hasil Kerja Dosen Digunakan oleh Masyarakat atau Mendapat Rekognisi Internasional',
                'deskripsi' => 'Indikator kinerja utama untuk mengukur dampak dan rekognisi karya dosen.',
                'status' => true
            ],
            [
                'kode' => 'IKU6',
                'nama' => 'Program Studi Bekerjasama dengan Mitra Kelas Dunia',
                'deskripsi' => 'Indikator kinerja utama untuk mengukur tingkat kerjasama internasional program studi.',
                'status' => true
            ],
            [
                'kode' => 'IKU7', 
                'nama' => 'Kelas yang Kolaboratif dan Partisipatif',
                'deskripsi' => 'Indikator kinerja utama untuk menciptakan lingkungan pembelajaran yang interaktif dan kolaboratif.',
                'status' => true
            ],
            [
                'kode' => 'IKU8',
                'nama' => 'Program Studi Berstandar Internasional',
                'deskripsi' => 'Indikator kinerja utama untuk menuju standar kualitas internasional pada program studi.',
                'status' => true
            ]
        ];

        foreach ($ikus as $iku) {
            Iku::create($iku);
        }

        $this->command->info('Data IKU berhasil ditambahkan!');
    }
}