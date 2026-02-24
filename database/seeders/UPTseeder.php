<?php
// database/seeders/UPTSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UPT;

class UPTSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Perpustakaan',
                'singkatan' => 'UPT Perpustakaan',
                'deskripsi' => 'Pusat sumber belajar dan informasi ilmiah',
                'ikon' => 'fa-book',
                'warna' => '#8B5E3C',
                'kepala_upt' => 'Dr. Rina Wijaya, M.Pd.',
                'email' => 'perpustakaan@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5678',
                'jumlah_staff' => 15,
                'jumlah_program' => 5,
                'status' => 'aktif',
                'urutan' => 1
            ],
            [
                'nama' => 'TIK',
                'singkatan' => 'UPT TIK',
                'deskripsi' => 'Teknologi Informasi & Komunikasi',
                'ikon' => 'fa-laptop',
                'warna' => '#A67B5B',
                'kepala_upt' => 'Ir. Budi Santoso, M.Kom.',
                'email' => 'tik@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5679',
                'jumlah_staff' => 12,
                'jumlah_program' => 8,
                'status' => 'aktif',
                'urutan' => 2
            ],
            [
                'nama' => 'Bahasa',
                'singkatan' => 'UPT Bahasa',
                'deskripsi' => 'Pengembangan dan layanan bahasa',
                'ikon' => 'fa-language',
                'warna' => '#C49A6C',
                'kepala_upt' => 'Dr. Siti Aminah, M.Hum.',
                'email' => 'bahasa@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5680',
                'jumlah_staff' => 10,
                'jumlah_program' => 6,
                'status' => 'aktif',
                'urutan' => 3
            ],
            [
                'nama' => 'Laboratorium',
                'singkatan' => 'UPT Laboratorium',
                'deskripsi' => 'Laboratorium terpadu dan penelitian',
                'ikon' => 'fa-flask',
                'warna' => '#6B4A2E',
                'kepala_upt' => 'Dr. Ahmad Hidayat, M.Si.',
                'email' => 'laboratorium@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5681',
                'jumlah_staff' => 18,
                'jumlah_program' => 12,
                'status' => 'aktif',
                'urutan' => 4
            ],
            [
                'nama' => 'Pengembangan Karir',
                'singkatan' => 'UPT Karir',
                'deskripsi' => 'Career center dan pengembangan profesional',
                'ikon' => 'fa-briefcase',
                'warna' => '#1976D2',
                'kepala_upt' => 'Dra. Dewi Lestari, M.Pd.',
                'email' => 'karir@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5682',
                'jumlah_staff' => 8,
                'jumlah_program' => 4,
                'status' => 'aktif',
                'urutan' => 5
            ],
            [
                'nama' => 'Publikasi',
                'singkatan' => 'UPT Publikasi',
                'deskripsi' => 'Jurnal dan publikasi ilmiah',
                'ikon' => 'fa-newspaper',
                'warna' => '#7B1FA2',
                'kepala_upt' => 'Dr. Hendra Gunawan, M.Pd.',
                'email' => 'publikasi@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5683',
                'jumlah_staff' => 7,
                'jumlah_program' => 3,
                'status' => 'aktif',
                'urutan' => 6
            ],
            [
                'nama' => 'Penjaminan Mutu',
                'singkatan' => 'UPT PM',
                'deskripsi' => 'Sistem penjaminan mutu internal',
                'ikon' => 'fa-check-circle',
                'warna' => '#009688',
                'kepala_upt' => 'Dr. Ratna Sari, M.M.',
                'email' => 'pm@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5684',
                'jumlah_staff' => 6,
                'jumlah_program' => 5,
                'status' => 'aktif',
                'urutan' => 7
            ],
            [
                'nama' => 'Kerjasama',
                'singkatan' => 'UPT Kerjasama',
                'deskripsi' => 'Hubungan masyarakat dan kerjasama',
                'ikon' => 'fa-handshake',
                'warna' => '#E91E63',
                'kepala_upt' => 'Dr. Agus Saputra, M.Si.',
                'email' => 'kerjasama@ikipsiliwangi.ac.id',
                'telepon' => '(022) 1234-5685',
                'jumlah_staff' => 5,
                'jumlah_program' => 7,
                'status' => 'aktif',
                'urutan' => 8
            ],
        ];

        foreach ($data as $item) {
            UPT::create($item);
        }
    }
}