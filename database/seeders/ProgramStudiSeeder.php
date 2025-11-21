<?php
// database/seeders/ProgramStudiSeeder.php
namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        $programStudi = [
            ['nama' => 'ILMU PENDIDIKAN', 'deskripsi' => 'Program studi bidang pendidikan dan pengajaran'],
            ['nama' => 'PENDIDIKAN BAHASA', 'deskripsi' => 'Program studi linguistik dan pendidikan bahasa'],
            ['nama' => 'MATEMATIKA DAN SAINS', 'deskripsi' => 'Program studi matematika dan ilmu pengetahuan'],
            ['nama' => 'PROGRAM STUDI KHUSUS', 'deskripsi' => 'Program studi khusus dengan kurikulum terpadu'],
            ['nama' => 'PASCASARJANA', 'deskripsi' => 'Program studi tingkat magister dan doktoral'],
            ['nama' => 'LPM SMART SISTEM', 'deskripsi' => 'Sistem penjaminan mutu terintegrasi'],
        ];

        foreach ($programStudi as $prodi) {
            ProgramStudi::create($prodi);
        }
    }
}