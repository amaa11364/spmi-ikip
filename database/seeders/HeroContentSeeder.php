<?php

namespace Database\Seeders;

use App\Models\HeroContent;
use Illuminate\Database\Seeder;

class HeroContentSeeder extends Seeder
{
    public function run(): void
    {
        HeroContent::create([
            'title' => 'Sistem Penjaminan Mutu Internal',
            'subtitle' => 'Mewujudkan Pendidikan Bermutu',
            'description' => 'Sistem Penjaminan Mutu Internal (SPMI) IKIP merupakan sistem yang dirancang untuk menjamin dan meningkatkan mutu pendidikan secara berkelanjutan.',
            'button_text' => 'Pelajari Lebih Lanjut',
            'button_link' => '/tentang',
            'background_image' => 'images/hero-bg.jpg',
            'is_active' => true,
        ]);
    }
}