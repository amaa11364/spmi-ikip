<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ProgramStudiSeeder::class,
            UnitKerjaSeeder::class,
            IkuSeeder::class,
            HeroContentSeeder::class,
            LoginTestingSeeder::class,
            BeritaSeeder::class,
        ]);
    }
}