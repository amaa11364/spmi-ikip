<?php

namespace Database\Seeders;

use App\Models\HeroContent;
use Illuminate\Database\Seeder;

class HeroContentSeeder extends Seeder
{
    public function run()
    {
        HeroContent::create([
            'title' => 'SPMI Digital',
            'subtitle' => 'Transformasi Digital SPMI Perguruan Tinggi',
            'description' => 'Kelola Mutu Pendidikan Lebih Efisien & Efektif',
            'cta_text' => 'Pelajari Lebih Lanjut',
            'cta_link' => '#about',
            'is_active' => true
        ]);
    }
}