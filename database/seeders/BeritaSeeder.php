<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $beritas = [
            [
                'judul' => 'Peluncuran Sistem SPMI Online',
                'isi' => '<p>Sistem Penjaminan Mutu Internal (SPMI) online resmi diluncurkan untuk mempermudah proses penjaminan mutu di seluruh unit kerja. Sistem ini akan membantu dalam pengelolaan dokumen, monitoring, dan evaluasi.</p><p>Dengan sistem ini, diharapkan semua proses SPMI dapat dilakukan secara digital dan real-time.</p>',
                'kategori' => 'Pengumuman',
                'penulis' => 'Admin SPMI',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Workshop Implementasi SPMI 2024',
                'isi' => '<p>Workshop implementasi SPMI akan diselenggarakan pada tanggal 15-17 Maret 2024. Workshop ini diikuti oleh perwakilan dari semua fakultas dan unit kerja.</p><p>Tujuan workshop adalah untuk mensosialisasikan prosedur baru dalam sistem SPMI online.</p>',
                'kategori' => 'Kegiatan',
                'penulis' => 'Tim SPMI',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'judul' => 'Pencapaian Indikator Kinerja Utama (IKU)',
                'isi' => '<p>Berdasarkan evaluasi triwulan I 2024, capaian IKU menunjukkan peningkatan sebesar 15% dibandingkan periode yang sama tahun sebelumnya.</p><p>Pencapaian tertinggi berada pada indikator kepuasan mahasiswa dan kualitas pembelajaran.</p>',
                'kategori' => 'Prestasi',
                'penulis' => 'Tim Monitoring',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Pelatihan Verifikator Dokumen SPMI',
                'isi' => '<p>Pelatihan verifikator dokumen SPMI akan dilaksanakan untuk memastikan semua dokumen terverifikasi dengan baik. Pelatihan diikuti oleh 25 orang dari berbagai unit.</p><p>Materi pelatihan meliputi teknik verifikasi, validasi dokumen, dan penggunaan sistem online.</p>',
                'kategori' => 'Pelatihan',
                'penulis' => 'LP3M',
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'judul' => 'Audit Internal SPMI Semester Genap',
                'isi' => '<p>Audit internal SPMI untuk semester genap akan dilaksanakan mulai bulan April 2024. Audit mencakup semua aspek penjaminan mutu.</p><p>Hasil audit akan menjadi dasar untuk perbaikan sistem di semester berikutnya.</p>',
                'kategori' => 'Audit',
                'penulis' => 'Tim Auditor',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($beritas as $berita) {
            $slug = Str::slug($berita['judul']);
            
            Berita::create([
                'judul' => $berita['judul'],
                'slug' => $slug,
                'isi' => $berita['isi'],
                'kategori' => $berita['kategori'],
                'penulis' => $berita['penulis'],
                'is_published' => $berita['is_published'],
                'published_at' => $berita['published_at'],
                'views' => rand(50, 500),
                'user_id' => 1, // ID admin
            ]);
        }

        $this->command->info('✅ Berita dummy created successfully!');
        $this->command->info('Total berita: ' . Berita::count());
        $this->command->info('Berita published: ' . Berita::where('is_published', true)->count());
    }
}