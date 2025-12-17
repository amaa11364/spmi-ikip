<?php
namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    public function run()
    {
        $unitKerjas = [
            [
                'kode' => 'UPT',
                'nama' => 'Unit Pelaksana Teknis',
                'deskripsi' => 'Unit kerja yang melaksanakan tugas teknis universitas',
                'status' => true
            ],
            [
                'kode' => 'BAGIAN',
                'nama' => 'Bagian Administrasi', 
                'deskripsi' => 'Unit kerja yang menangani administrasi umum',
                'status' => true
            ],
            [
                'kode' => 'PRODI',
                'nama' => 'Program Studi',
                'deskripsi' => 'Unit kerja program studi dan akademik',
                'status' => true
            ]
        ];

        foreach ($unitKerjas as $unit) {
            UnitKerja::create($unit);
        }

        $this->command->info('Data Unit Kerja berhasil ditambahkan!');
    }
}