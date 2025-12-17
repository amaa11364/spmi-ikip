<?php

namespace Database\Seeders;

use App\Models\HeroContent;
use Illuminate\Database\Seeder;

class HeroContentSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan semua hero content yang ada
        HeroContent::query()->update(['is_active' => false]);
        
        // Buat hero content default
        HeroContent::create([
            'title' => 'SPMI Digital',
            'subtitle' => 'Transformasi Digital Sistem Penjaminan Mutu Internal',
            'description' => 'Solusi lengkap untuk manajemen mutu pendidikan tinggi. Kelola standar, audit, dokumen, dan program studi dalam satu platform terintegrasi.',
            'cta_text' => 'Pelajari Lebih Lanjut',
            'cta_link' => '#features',
            'background_image' => null, // Kosong dulu, bisa diisi nanti oleh admin
            'is_active' => true,
        ]);
        
        // Buat hero content alternatif
        HeroContent::create([
            'title' => 'Q-TRACK SPMI',
            'subtitle' => 'Monitoring & Evaluasi Mutu Pendidikan',
            'description' => 'Sistem digital untuk penjaminan mutu perguruan tinggi yang efisien, transparan, dan terukur.',
            'cta_text' => 'Lihat Fitur',
            'cta_link' => '#programs',
            'background_image' => null,
            'is_active' => false,
        ]);
    }
}